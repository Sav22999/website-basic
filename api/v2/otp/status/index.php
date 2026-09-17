<?php
/**
 * POST /api/v2/otp/status
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * Differences with v1: the endpoint simply does not exist in v1, where a
 * client has no way to know whether the login verification code (two-factor
 * authentication) is enabled. Here a valid token is always required.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

api_ok(array(
    "otp-enabled" => $session["otp-enabled"],
));
?>
