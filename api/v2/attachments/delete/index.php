<?php
/**
 * POST /api/v2/attachments/delete
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox", "ids": ["...", "..."] }
 * → { "service": "notefox", "deleted": 2, "used": ..., "count": ..., "quota": ... }
 * Ids which don't exist are ignored: deleting twice is not an error.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

list($session, $service) = v2_attachments_begin($c);

$body = req_body();
if (!isset($body["ids"]) || !is_array($body["ids"]) || count($body["ids"]) === 0 || count($body["ids"]) > ATT_MAX_DELETE_IDS) {
    api_error(ERR_MISSING_PARAMETERS);
}
$ids = array();
foreach ($body["ids"] as $id) {
    if (!v2_attachment_id_valid($id)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    $ids[$id] = true;
}

v2_rate_limit($c, "attachments-delete", $session["user-id"], 60, 60, 60);

$deleted = v2_attachments_delete($c, $session["user-id"], $service, array_keys($ids));
if ($deleted < 0) {
    api_error(ERR_INTERNAL);
}

api_ok(array_merge(array("service" => $service, "deleted" => $deleted), v2_attachments_usage($c, $session["user-id"], $service)));
?>
