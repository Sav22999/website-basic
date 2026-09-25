<?php
$_maintenance_file = $_SERVER['DOCUMENT_ROOT'] . '/.maintenance';
$GLOBALS['_maintenance_bypass'] = false;

if (file_exists($_maintenance_file)) {
    $bypass_key = trim(file_get_contents($_maintenance_file));

    if ($bypass_key && !empty($_GET['bypass']) && $_GET['bypass'] === $bypass_key) {
        setcookie('maintenance_bypass', hash('sha256', $bypass_key), time() + 86400, '/', '', false, true);
        $GLOBALS['_maintenance_bypass'] = true;
    } elseif ($bypass_key && !empty($_COOKIE['maintenance_bypass']) && $_COOKIE['maintenance_bypass'] === hash('sha256', $bypass_key)) {
        $GLOBALS['_maintenance_bypass'] = true;
    } else {
        while (ob_get_level()) ob_end_clean();
        http_response_code(503);
        header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sav PDF Viewer — Maintenance</title>
<link rel="icon" href="/images/icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/style.css">
<style>
body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; text-align: center; }
.maintenance-page { max-width: 480px; margin: 0 auto; }
.maintenance-page__illustration { width: 180px; height: 180px; margin: 0 auto 32px; opacity: 0; animation: fadeInUp 0.6s ease 0.1s both; }
.maintenance-page__title { font-family: var(--font-family-display); font-size: var(--fs-xxl); font-weight: 400; color: var(--on-primary-color); margin-bottom: 12px; opacity: 0; animation: fadeInUp 0.6s ease 0.3s both; }
.maintenance-page__desc { font-size: var(--fs-md); color: var(--tertiary-color-variant); line-height: 1.6; margin-bottom: 36px; opacity: 0; animation: fadeInUp 0.6s ease 0.5s both; }
.maintenance-page__brand { display: inline-flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.3); text-decoration: none; font-size: 13px; transition: color 0.2s; opacity: 0; animation: fadeInUp 0.6s ease 0.7s both; }
.maintenance-page__brand:hover { color: rgba(255,255,255,0.5); }
.maintenance-page__brand img { width: 28px; height: 28px; border-radius: 6px; }
.illustration__ring { animation: ringPulse 3s ease-in-out infinite; transform-origin: 90px 90px; }
.illustration__wrench { animation: wrenchSwing 4s ease-in-out infinite; transform-origin: 72px 70px; }
.illustration__minute { animation: minuteHand 8s linear infinite; transform-origin: 112px 100px; }
.illustration__hour { animation: hourHand 24s linear infinite; transform-origin: 112px 100px; }
.illustration__center-dot { animation: dotPulse 2s ease-in-out infinite; }
@keyframes ringPulse {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.03); }
}
@keyframes wrenchSwing {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-6deg); }
    75% { transform: rotate(4deg); }
}
@keyframes minuteHand {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
@keyframes hourHand {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
@keyframes dotPulse {
    0%, 100% { r: 2; }
    50% { r: 3; }
}
@media (max-width: 860px) {
    .maintenance-page__illustration { width: 140px; height: 140px; margin-bottom: 24px; }
}
</style>
</head>
<body>
<div class="maintenance-page">
    <svg class="maintenance-page__illustration" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle class="illustration__ring" cx="90" cy="90" r="80" stroke="rgba(255,255,255,0.06)" stroke-width="2"/>
        <g class="illustration__wrench">
            <path d="M62 60l10 10-22 22a5 5 0 0 0 7 7l22-22 10 10 4-30z" stroke="#d32f2f" stroke-width="2.5" stroke-linejoin="round" fill="rgba(211,47,47,0.1)"/>
        </g>
        <circle cx="112" cy="100" r="24" stroke="#d32f2f" stroke-width="2.5" fill="rgba(211,47,47,0.08)"/>
        <line x1="112" y1="78" x2="112" y2="81" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
        <line x1="112" y1="119" x2="112" y2="122" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
        <line x1="134" y1="100" x2="131" y2="100" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
        <line x1="90" y1="100" x2="93" y2="100" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
        <line class="illustration__minute" x1="112" y1="100" x2="112" y2="84" stroke="#d32f2f" stroke-width="2.5" stroke-linecap="round"/>
        <line class="illustration__hour" x1="112" y1="100" x2="122" y2="106" stroke="#d32f2f" stroke-width="2" stroke-linecap="round"/>
        <circle class="illustration__center-dot" cx="112" cy="100" r="2" fill="#d32f2f"/>
    </svg>
    <h1 class="maintenance-page__title">Be right back</h1>
    <p class="maintenance-page__desc">We're doing a little maintenance. The site will be back shortly — try again soon.</p>
    <a href="/" class="maintenance-page__brand">
        <img src="/images/icon.png" alt="">
        Sav PDF Viewer
    </a>
</div>
</body>
</html>
<?php
        exit;
    }
}
