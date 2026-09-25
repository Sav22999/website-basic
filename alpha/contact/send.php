<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path;

include_once($root_path . '/include/credentials.php');

if (!defined('NOTEFOX_V2_ROOT')) {
    define('NOTEFOX_V2_ROOT', $root_path);
}
include_once($root_path . '/api/v2/include/mailer.php');
include_once($root_path . '/alpha/include/altcha.php');

if (!v2_mailer_available()) {
    http_response_code(500);
    echo json_encode(array('error' => 'Mailer not available'));
    exit;
}

$config_path = $root_path . '/alpha/include/mail-config.php';
$altcha_hmac_key = '';
if (file_exists($config_path)) {
    $cfg = include $config_path;
    $altcha_hmac_key = $cfg['altcha_hmac_key'];
}

if (!empty($_POST['website'])) {
    echo json_encode(array('ok' => true));
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$topic = isset($_POST['topic']) ? trim($_POST['topic']) : '';
$topic_other = isset($_POST['topic_other']) ? trim($_POST['topic_other']) : '';
$version = isset($_POST['version']) ? trim($_POST['version']) : '';
$browser = isset($_POST['browser']) ? trim($_POST['browser']) : '';
$browser_other = isset($_POST['browser_other']) ? trim($_POST['browser_other']) : '';
$os = isset($_POST['os']) ? trim($_POST['os']) : '';
$os_other = isset($_POST['os_other']) ? trim($_POST['os_other']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$altcha = isset($_POST['altcha']) ? $_POST['altcha'] : '';
$browser_lang = isset($_POST['browser_lang']) ? trim($_POST['browser_lang']) : '';
$timezone = isset($_POST['timezone']) ? trim($_POST['timezone']) : '';

if ($name === '' || $email === '' || $topic === '' || $browser === '' || $message === '') {
    http_response_code(400);
    echo json_encode(array('error' => 'missing_fields'));
    exit;
}

if ($topic === 'other' && $topic_other === '') {
    http_response_code(400);
    echo json_encode(array('error' => 'missing_fields'));
    exit;
}

if ($browser === 'other' && $browser_other === '') {
    http_response_code(400);
    echo json_encode(array('error' => 'missing_fields'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(array('error' => 'invalid_email'));
    exit;
}

if ($altcha === '' || !altcha_verify($altcha, $altcha_hmac_key)) {
    http_response_code(400);
    echo json_encode(array('error' => 'captcha_failed'));
    exit;
}

$topic_labels = array('bug' => 'Bug', 'report' => 'Report', 'suggestion' => 'Suggestion', 'donation' => 'Donation', 'pro-features' => 'Pro features', 'other' => 'Other');
$topic_display = isset($topic_labels[$topic]) ? $topic_labels[$topic] : $topic;
if ($topic === 'other' && $topic_other !== '') {
    $topic_display = 'Other: ' . $topic_other;
}

$browser_display = ucfirst($browser);
if ($browser === 'other' && $browser_other !== '') {
    $browser_display = 'Other: ' . $browser_other;
}

$os_display = '';
if ($os !== '') {
    $os_labels = array('windows' => 'Windows', 'macos' => 'macOS', 'linux' => 'Linux', 'other' => 'Other');
    $os_display = isset($os_labels[$os]) ? $os_labels[$os] : $os;
    if ($os === 'other' && $os_other !== '') {
        $os_display = 'Other: ' . $os_other;
    }
}

$safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safe_email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safe_topic = htmlspecialchars($topic_display, ENT_QUOTES, 'UTF-8');
$safe_version = htmlspecialchars($version, ENT_QUOTES, 'UTF-8');
$safe_browser = htmlspecialchars($browser_display, ENT_QUOTES, 'UTF-8');
$safe_os = htmlspecialchars($os_display, ENT_QUOTES, 'UTF-8');
$safe_message = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$safe_lang = htmlspecialchars($browser_lang, ENT_QUOTES, 'UTF-8');
$safe_tz = htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8');
$date = date('d M Y, H:i');

$icon_url = 'https://www.notefox.eu/images/icon.svg';
$accent = '#ffa56f';
$accent_dark = '#e8944f';
$section_bg = '#fff0e6';
$font_import = '<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@700&display=swap" rel="stylesheet">';
$notefox_font = "'Merienda', sans-serif";

function build_email_html($white_section, $grey_section)
{
    global $icon_url, $accent, $section_bg, $font_import, $notefox_font;
    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">' . $font_import . '</head>'
        . '<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;">'
        . '<tr><td align="center" style="padding:24px 16px;">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">'
        . '<tr><td align="center" style="background:' . $accent . ';padding:24px 20px;">'
        . '<table role="presentation" cellpadding="0" cellspacing="0"><tr>'
        . '<td style="vertical-align:middle;padding-right:12px;"><img src="' . $icon_url . '" alt="Notefox" width="36" height="36" style="display:block;"></td>'
        . '<td style="vertical-align:middle;"><span style="font-size:22px;font-weight:700;color:#ffffff;letter-spacing:0.5px;font-family:' . $notefox_font . ';">Notefox</span></td>'
        . '</tr></table>'
        . '</td></tr>'
        . '<tr><td style="background:#ffffff;padding:28px 24px;font-size:15px;line-height:1.6;color:#333333;">'
        . $white_section
        . '</td></tr>'
        . '<tr><td style="background:' . $section_bg . ';padding:24px;font-size:15px;line-height:1.6;color:#333333;">'
        . $grey_section
        . '</td></tr>'
        . '<tr><td align="center" style="background:' . $accent . ';padding:16px 20px;">'
        . '<span style="font-size:13px;color:#ffffff;">Developed with &#10084; by <a href="https://saveriomorelli.com" style="color:#ffffff;text-decoration:underline;" target="_blank" rel="noopener">Saverio Morelli</a></span>'
        . '</td></tr>'
        . '</table>'
        . '</td></tr></table>'
        . '</body></html>';
    return $html;
}

function info_row($label, $value)
{
    if ($value === '') return '';
    return '<tr><td style="padding:6px 0;color:#888;font-size:13px;width:100px;vertical-align:top;">' . $label . '</td><td style="padding:6px 0;font-weight:600;">' . $value . '</td></tr>';
}

$dev_white = '<p style="margin:0 0 16px;font-size:13px;color:#888;">Received on ' . $date . '</p>'
    . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">'
    . info_row('Name', $safe_name)
    . info_row('Email', '<a href="mailto:' . $safe_email . '" style="color:' . $accent_dark . ';">' . $safe_email . '</a>')
    . info_row('Topic', $safe_topic)
    . info_row('Version', $safe_version)
    . info_row('Browser', $safe_browser)
    . info_row('OS', $safe_os)
    . info_row('Language', $safe_lang)
    . info_row('Timezone', $safe_tz)
    . '</table>';

$dev_grey = '<p style="margin:0 0 4px;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Message</p>'
    . '<p style="margin:0;">' . $safe_message . '</p>';

$user_white = '<p style="margin:0 0 16px;">Hi <strong>' . $safe_name . '</strong>,</p>'
    . '<p style="margin:0;">We have received your message and will get back to you as soon as possible.</p>';

$user_grey = '<p style="margin:0 0 4px;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Topic</p>'
    . '<p style="margin:0 0 12px;font-weight:600;">' . $safe_topic . '</p>'
    . '<p style="margin:0 0 4px;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Message</p>'
    . '<p style="margin:0;">' . $safe_message . '</p>'
    . '<p style="margin:16px 0 0;font-size:13px;color:#888;">You don\'t need to reply to this email.</p>';

$dev_html = build_email_html($dev_white, $dev_grey);
$user_html = build_email_html($user_white, $user_grey);

$email_subject = 'Contact: ' . $topic_display;

$dev_sent = v2_send_email('saverio.morelli@protonmail.com', $email_subject, $dev_html);
$user_sent = v2_send_email($email, 'We received your message — Notefox', $user_html);

if ($dev_sent || $user_sent) {
    echo json_encode(array('ok' => true));
} else {
    http_response_code(500);
    echo json_encode(array('error' => 'send_failed'));
}
