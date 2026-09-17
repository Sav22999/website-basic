<?php
/**
 * v1 <-> v2 coexistence bridge.
 *
 * The v1 API (`api/v1`) keeps its own behaviour, its own request parameters and
 * its own answers: nothing here changes them. What it adds is the handful of
 * side effects v1 cannot know about, because the tables they live in were
 * created by the v2 migration (`api/v2/install/migration.sql`):
 *
 *   - `$user_keys_table`     the data key (DEK) wrapped with the password;
 *   - `$data_current_table`  the per service snapshot read by the v2 clients.
 *
 * Without this bridge, an account that changes its password or deletes itself
 * from v1 leaves those tables inconsistent (an unwrappable key, a snapshot
 * inherited by a new account with the same email).
 *
 * Two rules, on purpose:
 *   1. **Every function is a no-op when the v2 tables/columns do not exist.**
 *      An installation that has not run the migration keeps working exactly as
 *      before, and so does one where only part of it was applied.
 *   2. **Nothing here ever prints, throws or exits.** A failure is logged and
 *      ignored: the v1 answer must stay byte for byte the one it has always
 *      been.
 *
 * Only the two self contained pieces of the v2 core are reused - the crypto
 * helpers and the service registry - never api/v2/include/bootstrap.php, which
 * would install its own error handlers and answer JSON on its own.
 */

if (defined("NOTEFOX_V1_V2_COMPAT")) {
    return;
}
define("NOTEFOX_V1_V2_COMPAT", true);

include_once(__DIR__ . "/../api/v2/include/services.php");
include_once(__DIR__ . "/../api/v2/include/crypto.php");

/**
 * True when $table exists in the current database. `SHOW TABLES LIKE` is used
 * instead of information_schema because some shared hostings restrict the
 * latter; the answer is cached for the request.
 */
function v1v2_table_exists($c, $table)
{
    static $cache = array();

    if (!is_string($table) || $table === "" || $c === null) {
        return false;
    }
    if (isset($cache[$table])) {
        return $cache[$table];
    }

    $pattern = $c->real_escape_string(addcslashes($table, "%_\\"));
    $result = @$c->query("SHOW TABLES LIKE '" . $pattern . "'");
    $exists = $result !== false && $result->num_rows > 0;
    if ($result !== false) {
        $result->free();
    }

    $cache[$table] = $exists;
    return $exists;
}

/**
 * True when $table exists AND owns $column: the optional blocks of the
 * migration (for instance `legacy-data-id`) may legitimately be missing.
 */
function v1v2_column_exists($c, $table, $column)
{
    static $cache = array();

    if (!v1v2_table_exists($c, $table) || !is_string($column) || $column === "") {
        return false;
    }
    $key = $table . "\0" . $column;
    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $pattern = $c->real_escape_string(addcslashes($column, "%_\\"));
    $result = @$c->query("SHOW COLUMNS FROM `" . str_replace("`", "``", $table) . "` LIKE '" . $pattern . "'");
    $exists = $result !== false && $result->num_rows > 0;
    if ($result !== false) {
        $result->free();
    }

    $cache[$key] = $exists;
    return $exists;
}

/**
 * The configured name of a v2 table when it exists in the database, null
 * otherwise (not configured, or migration not run).
 */
function v1v2_keys_table($c)
{
    global $user_keys_table;
    if (!isset($user_keys_table) || !is_string($user_keys_table) || $user_keys_table === "") {
        return null;
    }
    return v1v2_table_exists($c, $user_keys_table) ? $user_keys_table : null;
}

function v1v2_snapshot_table($c)
{
    global $data_current_table;
    if (!isset($data_current_table) || !is_string($data_current_table) || $data_current_table === "") {
        return null;
    }
    return v1v2_table_exists($c, $data_current_table) ? $data_current_table : null;
}

