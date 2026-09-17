<?php
/**
 * POST /api/v2/delete/verify/get-new-code
 *
 * Body: { "email": "...", "password": "..." }
 *
 * Issues a new deletion code for a deletion that has already been requested.
 *
 * Differences with v1:
 *   - the resend is rate limited (3 per 15 minutes on the email address): v1
 *     lets anybody who knows the credentials flood the mailbox;
 *   - the account is looked up by the hash of the email and the credentials are
 *     answered with one single generic error;
 *   - the new code comes from random_int(), replaces the previous one and
 *     resets its expiry and its attempt counter.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "otp-resend", $email, 3, 900, 900);

global $users_table;

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);

if ($user === null || !v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

// A new code only makes sense when the deletion has been requested.
if (!isset($user["deleting-code"]) || $user["deleting-code"] === null || $user["deleting-code"] === "") {
    api_error(ERR_CODE_NOT_REQUESTED);
}

$issued = v2_code_issue($c, $users_table, "email", $user_id, $password, v2_codes_delete(), 10);
if ($issued === null) {
    api_error(ERR_INTERNAL);
}

$username = decryptTextWithPassword($user["username"], $password);

v2_email_delete_code($email, $username === false ? "" : $username, $issued["code"], $ip_address, $issued["expiry"], true);

api_ok(array(
    "verification-required" => true,
    "verification-expiry" => $issued["expiry"],
));
?>
