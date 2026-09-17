<?php
/**
 * Sav Account API v2 - per-account data key (DEK) management.
 *
 * One single key per account, shared by every service. One active row per user
 * in `$user_keys_table`:
 *   `wrapped-key` = the DEK encrypted with the current password
 *   `key-check`   = fingerprint used to verify an unwrap
 *
 * Existing accounts are migrated lazily: the first time v2 sees the plaintext
 * password (signup verification, login, OTP change...) the DEK is created and
 * the latest legacy row of every service that has a v1 mirror table is
 * promoted to the v2 snapshot of that service.
 */

function v2_keys_table_available($c)
{
    global $user_keys_table;
    return db_has_table($c, $user_keys_table);
}

/**
 * Active key row of the user, or null.
 */
function v2_user_key_row($c, $user_id)
{
    global $user_keys_table;
    if (!v2_keys_table_available($c)) {
        return null;
    }
    return db_select_one(
        $c,
        "SELECT * FROM `$user_keys_table` WHERE `user-id` = ? AND `status` = 1 ORDER BY `key-version` DESC LIMIT 1",
        "s",
        array($user_id)
    );
}

/**
 * Creates the first key of a user (or a new version). Returns the DEK or null.
 */
function v2_create_user_key($c, $user_id, $password, $dek = null)
{
    global $user_keys_table;
    if (!v2_keys_table_available($c)) {
        return null;
    }

    if ($dek === null) {
        $dek = v2_generate_dek();
    }
    $wrapped = v2_wrap_key($dek, $password);
    if ($wrapped === null) {
        return null;
    }
    $check = v2_key_check($dek);
    $now = getTimestamp();

    $row = db_select_one($c, "SELECT MAX(`key-version`) AS `v` FROM `$user_keys_table` WHERE `user-id` = ?", "s", array($user_id));
    $version = ($row !== null && $row["v"] !== null) ? ((int)$row["v"]) + 1 : 1;

    db_execute($c, "UPDATE `$user_keys_table` SET `status` = 0 WHERE `user-id` = ?", "s", array($user_id));

    $affected = db_execute(
        $c,
        "INSERT INTO `$user_keys_table` (`user-id`, `key-version`, `wrapped-key`, `key-check`, `status`, `created-date`) VALUES (?, ?, ?, ?, 1, ?)",
        "sisss",
        array($user_id, $version, $wrapped, $check, $now)
    );
    if ($affected < 0) {
        return null;
    }

    return $dek;
}

/**
 * DEK of the user, unwrapped with the password. Null when there is no key yet
 * or the password does not match it.
 */
function v2_unwrap_user_dek($c, $user_id, $password)
{
    $row = v2_user_key_row($c, $user_id);
    if ($row === null) {
        return null;
    }
    return v2_unwrap_key($row["wrapped-key"], $password, $row["key-check"]);
}

/**
 * DEK of the user, created on the fly for accounts that predate the v2 API
 * (lazy migration). Returns null only when the key cannot be created or the
 * password cannot open the existing one.
 */
function v2_get_or_create_dek($c, $user_id, $password)
{
    if (!v2_keys_table_available($c)) {
        return null;
    }

    $row = v2_user_key_row($c, $user_id);
    if ($row !== null) {
        return v2_unwrap_key($row["wrapped-key"], $password, $row["key-check"]);
    }

    $dek = v2_create_user_key($c, $user_id, $password);
    if ($dek === null) {
        return null;
    }

    // First key ever for this account: bring the legacy data over, so that the
    // v2 snapshot starts from what the user already has. Only the services
    // that declare a v1 mirror table have something to import.
    foreach (sav_service_names() as $service) {
        if (sav_service_legacy_table($service) === null) {
            continue;
        }
        v2_sync_bootstrap_from_legacy($c, $user_id, $service, $dek, $password);
    }

    return $dek;
}

/**
 * Password change: only the key is re-encrypted, the notes are left untouched.
 * This is the fix for the data loss of api/v1/password/edit/index.php.
 */
function v2_rewrap_user_key($c, $user_id, $old_password, $new_password)
{
    global $user_keys_table;
    if (!v2_keys_table_available($c)) {
        // The additive table does not exist yet: there is no key to re-wrap,
        // so a password change must not be blocked because of it.
        return true;
    }

    $row = v2_user_key_row($c, $user_id);
    if ($row === null) {
        // No key yet (legacy account): create it with the new password.
        return v2_create_user_key($c, $user_id, $new_password) !== null;
    }

    $dek = v2_unwrap_key($row["wrapped-key"], $old_password, $row["key-check"]);
    if ($dek === null) {
        return false;
    }

    $wrapped = v2_wrap_key($dek, $new_password);
    if ($wrapped === null) {
        return false;
    }
    $now = getTimestamp();

    $affected = db_execute(
        $c,
        "UPDATE `$user_keys_table` SET `wrapped-key` = ?, `updated-date` = ? WHERE `id` = ?",
        "ssi",
        array($wrapped, $now, (int)$row["id"])
    );

    return $affected >= 0;
}

function v2_delete_user_keys($c, $user_id)
{
    global $user_keys_table;
    if (!v2_keys_table_available($c)) {
        return;
    }
    db_execute($c, "DELETE FROM `$user_keys_table` WHERE `user-id` = ?", "s", array($user_id));
}
?>
