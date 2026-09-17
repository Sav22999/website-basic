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
?>