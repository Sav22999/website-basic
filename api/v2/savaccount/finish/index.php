<?php
/**
 * POST /api/v2/savaccount/finish
 *
 * Body: { "flow": "...", "state": "...", "code": "...", "iss": "..." (optional),
 *         "source": "web" | "addon" (optional) }
 * and, for a "connect" sign-in, also { "login-id", "token" }
 *
 * Answer, always with a `result`:
 *   "session"            signed in: login-id, token, expiry, username, email...
 *   "link-required"      a Notefox account with this email exists: `ticket`,
 *                        its password is needed once (POST /savaccount/link)
 *   "password-required"  already linked, but the password has been changed
 *                        since: `ticket`, the new one is needed once (same)
 *   "signup-required"    no Notefox account with this email: `ticket`
 *                        (POST /savaccount/signup)
 *   "connected"          (connect) the account of the session is now linked
 *
 * An account is never opened - or linked - just because the email is the same.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$flow_secret = req_string("flow", 128);
$state = req_string("state", 128);
$code = req_string("code", 512);
$issuer = req_optional_string("iss", 255);
$source = v2_savaccount_source();

$c = db();
v2_savaccount_require($c);
v2_rate_limit($c, "savaccount-finish-ip", req_ip_address(), 30, 900, 900);

$row = v2_savaccount_row($c, array("login", "connect"), $state, $flow_secret);
if ($row === null || !v2_savaccount_row_consume($c, $row)) {
    api_error(ERR_SAVACCOUNT_EXPIRED);
}
if ($issuer !== null && $issuer !== v2_savaccount_issuer()) {
    api_error(ERR_SAVACCOUNT_FAILED);
}

// Before the code is spent: the session must be the one that started.
$session = null;
if ($row["kind"] === "connect") {
    $session = v2_require_auth($c);
    if (!hash_equals((string)$row["user-id"], (string)$session["user-id"])) {
        api_error(ERR_SAVACCOUNT_EXPIRED);
    }
}

$who = v2_savaccount_identity($row["payload"], $code);
if ($who === null) {
    api_error(ERR_SAVACCOUNT_FAILED);
}

$link = v2_savaccount_link_by_sub($c, $who["sub"]);

if ($row["kind"] === "connect") {
    $user_id = $session["user-id"];
    if ($link !== null && $link["user-id"] !== $user_id) {
        api_error(ERR_SAVACCOUNT_IN_USE);
    }
    $recipient = $row["payload"]["email"];
    if (!v2_savaccount_link_save($c, $user_id, $who["sub"], $session["password"], $recipient)) {
        api_error(ERR_INTERNAL);
    }
    v2_email_savaccount_linked($recipient, (string)$session["username"], true, req_ip_address());
    api_ok(array("result" => "connected"));
}

$user = $link === null ? null : v2_user_by_id($c, $link["user-id"]);

if ($user !== null) {
    if (((int)$user["status"]) !== 1 || $user["verified"] === null) {
        api_error(ERR_USER_NOT_ACTIVE);
    }
    $opened = v2_savaccount_link_open($link, $user, $who["sub"]);
    if ($opened !== null && $opened["valid"]) {
        v2_savaccount_answer_session($c, $user, $opened["password"], $opened["email"], $source);
    }
    $account_email = $opened === null ? null : $opened["email"];
    if ($account_email === null && hash_equals((string)$user["email"], v2_email_hash($who["email"]))) {
        $account_email = $who["email"];
    }
    v2_savaccount_answer_ticket($c, "password-required", $who, $user["email"], $account_email);
}

$user = v2_user_by_email($c, $who["email"]);

if ($user !== null && $user["verified"] !== null) {
    if (((int)$user["status"]) !== 1) {
        api_error(ERR_USER_NOT_ACTIVE);
    }
    v2_savaccount_answer_ticket($c, "link-required", $who, $user["email"], $who["email"]);
}

v2_savaccount_answer_ticket($c, "signup-required", $who, null, $who["email"], array("name" => $who["name"]));
?>
