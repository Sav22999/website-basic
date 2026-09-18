<?php
/**
 * GET /api/v2/status - health check (no authentication).
 *
 * The extension can call it before a sync to know whether the server is
 * reachable, and use `server-time` to detect its own clock skew.
 * It never exposes connection details or DBMS error messages.
 *
 * `services` lists the services configured on this installation (the account
 * is shared, the data is partitioned per service) and `schema` is false until
 * the multi-service migration has been applied.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_get();

$c = db_connect();
$database_ok = $c !== null;

$schema_ready = false;
$schema_details = array();
if ($database_ok) {
    global $user_keys_table, $data_current_table, $rate_limits_table, $users_table;

    // Booleans only (no table name, no DBMS message): enough to tell WHICH
    // piece of the additive DDL is missing when an endpoint degrades.
    $schema_details = array(
        "keys" => db_has_table($c, $user_keys_table),
        "snapshots" => db_has_table($c, $data_current_table),
        "snapshots-multi-service" => db_has_column($c, $data_current_table, "service"),
        "rate-limits" => db_has_table($c, $rate_limits_table),
        "otp" => db_has_column($c, $users_table, "otp-enabled"),
        "otp-change-code" => db_has_column($c, $users_table, "otp-change-code")
            && db_has_column($c, $users_table, "otp-change-expiry"),
        "password-change-code" => db_has_column($c, $users_table, "password-change-code"),
        // Per-account permission of the sync history. When it is false the
        // column does not exist and the history is denied to EVERY account
        // (the default of the flag is 0, so a missing column means "nobody").
        "history-permission" => db_has_column($c, $users_table, "history-enabled"),
        // Per-account flag for future premium features (same pattern).
        "pro-features" => db_has_column($c, $users_table, "pro-features"),
        // The v1 mirror table of every service that declares one: when it is
        // false the table configured in include/credentials.php does not exist
        // (or lost a column), the sync history is empty and the v1 clients stop
        // seeing the notes. It used to make POST /data/get answer 503.
        "legacy-mirror" => v2_sync_legacy_mirrors_ready($c),
    );

    $schema_ready = $schema_details["keys"]
        && $schema_details["snapshots"]
        && $schema_details["snapshots-multi-service"]
        && $schema_details["rate-limits"]
        && $schema_details["otp"];
}

api_ok(array(
    "reachable" => true,
    "database" => $database_ok,
    "schema" => $schema_ready,
    "schema-details" => $schema_details,
    "mailer" => v2_mailer_available(),
    "services" => sav_service_names(),
    "api-version" => NOTEFOX_V2_API_VERSION,
    "server-time" => getTimestamp(),
    "server-timezone" => date_default_timezone_get(),
));
?>
