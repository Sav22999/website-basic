<?php
/**
 * POST /api/v2/data/insert
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox",
 *         "data": "...", "updated-locally": "YYYY-MM-DD HH:MM:SS",
 *         "base-revision": 12 }
 *
 * `base-revision` is optional: when it is missing the client does not support
 * revisions yet and the write is always accepted.
 * `service` is optional too and defaults to "notefox", so the clients written
 * for the first release of v2 keep working unchanged.
 *
 * Differences with v1:
 *   - the sync is snapshot based (ONE row per account AND service, with a
 *     revision owned by the server) instead of appending a new row at every
 *     save: the "latest" version no longer depends on the clock of the device
 *     that wrote it, and two services never overwrite each other;
 *   - an outdated `base-revision` is answered with a 409 carrying the current
 *     server data, so the client can merge instead of silently overwriting;
 *   - `updated-locally` is validated (v1 stored whatever the client sent in a
 *     DATETIME column) and the payload size is limited;
 *   - the write is rate limited and runs inside a transaction;
 *   - the data is encrypted with the DEK of the account (a legacy mirror row
 *     is still refreshed by the core, so v1 clients keep working).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
$dek = v2_require_dek($session);

$service = req_service();
$data = req_data_payload("data");
$updated_locally = req_datetime("updated-locally");
$base_revision = req_revision("base-revision");

v2_rate_limit($c, "data-insert", $session["user-id"], 120, 60, 60);

try {
    $result = v2_sync_write(
        $c,
        $session["user-id"],
        $service,
        $dek,
        $session["password"],
        $data,
        $updated_locally,
        $base_revision,
        req_ip_address()
    );
} catch (Throwable $e) {
    error_log("[sav-account] data/insert: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

if ($result === null) {
    // The snapshot table has not been created yet: nothing is written, so the
    // client must retry later instead of believing the save succeeded.
    api_error(ERR_UNAVAILABLE);
}

if ($result["conflict"]) {
    api_error(ERR_REVISION_CONFLICT, array(
        "service" => $result["service"],
        "revision" => $result["revision"],
        "updated-server" => $result["updated-server"],
        "updated-locally" => $result["updated-locally"],
        "data" => $result["data"],
    ));
}

api_ok(array(
    "service" => $result["service"],
    "revision" => $result["revision"],
    "updated-server" => $result["updated-server"],
    "updated-locally" => $result["updated-locally"],
));
?>
