<?php
/**
 * Sav Account API v2 - sync engine (snapshot + server-side revision, per service).
 *
 * This is the fix for the bug users report ("the sync does not work").
 *
 * v1 appends a row to the Notefox data table at every save and reads back
 * `ORDER BY updated-locally-date DESC LIMIT 1`, where that date comes from the
 * client: a device whose clock runs ahead wins forever and every later save
 * made from the other devices becomes invisible.
 *
 * v2 keeps ONE row per (user, service) in the snapshot table, with a
 * `revision` counter owned by the server. Ordering no longer depends on any
 * clock, and a client that writes with an outdated revision gets a 409 with
 * the current data so it can merge.
 *
 * The account is shared by every service (see services.php): the revision of
 * one service is completely independent from the revision of another one.
 *
 * Compatibility with the old extension: ONLY the services that declare a
 * "legacy-table" in the registry (today just Notefox) also refresh a single
 * "mirror" row in that v1 table, and promote a legacy row written by a v1
 * client when it is newer than the snapshot. For every other service the path
 * is a plain snapshot, and the legacy table is never touched.
 */

function v2_sync_available($c)
{
    global $data_current_table;
    if (!db_has_table($c, $data_current_table)) {
        return false;
    }
    // Multi-service schema: without this column the SQL below cannot run.
    return db_has_column($c, $data_current_table, "service");
}

function v2_sync_has_legacy_id_column($c)
{
    global $data_current_table;
    return db_has_column($c, $data_current_table, "legacy-data-id");
}

/**
 * True when the v1 mirror table of a service can really be queried: declared
 * in the registry (`include/credentials.php`), existing in this database and
 * carrying the columns the queries below use.
 *
 * Everything that reads the legacy table goes through this check, because
 * db_select() answers ERR_DATABASE (`code 401`, HTTP 503) as soon as a
 * statement cannot be prepared: a legacy table that was renamed, moved to
 * another database or never created used to turn POST /api/v2/data/get into
 * that opaque "Database connection error" while the connection was perfectly
 * fine. The sync now degrades instead (snapshot only, no history and no
 * mirror), and GET /status reports it as `legacy-mirror: false`.
 */
function v2_sync_legacy_available($c, $legacy_table)
{
    static $cache = array();

    if ($legacy_table === null) {
        return false;
    }
    if (array_key_exists($legacy_table, $cache)) {
        return $cache[$legacy_table];
    }

    $available = db_has_table($c, $legacy_table);
    if ($available) {
        foreach (array("id", "user-id", "data", "inserted-date", "updated-locally-date", "ip-address") as $column) {
            if (!db_has_column($c, $legacy_table, $column)) {
                $available = false;
                break;
            }
        }
    }

    if (!$available) {
        error_log("[sav-account] sync: the legacy table of the service is missing or incomplete");
    }

    $cache[$legacy_table] = $available;
    return $available;
}

/**
 * Same check for a service name, for the callers that only know the service.
 */
function v2_sync_legacy_available_for_service($c, $service)
{
    return v2_sync_legacy_available($c, sav_service_legacy_table($service));
}

/**
 * True when every service that declares a v1 mirror table can actually use it.
 * Reported by GET /status as `legacy-mirror`.
 */
function v2_sync_legacy_mirrors_ready($c)
{
    foreach (sav_service_names() as $service) {
        if (sav_service_legacy_table($service) === null) {
            continue;
        }
        if (!v2_sync_legacy_available_for_service($c, $service)) {
            return false;
        }
    }
    return true;
}

function v2_sync_snapshot_row($c, $user_id, $service, $for_update = false)
{
    global $data_current_table;
    if (!v2_sync_available($c)) {
        return null;
    }
    $sql = "SELECT * FROM `$data_current_table` WHERE `user-id` = ? AND `service` = ? LIMIT 1";
    if ($for_update) {
        $sql .= " FOR UPDATE";
    }
    return db_select_one($c, $sql, "ss", array($user_id, $service));
}

/**
 * Newest row of the legacy table of a service (what a v1 client and the v1
 * endpoints see). Returns null when the service has no legacy mirror at all.
 */
