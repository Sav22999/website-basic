<?php
/**
 * POST /api/v2/login/set-expiry
 *
 * Body: { "login-id": "...", "token": "...", "expiry": "..." | null }
 *
 * Differences with v1:
 *   - the expiry is validated and normalised before being stored: v1 writes
 *     whatever getCorrectedDateTimestamp() returns straight into the DATETIME
 *     column, so an unparsable value silently becomes a broken date;
 *   - an explicit null (or an empty string) removes the expiry, which v1 has no
 *     way to express;
 *   - the row is updated by its `login-id` only, never by a password ciphertext;
 *   - the previous value is returned, read from the authenticated session.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$expiry = req_expiry("expiry");

global $logins_table;

$login_id = $session["login"]["login-id"];
$old_expiry = $session["login"]["expiry"];

$updated = db_execute(
    $c,
    "UPDATE `$logins_table` SET `expiry` = ? WHERE `login-id` = ?",
    "ss",
    array($expiry, $login_id)
);
if ($updated < 0) {
    api_error(ERR_DATABASE);
}

api_ok(array(
    "login-id" => $login_id,
    "expiry" => $expiry,
    "old-expiry" => $old_expiry,
));
?>
