<?php
/**
 * POST /api/v2/signup
 *
 * Body: { "username": "...", "password": "...", "email": "..." }
 *
 * RULE - THE EMAIL VERIFICATION CODE IS ALWAYS MANDATORY.
 * The email address is the root of the identity: `users`.`email` is the
 * SHA-512 hash used as the user-id, and the whole encryption chain of the
 * account hangs from it. An account must therefore never become `verified`
 * without having proved the ownership of the mailbox, otherwise a typo would
 * create an account encrypted on somebody else's address.
 * `otp-enabled` is a LOGIN setting only: this file must never read it, and no
 * condition may ever skip v2_code_issue() below. A regression check in
 * api/v2/tests/smoke.php fails if this file starts looking at that flag.
 *
 * Differences with v1:
 *   - the answer is always the same whether the email is free, already used or
 *     used but not verified (v1 replies 416/419 and therefore lets anyone
 *     enumerate the registered accounts);
 *   - the verification code is generated with random_int(), has an expiry and
 *     a maximum number of attempts;
 *   - rate limited per IP and per email address.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");
$username = req_string("username", 256);

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "signup-ip", $ip_address, 10, 3600, 3600);
v2_rate_limit($c, "signup-email", $email, 5, 3600, 3600);

global $users_table;

$user_id = v2_email_hash($email);
$now = getTimestamp();
$answer = array("verification-required" => true);

$user = v2_user_by_id($c, $user_id);

if ($user !== null && $user["verified"] !== null) {
    // Already a verified account: same answer as a brand new signup, and no
    // email is sent. Nothing here tells the caller the account exists.
    api_ok($answer);
}

$encrypted_username = encryptTextWithPassword($username, $password);
$password_hash = encryptHash($password);

try {
    $issued = db_tx($c, function ($c) use ($user, $user_id, $encrypted_username, $password_hash, $ip_address, $now, $password, $users_table) {
        if ($user === null) {
            $inserted = db_execute(
                $c,
                "INSERT INTO `$users_table` (`username`, `email`, `password`, `ip-address`, `created`, `verification-code`, `verified`, `status`) VALUES (?, ?, ?, ?, ?, NULL, NULL, 0)",
                "sssss",
                array($encrypted_username, $user_id, $password_hash, $ip_address, $now)
            );
            if ($inserted < 0) {
                throw new RuntimeException("signup insert failed");
            }
        } else {
            // Signup never completed: the account is not owned by anybody yet,
            // so the new credentials replace the pending ones.
            $updated = db_execute(
                $c,
                "UPDATE `$users_table` SET `username` = ?, `password` = ?, `ip-address` = ? WHERE `email` = ? AND `verified` IS NULL",
                "ssss",
                array($encrypted_username, $password_hash, $ip_address, $user_id)
            );
            if ($updated < 0) {
                throw new RuntimeException("signup update failed");
            }
        }

        // Always issued, unconditionally: see the RULE at the top of the file.
        return v2_code_issue($c, $users_table, "email", $user_id, $password, v2_codes_signup(), 60);
    });
} catch (Throwable $e) {
    error_log("[sav-account] signup: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

if ($issued === null) {
    api_error(ERR_INTERNAL);
}

v2_refresh_password_hash($c, $user_id, $password);
v2_email_signup_code($email, $username, $issued["code"], $ip_address, $issued["expiry"], $user !== null);

api_ok($answer);
?>
