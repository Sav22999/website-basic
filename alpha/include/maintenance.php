<?php
global $root_path, $maintenance_bypass_active;
$maintenance_bypass_active = false;

$maintenance_file = $root_path . '/.maintenance';
if (!file_exists($maintenance_file)) {
    return;
}

$maintenance_key = trim(file_get_contents($maintenance_file));
if ($maintenance_key === '') {
    return;
}

$key_hash = hash('sha256', $maintenance_key);

if (isset($_GET['bypass']) && $_GET['bypass'] === $maintenance_key) {
    $is_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    setcookie('maintenance_bypass', $key_hash, time() + 86400, '/', '', $is_https, true);
    $maintenance_bypass_active = true;
    return;
}

if (isset($_COOKIE['maintenance_bypass']) && $_COOKIE['maintenance_bypass'] === $key_hash) {
    $maintenance_bypass_active = true;
    return;
}

while (ob_get_level()) {
    ob_end_clean();
}

http_response_code(503);
header('Retry-After: 3600');
header('Content-Type: text/html; charset=UTF-8');

include $root_path . '/alpha/include/maintenance-page.php';
exit;
