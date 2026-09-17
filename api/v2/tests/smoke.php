<?php
/**
 * Notefox API v2 - smoke tests of the pure functions of the core.
 *
 * CLI only:  php api/v2/tests/smoke.php
 *
 * It never touches the database and never sends an email: it only checks the
 * parts that can be verified in isolation (envelope encryption, one-time
 * codes, validators, revision conflict resolution). It exits with 1 when a
 * check fails, so it can be used in a pipeline.
 */

if (PHP_SAPI !== "cli") {
    http_response_code(403);
    echo "CLI only";
    exit;
}

include_once(__DIR__ . "/../include/bootstrap.php");

$tests_total = 0;
$tests_failed = 0;

function check($description, $condition)
{
    global $tests_total, $tests_failed;
    $tests_total++;
    if ($condition) {
        echo "  ok   " . $description . "\n";
        return;
    }
    $tests_failed++;
    echo "  FAIL " . $description . "\n";
}

echo "Notefox API v2 - smoke tests\n\n";

echo "envelope encryption\n";
$password = "correct horse battery staple";
$dek = v2_generate_dek();
$check_value = v2_key_check($dek);
$wrapped = v2_wrap_key($dek, $password);

check("the DEK is random and long enough", strlen($dek) >= 32 && $dek !== v2_generate_dek());
check("wrap produces the expected format", is_string($wrapped) && strpos($wrapped, V2_WRAP_PREFIX) === 0);
check("unwrap with the right password returns the same DEK", v2_unwrap_key($wrapped, $password, $check_value) === $dek);
check("unwrap with a wrong password returns null", v2_unwrap_key($wrapped, "wrong password", $check_value) === null);
check("unwrap with a wrong fingerprint returns null", v2_unwrap_key($wrapped, $password, str_repeat("a", 128)) === null);
check("a malformed payload returns null and does not throw", v2_unwrap_key("not-a-key", $password, $check_value) === null);
check("two wraps of the same DEK differ (random salt/iv)", v2_wrap_key($dek, $password) !== $wrapped);

echo "\npassword change (the data is never re-encrypted)\n";
$payload = json_encode(array("notes" => array("example.com" => "hello")));
$encrypted_data = v2_encrypt_with_dek($payload, $dek);
$rewrapped = v2_wrap_key($dek, "the new password");
check("the DEK survives the re-wrap", v2_unwrap_key($rewrapped, "the new password", $check_value) === $dek);
check("the data stays readable without being re-encrypted", v2_decrypt_with_dek($encrypted_data, v2_unwrap_key($rewrapped, "the new password", $check_value)) === $payload);
check("the old password no longer opens the key", v2_unwrap_key($rewrapped, $password, $check_value) === null);

echo "\none-time codes\n";
$code = v2_secure_code(6);
check("the code has the requested length", strlen($code) === 6);
check("the code only uses the safe alphabet", strspn($code, V2_CODE_ALPHABET) === strlen($code));
check("two codes differ", v2_secure_code(6) !== v2_secure_code(6) || v2_secure_code(6) !== v2_secure_code(6));
check("the comparison is case insensitive", v2_code_equals($code, strtolower($code)));
check("a wrong code is refused", !v2_code_equals($code, "AAAAAA" === $code ? "BBBBBB" : "AAAAAA"));
check("the code survives the encrypt/decrypt round trip", decryptTextWithPassword(encryptTextWithPassword($code, $password), $password) === $code);

echo "\nidentifiers\n";
$login_id = v2_random_id(32);
check("the login-id is 64 hexadecimal characters", strlen($login_id) === 64 && ctype_xdigit($login_id));
check("two login-id differ", $login_id !== v2_random_id(32));

