<?php
/**
 * Periodic cleanup of the legacy data table ($data_table), which gets one new
 * row (a full encrypted copy of the notes) at every sync. Per account it keeps:
 *   - $KEEP_WITH_HISTORY rows when `users`.`history-enabled` = 1, or when that
 *     column does not exist (the sync history lists at most 30 entries);
 *   - $KEEP_WITHOUT_HISTORY row otherwise (v1 clients only read the newest).
 *
 * "Newest" is taken both by `updated-locally-date` (what v1 reads) and by `id`
 * (what v2 and the history read), and the rows used as the v2 mirror
 * (`legacy-data-id` in the snapshot table) are never deleted.
 *
 * It works one account at a time, biggest first, in small DELETE batches and
 * within a time budget: when the answer says "remaining": true, the next run
 * carries on. Deleting rows does not shrink the table file: run
 * OPTIMIZE TABLE on it afterwards to give the space back.
 */

require __DIR__ . "/cron-guard.php";
global $localhost_db, $username_db, $password_db, $database_notefox, $data_table, $users_table, $data_current_table;

$KEEP_WITH_HISTORY = 50;
$KEEP_WITHOUT_HISTORY = 3;
$DELETE_BATCH = 500;
$TIME_BUDGET_SECONDS = 20;

@set_time_limit(120);
$started = microtime(true);

function check_data_out($payload)
{
    echo json_encode($payload);
    exit;
}

function check_data_has_table($c, $table)
{
    if (!is_string($table) || $table === "") {
        return false;
    }
    $result = $c->query("SHOW TABLES LIKE '" . $c->real_escape_string($table) . "'");
    return $result !== false && $result->num_rows > 0;
}

function check_data_has_column($c, $table, $column)
{
    if (!check_data_has_table($c, $table)) {
        return false;
    }
    $result = $c->query("SHOW COLUMNS FROM `" . str_replace("`", "", $table) . "` LIKE '" . $c->real_escape_string($column) . "'");
    return $result !== false && $result->num_rows > 0;
}

function check_data_ids($c, $sql, $user_id, $limit)
{
    $stmt = $c->prepare($sql);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("si", $user_id, $limit);
    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }
    $ids = array();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row["id"];
    }
    $stmt->close();
    return $ids;
}

function check_data_time_left($started, $budget)
{
    return (microtime(true) - $started) < $budget;
}

$c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
if (!$c || $c->connect_errno) {
    check_data_out(array("code" => "500", "status" => "Error", "description" => "Database connection failed"));
}
$c->set_charset("utf8mb4");

if (!check_data_has_table($c, $data_table)) {
    check_data_out(array("code" => "200", "status" => "Successful", "data" => array("deleted" => 0, "note" => "data table not found")));
}

$has_history_column = check_data_has_column($c, $users_table, "history-enabled");
$has_mirror_column = check_data_has_column($c, $data_current_table, "legacy-data-id");

$stmt = $c->prepare("SELECT `user-id`, COUNT(*) AS `total` FROM `$data_table` GROUP BY `user-id` HAVING `total` > ? ORDER BY `total` DESC");
$stmt->bind_param("i", $KEEP_WITHOUT_HISTORY);
if (!$stmt->execute()) {
    error_log("[periodic-checks] check-data.php: " . $stmt->error);
    check_data_out(array("code" => "500", "status" => "Error", "description" => "Select failed"));
}
$candidates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$history_stmt = $has_history_column
    ? $c->prepare("SELECT `history-enabled` FROM `$users_table` WHERE `email` = ? LIMIT 1")
    : null;
$mirror_stmt = $has_mirror_column
    ? $c->prepare("SELECT `legacy-data-id` FROM `$data_current_table` WHERE `user-id` = ? AND `legacy-data-id` IS NOT NULL")
    : null;

$deleted = 0;
$accounts_checked = 0;
$accounts_cleaned = 0;
$remaining = false;

foreach ($candidates as $candidate) {
    if (!check_data_time_left($started, $TIME_BUDGET_SECONDS)) {
        $remaining = true;
        break;
    }

    $accounts_checked++;
    $user_id = (string)$candidate["user-id"];
    $total = (int)$candidate["total"];

    $keep = $KEEP_WITH_HISTORY;
    if ($history_stmt) {
        $history_stmt->bind_param("s", $user_id);
        $history_stmt->execute();
        $row = $history_stmt->get_result()->fetch_assoc();
        $keep = ($row !== null && $row["history-enabled"] !== null && (int)$row["history-enabled"] === 1)
            ? $KEEP_WITH_HISTORY
            : $KEEP_WITHOUT_HISTORY;
    }
    if ($total <= $keep) {
        continue;
    }

    $by_local_date = check_data_ids($c, "SELECT `id` FROM `$data_table` WHERE `user-id` = ? ORDER BY `updated-locally-date` DESC, `id` DESC LIMIT ?", $user_id, $keep);
    $by_id = check_data_ids($c, "SELECT `id` FROM `$data_table` WHERE `user-id` = ? ORDER BY `id` DESC LIMIT ?", $user_id, $keep);
    if ($by_local_date === null || $by_id === null || count($by_id) === 0) {
        continue;
    }
    $keep_ids = array_merge($by_local_date, $by_id);

    if ($mirror_stmt) {
        $mirror_stmt->bind_param("s", $user_id);
        $mirror_stmt->execute();
        $mirrors = $mirror_stmt->get_result();
        while ($mirror = $mirrors->fetch_assoc()) {
            $keep_ids[] = (int)$mirror["legacy-data-id"];
        }
    }
    $keep_list = implode(",", array_unique(array_map("intval", $keep_ids)));

    $delete = $c->prepare("DELETE FROM `$data_table` WHERE `user-id` = ? AND `id` NOT IN ($keep_list) LIMIT $DELETE_BATCH");
    if (!$delete) {
        error_log("[periodic-checks] check-data.php: " . $c->error);
        check_data_out(array("code" => "500", "status" => "Error", "description" => "Delete failed"));
    }
    $delete->bind_param("s", $user_id);

    do {
        if (!$delete->execute()) {
            error_log("[periodic-checks] check-data.php: " . $delete->error);
            $delete->close();
            check_data_out(array("code" => "500", "status" => "Error", "description" => "Delete failed", "data" => array("deleted" => $deleted)));
        }
        $affected = $delete->affected_rows;
        $deleted += $affected;
        $budget_left = check_data_time_left($started, $TIME_BUDGET_SECONDS);
    } while ($affected === $DELETE_BATCH && $budget_left);
    $delete->close();

    if ($affected === $DELETE_BATCH) {
        $remaining = true;
        break;
    }
    $accounts_cleaned++;
}

if ($history_stmt) $history_stmt->close();
if ($mirror_stmt) $mirror_stmt->close();
$c->close();

check_data_out(array("code" => "200", "status" => "Successful", "data" => array(
    "deleted" => $deleted,
    "accounts-cleaned" => $accounts_cleaned,
    "accounts-checked" => $accounts_checked,
    "accounts-with-several-rows" => count($candidates),
    "remaining" => $remaining,
    "seconds" => round(microtime(true) - $started, 1),
)));
?>
