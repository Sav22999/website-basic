<?php
/**
 * Sav Account API v2 - email transport (Symfony Mailer over SMTP).
 *
 * v1 uses mail(), which on most shared hostings is either unauthenticated or
 * silently dropped. v2 uses the same configuration as test-email/new3:
 * $email_address / $email_password / $email_smtp / $email_smtp_port.
 *
 * The Composer autoload may legitimately be missing (dependencies installed
 * for another PHP version, vendor not uploaded...): in that case nothing
 * fatals, the failure is only logged and /status reports the mailer as
 * unavailable.
 */

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Loads the Composer autoload, looking at the v2 vendor first and then at the
 * one of test-email/ (same dependency, already installed there).
 * Returns null on success, an error message otherwise.
 */
function v2_mailer_autoload()
{
    static $result = "not-loaded";
    if ($result !== "not-loaded") {
        return $result;
    }

    if (class_exists("Symfony\\Component\\Mailer\\Mailer")) {
        $result = null;
        return $result;
    }

    $candidates = array(
        dirname(__DIR__) . "/vendor/autoload.php",
        NOTEFOX_V2_ROOT . "/test-email/vendor/autoload.php",
        NOTEFOX_V2_ROOT . "/vendor/autoload.php",
    );

    foreach ($candidates as $candidate) {
        if (!file_exists($candidate)) {
            continue;
        }
        try {
            include_once($candidate);
        } catch (Throwable $e) {
            error_log("[sav-account] mailer autoload failed: " . $e->getMessage());
            continue;
        }
        if (class_exists("Symfony\\Component\\Mailer\\Mailer")) {
            $result = null;
            return $result;
        }
    }

    $result = "Mailer dependencies are not available";
    error_log("[sav-account] " . $result);
    return $result;
}

function v2_mailer_available()
{
    return v2_mailer_autoload() === null && v2_mailer_configured();
}

function v2_mailer_configured()
{
    global $email_address, $email_password, $email_smtp, $email_smtp_port;
    return isset($email_address, $email_password, $email_smtp, $email_smtp_port)
        && is_string($email_address) && $email_address !== ""
        && is_string($email_password) && $email_password !== ""
        && is_string($email_smtp) && $email_smtp !== ""
        && (int)$email_smtp_port > 0;
}

/**
 * Sends an HTML email. Never throws, never prints: returns true/false and logs
 * the reason. $to must always come from the user record on the server, never
 * from the request payload (this is the fix for the open redirect of the
 * confirmation email in api/v1/login/verify/index.php).
 */
function v2_send_email($to, $subject, $html, $text = null)
{
    global $email_address, $email_password, $email_smtp, $email_smtp_port;

    if (!is_string($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        error_log("[sav-account] refusing to send an email to an invalid address");
        return false;
    }

    $autoload_error = v2_mailer_autoload();
    if ($autoload_error !== null) {
        return false;
    }
    if (!v2_mailer_configured()) {
        error_log("[sav-account] SMTP credentials are not configured");
        return false;
    }

    try {
        // smtp://user:pass@host:port - implicit TLS on 465, STARTTLS otherwise.
        $dsn = "smtp://" . rawurlencode($email_address) . ":" . rawurlencode($email_password)
            . "@" . $email_smtp . ":" . ((int)$email_smtp_port);

        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);

        $message = (new Email())
            ->from(new Address($email_address, "Notefox"))
            ->to($to)
            ->subject($subject)
            ->html($html);

        if ($text !== null && $text !== "") {
            $message->text($text);
        } else {
            $message->text(trim(html_entity_decode(strip_tags(str_replace(array("<br>", "<br/>", "<br />"), "\n", $html)), ENT_QUOTES, "UTF-8")));
        }

        $mailer->send($message);
        return true;
    } catch (Throwable $e) {
        error_log("[sav-account] email to " . $to . " failed: " . $e->getMessage());
        return false;
    }
}

?>