echo "\ndatetime validation\n";
check("a valid date is normalised", req_parse_datetime("2024-05-01T10:00:00Z") !== null);
check("an empty date is refused", req_parse_datetime("") === null);
check("a non-parsable date is refused", req_parse_datetime("not a date") === null);
check("a far future date is refused", req_parse_datetime(date("Y-m-d H:i:s", time() + 40 * 86400)) === null);
check("an absurd past date is refused", req_parse_datetime("1970-01-02 00:00:00") === null);
check("a slightly skewed clock is accepted (the v1 bug)", req_parse_datetime(date("Y-m-d H:i:s", time() - 3600)) !== null);

echo "\nservice registry\n";
check("the default service is notefox", sav_service_default() === "notefox");
check("the default service is registered", sav_service_valid(sav_service_default()));
check("the registry is never empty", count(sav_service_names()) >= 1);
check("a name is normalised to lower case", sav_service_normalise("NoteFox") === "notefox");
check("surrounding spaces are ignored", sav_service_normalise("  notefox  ") === "notefox");
check("a path traversal attempt is refused", sav_service_normalise("../etc") === null);
check("an empty name is refused", sav_service_normalise("") === null);
check("a name longer than 32 characters is refused", sav_service_normalise(str_repeat("a", 33)) === null);
check("a name starting with a dash is refused", sav_service_normalise("-notefox") === null);
check("an unknown but well formed service is not valid", !sav_service_valid("unknown-service"));
check("only notefox has a legacy mirror table", sav_service_legacy_table("notefox") !== null);
check("an unknown service has no legacy mirror table", sav_service_legacy_table("unknown-service") === null);
check("the label of the default service is readable", is_string(sav_service_label("notefox")));

echo "\nsignup always requires the email verification code\n";

/**
 * The source of a file with every comment and docblock removed, so that the
 * checks below look at the executable code only.
 */
function source_without_comments($path)
{
    $code = "";
    foreach (token_get_all(file_get_contents($path)) as $token) {
        if (is_array($token)) {
            if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                continue;
            }
            $code .= $token[1];
            continue;
        }
        $code .= $token;
    }
    return $code;
}

$signup_source = source_without_comments(__DIR__ . "/../signup/index.php");
check("the signup issues a verification code", strpos($signup_source, "v2_codes_signup()") !== false);
check("the signup never reads otp-enabled (that is a login setting)", strpos($signup_source, "otp-enabled") === false && strpos($signup_source, "otp_enabled") === false);
check("the signup never calls v2_otp_enabled()", strpos($signup_source, "v2_otp_enabled") === false);

$signup_verify_source = source_without_comments(__DIR__ . "/../signup/verify/index.php");
check("only signup/verify can mark an account as verified", strpos($signup_verify_source, "verified") !== false);
check("the signup verification does not depend on otp-enabled either", strpos($signup_verify_source, "otp-enabled") === false);

echo "\nthe critical operations always require the emailed code\n";

$columns_password = v2_codes_password();
check("the password change has its own code columns", $columns_password["code"] === "password-change-code"
    && $columns_password["expiry"] === "password-change-expiry"
    && $columns_password["attempts"] === "password-change-attempts");
check("the password code never shares the columns of the login code", $columns_password["code"] !== v2_codes_login()["code"]);

$password_edit_source = source_without_comments(__DIR__ . "/../password/edit/index.php");
check("password/edit only issues the code", strpos($password_edit_source, "v2_code_issue") !== false
    && strpos($password_edit_source, "v2_rewrap_user_key") === false);
check("password/edit emails the code to the address of the account", strpos($password_edit_source, "v2_require_recipient") !== false);
check("password/edit never depends on otp-enabled", strpos($password_edit_source, "otp-enabled") === false
    && strpos($password_edit_source, "v2_otp_enabled") === false);

