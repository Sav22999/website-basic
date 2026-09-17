<?php
/**
 * Sav Account API v2 - schema and configuration preflight.
 *
 * CLI only:  php api/v2/tests/schema-check.php
 *
 * Unlike smoke.php (pure functions, no database), this script connects to the
 * database configured in include/credentials.php and answers one question:
 * is this installation ready to expose /api/v2?
 *
 * It reuses the very same helpers as GET /api/v2/status (db_has_table(),
 * db_has_column(), v2_sync_legacy_mirrors_ready(), sav_service_names(),
 * v2_mailer_available()), so the two diagnoses can never diverge. Nothing is
 * written, no email is sent, no note is read.
 *
 * Exit code 1 when a MANDATORY element is missing (the API would degrade),
 * 0 with warnings when only the optional blocks or the legacy mirror are.
 * See api/v2/install/migration.sql and api/v2/install/DEPLOY.md.
 */

if (PHP_SAPI !== "cli") {
    http_response_code(403);
    echo "CLI only";
    exit;
}

include_once(__DIR__ . "/../include/bootstrap.php");

$checks_missing = 0;
$checks_warning = 0;

/**
 * One line of the report. $mandatory decides whether a missing element is a
 * blocking error or just a warning; $note explains what degrades without it.
 */
function report($description, $present, $mandatory = true, $note = "")
{
    global $checks_missing, $checks_warning;

    if ($present) {
        $label = "  ok      ";
    } elseif ($mandatory) {
        $checks_missing++;
        $label = "  MISSING ";
    } else {
        $checks_warning++;
        $label = "  warning ";
    }

    echo $label . $description;
    if (!$present && $note !== "") {
        echo " - " . $note;
    }
    echo "\n";
}

function config_name($variable)
{
    return isset($variable) && is_string($variable) && $variable !== "" ? $variable : null;
}

echo "Sav Account API v2 - schema check\n\n";

echo "configuration (include/credentials.php)\n";
$user_keys_name = config_name(isset($user_keys_table) ? $user_keys_table : null);
$data_current_name = config_name(isset($data_current_table) ? $data_current_table : null);
$rate_limits_name = config_name(isset($rate_limits_table) ? $rate_limits_table : null);
$users_name = config_name(isset($users_table) ? $users_table : null);
$logins_name = config_name(isset($logins_table) ? $logins_table : null);

report("\$user_keys_table is configured", $user_keys_name !== null, true, "block 1 of migration.sql cannot be located");
report("\$data_current_table is configured", $data_current_name !== null, true, "every data/* endpoint answers 503");
report("\$rate_limits_table is configured", $rate_limits_name !== null, true, "block 3 of migration.sql cannot be located");
report("\$users_table is configured", $users_name !== null, true, "the v1 accounts table is unknown");
report("\$logins_table is configured", $logins_name !== null, true, "the v1 sessions table is unknown");

$service_names = sav_service_names();
report("the \$services registry is usable", count($service_names) >= 1, true, "no service is declared and the fallback failed");
report(
    "the default service (" . sav_service_default() . ") is declared",
    sav_service_valid(sav_service_default()),
    true,
    "a client that does not send `service` would be refused with 400"
);
echo "          services: " . implode(", ", $service_names) . "\n";

report("the mailer is available (Composer + SMTP)", v2_mailer_available(), true, "no verification code can be emailed");

echo "\ndatabase\n";
// A preflight must answer, not hang: without this a wrong host makes mysqli
// wait for the system timeout (the API itself never runs from the CLI).
@ini_set("mysqli.connect_timeout", "10");
$c = db_connect();
report("the database is reachable", $c !== null, true, "check the credentials, the details are only in the server log");

if ($c === null) {
    echo "\nSchema not verified: no connection.\n";
    exit(1);
}

