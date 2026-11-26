<?php
/**
 * POST /api/v2/attachments/get
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox", "id": "..." }
 * → { "service": "notefox", "id": "...", "mime": "image/webp", "data": "data:image/webp;base64,..." }
 * 201 when the image doesn't exist (never uploaded, or deleted).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

list($session, $service) = v2_attachments_begin($c);
$dek = v2_optional_dek($session);
if ($dek === null) {
    // without a key no image can have been uploaded
    api_error(ERR_NO_DATA);
}

$id = req_string("id", 64);
if (!v2_attachment_id_valid($id)) {
    api_error(ERR_MISSING_PARAMETERS);
}

v2_rate_limit($c, "attachments-get", $session["user-id"], 300, 60, 60);

$image = v2_attachment_read($c, $session["user-id"], $service, $id, $dek);
if ($image === null) {
    api_error(ERR_NO_DATA);
}

api_ok(array("service" => $service, "id" => $id, "mime" => $image["mime"], "data" => $image["data"]));
?>
