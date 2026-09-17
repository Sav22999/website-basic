<?php
/**
 * POST /api/v2/logout
 *
 * Body: { "login-id": "...", "token": "...",
 *         "all-devices": true|false (optional, also accepted as
 *         ?all-devices=true in the query string) }
 *
 * Differences with v1: api/v1/logout/index.php only needs the `login-id`, so
 * anybody knowing (or guessing) it can terminate other people's sessions, even
 * on every device. Here the `token` is mandatory and the two writes (tokens +
 * logins) are done in a single transaction instead of the useless LOCK TABLES
 * of v1.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$login_id = $session["login"]["login-id"];
$user_id = $session["user-id"];
$all_devices = req_bool("all-devices", false);

global $logins_table, $tokens_table;

try {
    db_tx($c, function ($c) use ($login_id, $user_id, $all_devices, $logins_table, $tokens_table) {
        if ($all_devices) {
            v2_invalidate_sessions($c, $user_id);
            return;
        }

        $updated = db_execute(
            $c,
            "UPDATE `$tokens_table` SET `status` = 0 WHERE `login-id` = ?",
            "s",
            array($login_id)
        );
        if ($updated < 0) {
            throw new RuntimeException("tokens update failed");
        }

        $updated = db_execute(
            $c,
            "UPDATE `$logins_table` SET `status` = 2 WHERE `login-id` = ?",
            "s",
            array($login_id)
        );
        if ($updated < 0) {
            throw new RuntimeException("login update failed");
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] logout: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

api_ok(array(
    "logged-out" => true,
    "all-devices" => $all_devices,
));
?>
