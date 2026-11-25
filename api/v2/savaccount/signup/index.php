<?php
/**
 * POST /api/v2/savaccount/signup
 *
 * Body: { "ticket": "...", "username": "...", "password": "...",
 *         "source": "web" | "addon" (optional) }
 *
 * Second step of "signup-required": creates the account with the email address
 * SavAccount has verified, and links it. The password is still needed: it is
 * what the notes are encrypted with.
 *
 * The rule of /signup (the ownership of the mailbox is always proved) holds:
 * the address comes from the ID token (`email_verified` is checked), never
 * from the request, so there is no code to send.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$password = req_password("password");
$username = req_string("username", 256);
$source = v2_savaccount_source();
if (strlen($password) < SAVACCOUNT_PASSWORD_MIN_LENGTH) {
    api_error(ERR_MISSING_PARAMETERS);
}

$c = db();
v2_savaccount_require($c);
$ip_address = req_ip_address();
v2_rate_limit($c, "signup-ip", $ip_address, 10, 3600, 3600);

$row = v2_savaccount_require_ticket($c, "signup");
$who = $row["payload"];
if (!v2_savaccount_row_consume($c, $row)) {
    api_error(ERR_SAVACCOUNT_EXPIRED);
}

global $users_table;

$user_id = v2_email_hash($who["email"]);
$now = getTimestamp();
$encrypted_username = encryptTextWithPassword($username, $password);
$password_hash = encryptHash($password);

if (v2_savaccount_link_by_sub($c, $who["sub"]) !== null) {
    $owner = v2_savaccount_link_by_sub($c, $who["sub"]);
    if (v2_user_by_id($c, $owner["user-id"]) !== null) {
        api_error(ERR_SAVACCOUNT_IN_USE);
    }
    v2_savaccount_forget_user($c, $owner["user-id"]);
}

try {
    $created = db_tx($c, function ($c) use ($user_id, $encrypted_username, $password_hash, $ip_address, $now, $password, $users_table) {
        $existing = v2_user_by_id($c, $user_id);
        if ($existing !== null && $existing["verified"] !== null) {
            return false;
        }
        if ($existing === null) {
            $written = db_execute(
                $c,
                "INSERT INTO `$users_table` (`username`, `email`, `password`, `ip-address`, `created`, `verification-code`, `verified`, `status`) VALUES (?, ?, ?, ?, ?, NULL, ?, 1)",
                "ssssss",
                array($encrypted_username, $user_id, $password_hash, $ip_address, $now, $now)
            );
        } else {
            // A signup by email never completed: nobody owns it yet.
            $written = db_execute(
                $c,
                "UPDATE `$users_table` SET `username` = ?, `password` = ?, `ip-address` = ?, `verification-code` = NULL, `verified` = ?, `status` = 1 WHERE `email` = ? AND `verified` IS NULL",
                "sssss",
                array($encrypted_username, $password_hash, $ip_address, $now, $user_id)
            );
        }
        if ($written !== 1) {
            throw new RuntimeException("account creation failed");
        }
        return true;
    });
} catch (Throwable $e) {
    error_log("[sav-account] savaccount/signup: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

if (!$created) {
    api_error(ERR_ALREADY_VERIFIED);
}

$dek = v2_get_or_create_dek($c, $user_id, $password);
if (!v2_savaccount_link_save($c, $user_id, $who["sub"], $password, $who["email"])) {
    error_log("[sav-account] savaccount/signup: the account has been created but not linked");
}

v2_email_signed_up($who["email"], $username, $ip_address);

v2_savaccount_answer_session($c, v2_user_by_id($c, $user_id), $password, $who["email"], $source, array("created" => true));
?>
