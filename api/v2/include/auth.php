<?php
/**
 * Sav Account API v2 - authentication.
 *
 * Single implementation of the logins -> users -> tokens chain that v1 copies
 * into every endpoint. Two behaviours change on purpose:
 *   - a valid `token` is ALWAYS required (v1 lets /logout and
 *     /data/get/last-update work with the login-id alone);
 *   - the plaintext password is recovered from the token (v1 semantics, kept
 *     for compatibility) and used to unwrap the DEK.
 *
 * Chain: token -> password -> KEK -> DEK -> data.
 */

function v2_email_hash($email)
{
    return encryptHash(strtolower(trim($email)));
}

function v2_user_by_id($c, $user_id)
{
    global $users_table;
    return db_select_one($c, "SELECT * FROM `$users_table` WHERE `email` = ? LIMIT 1", "s", array($user_id));
}

function v2_user_by_email($c, $email)
{
    return v2_user_by_id($c, v2_email_hash($email));
}

/**
 * Password check.
 *
 * The SHA-512 column stays authoritative because v1 still writes it; the
 * additive `password-v2` column (password_hash) is kept aligned at every
 * successful check so it can become authoritative once v1 is retired.
 */
function v2_verify_password($user_row, $password)
{
    if (!is_array($user_row) || !isset($user_row["password"])) {
        return false;
    }
    return hash_equals((string)$user_row["password"], encryptHash($password));
}

/**
 * Re-aligns `password-v2` with the current password (no-op when the additive
 * column has not been created yet).
 */