echo "\nschema - mandatory blocks\n";
report(
    "1) table `" . $user_keys_name . "` (user_keys)",
    $user_keys_name !== null && db_has_table($c, $user_keys_name),
    true,
    "no account gets a data key: `keys: false` in GET /status"
);
$snapshots = $data_current_name !== null && db_has_table($c, $data_current_name);
report(
    "2) table `" . $data_current_name . "` (snapshot)",
    $snapshots,
    true,
    "the sync has no snapshot: run block 2a or 2b, and check \$data_current_table after the RENAME"
);
report(
    "2) column `service` of the snapshot table",
    $snapshots && db_has_column($c, $data_current_name, "service"),
    true,
    "every data/* endpoint answers 503 (multi-service migration not applied)"
);
report(
    "3) table `" . $rate_limits_name . "` (rate_limits)",
    $rate_limits_name !== null && db_has_table($c, $rate_limits_name),
    true,
    "the sensitive endpoints lose the brute force protection"
);
report(
    "4) column `otp-enabled` of `" . $users_name . "`",
    $users_name !== null && db_has_column($c, $users_name, "otp-enabled"),
    true,
    "the otp/* endpoints cannot read the setting"
);
report(
    "5) column `password-v2` of `" . $users_name . "`",
    $users_name !== null && db_has_column($c, $users_name, "password-v2"),
    true,
    "the modern password hash is never kept aligned"
);
report(
    "8) columns `password-change-*` of `" . $users_name . "`",
    $users_name !== null
        && db_has_column($c, $users_name, "password-change-code")
        && db_has_column($c, $users_name, "password-change-expiry")
        && db_has_column($c, $users_name, "password-change-attempts"),
    true,
    "POST /password/edit answers 500"
);

echo "\nschema - optional blocks\n";
report(
    "6) columns `otp-change-*` of `" . $users_name . "`",
    $users_name !== null
        && db_has_column($c, $users_name, "otp-change-code")
        && db_has_column($c, $users_name, "otp-change-expiry")
        && db_has_column($c, $users_name, "otp-change-attempts"),
    false,
    "POST /otp/disable and its verify step answer 503, the 2FA stays enabled"
);
report(
    "7) columns `verification-expiry` / `verification-attempts` / `deleting-attempts` of `" . $users_name . "`",
    $users_name !== null
        && db_has_column($c, $users_name, "verification-expiry")
        && db_has_column($c, $users_name, "verification-attempts")
        && db_has_column($c, $users_name, "deleting-attempts"),
    false,
    "the codes lose their expiry and their attempt limit"
);
report(
    "7) column `verification-attempts` of `" . $logins_name . "`",
    $logins_name !== null && db_has_column($c, $logins_name, "verification-attempts"),
    false,
    "the login code has no attempt limit"
);
report(
    "2) column `legacy-data-id` of the snapshot table",
    $snapshots && db_has_column($c, $data_current_name, "legacy-data-id"),
    false,
    "the v1 mirror row is recreated instead of being updated"
);
report(
    "10) column `history-enabled` of `" . $users_name . "`",
    $users_name !== null && db_has_column($c, $users_name, "history-enabled"),
    false,
    "the two data/get/history* endpoints answer 433 to every account (the permission cannot be granted)"
);

echo "\nlegacy mirror (v1 tables, not part of the additive DDL)\n";
$mirrors = 0;
foreach ($service_names as $service) {
    $legacy_table = sav_service_legacy_table($service);
    if ($legacy_table === null) {
        echo "  ok      " . $service . " declares no legacy table (it never touches a v1 table)\n";
        continue;
    }
    $mirrors++;
    report(
        $service . " -> `" . $legacy_table . "`",
        v2_sync_legacy_available_for_service($c, $service),
        false,
        "the table is missing or lost a column: the history is empty and the v1 clients stop seeing the notes"
    );
}
if ($mirrors > 0) {
    report(
        "`legacy-mirror` of GET /status",
        v2_sync_legacy_mirrors_ready($c),
        false,
        "at least one service cannot use its v1 mirror table"
    );
}

echo "\n";
if ($checks_missing > 0) {
    echo $checks_missing . " mandatory element(s) missing";
    if ($checks_warning > 0) {
        echo ", " . $checks_warning . " warning(s)";
    }
    echo ".\nRun api/v2/install/migration.sql and check include/credentials.php.\n";
    exit(1);
}

if ($checks_warning > 0) {
    echo "Ready, with " . $checks_warning . " warning(s): the API answers, some features degrade.\n";
    exit(0);
}

echo "Ready: schema and configuration complete.\n";
exit(0);
?>
