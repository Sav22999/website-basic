<?php
/**
 * Sav Account API v2 - email messages.
 *
 * The layout is the shared template include/email-template.php, which is only
 * read (never modified). Every function takes the recipient address as it was
 * resolved from the server side user record.
 */

function v2_email_template()
{
    static $template = null;
    if ($template !== null) {
        return $template;
    }

    $path = NOTEFOX_V2_ROOT . "/include/email-template.php";
    $content = @file_get_contents($path);
    if ($content === false) {
        error_log("[sav-account] email template not found: " . $path);
        $content = "<html><body><h1>{{section-1}}</h1><p>Hi {{username}},</p><p>{{section-2}}</p><p>{{code}}</p><p>{{section-3}}</p><p>{{ip-address}}</p></body></html>";
    }

    $template = $content;
    return $template;
}

/**
 * Fills the template. $code / $ip_address may be null: the corresponding block
 * is hidden exactly like the v1 emails do.
 */
function v2_email_render($username, $section_1, $section_2, $section_3, $code = null, $ip_address = null)
{
    $message = v2_email_template();
    $message = str_replace("{{username}}", htmlspecialchars((string)$username, ENT_QUOTES, "UTF-8"), $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", $code === null ? "hidden-small" : "", $message);
    $message = str_replace("{{code}}", $code === null ? "" : htmlspecialchars($code, ENT_QUOTES, "UTF-8"), $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", $ip_address === null ? "hidden" : "", $message);
    $message = str_replace("{{ip-address}}", $ip_address === null ? "" : htmlspecialchars($ip_address, ENT_QUOTES, "UTF-8"), $message);
    return $message;
}

function v2_email_signup_code($to, $username, $code, $ip_address, $expiry, $new_code = false)
{
    $html = v2_email_render(
        $username,
        $new_code ? "New code to verify your email" : "Verify your email",
        ($new_code ? "You required another verification code." : "Thank you for signing up to Notefox.") . " To confirm your account, please use the following code:",
        "The code will be valid until " . $expiry . ".<br>If you didn't sign up to Notefox, please ignore this email.",
        $code,
        $ip_address
    );
    return v2_send_email($to, "Notefox: verify your email", $html);
}

function v2_email_signed_up($to, $username, $ip_address)
{
    $html = v2_email_render(
        $username,
        "Account created",
        "You just created a Notefox account with this email.",
        "If you didn't sign up to Notefox, please contact support.",
        null,
        $ip_address
    );
    return v2_send_email($to, "Notefox: account created", $html);
}

function v2_email_login_code($to, $username, $code, $ip_address, $expiry, $new_code = false)
{
    $html = v2_email_render(
        $username,
        $new_code ? "New code to log in" : "Confirm your log in",
        ($new_code ? "You required another otp to verify the login process.<br>" : "") . "To confirm your login, please use the following code:",
        "The code will be valid until " . $expiry . ".<br>If you didn't log in to Notefox, you should definitely change your password.",
        $code,
        $ip_address
    );
    return v2_send_email($to, "Notefox: confirm your login", $html);
}

function v2_email_logged_in($to, $username, $ip_address)
{
    $html = v2_email_render(
        $username,
        "Just logged in",
        "You just logged in to your Notefox account.",
        "If you haven't logged in to Notefox, please change your password immediately.",
        null,
        $ip_address
    );
    return v2_send_email($to, "Notefox: just logged in", $html);
}

function v2_email_password_change_code($to, $username, $code, $ip_address, $expiry, $new_code = false)
{
    $html = v2_email_render(
        $username,
        $new_code ? "New code to change your password" : "Confirm your new password",
        ($new_code ? "You required another code to confirm the change of your password.<br>" : "") . "To confirm you want to change the password of your Notefox account, please use the following code:",
        "The code will be valid until " . $expiry . ".<br>If you didn't ask for changing your password, please ignore this email: nothing has been changed yet.",
        $code,
        $ip_address
    );
    return v2_send_email($to, "Notefox: confirm your new password", $html);
}

function v2_email_password_changed($to, $username, $ip_address)
{
    $html = v2_email_render(
        $username,
        "Password changed",
        "The password of your Notefox account has just been changed. All the other sessions have been signed out.",
        "If you didn't change your password, please contact support immediately.",
        null,
        $ip_address
    );
    return v2_send_email($to, "Notefox: password changed", $html);
}

function v2_email_otp_disable_code($to, $username, $code, $ip_address, $expiry)
{
    $html = v2_email_render(
        $username,
        "Confirm disabling the login code",
        "You asked to disable the login verification code (two-factor authentication) of your Notefox account. To confirm, please use the following code:",
        "The code will be valid until " . $expiry . ".<br>If you didn't ask for this, ignore this email and change your password: someone may know it.",
        $code,
        $ip_address
    );
    return v2_send_email($to, "Notefox: confirm disabling the login code", $html);
}

function v2_email_otp_changed($to, $username, $enabled, $ip_address)
{
    $html = v2_email_render(
        $username,
        $enabled ? "Login code enabled" : "Login code disabled",
        $enabled
            ? "The login verification code (two-factor authentication) is now enabled on your Notefox account: a code will be emailed to you at every login."
            : "The login verification code (two-factor authentication) is now disabled on your Notefox account: from now on your password alone is enough to log in.",
        "If you didn't ask for this change, please change your password immediately.",
        null,
        $ip_address
    );
    return v2_send_email($to, $enabled ? "Notefox: login code enabled" : "Notefox: login code disabled", $html);
}

function v2_email_delete_code($to, $username, $code, $ip_address, $expiry, $new_code = false)
{
    $html = v2_email_render(
        $username,
        $new_code ? "New code to delete account" : "Confirm deleting account",
        ($new_code ? "You required another otp to confirm the deleting of your Notefox Account.<br>" : "") . "To confirm you want to delete permanently your account, please use the following deleting code:",
        "The code will be valid until " . $expiry . ".<br>If you didn't ask for deleting your Notefox account, please change your password immediately.<br>Once deleted the account, all data will be definitely deleted from database and you'll lose data forever.<br><br>If you asked for deleting your account, but you changed your mind, please ignore this email.",
        $code,
        $ip_address
    );
    return v2_send_email($to, "Notefox: confirm deleting account", $html);
}

function v2_email_deleted($to, $username)
{
    $html = v2_email_render(
        $username,
        "Account permanently deleted",
        "Your Notefox account is now deleted permanently, together to all your data.<br>I'm really sorry about your decision to leave Notefox.",
        "If you would like creating a new one, you can also reuse this email address.",
        null,
        null
    );
    return v2_send_email($to, "Notefox: account deleted", $html);
}
?>
