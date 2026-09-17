<?php
/**
 * POST /api/v2/data/get/history
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox" }
 *
 * `service` is optional and defaults to "notefox".
 *
 * Dated list (newest first) of the past synced versions of a service, read
 * from the v1 legacy mirror table (the only place that keeps one row per
 * historical save). Only the metadata is returned here (no decryption): use
 * POST /api/v2/data/get/history/download to fetch the plaintext of one entry.
 *
 * Only services declaring a "legacy-table" in the registry support a history:
 * every other service answers ERR_HISTORY_UNAVAILABLE. A service that declares
 * one but has nothing stored yet answers an empty list, never a 409: the
 * conflict is reserved to "this service has no history at all".
 *
 * The history is a per-account permission (`users`.`history-enabled`, block 10
 * of migration.sql), DENIED by default: an account without it answers
 * ERR_HISTORY_FORBIDDEN. The check comes before anything is read, so a
 * forbidden account never even learns whether it has any stored version.
 */

include_once(__DIR__ . "/../../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$service = req_service();

v2_rate_limit($c, "data-history", $session["user-id"], 60, 60, 60);

v2_require_history($session);

$entries = v2_sync_history_list($c, $session["user-id"], $service);
if ($entries === null) {
    api_error(ERR_HISTORY_UNAVAILABLE);
}

api_ok(array(
    "service" => $service,
    "entries" => $entries,
));
?>
