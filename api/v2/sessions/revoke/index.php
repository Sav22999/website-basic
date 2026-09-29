<?php
/**
 * POST /api/v2/sessions/revoke
 *
 * Body: { "login-id": "...", "token": "...", "target": "..." }
 *
 * Revokes another active session of the same user, identified by its
 * full login-id (`target`). The caller cannot revoke their own current
 * session (use /logout for that). The target must belong to the same
 * user-id and must be active (status = 1).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$target_login_id = req_string("target", 512);

$user_id = $session["user-id"];
$current_login_id = $session["login"]["login-id"];

v2_rate_limit($c, "sessions-revoke", $session["user-id"], 20, 60, 60);

if ($target_login_id === $current_login_id) {
    api_error(ERR_MISSING_PARAMETERS, null, "Cannot revoke the current session. Use logout instead.");
}

global $logins_table, $tokens_table;

$target = db_select_one(
    $c,
    "SELECT `login-id`, `user-id` FROM `$logins_table` WHERE `login-id` = ? AND `user-id` = ? AND `status` = 1 LIMIT 1",
    "ss",
    array($target_login_id, $user_id)
);

if ($target === null) {
    api_error(ERR_LOGIN_ID);
}

try {
    db_tx($c, function ($c) use ($target_login_id, $logins_table, $tokens_table) {
        db_execute(
            $c,
            "UPDATE `$tokens_table` SET `status` = 0 WHERE `login-id` = ?",
            "s",
            array($target_login_id)
        );
        db_execute(
            $c,
            "UPDATE `$logins_table` SET `status` = 2 WHERE `login-id` = ?",
            "s",
            array($target_login_id)
        );
    });
} catch (Throwable $e) {
    error_log("[sav-account] sessions/revoke: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

api_ok(array(
    "revoked" => true,
    "target" => substr($target_login_id, 0, 8) . "…",
));
?>
