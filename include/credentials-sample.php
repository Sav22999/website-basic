<?php
$localhost_db = "<YOUR DATABASE LOCALHOST>";
$username_db = "<YOUR DATABASE USERNAME>";
$password_db = "<YOUR DATABASE PASSWORD>";
$database_notefox = "<YOUR DATABASE NAME>";

$data_table = "<YOUR DATA TABLE>";//e.g. data - the v1 table read by the old extensions
$logins_table = "<YOUR LOGINS TABLE>";//e.g. logins
$users_table = "<YOUR USERS TABLE>";//e.g. users
$tokens_table = "<YOUR TOKENS TABLE>";//e.g. tokens
$error_logs_table = "<YOUR ERROR LOGS TABLE>";
$telemetry_table = "<YOUR TELEMETRY TABLE>";
$user_keys_table = "<YOUR USER KEYS TABLE>";//e.g. user_keys
$data_current_table = "<YOUR DATA CURRENT TABLE>";//e.g. sav_data_current
$rate_limits_table = "<YOUR RATE LIMITS TABLE>";//e.g. rate_limits
$attachments_table = "<YOUR ATTACHMENTS TABLE>";//e.g. sav_attachments (optional, default sav_attachments)
$attachments_quota_bytes = 100 * 1024 * 1024;//space for the screenshots of each account (optional, default 100 MB)

// Sav Account services: the account is shared, the synchronised data is
// partitioned per service. "legacy-table" is the v1 mirror table, if any.
$services = array(
    "notefox" => array(
        "name" => "Notefox",
        "legacy-table" => "<YOUR DATA TABLE>",
    ),
);

$database_notefox_alpha = "<DATABASE ALPHA>";

$email_address = "<EMAIL ADDRESS>";
$email_password = "<EMAIL PASSWORD>";
$email_smtp = "<EMAIL SMTP SERVER>";
$email_smtp_port = "<EMAIL SMTP PORT>";//probably 465

// "Continue with SavAccount" (optional: the button stays hidden until the keys
// are here and block 13 of api/v2/install/migration.sql has been run).
// Register Notefox on https://www.savaccount.eu as a CONFIDENTIAL service with
// PAIRWISE identifiers and this redirect URI:
//   https://www.notefox.eu/my/savaccount/
$savaccount_client_id = "<SAVACCOUNT CLIENT ID>";
$savaccount_client_secret = "<SAVACCOUNT CLIENT SECRET>";
// At least 32 chars, e.g. php -r 'echo bin2hex(random_bytes(32));'
// Changing it makes every linked account type its password once again.
$savaccount_pepper = "<SAVACCOUNT PEPPER>";

// Secret for the cron calls to include/periodic-checks/*.php (at least 32 chars),
// e.g. php -r 'echo bin2hex(random_bytes(32));'
$cron_token = "<CRON TOKEN>";
?>