$password_verify_source = source_without_comments(__DIR__ . "/../password/edit/verify/index.php");
check("only password/edit/verify applies the change", strpos($password_verify_source, "v2_rewrap_user_key") !== false);
check("password/edit/verify checks the code", strpos($password_verify_source, "v2_code_verify") !== false);
check("password/edit/verify consumes the code", strpos($password_verify_source, "v2_code_clear") !== false);
check("password/edit/verify never depends on otp-enabled", strpos($password_verify_source, "otp-enabled") === false
    && strpos($password_verify_source, "v2_otp_enabled") === false);

echo "\nthe OTP disable flow never fails with an opaque 500\n";

$columns_otp_change = v2_codes_otp_change();
check("disabling the OTP has its own code columns", $columns_otp_change["code"] === "otp-change-code"
    && $columns_otp_change["expiry"] === "otp-change-expiry"
    && $columns_otp_change["attempts"] === "otp-change-attempts");

$otp_disable_source = source_without_comments(__DIR__ . "/../otp/disable/index.php");
$otp_disable_verify_source = source_without_comments(__DIR__ . "/../otp/disable/verify/index.php");

foreach (array("otp/disable" => $otp_disable_source, "otp/disable/verify" => $otp_disable_verify_source) as $endpoint => $source) {
    check($endpoint . " checks the additive columns before anything else", strpos($source, "db_has_column") !== false
        && strpos($source, "v2_code_column_available") !== false);
    check($endpoint . " answers 503 when they are missing, never 500", strpos($source, "ERR_UNAVAILABLE") !== false);
}

check("otp/disable emails the code to the address of the account", strpos($otp_disable_source, "v2_require_recipient") !== false);
check("otp/disable only issues the code", strpos($otp_disable_source, "v2_code_issue") !== false
    && strpos($otp_disable_source, "v2_set_otp_enabled") === false);
check("only otp/disable/verify disables the OTP", strpos($otp_disable_verify_source, "v2_set_otp_enabled") !== false
    && strpos($otp_disable_verify_source, "v2_code_verify") !== false
    && strpos($otp_disable_verify_source, "v2_code_clear") !== false);

$delete_verify_source = source_without_comments(__DIR__ . "/../delete/verify/index.php");
check("the deletion checks the code too", strpos($delete_verify_source, "v2_code_verify") !== false);
check("the deletion never depends on otp-enabled", strpos($delete_verify_source, "otp-enabled") === false
    && strpos($delete_verify_source, "v2_otp_enabled") === false);

echo "\nthe v1 mirror table is never queried blindly (the 503 of data/get)\n";

$sync_source = source_without_comments(__DIR__ . "/../include/sync.php");

check("a service without a declared mirror table is never available", v2_sync_legacy_available(null, null) === false);

// Every legacy query answers ERR_DATABASE (code 401, HTTP 503) when the table
// is missing, so none of them may run without the availability check.
$unguarded = array();
foreach (preg_split('/\bfunction\s+/', $sync_source) as $chunk) {
    if (strpos($chunk, '`$legacy_table`') === false) {
        continue;
    }
    if (strpos($chunk, "v2_sync_legacy_available") !== false) {
        continue;
    }
    $unguarded[] = trim(substr($chunk, 0, strcspn($chunk, "(")));
}
check("no query on the legacy table is left unguarded", count($unguarded) === 0);
if (count($unguarded) > 0) {
    echo "       unguarded: " . implode(", ", $unguarded) . "\n";
}

check("the check looks at the columns too, not only at the table", strpos($sync_source, "\"updated-locally-date\"") !== false
    && strpos($sync_source, "db_has_column(\$c, \$legacy_table") !== false);
$mirror_write = strstr($sync_source, "function v2_sync_write_legacy_mirror");
check("a missing mirror table never fails a snapshot write", is_string($mirror_write)
    && strpos($mirror_write, "v2_sync_legacy_available") !== false);

$data_get_source = source_without_comments(__DIR__ . "/../data/get/index.php");
check("data/get answers \"no data\" when nothing can be read", strpos($data_get_source, "ERR_NO_DATA") !== false);

