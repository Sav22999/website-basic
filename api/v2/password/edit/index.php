<?php
/**
 * POST /api/v2/password/edit
 *
 * Body: { "login-id": "...", "token": "...", "password": "...",
 *         "new-password": "..." (optional, only validated here),
 *         "email": "..." }
 *
 * First step of the password change: nothing is written yet, a confirmation
 * code is emailed to the address of the account. The change is applied only by
 * POST /api/v2/password/edit/verify.
 *
 * Differences with v1:
 *   - v1 changes the password immediately, with the password alone: a stolen
 *     token (or a session left open) was enough to take an account over. Here
 *     the second factor is ALWAYS required, exactly like for the account
 *     deletion, no matter the value of `otp-enabled` (which only concerns the
 *     login);
 *   - a valid token AND the current password are both required;
 *   - the address is accepted only when its hash matches the authenticated
 *     account, so the code can never be sent elsewhere;
 *   - the endpoint is rate limited and the code has an expiry and a maximum
 *     number of attempts.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

define("V2_PASSWORD_MIN_LENGTH", 8);

$c = db();
$ip_address = req_ip_address();

$session = v2_require_auth($c);

$password = req_password("password");

if (!v2_verify_password($session["user"], $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

// The new password is optional here (it is mandatory on the verify step), but
// when the client already sends it the rules are checked before an email is
// sent for a change that could never be completed.
$body = req_body();
if (isset($body["new-password"]) && is_string($body["new-password"]) && $body["new-password"] !== "") {
    $new_password = $body["new-password"];
    if (strlen($new_password) < V2_PASSWORD_MIN_LENGTH || $new_password === $password) {
        api_error(ERR_MISSING_PARAMETERS);
    }
}

$user_id = $session["user-id"];

v2_rate_limit($c, "password-edit", $user_id, 5, 900, 900);

global $users_table;

// The code is emailed, so here the address is not optional.
$email = v2_require_recipient($user_id, "email");

$columns = v2_codes_password();

// Without the additive column the code could not be stored: the change must
// not be applied without its second factor, so the request fails cleanly.
if (!db_has_column($c, $users_table, $columns["code"])) {
    error_log("[sav-account] password/edit: the `password-change-code` column is missing");
    api_error(ERR_INTERNAL);
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
    false
);

api_ok(array(
    "verification-required" => true,
    "verification-expiry" => $issued["expiry"],
));
?>
