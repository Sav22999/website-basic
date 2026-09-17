<?php
/**
 * POST /api/v2/login/verify
 *
 * Body: { "login-id": "...", "email": "...", "password": "...",
 *         "verification-code": "..." }
 *
 * Differences with v1:
 *   - the confirmation email goes to the address of the account, not to the
 *     one contained in the payload (v1 lets an attacker choose the recipient);
 *   - the code is consumed and the attempts are limited;
 *   - login row + token row are written in a single transaction.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$login_id = req_string("login-id", 512);
$password = req_password("password");
$code = req_code("verification-code");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "otp-verify", $login_id, 5, 900, 1800);

global $logins_table, $users_table, $tokens_table;

$login = db_select_one(
    $c,
    "SELECT * FROM `$logins_table` WHERE `login-id` = ? AND `status` = 0 AND `verified` IS NULL AND (`expiry` > NOW() OR `expiry` IS NULL) AND (`verification-expiry` > NOW() OR `verification-expiry` IS NULL) LIMIT 1",
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

// The address is only used to send the notification, and only when it really
// belongs to this account.
$email = v2_require_recipient($login["user-id"], "email");

$result = v2_code_verify($c, $logins_table, "login-id", $login_id, $login, $password, v2_codes_login(), $code);
if ($result !== CODE_RESULT_OK) {
    $username = decryptTextWithPassword($user["username"], $password);
    v2_email_login_verification_failed($email, $username === false ? "" : $username, $ip_address);
    api_error(v2_code_error($result));
}

$now = getTimestamp();
$token = v2_random_id(32);
$password_token = encryptTextWithPassword($password, $token);
$expiry = null;

try {
    db_tx($c, function ($c) use ($login_id, $now, $password_token, $expiry, $ip_address, $logins_table, $tokens_table) {
        $updated = db_execute(
            $c,
            "UPDATE `$logins_table` SET `status` = 1, `verification-code` = NULL, `verification-expiry` = NULL, `verified` = ? WHERE `login-id` = ? AND `status` = 0 AND `verified` IS NULL",
            "ss",
            array($now, $login_id)
        );
        if ($updated <= 0) {
            throw new RuntimeException("login already verified or gone");
        }

        $inserted = db_execute(
            $c,
            "INSERT INTO `$tokens_table` (`id`, `password`, `login-id`, `expiry`, `ip-address`, `inserted-date`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, 1)",
            "sssss",
            array($password_token, $login_id, $expiry, $ip_address, $now)
        );
        if ($inserted < 0) {
            throw new RuntimeException("token insert failed");
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] login/verify: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

v2_rate_limit_reset($c, "otp-verify", $login_id);
v2_refresh_password_hash($c, $login["user-id"], $password);

$dek = v2_get_or_create_dek($c, $login["user-id"], $password);
$username = decryptTextWithPassword($user["username"], $password);

v2_email_logged_in($email, $username === false ? "" : $username, $ip_address);

api_ok(array(
    "login-id" => $login_id,
    "token" => $token,
    "expiry" => $expiry,
    "username" => $username === false ? null : $username,
    "encryption-ready" => $dek !== null,
));
?>
