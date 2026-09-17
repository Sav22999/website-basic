<?php
/**
 * POST /api/v2/delete
 *
 * Body: { "email": "...", "password": "..." }
 *
 * Asks for the deletion code, exactly like v1, but:
 *   - the request is rate limited (3 per hour on the email address), so the
 *     endpoint cannot be used to bomb a mailbox;
 *   - the account is looked up by the hash of the email and the password is
 *     checked with a constant time comparison: a wrong email and a wrong
 *     password give the very same generic error;
 *   - the code comes from random_int() (v1 uses rand()), gets an expiry and a
 *     maximum number of attempts;
 *   - the email goes to the address of the request only because its hash is the
 *     user-id the lookup was made with.
 */

include_once(__DIR__ . "/../include/bootstrap.php");

req_require_post();

$email = req_email("email");
$password = req_password("password");

$c = db();
$ip_address = req_ip_address();

v2_rate_limit($c, "delete-request", $email, 3, 3600, 3600);

global $users_table;

$user_id = v2_email_hash($email);
$user = v2_user_by_id($c, $user_id);

if ($user === null || !v2_verify_password($user, $password)) {
    api_error(ERR_INVALID_CREDENTIALS);
}

// A code already issued and not expired yet: no new email is sent. When the
// additive `deleting-expiry` column is missing there is nothing to compare, so
// the request is allowed (the rate limit above is what protects the mailbox).
if (isset($user["deleting-code"]) && $user["deleting-code"] !== null && $user["deleting-code"] !== "") {
    $pending_expiry = isset($user["deleting-expiry"]) ? $user["deleting-expiry"] : null;
    if ($pending_expiry !== null && strtotime($pending_expiry) > time()) {
        api_error(ERR_DELETING_PENDING);
    }
}

$issued = v2_code_issue($c, $users_table, "email", $user_id, $password, v2_codes_delete(), 10);
if ($issued === null) {
    api_error(ERR_INTERNAL);
}

$username = decryptTextWithPassword($user["username"], $password);

v2_email_delete_code($email, $username === false ? "" : $username, $issued["code"], $ip_address, $issued["expiry"], false);

api_ok(array(
    "verification-required" => true,
    "verification-expiry" => $issued["expiry"],
));
?>
