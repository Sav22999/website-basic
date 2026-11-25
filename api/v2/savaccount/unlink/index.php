<?php
/**
 * POST /api/v2/savaccount/unlink
 *
 * Body: { "login-id": "...", "token": "...", "email": "..." (optional, only to
 *         send the notification) }
 *
 * The account always has its own password, so it can always be unlinked.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();
$session = v2_require_auth($c);
$user_id = $session["user-id"];

// Deliberately not v2_savaccount_require(): a link must be removable even when
// the sign-in has been switched off.
$table = v2_savaccount_table("links");
if (!db_has_table($c, $table)) {
    api_ok(array("linked" => false));
}

$deleted = db_execute($c, "DELETE FROM `$table` WHERE `user-id` = ?", "s", array($user_id));
if ($deleted < 0) {
    api_error(ERR_INTERNAL);
}

$recipient = v2_optional_recipient($user_id, "email");
if ($deleted > 0 && $recipient !== null) {
    v2_email_savaccount_linked($recipient, (string)$session["username"], false, req_ip_address());
}

api_ok(array("linked" => false));
?>
