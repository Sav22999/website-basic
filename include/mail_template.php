<?php
function mail_template(string $title, string $body_html): string
{
    $icon_url = 'https://savpdfviewer.com/images/icon.png';

    return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>' . htmlspecialchars($title) . '</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;color:#333;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;">
<tr><td align="center" style="padding:32px 16px;">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;">

<!-- Header -->
<tr><td align="center" style="background:#d32f2f;padding:20px 32px;">
<table role="presentation" cellpadding="0" cellspacing="0">
<tr>
<td valign="middle"><img src="' . $icon_url . '" alt="Sav PDF Viewer" width="36" height="36" style="display:block;border-radius:6px;border:0;" /></td>
<td style="padding-left:14px;color:#ffffff;font-size:20px;font-weight:700;letter-spacing:0.3px;" valign="middle">Sav PDF Viewer</td>
</tr>
</table>
</td></tr>

<!-- Body -->
<tr><td style="padding:32px;">
' . $body_html . '
</td></tr>

<!-- Footer -->
<tr><td style="background:#d32f2f;padding:16px 32px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center" style="font-size:13px;color:rgba(255,255,255,0.85);line-height:1.5;">
Developed by <a href="https://saveriomorelli.com" style="color:#ffffff;text-decoration:none;font-weight:600;">Saverio Morelli</a>
</td></tr>
</table>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>';
}

function mail_confirmation_body(string $name, string $topic_label, string $app_version, string $os_label, string $os_version, string $description): string
{
    $rows = '';
    $rows .= detail_row('Topic', $topic_label);
    if ($app_version) $rows .= detail_row('App version', $app_version);
    if ($os_label) $rows .= detail_row('OS', $os_label);
    if ($os_version) $rows .= detail_row('OS version', $os_version);

    $desc_escaped = nl2br(htmlspecialchars($description));

    return '<p style="font-size:15px;line-height:1.6;margin:0 0 20px;">Hi <strong>' . htmlspecialchars($name) . '</strong>,</p>
<p style="font-size:15px;line-height:1.6;margin:0 0 24px;">We received your message. You will get a response as soon as possible.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border:1px solid #eee;border-radius:6px;overflow:hidden;">
<tr><td style="background:#f9f9f9;padding:12px 16px;font-size:13px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #eee;">Summary</td></tr>
<tr><td style="padding:0;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $rows . '</table>
</td></tr>
</table>

<div style="margin:0 0 8px;font-size:13px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:0.5px;">Description</div>
<div style="padding:16px;background:#f9f9f9;border-radius:6px;font-size:14px;line-height:1.6;color:#444;">' . $desc_escaped . '</div>';
}

function mail_developer_body(string $name, string $user_email, string $topic_label, string $app_version, string $os_label, string $os_version, string $description, string $browser_lang = '', string $country = ''): string
{
    $rows = '';
    $rows .= detail_row('Name', $name);
    $rows .= detail_row('Email', '<a href="mailto:' . htmlspecialchars($user_email) . '" style="color:#d32f2f;">' . htmlspecialchars($user_email) . '</a>');
    $rows .= detail_row('Topic', $topic_label);
    if ($app_version) $rows .= detail_row('App version', $app_version);
    if ($os_label) $rows .= detail_row('OS', $os_label);
    if ($os_version) $rows .= detail_row('OS version', $os_version);
    if ($country) $rows .= detail_row('Country', $country);
    if ($browser_lang) $rows .= detail_row('Browser language', $browser_lang);

    $desc_escaped = nl2br(htmlspecialchars($description));

    return '<p style="font-size:15px;line-height:1.6;margin:0 0 24px;">New contact request from the website.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border:1px solid #eee;border-radius:6px;overflow:hidden;">
<tr><td style="background:#f9f9f9;padding:12px 16px;font-size:13px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #eee;">Details</td></tr>
<tr><td style="padding:0;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $rows . '</table>
</td></tr>
</table>

<div style="margin:0 0 8px;font-size:13px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:0.5px;">Description</div>
<div style="padding:16px;background:#f9f9f9;border-radius:6px;font-size:14px;line-height:1.6;color:#444;">' . $desc_escaped . '</div>';
}

function detail_row(string $label, string $value): string
{
    return '<tr>
<td style="padding:10px 16px;font-size:14px;color:#888;width:120px;border-bottom:1px solid #f0f0f0;">' . htmlspecialchars($label) . '</td>
<td style="padding:10px 16px;font-size:14px;color:#333;border-bottom:1px solid #f0f0f0;">' . $value . '</td>
</tr>';
}
