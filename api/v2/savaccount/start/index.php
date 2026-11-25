<?php
/**
 * POST /api/v2/savaccount/start
 *
 * Body: { "mode": "login", "lang": "it" (optional) }
 *    or { "mode": "connect", "login-id": "...", "token": "...", "email": "...",
 *         "password": "...", "lang": "it" (optional) }
 *
 * Answer: { "url": "https://www.savaccount.eu/authorize?...", "flow": "...",
 *           "state": "...", "redirect-uri": "..." }
 *
 * The client opens `url` and keeps `flow`: only who has it can complete the
 * sign-in, so a callback URL forwarded to someone else (or a page that makes
 * the browser visit one) is worthless.
 *
 * Connecting asks the email (only its hash is stored here) and the password again: it adds a way to log in, so a session
 * left open on a shared computer must not be enough.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$mode = req_string("mode", 16);
if ($mode !== "login" && $mode !== "connect") {
    api_error(ERR_MISSING_PARAMETERS);
}
$language = req_optional_string("lang", 16);
if ($language !== null && !preg_match('/^[A-Za-z]{2,3}([_-][A-Za-z0-9]{2,8})*$/', $language)) {
    $language = null;
}

$c = db();
v2_savaccount_require($c);
v2_rate_limit($c, "savaccount-start-ip", req_ip_address(), 30, 900, 900);

$user_id = null;
$email = null;
if ($mode === "connect") {
    $session = v2_require_auth($c);
    $user_id = $session["user-id"];
    v2_rate_limit($c, "savaccount-connect", $user_id, 5, 900, 900);
    if (!v2_verify_password($session["user"], req_password("password"))) {
        api_error(ERR_INVALID_CREDENTIALS);
    }
    $email = v2_require_recipient($user_id, "email");
    v2_rate_limit_reset($c, "savaccount-connect", $user_id);
}

api_ok(v2_savaccount_start($c, $mode, $user_id, $email, $language));
?>
