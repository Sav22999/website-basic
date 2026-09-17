<?php
/**
 * POST /api/v2/token/set-expiry
 *
 * Body: { "login-id": "...", "token": "...", "expiry": "..." | null }
 *
 * Differences with v1:
 *   - only the token row of this session is touched, matched by its primary
 *     key: v1 matches the rows by the password ciphertext, so every token of
 *     the login-id sharing that ciphertext is updated;
 *   - the old expiry is read from that same row before the UPDATE. v1 reads it
 *     after a spurious extra fetch_assoc(), which moves the cursor past the
 *     matched row and makes `old_expiry` always wrong (null);
 *   - the expiry is validated and normalised, and an explicit null removes it.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);

$expiry = req_expiry("expiry");

global $tokens_table;

$token_id = (int)$session["token-id"];

// The old value comes from the row that is about to be updated, before the
// write.
$row = db_select_one($c, "SELECT `expiry` FROM `$tokens_table` WHERE `id` = ? LIMIT 1", "i", array($token_id));
if ($row === null) {
    api_error(ERR_TOKEN_NOT_FOUND);
}
$old_expiry = $row["expiry"];

$updated = db_execute(
    $c,
    "UPDATE `$tokens_table` SET `expiry` = ? WHERE `id` = ?",
    "si",
    array($expiry, $token_id)
);
if ($updated < 0) {
    api_error(ERR_DATABASE);
}

api_ok(array(
    "expiry" => $expiry,
    "old-expiry" => $old_expiry,
));
?>