function v2_sync_legacy_row($c, $user_id, $legacy_table)
{
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return null;
    }
    return db_select_one(
        $c,
        "SELECT * FROM `$legacy_table` WHERE `user-id` = ? ORDER BY `inserted-date` DESC, `id` DESC LIMIT 1",
        "s",
        array($user_id)
    );
}

function v2_sync_legacy_max_local_date($c, $user_id, $legacy_table)
{
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return null;
    }
    $row = db_select_one(
        $c,
        "SELECT MAX(`updated-locally-date`) AS `max-date` FROM `$legacy_table` WHERE `user-id` = ?",
        "s",
        array($user_id)
    );
    return ($row !== null && $row["max-date"] !== null) ? $row["max-date"] : null;
}

/**
 * Creates the first snapshot of an account that only has legacy rows.
 * Called during the lazy key migration, when the password is available.
 * Only meaningful for a service that owns a legacy table.
 */
function v2_sync_bootstrap_from_legacy($c, $user_id, $service, $dek, $password)
{
    global $data_current_table;

    $legacy_table = sav_service_legacy_table($service);
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return false;
    }
    if (!v2_sync_available($c)) {
        return false;
    }
    if (v2_sync_snapshot_row($c, $user_id, $service) !== null) {
        return false;
    }

    $legacy = v2_sync_legacy_row($c, $user_id, $legacy_table);
    if ($legacy === null) {
        return false;
    }

    $plain = decryptTextWithPassword($legacy["data"], $password);
    if ($plain === false || $plain === null) {
        return false;
    }

    $encrypted = v2_encrypt_with_dek($plain, $dek);
    $now = getTimestamp();

    if (v2_sync_has_legacy_id_column($c)) {
        $affected = db_execute(
            $c,
            "INSERT INTO `$data_current_table` (`user-id`, `service`, `revision`, `data`, `key-version`, `updated-locally-date`, `updated-server-date`, `ip-address`, `legacy-data-id`) VALUES (?, ?, 1, ?, 1, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `user-id` = `user-id`",
            "ssssssi",
            array($user_id, $service, $encrypted, $legacy["updated-locally-date"], $now, $legacy["ip-address"], (int)$legacy["id"])
        );
    } else {
        $affected = db_execute(
            $c,
            "INSERT INTO `$data_current_table` (`user-id`, `service`, `revision`, `data`, `key-version`, `updated-locally-date`, `updated-server-date`, `ip-address`) VALUES (?, ?, 1, ?, 1, ?, ?, ?) ON DUPLICATE KEY UPDATE `user-id` = `user-id`",
            "ssssss",
            array($user_id, $service, $encrypted, $legacy["updated-locally-date"], $now, $legacy["ip-address"])
        );
    }

    return $affected > 0;
}

/**
 * If a v1 client wrote after the last v2 write, that row is promoted into the
 * snapshot (revision + 1) so the two worlds never diverge. Services without a
 * legacy table simply get their snapshot back untouched.
 * Returns the (possibly refreshed) snapshot row.
 */
