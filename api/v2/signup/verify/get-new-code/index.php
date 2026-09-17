<?php
/**
 * POST /api/v2/signup/verify/get-new-code
 *
 * Body: { "email": "...", "password": "..." }
 *
 * Sends a new signup verification code. The answer never changes, so it cannot
 * be used to find out which addresses are registered; the rate limit prevents
 * using it to bomb a mailbox (v1 has no limit at all).
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "otp-resend", $email, 3, 900, 1800);
v2_rate_limit($c, "otp-resend-ip", $ip_address, 10, 900, 1800);

global $users_table;

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);
$answer = array("verification-required" => true);

if ($user === null || $user["verified"] !== null || !v2_verify_password($user, $password)) {
    api_ok($answer);
}

$issued = v2_code_issue($c, $users_table, "email", $user_id, $password, v2_codes_signup(), 60);
if ($issued === null) {
    api_error(ERR_INTERNAL);
}

$username = decryptTextWithPassword($user["username"], $password);
v2_email_signup_code($email, $username === false ? "" : $username, $issued["code"], $ip_address, $issued["expiry"], true);

api_ok($answer);
?>
