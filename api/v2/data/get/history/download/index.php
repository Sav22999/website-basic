<?php
/**
 * POST /api/v2/data/get/history/download
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox", "id": 498 }
 *
 * `service` is optional and defaults to "notefox". `id` is one of the ids
 * returned by POST /api/v2/data/get/history.
 *
 * Returns the plaintext of one past synced version of a service, read (and
 * decrypted) from the v1 legacy mirror table. The server already unwraps the
 * account password from the session and decrypts before answering: the
 * caller never handles any cryptography.
 *
 * Same per-account permission as POST /api/v2/data/get/history
 * (`users`.`history-enabled`, DENIED by default): without it the answer is
 * ERR_HISTORY_FORBIDDEN and no row is ever read or decrypted.
 */

include_once(__DIR__ . "/../../../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$service = req_service();
$id = req_revision("id", true);

v2_rate_limit($c, "data-history-download", $session["user-id"], 30, 60, 60);

v2_require_history($session);

if (sav_service_legacy_table($service) === null) {
    api_error(ERR_HISTORY_UNAVAILABLE);
}

$entry = v2_sync_history_entry($c, $session["user-id"], $service, $id, $session["password"]);
if ($entry === null) {
    api_error(ERR_NO_DATA);
}

api_ok(array(
    "service" => $service,
    "id" => $entry["id"],
    "inserted-date" => $entry["inserted-date"],
    "updated-locally-date" => $entry["updated-locally-date"],
    "data" => $entry["data"],
));
?>
