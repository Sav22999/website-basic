<?php
/**
 * POST /api/v2/admin/stats - aggregate, read-only statistics for administrators.
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * Only COUNT/SUM/AVG over metadata columns: the encrypted `data` column is
 * never read, and no per-user value (email hash, IP, username) is returned.
 * Sections whose table is missing are null (accounts/sync) or report
 * "available": false with the reason (telemetry, error logs).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");
include_once(__DIR__ . "/../admin-common.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
v2_require_admin($session);

v2_rate_limit($c, "admin-stats", $session["user-id"], 30, 60, 60);

global $users_table, $logins_table, $data_current_table;

function admin_stats_int($c, $sql)
{
    $row = db_select_one($c, $sql);
    if ($row === null) {
        return null;
    }
    $value = reset($row);
    return $value === null ? 0 : (int)$value;
}

function admin_stats_float($c, $sql)
{
    $row = db_select_one($c, $sql);
    if ($row === null) {
        return null;
    }
    $value = reset($row);
    return $value === null ? 0 : round((float)$value, 1);
}

function admin_stats_top($c, $table, $column, $date_expression, $days, $limit = 5)
{
    if (!db_has_column($c, $table, $column)) {
        return null;
    }
    $where = $days === null ? "" : " WHERE $date_expression >= DATE_SUB(NOW(), INTERVAL " . (int)$days . " DAY)";
    $rows = db_select(
        $c,
        "SELECT `$column` AS label, COUNT(*) AS total FROM `$table`$where GROUP BY `$column` ORDER BY total DESC LIMIT " . (int)$limit
    );
    $out = array();
    foreach ($rows as $row) {
        $label = $row["label"];
        $out[] = array(
            "label" => ($label === null || $label === "") ? "unknown" : mb_substr((string)$label, 0, 80),
            "count" => (int)$row["total"],
        );
    }
    return $out;
}

function admin_stats_count_since($c, $table, $date_expression, $days, $select = "COUNT(*)")
{
    $where = $days === null ? "" : " WHERE $date_expression >= DATE_SUB(NOW(), INTERVAL " . (int)$days . " DAY)";
    return admin_stats_int($c, "SELECT $select FROM `$table`$where");
}

$accounts = array(
    "total" => admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table`"),
    "verified" => admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `verified` IS NOT NULL"),
    "new-7d" => null,
    "new-30d" => null,
    "otp-enabled" => null,
    "history-enabled" => null,
    "pro-features" => null,
    "admins" => null,
);
$accounts["unverified"] = ($accounts["total"] !== null && $accounts["verified"] !== null)
    ? $accounts["total"] - $accounts["verified"]
    : null;
if (db_has_column($c, $users_table, "created")) {
    $accounts["new-7d"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `created` >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $accounts["new-30d"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `created` >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
}
if (db_has_column($c, $users_table, "otp-enabled")) {
    $accounts["otp-enabled"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `verified` IS NOT NULL AND `otp-enabled` = 1");
}
if (db_has_column($c, $users_table, "history-enabled")) {
    $accounts["history-enabled"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `history-enabled` = 1");
}
if (db_has_column($c, $users_table, "pro-features")) {
    $accounts["pro-features"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `pro-features` = 1");
}
if (db_has_column($c, $users_table, "permissions")) {
    $accounts["admins"] = admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` WHERE `permissions` >= 10");
}

$active_login = "`status` = 1 AND `verified` IS NOT NULL AND (`expiry` > NOW() OR `expiry` IS NULL)";
$sessions = array(
    "active" => admin_stats_int($c, "SELECT COUNT(*) FROM `$logins_table` WHERE $active_login"),
    "users-with-active-session" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$logins_table` WHERE $active_login"),
    "users-logged-in-30d" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$logins_table` WHERE `verified` >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
);

$sync = null;
if (db_has_table($c, $data_current_table)) {
    $sync = array(
        "accounts-with-data" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$data_current_table`"),
        "synced-24h" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$data_current_table` WHERE `updated-server-date` >= DATE_SUB(NOW(), INTERVAL 1 DAY)"),
        "synced-7d" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$data_current_table` WHERE `updated-server-date` >= DATE_SUB(NOW(), INTERVAL 7 DAY)"),
        "synced-30d" => admin_stats_int($c, "SELECT COUNT(DISTINCT `user-id`) FROM `$data_current_table` WHERE `updated-server-date` >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
        "average-revisions" => admin_stats_float($c, "SELECT AVG(`revision`) FROM `$data_current_table`"),
        "verified-without-v2-data" => admin_stats_int($c, "SELECT COUNT(*) FROM `$users_table` u WHERE u.`verified` IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `$data_current_table` d WHERE d.`user-id` = u.`email`)"),
    );
}

$sources = admin_log_sources();

$telemetry_state = admin_log_source_state($c, $sources["telemetry"]);
if ($telemetry_state["available"]) {
    $t = $telemetry_state["table"];
    $d = $telemetry_state["date"];
    $has_installations = db_has_column($c, $t, "anonymous-userid");
    $telemetry = array(
        "available" => true,
        "events-total" => admin_stats_count_since($c, $t, $d, null),
        "events-30d" => admin_stats_count_since($c, $t, $d, 30),
        "installations-total" => $has_installations ? admin_stats_count_since($c, $t, $d, null, "COUNT(DISTINCT `anonymous-userid`)") : null,
        "installations-30d" => $has_installations ? admin_stats_count_since($c, $t, $d, 30, "COUNT(DISTINCT `anonymous-userid`)") : null,
        "last-event" => null,
        "actions" => admin_stats_top($c, $t, "action", $d, 30, 8),
        "versions" => admin_stats_top($c, $t, "notefox-version", $d, 30),
        "browsers" => admin_stats_top($c, $t, "browser", $d, 30),
        "os" => admin_stats_top($c, $t, "os", $d, 30),
        "languages" => admin_stats_top($c, $t, "language", $d, 30),
    );
    $last = db_select_one($c, "SELECT MAX($d) AS `last` FROM `$t`");
    if ($last !== null && $last["last"] !== null) {
        $telemetry["last-event"] = (string)$last["last"];
    }
} else {
    $telemetry = $telemetry_state;
}

$errors_state = admin_log_source_state($c, $sources["errors"]);
if ($errors_state["available"]) {
    $t = $errors_state["table"];
    $d = $errors_state["date"];
    $error_logs = array(
        "available" => true,
        "errors-total" => admin_stats_count_since($c, $t, $d, null),
        "errors-7d" => admin_stats_count_since($c, $t, $d, 7),
        "errors-30d" => admin_stats_count_since($c, $t, $d, 30),
        "contexts" => admin_stats_top($c, $t, "context", $d, 7),
        "versions" => admin_stats_top($c, $t, "notefox-version", $d, 7),
    );
} else {
    $error_logs = $errors_state;
}

api_ok(array(
    "accounts" => $accounts,
    "sessions" => $sessions,
    "sync" => $sync,
    "telemetry" => $telemetry,
    "error-logs" => $error_logs,
    "generated-at" => getTimestamp(),
));
?>
