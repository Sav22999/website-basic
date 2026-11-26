<?php
/**
 * POST /api/v2/attachments/usage
 *
 * Body: { "login-id": "...", "token": "...", "service": "notefox" }
 * → { "service": "notefox", "used": <bytes>, "count": <images>, "quota": <bytes> }
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

list($session, $service) = v2_attachments_begin($c);

api_ok(array_merge(array("service" => $service), v2_attachments_usage($c, $session["user-id"], $service)));
?>
