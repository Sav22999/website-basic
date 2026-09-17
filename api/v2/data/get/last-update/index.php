<?php
/**
 * POST /api/v2/data/get/last-update
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox" }
 *
 * `service` is optional and defaults to "notefox".
 *
 * Differences with v1:
 *   - the `token` is mandatory: v1 answered with the login-id alone, so anyone
 *     holding (or guessing) a login-id could read the sync metadata of someone
 *     else;
 *   - the dates come from the snapshot of that service, not from
 *     `ORDER BY updated-locally-date DESC LIMIT 1` on the legacy table;
 *   - the `revision` is returned too, so a client can know whether it is up to
 *     date without downloading the whole payload;
 *   - the payload is never decrypted here (v2_sync_read(..., false)), so the
 *     data key is not needed at all: the metadata is answered even for an
 *     account that has no key yet (it used to be a 409).
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
$dek = v2_optional_dek($session);

$service = req_service();

$state = v2_sync_read($c, $session["user-id"], $service, $dek, $session["password"], false);
if ($state === null) {
    api_error(ERR_NO_DATA);
}

api_ok(array(
    "service" => $state["service"],
    "revision" => $state["revision"],
    "updated-locally" => $state["updated-locally"],
    "updated-server" => $state["updated-server"],
));
?>
