<?php
/**
 * Shared guard for the periodic cleanup scripts.
 *
 * CLI runs (php include/periodic-checks/check-*.php) are always allowed.
 * HTTP runs need the secret `$cron_token` from include/credentials.php, sent
 * as the `X-Cron-Token` header or the `token` query parameter. Without a
 * configured token every HTTP run is refused.
 */

include_once(__DIR__ . "/../credentials.php");

if (PHP_SAPI === "cli") {
    return;
}

header("Content-Type: application/json");
header("Cache-Control: no-store");

$cron_expected = isset($cron_token) && is_string($cron_token) ? $cron_token : "";
$cron_provided = "";
if (isset($_SERVER["HTTP_X_CRON_TOKEN"])) {
    $cron_provided = (string)$_SERVER["HTTP_X_CRON_TOKEN"];
} elseif (isset($_GET["token"])) {
    $cron_provided = (string)$_GET["token"];
}

if (strlen($cron_expected) < 32 || !hash_equals($cron_expected, $cron_provided)) {
    http_response_code(403);
    echo json_encode(array("code" => "403", "status" => "Error", "description" => "Forbidden"));
    exit;
}

unset($cron_expected, $cron_provided);
