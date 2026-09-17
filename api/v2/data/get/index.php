<?php
/**
 * POST /api/v2/data/get
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox" }
 *
 * `service` is optional and defaults to "notefox".
 *
 * Differences with v1:
 *   - the data comes from the snapshot of the account for that service (one
 *     row per account and service, with the revision owned by the server):
 *     the v1 query
 *     `ORDER BY updated-locally-date DESC LIMIT 1`, where the date comes from
 *     the client and a device with a clock ahead wins forever, is gone;
 *   - the answer also carries the `revision`, which the client sends back as
 *     `base-revision` on the next save to detect conflicts;
 *   - a row written by a v1 client is promoted into the snapshot by the core,
 *     so the two worlds never diverge.
 *
 * The data key is NOT mandatory here: an account that has never logged in
 * through v2 (or an installation whose additive `user_keys` table has not been
 * created yet) has no key, and the notes are still read from the legacy row of
 * the service, which is encrypted with the account password. Answering 409
 * there, as it used to happen, hid a perfectly readable payload.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
$dek = v2_optional_dek($session);

$service = req_service();

$state = v2_sync_read($c, $session["user-id"], $service, $dek, $session["password"]);
if ($state === null) {
    api_error(ERR_NO_DATA);
}
if ($state["data"] === null) {
    // The snapshot exists but cannot be decrypted: better a clear error than
    // an answer that looks successful with no notes in it.
    api_error(ERR_KEY_UNAVAILABLE);
}

api_ok(array(
    "service" => $state["service"],
    "data" => $state["data"],
    "revision" => $state["revision"],
    "updated-locally" => $state["updated-locally"],
    "updated-server" => $state["updated-server"],
));
?>
