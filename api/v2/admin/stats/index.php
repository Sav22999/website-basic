<?php
/**
 * POST /api/v2/admin/stats - aggregate, read-only statistics for administrators.
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * Only COUNT/SUM/AVG over metadata columns: the encrypted `data` column is
 * never read, and no per-user value (email hash, IP, username) is returned.
 * Every section degrades to null when its table or column does not exist.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
v2_require_admin($session);

v2_rate_limit($c, "admin-stats", $session["user-id"], 30, 60, 60);

global $users_table, $logins_table, $data_current_table, $telemetry_table, $error_logs_table;

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

function admin_stats_top($c, $table, $column, $date_column, $days, $limit = 5)
{
    if (!db_has_column($c, $table, $column) || !db_has_column($c, $table, $date_column)) {
        return null;
    }
    $rows = db_select(
        $c,
        "SELECT `$column` AS label, COUNT(*) AS total FROM `$table` WHERE `$date_column` >= DATE_SUB(NOW(), INTERVAL " . (int)$days . " DAY) GROUP BY `$column` ORDER BY total DESC LIMIT " . (int)$limit
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

$telemetry = null;
if (db_has_table($c, $telemetry_table) && db_has_column($c, $telemetry_table, "server-datetime")) {
    $telemetry = array(
        "events-30d" => admin_stats_int($c, "SELECT COUNT(*) FROM `$telemetry_table` WHERE `server-datetime` >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
        "installations-30d" => admin_stats_int($c, "SELECT COUNT(DISTINCT `anonymous-userid`) FROM `$telemetry_table` WHERE `server-datetime` >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
        "versions" => admin_stats_top($c, $telemetry_table, "notefox-version", "server-datetime", 30),
        "browsers" => admin_stats_top($c, $telemetry_table, "browser", "server-datetime", 30),
        "os" => admin_stats_top($c, $telemetry_table, "os", "server-datetime", 30),
    );
}

$error_logs = null;
if (db_has_table($c, $error_logs_table) && db_has_column($c, $error_logs_table, "inserted-date")) {
    $error_logs = array(
        "errors-7d" => admin_stats_int($c, "SELECT COUNT(*) FROM `$error_logs_table` WHERE `inserted-date` >= DATE_SUB(NOW(), INTERVAL 7 DAY)"),
        "errors-30d" => admin_stats_int($c, "SELECT COUNT(*) FROM `$error_logs_table` WHERE `inserted-date` >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
        "contexts" => admin_stats_top($c, $error_logs_table, "context", "inserted-date", 7),
        "versions" => admin_stats_top($c, $error_logs_table, "notefox-version", "inserted-date", 7),
    );
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
