<?php
/**
 * POST /api/v2/savaccount/link
 *
 * Body: { "ticket": "...", "password": "...", "source": "web" | "addon" (optional) }
 *
 * Second step of "link-required" and "password-required": the password of the
 * Notefox account proves it belongs to the same person. The account is linked,
 * and a session is returned (same answer as "session" of /savaccount/finish).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$password = req_password("password");
$source = v2_savaccount_source();

$c = db();
v2_savaccount_require($c);
v2_rate_limit($c, "savaccount-link-ip", req_ip_address(), 30, 900, 900);

$row = v2_savaccount_require_ticket($c, "link");
$user_id = $row["user-id"];
$who = $row["payload"];
v2_rate_limit($c, "savaccount-link", $user_id, 10, 900, 900);

$user = v2_user_by_id($c, $user_id);
if ($user === null) {
    api_error(ERR_SAVACCOUNT_EXPIRED);
}
if (!v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS, array("attempts-left" => max(0, SAVACCOUNT_TICKET_ATTEMPTS - 1 - (int)$row["attempts"])));
}
if (((int)$user["status"]) !== 1 || $user["verified"] === null) {
    api_error(ERR_USER_NOT_ACTIVE);
}
if (!v2_savaccount_row_consume($c, $row)) {
    api_error(ERR_SAVACCOUNT_EXPIRED);
}
v2_rate_limit_reset($c, "savaccount-link", $user_id);

// Linked to another account in the meantime: never moved silently.
$link = v2_savaccount_link_by_sub($c, $who["sub"]);
if ($link !== null && $link["user-id"] !== $user_id) {
    api_error(ERR_SAVACCOUNT_IN_USE);
}

$email = $who["account-email"];

if (!v2_savaccount_link_save($c, $user_id, $who["sub"], $password, $email)) {
    api_error(ERR_INTERNAL);
}

if ($link === null && $email !== null) {
    $username = decryptTextWithPassword($user["username"], $password);
    v2_email_savaccount_linked($email, $username === false ? "" : (string)$username, true, req_ip_address());
}

v2_savaccount_answer_session($c, $user, $password, $email, $source, array("linked" => $link === null));
?>
