<?php
/**
 * POST /api/v2/delete/verify
 *
 * Body: { "email": "...", "password": "...", "deleting-code": "..." }
 *
 * Differences with v1:
 *   - the code is compared in constant time, has an expiry and a limited number
 *     of attempts (v1 compares it with === and never limits the attempts);
 *   - the whole deletion happens inside a single transaction, so an account can
 *     never be left half deleted (v1 fires five independent statements);
 *   - the v2 tables are cleaned up too: the snapshots of EVERY service of the
 *     account, the data keys and the legacy rows of every service that has a
 *     v1 mirror table;
 *   - the rate limit counters of the address are forgotten after the deletion.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");
$code = req_code("deleting-code");

$c = db();

global $users_table, $logins_table, $tokens_table;

// Every service that still has a v1 mirror table (today only Notefox).
$legacy_tables = array();
foreach (sav_service_names() as $service_name) {
    $legacy_table = sav_service_legacy_table($service_name);
    if ($legacy_table !== null && !in_array($legacy_table, $legacy_tables, true)) {
        $legacy_tables[] = $legacy_table;
    }
}

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);

if ($user === null || !v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

v2_rate_limit($c, "delete-verify", $email, 5, 900, 900);

$result = v2_code_verify($c, $users_table, "email", $user_id, $user, $password, v2_codes_delete(), $code);
if ($result !== CODE_RESULT_OK) {
    api_error(v2_code_error($result));
}

$username = decryptTextWithPassword($user["username"], $password);

try {
    db_tx($c, function ($c) use ($user_id, $users_table, $logins_table, $tokens_table, $legacy_tables) {
        $deleted = db_execute(
            $c,
            "DELETE FROM `$tokens_table` WHERE `login-id` IN (SELECT `login-id` FROM `$logins_table` WHERE `user-id` = ?)",
            "s",
            array($user_id)
        );
        if ($deleted < 0) {
            throw new RuntimeException("tokens delete failed");
        }

        $deleted = db_execute($c, "DELETE FROM `$logins_table` WHERE `user-id` = ?", "s", array($user_id));
        if ($deleted < 0) {
            throw new RuntimeException("logins delete failed");
        }

        foreach ($legacy_tables as $legacy_table) {
            $deleted = db_execute($c, "DELETE FROM `$legacy_table` WHERE `user-id` = ?", "s", array($user_id));
            if ($deleted < 0) {
                throw new RuntimeException("legacy data delete failed");
            }
        }

        // Snapshots of every service of the account.
        v2_sync_delete_user($c, $user_id);
        v2_delete_user_keys($c, $user_id);

        $deleted = db_execute($c, "DELETE FROM `$users_table` WHERE `email` = ?", "s", array($user_id));
        if ($deleted < 0) {
            throw new RuntimeException("user delete failed");
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] delete/verify: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

v2_rate_limit_forget_subject($c, $email);

v2_email_deleted($email, $username === false ? "" : $username);

api_ok(array(
    "deleted" => true,
));
?>
