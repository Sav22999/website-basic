<?php
/**
 * POST /api/v2/data/services
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * New in v2: the account is shared by several services (see
 * api/v2/include/services.php) and each one owns its own snapshot and its own
 * revision. This endpoint tells a client which services actually hold data
 * for the authenticated account, so it knows what is worth synchronising.
 *
 * The payloads are never decrypted here: only the metadata
 * (revision, updated-server, updated-locally) is returned, which is why the
 * DEK is not needed at all.
 *
 * `history-enabled` is the per-account permission of the two
 * `data/get/history*` endpoints (`users`.`history-enabled`, denied by
 * default): it is reported here so a client can hide the sync history instead
 * of discovering the 433 only once the user asks for it.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

v2_rate_limit($c, "data-services", $session["user-id"], 60, 60, 60);

$services = v2_sync_services($c, $session["user-id"]);

api_ok(array(
    "services" => $services,
    "supported" => sav_service_names(),
    "history-enabled" => $session["history-enabled"],
));
?>
