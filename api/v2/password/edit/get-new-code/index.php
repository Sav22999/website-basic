<?php
/**
 * POST /api/v2/password/edit/get-new-code
 *
 * Body: { "login-id": "...", "token": "...", "password": "...", "email": "..." }
 *
 * Issues a new code for a password change that has already been requested with
 * POST /api/v2/password/edit.
 *
 * Differences with v1: the endpoint does not exist in v1 (where the password
 * changes with no confirmation at all). Here the resend requires a valid token
 * and the current password, is rate limited (3 per 15 minutes), the address is
 * accepted only when its hash matches the authenticated account, and the new
 * code replaces the previous one resetting its expiry and attempt counter.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

$session = v2_require_auth($c);
if (!v2_verify_password($session["user"], $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

$user_id = $session["user-id"];

v2_rate_limit($c, "otp-resend", $user_id, 3, 900, 900);

global $users_table;

$email = v2_require_recipient($user_id, "email");

$columns = v2_codes_password();

if (!db_has_column($c, $users_table, $columns["code"])) {
    error_log("[sav-account] password/edit/get-new-code: the `password-change-code` column is missing");
    api_error(ERR_INTERNAL);
}

// A new code only makes sense when the change has been requested.
$user = v2_user_by_id($c, $user_id);
if ($user === null) {
    api_error(ERR_USER_NOT_FOUND);
}
if (!isset($user[$columns["code"]]) || $user[$columns["code"]] === null || $user[$columns["code"]] === "") {
    api_error(ERR_CODE_NOT_REQUESTED);
}

$issued = v2_code_issue($c, $users_table, "email", $user_id, $password, $columns, 15);
if ($issued === null) {
    api_error(ERR_INTERNAL);
}

v2_email_password_change_code(
    $email,
    $session["username"] === null ? "" : $session["username"],
    $issued["code"],
    $ip_address,
    $issued["expiry"],
    true
);

api_ok(array(
    "verification-required" => true,
    "verification-expiry" => $issued["expiry"],
));
?>