$status_source = source_without_comments(__DIR__ . "/../status/index.php");
check("/status reports the mirror table", strpos($status_source, "legacy-mirror") !== false
    && strpos($status_source, "v2_sync_legacy_mirrors_ready") !== false);

echo "\nv1 keeps the v2 tables consistent (include/v1-v2-compat.php)\n";

$compat_path = __DIR__ . "/../../../include/v1-v2-compat.php";
$compat_source = source_without_comments($compat_path);

// The bridge runs inside the v1 endpoints: their answer must stay byte for
// byte the one it has always been, so it can never print nor stop the script.
check("the bridge never prints anything", strpos($compat_source, "echo ") === false
    && strpos($compat_source, "print ") === false
    && strpos($compat_source, "json_encode") === false);
check("the bridge never stops the script", strpos($compat_source, "exit") === false
    && strpos($compat_source, "die(") === false
    && strpos($compat_source, "throw ") === false);
check("the bridge never loads the v2 bootstrap (it would install its own handlers)", strpos($compat_source, "bootstrap.php") === false);
check("the bridge only reuses the self contained parts of the core", strpos($compat_source, "crypto.php") !== false
    && strpos($compat_source, "services.php") !== false
    && strpos($compat_source, "db.php") === false);

// Every entry point is a no-op on an installation that has not run the
// additive migration: it asks for the table (and, when needed, the column)
// before touching anything.
foreach (array("v1v2_rewrap_user_key", "v1v2_account_deleted") as $entry_point) {
    $body = strstr($compat_source, "function " . $entry_point);
    check($entry_point . "() is a no-op without the v2 tables", is_string($body)
        && (strpos($body, "v1v2_keys_table(") !== false || strpos($body, "v1v2_snapshot_table(") !== false));
}
foreach (array("v1v2_mirror_data_ids", "v1v2_legacy_data_inserted") as $entry_point) {
    $body = strstr($compat_source, "function " . $entry_point);
    check($entry_point . "() checks the optional legacy-data-id column", is_string($body)
        && strpos($body, "legacy-data-id\")") !== false);
}
check("the password change re-wraps the key instead of re-encrypting the notes", strpos($compat_source, "v2_unwrap_key") !== false
    && strpos($compat_source, "v2_wrap_key") !== false
    && strpos($compat_source, "v2_generate_dek") === false);

$v1_password_edit = source_without_comments(__DIR__ . "/../../v1/password/edit/index.php");
check("v1 password/edit re-wraps the data key", strpos($v1_password_edit, "v1v2_password_changed") !== false);
check("v1 password/edit re-encrypts the newest rows, not 50 random ones", strpos($v1_password_edit, "ORDER BY `id` DESC LIMIT 50") !== false);
check("v1 password/edit re-encrypts the mirror rows the LIMIT would miss", strpos($v1_password_edit, "v1v2_mirror_data_ids") !== false
    && strpos($v1_password_edit, "v1v2_reencrypt_legacy_row") !== false);

$v1_delete_verify = source_without_comments(__DIR__ . "/../../v1/delete/verify/index.php");
check("v1 delete/verify removes the key and the snapshots too", strpos($v1_delete_verify, "v1v2_account_deleted") !== false);

$v1_data_insert = source_without_comments(__DIR__ . "/../../v1/data/insert/index.php");
check("v1 data/insert invalidates the stale mirror pointer", strpos($v1_data_insert, "v1v2_legacy_data_inserted") !== false);

// The three endpoints keep their own request/response contract: no v2 helper
// ever decides what they answer.
foreach (array("password/edit" => $v1_password_edit, "delete/verify" => $v1_delete_verify, "data/insert" => $v1_data_insert) as $endpoint => $source) {
    check("v1 " . $endpoint . " still answers with its own echo_result/echo_error", strpos($source, "echo_result") !== false
        && strpos($source, "echo_error") !== false
        && strpos($source, "api_error") === false
        && strpos($source, "api_ok") === false);
}