function v2_sync_promote_legacy($c, $user_id, $service, $dek, $password, $snapshot)
{
    global $data_current_table;

    if ($snapshot === null) {
        return null;
    }

    if ($dek === null) {
        // Without a data key the promoted row could not be re-encrypted: the
        // snapshot is returned untouched and the caller falls back to the
        // legacy row, which is readable with the password alone.
        return $snapshot;
    }

    $legacy_table = sav_service_legacy_table($service);
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return $snapshot;
    }

    $legacy = v2_sync_legacy_row($c, $user_id, $legacy_table);
    if ($legacy === null) {
        return $snapshot;
    }

    $mirror_id = v2_sync_has_legacy_id_column($c) && $snapshot["legacy-data-id"] !== null
        ? (int)$snapshot["legacy-data-id"]
        : null;
    if ($mirror_id !== null && (int)$legacy["id"] === $mirror_id) {
        return $snapshot; // the newest legacy row is our own mirror
    }

    $legacy_time = strtotime((string)$legacy["inserted-date"]);
    $snapshot_time = strtotime((string)$snapshot["updated-server-date"]);
    if ($legacy_time === false || $snapshot_time === false || $legacy_time <= $snapshot_time) {
        return $snapshot;
    }

    $plain = decryptTextWithPassword($legacy["data"], $password);
    if ($plain === false || $plain === null) {
        return $snapshot;
    }

    $encrypted = v2_encrypt_with_dek($plain, $dek);
    $revision = ((int)$snapshot["revision"]) + 1;
    $now = getTimestamp();

    if (v2_sync_has_legacy_id_column($c)) {
        db_execute(
            $c,
            "UPDATE `$data_current_table` SET `revision` = ?, `data` = ?, `updated-locally-date` = ?, `updated-server-date` = ?, `ip-address` = ?, `legacy-data-id` = ? WHERE `user-id` = ? AND `service` = ?",
            "issssiss",
            array($revision, $encrypted, $legacy["updated-locally-date"], $now, $legacy["ip-address"], (int)$legacy["id"], $user_id, $service)
        );
    } else {
        db_execute(
            $c,
            "UPDATE `$data_current_table` SET `revision` = ?, `data` = ?, `updated-locally-date` = ?, `updated-server-date` = ?, `ip-address` = ? WHERE `user-id` = ? AND `service` = ?",
            "issssss",
            array($revision, $encrypted, $legacy["updated-locally-date"], $now, $legacy["ip-address"], $user_id, $service)
        );
    }

    $snapshot["revision"] = $revision;
    $snapshot["data"] = $encrypted;
    $snapshot["updated-locally-date"] = $legacy["updated-locally-date"];
    $snapshot["updated-server-date"] = $now;
    $snapshot["ip-address"] = $legacy["ip-address"];
    if (v2_sync_has_legacy_id_column($c)) {
        $snapshot["legacy-data-id"] = (int)$legacy["id"];
    }

    return $snapshot;
}

/**
 * Plaintext of the newest legacy row of a service, or null.
 * The legacy rows are encrypted with the account password, so they stay
 * readable even when the account has no data key yet (an installation where
 * the additive `user_keys` table has not been created, or an account that
 * never logged in through v2).
 */
function v2_sync_legacy_plaintext($c, $user_id, $service, $password)
{
    $legacy = v2_sync_legacy_row($c, $user_id, sav_service_legacy_table($service));
    if ($legacy === null) {
        return null;
    }
    $plain = decryptTextWithPassword($legacy["data"], $password);
    if ($plain === false || $plain === null) {
        return null;
    }
    return $plain;
}

/**
 * Current state of a service for this account:
 *   array("service" => string, "data" => plaintext|null, "revision" => int,
 *         "updated-locally" => ..., "updated-server" => ...)
 * or null when that service never synced anything.
 *
 * $dek may be null: the snapshot cannot be decrypted then, so the plaintext
 * comes from the legacy mirror row (same content, encrypted with the password)
 * instead of failing the whole read.
 */
function v2_sync_read($c, $user_id, $service, $dek, $password, $include_data = true)
{
    $snapshot = v2_sync_snapshot_row($c, $user_id, $service);
    $snapshot = v2_sync_promote_legacy($c, $user_id, $service, $dek, $password, $snapshot);

    if ($snapshot === null) {
        // No snapshot yet: fall back to the legacy row, if this service has one.
        $legacy = v2_sync_legacy_row($c, $user_id, sav_service_legacy_table($service));
        if ($legacy === null) {
            return null;
        }
        $plain = decryptTextWithPassword($legacy["data"], $password);
        return array(
            "service" => $service,
            "data" => $include_data ? ($plain === false ? null : $plain) : null,
            "revision" => 0,
            "updated-locally" => $legacy["updated-locally-date"],
            "updated-server" => $legacy["inserted-date"],
        );
    }

    $plain = null;
    if ($include_data) {
        $plain = $dek !== null ? v2_decrypt_with_dek($snapshot["data"], $dek) : null;
        if ($plain === null) {
            // No key (or a key that does not open this snapshot): the mirror
            // row of the legacy table holds the very same notes.
            $plain = v2_sync_legacy_plaintext($c, $user_id, $service, $password);
        }
    }

    return array(
        "service" => $service,
        "data" => $plain,
        "revision" => (int)$snapshot["revision"],
        "updated-locally" => $snapshot["updated-locally-date"],
        "updated-server" => $snapshot["updated-server-date"],
    );
}

