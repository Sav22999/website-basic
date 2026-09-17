<?php
/**
 * POST /api/v2/signup/verify
 *
 * Body: { "email": "...", "password": "...", "verification-code": "..." }
 *
 * Completes the signup: activates the account, creates the data key (DEK) and
 * consumes the code, which can never be replayed.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");
$code = req_code("verification-code");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "signup-verify", $email, 10, 900, 900);

global $users_table;

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);

if ($user === null || !v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}
if ($user["verified"] !== null) {
    api_error(ERR_ALREADY_VERIFIED);
}

$result = v2_code_verify($c, $users_table, "email", $user_id, $user, $password, v2_codes_signup(), $code);
if ($result !== CODE_RESULT_OK) {
    api_error(v2_code_error($result));
}

$now = getTimestamp();

try {
    db_tx($c, function ($c) use ($user_id, $now, $password, $users_table) {
        $updated = db_execute(
            $c,
            "UPDATE `$users_table` SET `status` = 1, `verified` = ? WHERE `email` = ? AND `verified` IS NULL",
            "ss",
            array($now, $user_id)
        );
        if ($updated < 0) {
            throw new RuntimeException("verify update failed");
        }
        v2_code_clear($c, $users_table, "email", $user_id, v2_codes_signup());
    });
} catch (Throwable $e) {
    error_log("[sav-account] signup/verify: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

v2_refresh_password_hash($c, $user_id, $password);

// The data key is created now, while the plaintext password is available.
$dek = v2_get_or_create_dek($c, $user_id, $password);

$username = decryptTextWithPassword($user["username"], $password);
v2_email_signed_up($email, $username === false ? "" : $username, $ip_address);

v2_rate_limit_reset($c, "signup-verify", $email);

api_ok(array(
    "verified" => true,
    "username" => $username === false ? null : $username,
    "encryption-ready" => $dek !== null,
));
?>
