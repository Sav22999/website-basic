<?php
/**
 * POST /api/v2/login
 *
 * Body: { "email": "...", "password": "..." }
 *
 * With the OTP enabled (default) the answer is the same two-step flow as v1:
 *   { "otp-required": true, "login-id": "..." }
 * With the OTP disabled the session is issued straight away:
 *   { "otp-required": false, "login-id": "...", "token": "...", "username": "..." }
 *
 * Differences with v1: real transaction instead of the misplaced LOCK TABLES,
 * unpredictable login-id (random_bytes instead of sha512(email+ip+time)),
 * rate limiting, and the data key created/opened at login.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");
$source = req_optional_string("source", 16);

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "login", $email, 10, 900, 900);
v2_rate_limit($c, "login-ip", $ip_address, 30, 900, 900);

global $users_table, $logins_table;

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);

if ($user === null || !v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}
if (((int)$user["status"]) !== 1 || $user["verified"] === null) {
    api_error(ERR_USER_NOT_ACTIVE);
}

v2_rate_limit_reset($c, "login", $email);
v2_refresh_password_hash($c, $user_id, $password);

$username = decryptTextWithPassword($user["username"], $password);
if ($username === false) {
    $username = null;
}

// Lazy migration: creates the data key (and imports the legacy data) the
// first time this account is used through the v2 API.
$dek = v2_get_or_create_dek($c, $user_id, $password);

if (!v2_otp_enabled($c, $user)) {
    try {
        $session = db_tx($c, function ($c) use ($user_id, $password, $ip_address) {
            $created = v2_create_session($c, $user_id, $password, $ip_address, null);
            if ($created === null) {
                throw new RuntimeException("session creation failed");
            }
            return $created;
        });
    } catch (Throwable $e) {
        error_log("[sav-account] login: " . $e->getMessage());
        api_error(ERR_INTERNAL);
    }

    v2_email_logged_in($email, $username === null ? "" : $username, $ip_address, $source);

    api_ok(array(
        "otp-required" => false,
        "login-id" => $session["login-id"],
        "token" => $session["token"],
        "expiry" => $session["expiry"],
        "username" => $username,
        "encryption-ready" => $dek !== null,
    ));
}

$login_id = v2_random_id(32);
$verification_expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

try {
    $issued = db_tx($c, function ($c) use ($login_id, $user_id, $ip_address, $verification_expiry, $password, $logins_table) {
        $inserted = db_execute(
            $c,
            "INSERT INTO `$logins_table` (`login-id`, `user-id`, `expiry`, `status`, `ip-address`, `verified`, `verification-code`, `verification-expiry`) VALUES (?, ?, NULL, 0, ?, NULL, NULL, ?)",
            "ssss",
            array($login_id, $user_id, $ip_address, $verification_expiry)
        );
        if ($inserted < 0) {
            throw new RuntimeException("login insert failed");
        }

        return v2_code_issue($c, $logins_table, "login-id", $login_id, $password, v2_codes_login(), 30);
    });
} catch (Throwable $e) {
    error_log("[sav-account] login: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

if ($issued === null) {
    api_error(ERR_INTERNAL);
}

v2_email_login_code($email, $username === null ? "" : $username, $issued["code"], $ip_address, $issued["expiry"], false, $source);

api_ok(array(
    "otp-required" => true,
    "login-id" => $login_id,
    "verification-expiry" => $issued["expiry"],
));
?>