/**
 * Writes a new version of the data of one service.
 *
 * Returns:
 *   array("conflict" => false, "service" => ..., "revision" => int,
 *         "updated-server" => ..., "updated-locally" => ...)
 *   array("conflict" => true,  "service" => ..., "revision" => int,
 *         "updated-server" => ..., "updated-locally" => ..., "data" => plaintext)
 *
 * $base_revision is the revision the client believes it owns, for THIS
 * service. When it is null the write is always accepted (client that does not
 * implement revisions yet).
 */
function v2_sync_write($c, $user_id, $service, $dek, $password, $plaintext, $updated_locally, $base_revision, $ip_address)
{
    global $data_current_table;

    if (!v2_sync_available($c)) {
        return null;
    }

    return db_tx($c, function ($c) use ($user_id, $service, $dek, $password, $plaintext, $updated_locally, $base_revision, $ip_address, $data_current_table) {
        $legacy_table = sav_service_legacy_table($service);

        $snapshot = v2_sync_snapshot_row($c, $user_id, $service, true);
        $snapshot = v2_sync_promote_legacy($c, $user_id, $service, $dek, $password, $snapshot);

        $current_revision = $snapshot !== null ? (int)$snapshot["revision"] : 0;

        if ($base_revision !== null && $base_revision !== $current_revision) {
            $plain = $snapshot !== null ? v2_decrypt_with_dek($snapshot["data"], $dek) : null;
            return array(
                "conflict" => true,
                "service" => $service,
                "revision" => $current_revision,
                "updated-server" => $snapshot !== null ? $snapshot["updated-server-date"] : null,
                "updated-locally" => $snapshot !== null ? $snapshot["updated-locally-date"] : null,
                "data" => $plain,
            );
        }

        $revision = $current_revision + 1;
        $now = getTimestamp();
        $encrypted = v2_encrypt_with_dek($plaintext, $dek);

        // The legacy mirror must stay the newest row for the v1 endpoints,
        // which still order by the client date. Only for services that have one.
        $effective_local = v2_sync_effective_local_date($c, $user_id, $legacy_table, $updated_locally, $snapshot);
        $mirror_id = null;
        if ($legacy_table !== null) {
            $mirror_id = v2_sync_write_legacy_mirror($c, $user_id, $legacy_table, $password, $plaintext, $effective_local, $now, $ip_address, $snapshot);
        }

        if (v2_sync_has_legacy_id_column($c)) {
            $affected = db_execute(
                $c,
                "INSERT INTO `$data_current_table` (`user-id`, `service`, `revision`, `data`, `key-version`, `updated-locally-date`, `updated-server-date`, `ip-address`, `legacy-data-id`) VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?)"
                . " ON DUPLICATE KEY UPDATE `revision` = VALUES(`revision`), `data` = VALUES(`data`), `updated-locally-date` = VALUES(`updated-locally-date`), `updated-server-date` = VALUES(`updated-server-date`), `ip-address` = VALUES(`ip-address`), `legacy-data-id` = VALUES(`legacy-data-id`)",
                "ssissssi",
                array($user_id, $service, $revision, $encrypted, $effective_local, $now, $ip_address, $mirror_id)
            );
        } else {
            $affected = db_execute(
                $c,
                "INSERT INTO `$data_current_table` (`user-id`, `service`, `revision`, `data`, `key-version`, `updated-locally-date`, `updated-server-date`, `ip-address`) VALUES (?, ?, ?, ?, 1, ?, ?, ?)"
                . " ON DUPLICATE KEY UPDATE `revision` = VALUES(`revision`), `data` = VALUES(`data`), `updated-locally-date` = VALUES(`updated-locally-date`), `updated-server-date` = VALUES(`updated-server-date`), `ip-address` = VALUES(`ip-address`)",
                "ssissss",
                array($user_id, $service, $revision, $encrypted, $effective_local, $now, $ip_address)
            );
        }

        if ($affected < 0) {
            throw new RuntimeException("snapshot write failed");
        }

        return array(
            "conflict" => false,
            "service" => $service,
            "revision" => $revision,
            "updated-server" => $now,
            "updated-locally" => $effective_local,
        );
    });
}

