<?php
/**
 * POST /api/v2/otp/disable/verify
 *
 * Body: { "login-id": "...", "token": "...", "password": "...",
 *         "verification-code": "...", "email": "..." (optional) }
 *
 * Differences with v1: the endpoint does not exist in v1. Confirms the request
 * of api/v2/otp/disable: the code is compared in constant time, the attempts
 * are limited and the code is consumed, so it can never be replayed. When the
 * additive columns are missing no code could have been issued, so the flow is
 * unavailable (`503`) exactly like POST /api/v2/otp/disable: it never fails
 * with a fatal error nor with an opaque `500`.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$password = req_password("password");
$code = req_code("verification-code");

$c = db();
$ip_address = req_ip_address();

$session = v2_require_auth($c);
if (!v2_verify_password($session["user"], $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

$user_id = $session["user-id"];

v2_rate_limit($c, "otp-change-verify", $user_id, 5, 900, 900);

global $users_table;

$columns = v2_codes_otp_change();

// Without the additive columns no code could have been issued: the flow is
// unavailable on this installation, not broken (`GET /status` reports which
// column is missing).
if (!db_has_column($c, $users_table, $columns["code"]) || !v2_code_column_available($c, $users_table, $columns, "expiry")) {
    error_log("[sav-account] otp/disable/verify: the `otp-change-code`/`otp-change-expiry` columns are missing");
    api_error(ERR_UNAVAILABLE);
}

// The row is read again: the code may have been issued after the session was
// authenticated.
$user = v2_user_by_id($c, $user_id);
if ($user === null) {
    api_error(ERR_USER_NOT_FOUND);
}

$result = v2_code_verify($c, $users_table, "email", $user_id, $user, $password, $columns, $code);
if ($result !== CODE_RESULT_OK) {
    api_error(v2_code_error($result));
}

try {
    db_tx($c, function ($c) use ($user_id, $columns, $users_table) {
        if (!v2_set_otp_enabled($c, $user_id, false)) {
            throw new RuntimeException("the `otp-enabled` column is missing");
        }
        if (!v2_code_clear($c, $users_table, "email", $user_id, $columns)) {
            throw new RuntimeException("code clear failed");
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] otp/disable/verify: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

v2_rate_limit_reset($c, "otp-change-verify", $user_id);

$email = v2_optional_recipient($user_id, "email");
if ($email !== null) {
    v2_email_otp_changed($email, $session["username"] === null ? "" : $session["username"], false, $ip_address);
}

api_ok(array(
    "otp-enabled" => false,
));
?>
