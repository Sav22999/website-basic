<?php
/**
 * POST /api/v2/password/edit/verify
 *
 * Body: { "login-id": "...", "token": "...", "password": "...",
 *         "new-password": "...", "verification-code": "...",
 *         "email": "..." (optional, only to send the notification) }
 *
 * Second and last step of the password change: confirms the request of
 * POST /api/v2/password/edit and applies it.
 *
 * Differences with v1:
 *   - v1 has no confirmation step at all: the password alone changes the
 *     password. Here the code emailed by the first step is compared in constant
 *     time, the attempts are limited and the code is consumed, so it can never
 *     be replayed;
 *   - the notes are NEVER re-encrypted: only the wrapped data key (DEK) and the
 *     single legacy mirror row of every service that has one are re-written.
 *     v1 re-encrypts `LIMIT 50` rows of `data` and leaves the older ones
 *     permanently unreadable;
 *   - the data key is a single one for the whole account, so the data of every
 *     service stays readable after the change with one single re-wrap;
 *   - the rows are matched by their key (`email`, id), never by ciphertext;
 *   - the answer is always a real payload: v1 can end the request with a silent
 *     null when the while() loop over the token rows never matches;
 *   - `$login_id` is not overwritten in the middle of a loop;
 *   - the new password is validated (minimum length, must differ) and the
 *     endpoint is rate limited;
 *   - every session is invalidated (the old tokens wrap the old password) and a
 *     brand new session is returned to the caller;
 *   - everything is written inside a single transaction.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

define("V2_PASSWORD_MIN_LENGTH", 8);

$c = db();
$ip_address = req_ip_address();

$session = v2_require_auth($c);

$password = req_password("password");
$new_password = req_password("new-password");
$code = req_code("verification-code");

if (!v2_verify_password($session["user"], $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}
if (strlen($new_password) < V2_PASSWORD_MIN_LENGTH) {
    api_error(ERR_MISSING_PARAMETERS);
}
if ($new_password === $password) {
    api_error(ERR_MISSING_PARAMETERS);
}

$user_id = $session["user-id"];

v2_rate_limit($c, "password-edit-verify", $user_id, 5, 900, 900);

global $users_table;

$columns = v2_codes_password();

// Without the additive columns no code could have been issued: answering with
// an internal error is the only honest answer here.
if (!db_has_column($c, $users_table, $columns["code"]) || !v2_code_column_available($c, $users_table, $columns, "expiry")) {
    error_log("[sav-account] password/edit/verify: the `password-change-code`/`password-change-expiry` columns are missing");
    api_error(ERR_INTERNAL);
}

// The row is read again: the code has been issued after the session was
// authenticated.
$user = v2_user_by_id($c, $user_id);
if ($user === null) {
    api_error(ERR_USER_NOT_FOUND);
}

$result = v2_code_verify($c, $users_table, "email", $user_id, $user, $password, $columns, $code);
if ($result !== CODE_RESULT_OK) {
    api_error(v2_code_error($result));
}

// Resolved before the transaction: the address is only accepted when its hash
// matches the account of the session.
$recipient = v2_optional_recipient($user_id, "email");

try {
    $created = db_tx($c, function ($c) use ($user_id, $user, $password, $new_password, $ip_address, $users_table, $columns) {
        // a) only the data key is re-wrapped: the notes stay as they are.
        if (!v2_rewrap_user_key($c, $user_id, $password, $new_password)) {
            throw new RuntimeException("key rewrap failed");
        }

        // b) the username is the only user field encrypted with the password.
        $username = decryptTextWithPassword($user["username"], $password);
        if ($username === false || $username === null) {
            throw new RuntimeException("username cannot be decrypted");
        }
        $new_username = encryptTextWithPassword($username, $new_password);

        // c) matched by email (the user-id), never by the password ciphertext.
        $updated = db_execute(
            $c,
            "UPDATE `$users_table` SET `password` = ?, `username` = ? WHERE `email` = ?",
            "sss",
            array(encryptHash($new_password), $new_username, $user_id)
        );
        if ($updated < 0) {
            throw new RuntimeException("user update failed");
        }

        // d) the code is consumed: it can never be replayed.
        if (!v2_code_clear($c, $users_table, "email", $user_id, $columns)) {
            throw new RuntimeException("code clear failed");
        }

        // e) the legacy mirror rows kept for the v1 clients, one per service
        // that declares a legacy table (today only Notefox).
        if (!v2_sync_rewrite_legacy_mirrors($c, $user_id, $password, $new_password)) {
            throw new RuntimeException("legacy mirror rewrite failed");
        }

        // f) every token wraps the old password: all the sessions have to go,
        // the caller gets a new one.
        v2_invalidate_sessions($c, $user_id);

        $created = v2_create_session($c, $user_id, $new_password, $ip_address, null);
        if ($created === null) {
            throw new RuntimeException("session creation failed");
        }

        return $created;
    });
} catch (Throwable $e) {
    error_log("[sav-account] password/edit/verify: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

v2_rate_limit_reset($c, "password-edit-verify", $user_id);
v2_rate_limit_reset($c, "password-edit", $user_id);

v2_refresh_password_hash($c, $user_id, $new_password);

if ($recipient !== null) {
    $username = $session["username"];
    v2_email_password_changed($recipient, $username === null ? "" : $username, $ip_address);
}

api_ok(array(
    "login-id" => $created["login-id"],
    "token" => $created["token"],
    "expiry" => null,
));
?>