echo "\nrevision conflicts\n";
function revision_accepted($base_revision, $current_revision)
{
    // Same rule as v2_sync_write().
    return $base_revision === null || $base_revision === $current_revision;
}

check("a write without base-revision is always accepted", revision_accepted(null, 7));
check("a write with the current revision is accepted", revision_accepted(7, 7));
check("a write with an outdated revision is a conflict", !revision_accepted(6, 7));
check("a write with a revision from the future is a conflict", !revision_accepted(8, 7));

echo "\nsync history permission (users.`history-enabled`)\n";

// Mirror image of otp-enabled: every uncertain case must read as DENIED, so an
// installation that has not run block 10 of migration.sql closes the history
// instead of opening it to everybody.
check("a missing column is not a permission", v2_history_enabled(null, array("email" => "x")) === false);
check("a NULL value is not a permission", v2_history_enabled(null, array("history-enabled" => null)) === false);
check("0 is not a permission", v2_history_enabled(null, array("history-enabled" => "0")) === false);
check("no user row is not a permission", v2_history_enabled(null, null) === false);
check("1 is a permission", v2_history_enabled(null, array("history-enabled" => "1")) === true);

$history_list_source = source_without_comments(__DIR__ . "/../data/get/history/index.php");
$history_download_source = source_without_comments(__DIR__ . "/../data/get/history/download/index.php");
check("data/get/history requires the permission", strpos($history_list_source, "v2_require_history(\$session)") !== false);
check("data/get/history/download requires it before reading any row", strpos($history_download_source, "v2_require_history(\$session)") !== false
    && strpos($history_download_source, "v2_require_history(\$session)") < strpos($history_download_source, "v2_sync_history_entry"));

$services_source = source_without_comments(__DIR__ . "/../data/services/index.php");
check("data/services reports the permission to the client", strpos($services_source, "\"history-enabled\" => \$session[\"history-enabled\"]") !== false);

// Nobody may grant it to themselves: no v2 source is allowed to WRITE the
// column, it is a manual UPDATE (see block 10 of migration.sql).
$writers = array();
foreach (array("include/auth.php", "include/sync.php", "include/keys.php", "otp/enable/index.php", "otp/disable/index.php", "password/edit/verify/index.php", "signup/verify/index.php", "data/services/index.php") as $file) {
    if (strpos(source_without_comments(__DIR__ . "/../" . $file), "`history-enabled` =") !== false) {
        $writers[] = $file;
    }
}
check("no endpoint can grant the permission", count($writers) === 0);
if (count($writers) > 0) {
    echo "       writes the column: " . implode(", ", $writers) . "\n";
}

echo "\nerror catalogue\n";
$catalogue = api_catalogue();
check("every code has a description and an HTTP status", count(array_filter($catalogue, function ($entry) {
    return is_array($entry) && count($entry) === 2 && is_string($entry[0]) && is_int($entry[1]);
})) === count($catalogue));
check("the conflict is a real 409", $catalogue[ERR_REVISION_CONFLICT][1] === 409);
check("a payload that is too large is a real 413", $catalogue[ERR_PAYLOAD_TOO_LARGE][1] === 413);
check("the rate limit is a real 429", $catalogue[ERR_RATE_LIMITED][1] === 429);
check("a denied sync history is a real 403", $catalogue[ERR_HISTORY_FORBIDDEN][1] === 403);

echo "\nemail\n";
$html = v2_email_render("Sara", "Title", "Body", "Footer", "A1B2C3", "127.0.0.1");
check("the template is filled in", strpos($html, "A1B2C3") !== false && strpos($html, "Sara") !== false);
check("no placeholder is left behind", strpos($html, "{{") === false);

echo "\n" . ($tests_total - $tests_failed) . "/" . $tests_total . " checks passed\n";
exit($tests_failed === 0 ? 0 : 1);
?>