/**
 * Inventory of the services that hold data for this account, without ever
 * decrypting a payload. Used by POST /api/v2/data/services.
 */
function v2_sync_services($c, $user_id)
{
    global $data_current_table;

    if (!v2_sync_available($c)) {
        return array();
    }

    $rows = db_select(
        $c,
        "SELECT `service`, `revision`, `updated-locally-date`, `updated-server-date` FROM `$data_current_table` WHERE `user-id` = ? ORDER BY `service` ASC",
        "s",
        array($user_id)
    );

    $services = array();
    foreach ($rows as $row) {
        $services[] = array(
            "service" => $row["service"],
            "revision" => (int)$row["revision"],
            "updated-locally" => $row["updated-locally-date"],
            "updated-server" => $row["updated-server-date"],
        );
    }
    return $services;
}

/**
 * The date written in the legacy table: never older than what is already
 * stored, otherwise a v1 client would keep reading an older row.
 */
function v2_sync_effective_local_date($c, $user_id, $legacy_table, $updated_locally, $snapshot)
{
    $candidates = array();
    if ($updated_locally !== null) {
        $candidates[] = strtotime($updated_locally);
    }

    $legacy_max = v2_sync_legacy_max_local_date($c, $user_id, $legacy_table);
    if ($legacy_max !== null) {
        $candidates[] = strtotime($legacy_max) + 1;
    }
    if ($snapshot !== null && $snapshot["updated-locally-date"] !== null) {
        $candidates[] = strtotime($snapshot["updated-locally-date"]) + 1;
    }

    $candidates = array_filter($candidates, function ($value) {
        return is_int($value) && $value > 0;
    });

    if (count($candidates) === 0) {
        return getTimestamp();
    }

    return date("Y-m-d H:i:s", max($candidates));
}

/**
 * Inserts a new row in the legacy table for each sync, building a full
 * history of past saves. v1 clients read the newest row (ORDER BY
 * inserted-date DESC LIMIT 1), so they always see the latest data.
 * Returns the id of the new row.
 */
function v2_sync_write_legacy_mirror($c, $user_id, $legacy_table, $password, $plaintext, $updated_locally, $now, $ip_address, $snapshot)
{
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return null;
    }

    $last = db_select_one(
        $c,
        "SELECT `id`, `data` FROM `$legacy_table` WHERE `user-id` = ? ORDER BY `id` DESC LIMIT 1",
        "s",
        array($user_id)
    );
    if ($last !== null && $last["data"] !== null && $last["data"] !== "") {
        $last_plain = decryptTextWithPassword($last["data"], $password);
        if ($last_plain !== false && $last_plain !== null && $last_plain === $plaintext) {
            return (int)$last["id"];
        }
    }

    $encrypted = encryptTextWithPassword($plaintext, $password);

    $affected = db_execute(
        $c,
        "INSERT INTO `$legacy_table` (`id`, `user-id`, `data`, `updated-locally-date`, `inserted-date`, `ip-address`) VALUES (NULL, ?, ?, ?, ?, ?)",
        "sssss",
        array($user_id, $encrypted, $updated_locally, $now, $ip_address)
    );
    if ($affected < 0) {
        return null;
    }

    return (int)$c->insert_id;
}

/**
 * Re-encrypts ALL legacy rows of one service after a password change.
 * Rows encrypted with an even older password (from a previous change)
 * are silently skipped — they remain undecryptable.
 */
