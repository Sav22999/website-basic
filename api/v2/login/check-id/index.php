<?php
/**
 * POST /api/v2/login/check-id
 *
 * Body: { "login-id": "...", "token": "..." }
 *
 * Tells the client whether its session is still usable, which is exactly what
 * the v1 endpoint does (it validates the login-id, the user and the token and
 * then answers).
 *
 * Differences with v1:
 *   - the whole logins -> users -> tokens chain lives in one single helper, so
 *     a session is validated the same way on every endpoint;
 *   - the answer carries something useful (username, expiry and the state of
 *     the two-factor authentication) instead of a null payload.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

api_ok(array(
    "valid" => true,
    "username" => $session["username"],
    "expiry" => $session["login"]["expiry"],
    "otp-enabled" => $session["otp-enabled"],
));
?>
