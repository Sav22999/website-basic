<?php
/**
 * POST (or GET) /api/v2/savaccount/status
 *
 * Body: {} or { "login-id": "...", "token": "..." }
 *
 * `enabled` tells the client whether to offer "Continue with SavAccount". With
 * a session, `linked` tells whether the account is linked to a SavAccount.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_get();

$c = db();
$enabled = v2_savaccount_enabled($c);

if (req_optional_string("login-id") === null) {
    api_ok(array("enabled" => $enabled));
}

$session = v2_require_auth($c);
$link = $enabled ? v2_savaccount_link_by_user($c, $session["user-id"]) : null;

api_ok(array(
    "enabled" => $enabled,
    "linked" => $link !== null,
    "linked-date" => $link === null ? null : $link["created-date"],
));
?>