/**
 * A password change done from v1.
 *
 * v1 only knows about `users`, so it would leave behind:
 *   - a `wrapped-key` still encrypted with the OLD password: the unwrap fails,
 *     the account becomes "encryption-ready: false" and its v2 snapshot is no
 *     longer decryptable;
 *   - a stale `password-v2` (the modern hash v2 keeps aligned).
 *
 * Here the DEK is unwrapped with the old password and re-wrapped with the new
 * one - exactly what `v2_rewrap_user_key()` does on the v2 side - so no note is
 * re-encrypted and the whole v2 data stays readable.
 *
 * When the unwrap fails the key is already unusable (it was wrapped with some
 * other password): the row is retired (`status` = 0) so that the next v2 login
 * creates a fresh key and bootstraps it from the legacy data, instead of
 * answering "encryption key unavailable" forever.
 *
 * $user_id is `users`.`email`, i.e. the SHA-512 of the address.
 * Returns nothing: a v1 answer never depends on this.
 */
function v1v2_password_changed($c, $user_id, $old_password, $new_password)
{
    v1v2_rewrap_user_key($c, $user_id, $old_password, $new_password);
    v1v2_align_password_v2($c, $user_id, $new_password);
}

function v1v2_rewrap_user_key($c, $user_id, $old_password, $new_password)
{
    $keys_table = v1v2_keys_table($c);
    if ($keys_table === null) {
        return;
    }

    $stmt = @$c->prepare("SELECT `id`, `wrapped-key`, `key-check` FROM `$keys_table` WHERE `user-id` = ? AND `status` = 1 ORDER BY `key-version` DESC LIMIT 1");
    if ($stmt === false) {
        error_log("[v1-v2-compat] prepare failed on $keys_table");
        return;
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = ($result !== false && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    $stmt->close();

    if ($row === null) {
        // No key yet: the first v2 login will create it with the new password.
        return;
    }

    $dek = v2_unwrap_key($row["wrapped-key"], $old_password, $row["key-check"]);
    if ($dek === null) {
        $stmt = @$c->prepare("UPDATE `$keys_table` SET `status` = 0 WHERE `id` = ?");
        if ($stmt !== false) {
            $id = (int)$row["id"];
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        error_log("[v1-v2-compat] the data key could not be unwrapped with the current password: retired");
        return;
    }

    $wrapped = v2_wrap_key($dek, $new_password);
    if ($wrapped === null) {
        error_log("[v1-v2-compat] the data key could not be re-wrapped");
        return;
    }

    $stmt = @$c->prepare("UPDATE `$keys_table` SET `wrapped-key` = ?, `updated-date` = ? WHERE `id` = ?");
    if ($stmt === false) {
        return;
    }
    $now = getTimestamp();
    $id = (int)$row["id"];
    $stmt->bind_param("ssi", $wrapped, $now, $id);
    $stmt->execute();
    $stmt->close();
}

/**
 * Keeps the additive `users`.`password-v2` aligned with the SHA-512 column v1
 * writes. Optional column: missing, nothing happens.
 */
function v1v2_align_password_v2($c, $user_id, $new_password)
{
    global $users_table;
    if (!v1v2_column_exists($c, isset($users_table) ? $users_table : null, "password-v2")) {
        return;
    }

    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    if (!is_string($hash) || $hash === "") {
        return;
    }

    $stmt = @$c->prepare("UPDATE `$users_table` SET `password-v2` = ? WHERE `email` = ?");
    if ($stmt === false) {
        return;
    }
    $stmt->bind_param("ss", $hash, $user_id);
    $stmt->execute();
    $stmt->close();
}

/**
 * The ids of the legacy rows used as the v1 mirror of a v2 snapshot: they are
 * the rows the v1 clients actually read, so a password change must re-encrypt
 * them whatever their position in the table.
 * Empty array when the snapshot table or its optional `legacy-data-id` column
 * do not exist.
 */
function v1v2_mirror_data_ids($c, $user_id)
{
    $snapshot_table = v1v2_snapshot_table($c);
    if ($snapshot_table === null || !v1v2_column_exists($c, $snapshot_table, "legacy-data-id")) {
        return array();
    }

    $stmt = @$c->prepare("SELECT `legacy-data-id` FROM `$snapshot_table` WHERE `user-id` = ? AND `legacy-data-id` IS NOT NULL");
    if ($stmt === false) {
        return array();
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ids = array();
    while ($result !== false && $row = $result->fetch_assoc()) {
        $ids[] = (int)$row["legacy-data-id"];
    }
    $stmt->close();

    return array_values(array_unique($ids));
}

/**
 * Re-encrypts one single row of a v1 data table from $old_password to
 * $new_password. Used for the mirror rows a `LIMIT 50` could miss.
 * Returns true when the row was rewritten.
 */
function v1v2_reencrypt_legacy_row($c, $data_table, $user_id, $id, $old_password, $new_password)
{
    if (!is_string($data_table) || $data_table === "" || $id <= 0) {
        return false;
    }

    $stmt = @$c->prepare("SELECT `data` FROM `$data_table` WHERE `id` = ? AND `user-id` = ? LIMIT 1");
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("is", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = ($result !== false && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    $stmt->close();

    if ($row === null) {
        return false;
    }

    $plain = decryptTextWithPassword($row["data"], $old_password);
    if ($plain === false || $plain === null || $plain === "") {
        // Already unreadable with this password: rewriting it would only make
        // things worse, so it is left exactly as it is.
        return false;
    }

    $new_data = encryptTextWithPassword($plain, $new_password);
    $old_data = $row["data"];

    $stmt = @$c->prepare("UPDATE `$data_table` SET `data` = ? WHERE `id` = ? AND `user-id` = ? AND `data` = ?");
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("siss", $new_data, $id, $user_id, $old_data);
    $stmt->execute();
    $stmt->close();

    return true;
}

/**
 * An account deleted from v1.
 *
 * `users`.`email` is the SHA-512 of the address, so the user-id of an account
 * is a pure function of the email: without this cleanup a new signup with the
 * same address would inherit the data key and the snapshots of the deleted
 * account.
 */
function v1v2_account_deleted($c, $user_id)
{
    $keys_table = v1v2_keys_table($c);
    if ($keys_table !== null) {
        $stmt = @$c->prepare("DELETE FROM `$keys_table` WHERE `user-id` = ?");
        if ($stmt !== false) {
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $snapshot_table = v1v2_snapshot_table($c);
    if ($snapshot_table !== null) {
        $stmt = @$c->prepare("DELETE FROM `$snapshot_table` WHERE `user-id` = ?");
        if ($stmt !== false) {
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

/**
 * A new row written in a v1 data table.
 *
 * The v2 snapshot keeps, in `legacy-data-id`, the id of the row it uses as its
 * v1 mirror. Now that a newer row exists, that pointer is stale: clearing it
 * makes the next v2 write create a fresh mirror row instead of overwriting the
 * one this client just wrote. The promotion of the new row into the snapshot
 * (`revision` + 1) keeps happening on the v2 side, at the first read.
 *
 * Only the services whose legacy table is $data_table are concerned; the
 * others never touch a v1 table.
 */
function v1v2_legacy_data_inserted($c, $user_id, $data_table)
{
    $snapshot_table = v1v2_snapshot_table($c);
    if ($snapshot_table === null || !v1v2_column_exists($c, $snapshot_table, "legacy-data-id")) {
        return;
    }

    foreach (sav_service_names() as $service) {
        if (sav_service_legacy_table($service) !== $data_table) {
            continue;
        }
        $stmt = @$c->prepare("UPDATE `$snapshot_table` SET `legacy-data-id` = NULL WHERE `user-id` = ? AND `service` = ?");
        if ($stmt === false) {
            return;
        }
        $stmt->bind_param("ss", $user_id, $service);
        $stmt->execute();
        $stmt->close();
    }
}

?>