function v2_sync_rewrite_legacy_mirror($c, $user_id, $service, $old_password, $new_password)
{
    $legacy_table = sav_service_legacy_table($service);
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return true;
    }

    $rows = db_select(
        $c,
        "SELECT `id`, `data` FROM `$legacy_table` WHERE `user-id` = ?",
        "s",
        array($user_id)
    );
    if (count($rows) === 0) {
        return true;
    }

    foreach ($rows as $row) {
        if ($row["data"] === null || $row["data"] === "") {
            continue;
        }
        $plain = decryptTextWithPassword($row["data"], $old_password);
        if ($plain === false || $plain === null) {
            continue;
        }
        $encrypted = encryptTextWithPassword($plain, $new_password);
        $ok = db_execute(
            $c,
            "UPDATE `$legacy_table` SET `data` = ? WHERE `id` = ?",
            "si",
            array($encrypted, (int)$row["id"])
        );
        if ($ok < 0) {
            return false;
        }
    }
    return true;
}

/**
 * Same as above for every service that declares a legacy table: the data key
 * is a single one for the whole account, so a password change has to refresh
 * all of the mirrors.
 */
function v2_sync_rewrite_legacy_mirrors($c, $user_id, $old_password, $new_password)
{
    foreach (sav_service_names() as $service) {
        if (sav_service_legacy_table($service) === null) {
            continue;
        }
        if (!v2_sync_rewrite_legacy_mirror($c, $user_id, $service, $old_password, $new_password)) {
            return false;
        }
    }
    return true;
}

/**
 * Dated list of the past synced versions of a service, newest first.
 * Only meaningful for services with a legacy table (the only place a history
 * of past saves is kept): returns null ONLY when the service declares none, so
 * the caller can tell "this service has no history at all" apart from "no rows
 * yet" ([]). A declared table that does not exist (yet) is an empty history,
 * not an error.
 * Never decrypts anything: cheap enough to be called on every page load.
 */
function v2_sync_history_list($c, $user_id, $service, $limit = 30)
{
    $legacy_table = sav_service_legacy_table($service);
    if ($legacy_table === null) {
        return null;
    }
    if (!v2_sync_legacy_available($c, $legacy_table)) {
        return array();
    }

    $limit = (int)$limit;
    if ($limit < 1) {
        $limit = 1;
    }
    if ($limit > 200) {
        $limit = 200;
    }

    $rows = db_select(
        $c,
        "SELECT `id`, `inserted-date`, `updated-locally-date` FROM `$legacy_table` WHERE `user-id` = ? ORDER BY `inserted-date` DESC, `id` DESC LIMIT $limit",
        "s",
        array($user_id)
    );

    $entries = array();
    foreach ($rows as $row) {
        $entries[] = array(
            "id" => (int)$row["id"],
            "inserted-date" => $row["inserted-date"],
            "updated-locally-date" => $row["updated-locally-date"],
        );
    }
    return $entries;
}

/**
 * A single past synced version, decrypted. Returns null when the service has
 * no legacy table, the row does not exist (or belongs to another account), or
 * the decryption fails.
 */
function v2_sync_history_entry($c, $user_id, $service, $id, $password)
{
    $legacy_table = sav_service_legacy_table($service);
    if ($legacy_table === null || !v2_sync_legacy_available($c, $legacy_table)) {
        return array("error" => "unavailable");
    }

    $row = db_select_one(
        $c,
        "SELECT `id`, `data`, `inserted-date`, `updated-locally-date` FROM `$legacy_table` WHERE `id` = ? AND `user-id` = ? LIMIT 1",
        "is",
        array((int)$id, $user_id)
    );
    if ($row === null) {
        return null;
    }

    $raw = $row["data"];
    if ($raw === null || $raw === "") {
        return array("error" => "decrypt", "debug" => "data column is null or empty");
    }

    $plain = decryptTextWithPassword($raw, $password);
    if ($plain === false || $plain === null) {
        return array("error" => "decrypt");
    }

    return array(
        "id" => (int)$row["id"],
        "inserted-date" => $row["inserted-date"],
        "updated-locally-date" => $row["updated-locally-date"],
        "data" => $plain,
    );
}

/**
 * Removes the snapshots of EVERY service of the account (account deletion).
 */
function v2_sync_delete_user($c, $user_id)
{
    global $data_current_table;
    if (!db_has_table($c, $data_current_table)) {
        return;
    }
    db_execute($c, "DELETE FROM `$data_current_table` WHERE `user-id` = ?", "s", array($user_id));
}

?>
