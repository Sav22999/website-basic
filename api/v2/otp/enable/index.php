<?php
/**
 * POST /api/v2/otp/enable
 *
 * Body: { "login-id": "...", "token": "...", "password": "...",
 *         "email": "..." (optional, only to send the notification) }
 *
 * Differences with v1: the endpoint does not exist in v1. Enabling the login
 * verification code (two-factor authentication) requires a valid token AND the
 * password, is rate limited, and the notification is sent only to an address
 * that really belongs to the account.
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

v2_rate_limit($c, "otp-change", $user_id, 10, 900, 900);

global $users_table;

// Without the additive column the state cannot be read nor written: the
// answer must say so, not pretend the OTP is already enabled.
if (!db_has_column($c, $users_table, "otp-enabled")) {
    error_log("[sav-account] otp/enable: the `otp-enabled` column is missing");
    api_error(ERR_INTERNAL);
}

if ($session["otp-enabled"]) {
    api_error(ERR_OTP_ALREADY_SET);
}

// Enabling needs no confirmation code: it only makes the account safer.
if (!v2_set_otp_enabled($c, $user_id, true)) {
    error_log("[sav-account] otp/enable: the `otp-enabled` column is missing");
    api_error(ERR_INTERNAL);
}

// A pending request to disable the OTP must not survive.
v2_code_clear($c, $users_table, "email", $user_id, v2_codes_otp_change());

$email = v2_optional_recipient($user_id, "email");
if ($email !== null) {
    v2_email_otp_changed($email, $session["username"] === null ? "" : $session["username"], true, $ip_address);
}

api_ok(array(
    "otp-enabled" => true,
));
?>