function v2_refresh_password_hash($c, $user_id, $password)
{
    global $users_table;
    if (!db_has_column($c, $users_table, "password-v2")) {
        return;
    }

    $row = db_select_one($c, "SELECT `password-v2` FROM `$users_table` WHERE `email` = ? LIMIT 1", "s", array($user_id));
    if ($row !== null && is_string($row["password-v2"]) && $row["password-v2"] !== "" && password_verify($password, $row["password-v2"])) {
        return;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    db_execute($c, "UPDATE `$users_table` SET `password-v2` = ? WHERE `email` = ?", "ss", array($hash, $user_id));
}

/**
 * OTP state of an account. Defaults to enabled: both when the additive column
 * is missing and when the value is NULL.
 */
function v2_otp_enabled($c, $user_row)
{
    global $users_table;
    if (!is_array($user_row)) {
        return true;
    }
    if (!array_key_exists("otp-enabled", $user_row)) {
        return true;
    }
    if ($user_row["otp-enabled"] === null) {
        return true;
    }
    return ((int)$user_row["otp-enabled"]) === 1;
}

function v2_set_otp_enabled($c, $user_id, $enabled)
{
    global $users_table;
    if (!db_has_column($c, $users_table, "otp-enabled")) {
        return false;
    }
    $value = $enabled ? 1 : 0;
    return db_execute($c, "UPDATE `$users_table` SET `otp-enabled` = ? WHERE `email` = ?", "is", array($value, $user_id)) >= 0;
}

/**
 * Sync history permission of an account. The mirror image of v2_otp_enabled():
 * here the default is DENIED, both when the additive column is missing and when
 * the value is NULL, so the history endpoints stay closed until the permission
 * is granted explicitly - an installation that has not run block 10 of
 * migration.sql exposes the history to nobody instead of to everybody.
 *
 * There is no endpoint to change it on purpose: a user must never be able to
 * grant it to themselves. It is set by hand, per account:
 *   UPDATE `users` SET `history-enabled` = 1 WHERE `email` = '<sha512 of the email>';
 */
function v2_history_enabled($c, $user_row)
{
    if (!is_array($user_row)) {
        return false;
    }
    if (!array_key_exists("history-enabled", $user_row)) {
        return false;
    }
    if ($user_row["history-enabled"] === null) {
        return false;
    }
    return ((int)$user_row["history-enabled"]) === 1;
}

/**
 * Pro-features flag: grants access to future premium features (same pattern as
 * history-enabled — denied by default, set per account with an UPDATE).
 */
function v2_pro_features($c, $user_row)
{
    if (!is_array($user_row)) {
        return false;
    }
    if (!array_key_exists("pro-features", $user_row)) {
        return false;
    }
    if ($user_row["pro-features"] === null) {
        return false;
    }
    return ((int)$user_row["pro-features"]) === 1;
}

/**
 * The sync history permission of an authenticated session, or a clean error.
 * The two `data/get/history*` endpoints call this right after the rate limit.
 */
function v2_require_history($session)
{
    if (!isset($session["history-enabled"]) || $session["history-enabled"] !== true) {
        api_error(ERR_HISTORY_FORBIDDEN);
    }
    return true;
}

/**
 * Creates a verified session (login row + token row) and returns
 * array("login-id" => ..., "token" => ..., "expiry" => ...).
 *
 * The token row keeps the v1 format (the password encrypted with the token),
 * so a token issued by v2 also works on the v1 endpoints.
 */
function v2_create_session($c, $user_id, $password, $ip_address, $expiry = null)
{
    global $logins_table, $tokens_table;

    $now = getTimestamp();
    $login_id = v2_random_id(32);
    $token = v2_random_id(32);
    $password_token = encryptTextWithPassword($password, $token);

    $inserted = db_execute(
        $c,
        "INSERT INTO `$logins_table` (`login-id`, `user-id`, `expiry`, `status`, `ip-address`, `verified`, `verification-code`, `verification-expiry`) VALUES (?, ?, ?, 1, ?, ?, NULL, NULL)",
        "sssss",
        array($login_id, $user_id, $expiry, $ip_address, $now)
    );
    if ($inserted < 0) {
        return null;
    }

    $inserted = db_execute(
        $c,
        "INSERT INTO `$tokens_table` (`id`, `password`, `login-id`, `expiry`, `ip-address`, `inserted-date`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, 1)",
        "sssss",
        array($password_token, $login_id, $expiry, $ip_address, $now)
    );
    if ($inserted < 0) {
        return null;
    }

    return array("login-id" => $login_id, "token" => $token, "expiry" => $expiry);
}

/**
 * Resolves login-id + token into a full session context, or null.
 *
 * Returned keys:
 *   user-id, user (row), password (plaintext), username (plaintext or null),
 *   dek (or null), otp-enabled (bool), history-enabled (bool), pro-features (bool), token-id,
 *   login (row)
 */
function v2_authenticate($c, $login_id, $token)
{
    global $logins_table, $tokens_table;

    $login = db_select_one(
        $c,
        "SELECT * FROM `$logins_table` WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL) LIMIT 1",
        "s",
        array($login_id)
    );
    if ($login === null) {
        return null;
    }

    $user = v2_user_by_id($c, $login["user-id"]);
    if ($user === null) {
        return array("error" => ERR_USER_NOT_FOUND);
    }

    $token_rows = db_select(
        $c,
        "SELECT * FROM `$tokens_table` WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)",
        "s",
        array($login_id)
    );
    if (count($token_rows) === 0) {
        return array("error" => ERR_TOKEN_NOT_FOUND);
    }

    $password = null;
    $token_id = null;
    foreach ($token_rows as $row) {
        $candidate = decryptTextWithPassword($row["password"], $token);
        if ($candidate !== false && $candidate !== null && hash_equals((string)$user["password"], encryptHash($candidate))) {
            $password = $candidate;
            $token_id = (int)$row["id"];
            break;
        }
    }

    if ($password === null) {
        return array("error" => ERR_TOKEN_INVALID);
    }

    $username = decryptTextWithPassword($user["username"], $password);
    if ($username === false) {
        $username = null;
    }

    return array(
        "user-id" => $login["user-id"],
        "user" => $user,
        "login" => $login,
        "password" => $password,
        "username" => $username,
        "token-id" => $token_id,
        "otp-enabled" => v2_otp_enabled($c, $user),
        "history-enabled" => v2_history_enabled($c, $user),
        "pro-features" => v2_pro_features($c, $user),
        "dek" => v2_get_or_create_dek($c, $login["user-id"], $password),
    );
}

/**
 * Reads `login-id` and `token` from the body, authenticates, and answers with
 * the proper error when anything is wrong. Endpoints just call this.
 */
function v2_require_auth($c)
{
    $login_id = req_string("login-id", 512);
    $token = req_string("token", 512);

    $session = v2_authenticate($c, $login_id, $token);
    if ($session === null) {
        api_error(ERR_LOGIN_ID);
    }
    if (isset($session["error"])) {
        api_error($session["error"]);
    }

    return $session;
}

/**
 * The DEK of an authenticated session, or a clean error.
 *
 * Only the endpoints that WRITE need it: a read must never fail because of a
 * missing key (see v2_optional_dek()), otherwise an installation whose
 * additive `user_keys` table has not been created yet answers 409 to every
 * single read.
 */
function v2_require_dek($session)
{
    if (!isset($session["dek"]) || $session["dek"] === null) {
        api_error(ERR_KEY_UNAVAILABLE);
    }
    return $session["dek"];
}

/**
 * The DEK of an authenticated session, or null when the account has none yet.
 *
 * The reads use this one: without a key the data is still readable from the
 * legacy row of the service, which is encrypted with the account password.
 */
function v2_optional_dek($session)
{
    return isset($session["dek"]) ? $session["dek"] : null;
}

/**
 * Recipient of an email.
 *
 * `users`.`email` only holds the SHA-512 hash of the address, so the plaintext
 * address has to come from the request; but it is accepted ONLY when its hash
 * matches the account the request is authenticated for. This closes the
 * vulnerability of api/v1/login/verify/index.php, where the confirmation email
 * was sent to whatever address the payload contained.
 */
function v2_require_recipient($user_id, $key = "email")
{
    $email = req_email($key);
    if (!hash_equals((string)$user_id, v2_email_hash($email))) {
        api_error(ERR_INVALID_CREDENTIALS);
    }
    return $email;
}

/**
 * Same as above but optional: returns null when the client did not send the
 * address, when it is malformed or when it does not belong to the account.
 *
 * It never aborts the request: it is only used for notification emails, which
 * must not be able to fail an operation that already succeeded.
 */
function v2_optional_recipient($user_id, $key = "email")
{
    $body = req_body();
    if (!isset($body[$key]) || !is_string($body[$key]) || trim($body[$key]) === "") {
        return null;
    }

    $email = strtolower(trim($body[$key]));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return null;
    }
    if (!hash_equals((string)$user_id, v2_email_hash($email))) {
        return null;
    }

    return $email;
}

/**
 * Invalidates sessions of a user. $keep_login_id stays active when given.
 */
function v2_invalidate_sessions($c, $user_id, $keep_login_id = null)
{
    global $logins_table, $tokens_table;

    if ($keep_login_id === null) {
        db_execute(
            $c,
            "UPDATE `$tokens_table` SET `status` = 0 WHERE `login-id` IN (SELECT `login-id` FROM `$logins_table` WHERE `user-id` = ?)",
            "s",
            array($user_id)
        );
        db_execute($c, "UPDATE `$logins_table` SET `status` = 2 WHERE `user-id` = ?", "s", array($user_id));
        return;
    }

    db_execute(
        $c,
        "UPDATE `$tokens_table` SET `status` = 0 WHERE `login-id` IN (SELECT `login-id` FROM `$logins_table` WHERE `user-id` = ? AND `login-id` <> ?)",
        "ss",
        array($user_id, $keep_login_id)
    );
    db_execute(
        $c,
        "UPDATE `$logins_table` SET `status` = 2 WHERE `user-id` = ? AND `login-id` <> ?",
        "ss",
        array($user_id, $keep_login_id)
    );
}

?>
