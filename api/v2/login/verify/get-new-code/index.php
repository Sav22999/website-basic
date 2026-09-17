<?php
/**
 * POST /api/v2/login/verify/get-new-code
 *
 * Body: { "login-id": "...", "email": "...", "password": "..." }
 *
 * Issues a new login OTP for a pending session. Rate limited per login-id and
 * per IP; the previous code stops working immediately.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$login_id = req_string("login-id", 512);
$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "otp-resend", $login_id, 3, 900, 1800);
v2_rate_limit($c, "otp-resend-ip", $ip_address, 10, 900, 1800);

global $logins_table;

$login = db_select_one(
    $c,
    "SELECT * FROM `$logins_table` WHERE `login-id` = ? AND `status` = 0 AND `verified` IS NULL LIMIT 1",
    "s",
    array($login_id)
);
if ($login === null) {
    api_error(ERR_LOGIN_ID);
}

$user = v2_user_by_id($c, $login["user-id"]);
if ($user === null) {
    api_error(ERR_USER_NOT_FOUND);
}
if (!v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

$email = v2_require_recipient($login["user-id"], "email");

$verification_expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
$updated = db_execute(
    $c,
    "UPDATE `$logins_table` SET `verification-expiry` = ? WHERE `login-id` = ?",
    "ss",
    array($verification_expiry, $login_id)
);
if ($updated < 0) {
    api_error(ERR_INTERNAL);
}

$issued = v2_code_issue($c, $logins_table, "login-id", $login_id, $password, v2_codes_login(), 30);
if ($issued === null) {
    api_error(ERR_INTERNAL);
}

$username = decryptTextWithPassword($user["username"], $password);
v2_email_login_code($email, $username === false ? "" : $username, $issued["code"], $ip_address, $issued["expiry"], true);

api_ok(array(
    "otp-required" => true,
    "login-id" => $login_id,
    "verification-expiry" => $issued["expiry"],
));
?>
