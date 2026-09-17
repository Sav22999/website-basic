<?php
/**
 * POST /api/v2/otp/disable
 *
 * Body: { "login-id": "...", "token": "...", "password": "...", "email": "..." }
 *
 * Differences with v1: the endpoint does not exist in v1. Disabling the login
 * verification code (two-factor authentication) lowers the security of the
 * account, so it is never immediate: a valid token, the password and a code
 * emailed to the address of the account are all required. The address is
 * mandatory and accepted only when its hash matches the authenticated account.
 *
 * When block 6 of the additive DDL (`otp-change-code`/`otp-change-expiry`) has
 * not been applied the code could not be stored nor checked by
 * POST /api/v2/otp/disable/verify: the answer is then a clean `503`, never a
 * `500` and never a disabled OTP without its confirmation code.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

$session = v2_require_auth($c);
if (!v2_verify_password($session["user"], $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

$user_id = $session["user-id"];

v2_rate_limit($c, "otp-change", $user_id, 5, 900, 900);

if (!$session["otp-enabled"]) {
    api_error(ERR_OTP_ALREADY_SET);
}

global $users_table;

$columns = v2_codes_otp_change();

// Block 6 of the additive DDL is optional: without those columns the
// confirmation code cannot be stored, and the OTP must never be disabled
// without it. The condition is exactly the one of POST /otp/disable/verify, so
// a code is never emailed for a confirmation that could not succeed. The flow
// is unavailable on this installation (503, like the data endpoints without
// the `service` column) instead of failing with an opaque 500, and
// `GET /status` reports the missing column.
if (!db_has_column($c, $users_table, $columns["code"]) || !v2_code_column_available($c, $users_table, $columns, "expiry")) {
    error_log("[sav-account] otp/disable: the `otp-change-code`/`otp-change-expiry` columns are missing");
    api_error(ERR_UNAVAILABLE);
}

// The code is emailed, so here the address is not optional.
$email = v2_require_recipient($user_id, "email");

$issued = v2_code_issue($c, $users_table, "email", $user_id, $password, $columns, 15);
if ($issued === null) {
    error_log("[sav-account] otp/disable: the `otp-change-code` column could not be written");
    api_error(ERR_INTERNAL);
}

v2_email_otp_disable_code(
    $email,
    $session["username"] === null ? "" : $session["username"],
    $issued["code"],
    $ip_address,
    $issued["expiry"]
);

api_ok(array(
    "verification-required" => true,
    "verification-expiry" => $issued["expiry"],
));
?>
