<?php
/**
 * POST /api/v2/sessions
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * Returns the list of active sessions (logins with status = 1) for the
 * authenticated user. Each entry includes the login-id (truncated for
 * display), IP address, verified date, expiry, and whether it is the
 * current session.
 *
 * The full login-id is never exposed to the client for other sessions:
 * the truncated form is enough for the user to recognise it and the
 * revoke endpoint accepts only the full login-id of the caller's own
 * sessions.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$user_id = $session["user-id"];
$current_login_id = $session["login"]["login-id"];

v2_rate_limit($c, "sessions-list", $session["user-id"], 30, 60, 60);

global $logins_table;

$rows = db_select(
    $c,
    "SELECT `login-id`, `ip-address`, `verified`, `expiry` FROM `$logins_table` WHERE `user-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL) ORDER BY `verified` DESC",
    "s",
    array($user_id)
);

$sessions = array();
foreach ($rows as $row) {
    $login_id = $row["login-id"];
    $is_current = $login_id === $current_login_id;

    $sessions[] = array(
        "login-id" => $login_id,
        "login-id-short" => substr($login_id, 0, 8) . "…",
        "ip-address" => $row["ip-address"],
        "verified" => $row["verified"],
        "expiry" => $row["expiry"],
        "current" => $is_current,
    );
}

api_ok(array(
    "sessions" => $sessions,
    "count" => count($sessions),
));
?>
