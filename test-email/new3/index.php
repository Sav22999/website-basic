<?php
/**
 * new3 - Invio email di test con Symfony Mailer (symfony/mailer) via SMTP.
 *
 * Libreria open-source: https://github.com/symfony/mailer
 * Usa le credenziali SMTP configurate in include/credentials.php:
 *   $email_address, $email_password, $email_smtp, $email_smtp_port
 *
 * ATTENZIONE: nella pagina e nell'email non va indicato il metodo usato,
 * ma solo l'etichetta generica ("Test 3").
 */

include_once(__DIR__ . "/../_shared.php");

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

// L'autoload di Composer puo' fallire (es. dipendenze installate per una
// versione di PHP diversa da quella del server): in quel caso la pagina resta
// utilizzabile e mostra solo un messaggio di errore generico.
$autoloadError = testEmailLoadVendorAutoload();

$label = "Test 3";
$description = "Send a test email to the address you enter below, using the third sending configuration. "
    . "Check whether the email arrives (also in the spam folder) and how long it takes.";

$sent = null;
$resultMsg = "";
$recipient = "";

$requested = testEmailRequestedRecipient();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($requested === null) {
        $sent = false;
        $resultMsg = testEmailInvalidAddressMessage();
        $recipient = isset($_POST["email"]) ? $_POST["email"] : "";
    } else {
        $recipient = $requested;
        $credError = testEmailCheckSmtpCredentials();
        if ($autoloadError !== null) {
            $sent = false;
            $resultMsg = $autoloadError;
        } elseif ($credError !== null) {
            $sent = false;
            $resultMsg = $credError;
        } else {
            global $email_address, $email_password, $email_smtp, $email_smtp_port;
            $from = testEmailFromAddress();
            $port = (int)$email_smtp_port;

            try {
                // DSN: smtp://user:pass@host:port
                // Symfony Mailer attiva automaticamente TLS implicito sulla porta 465
                // e STARTTLS sulle altre porte (es. 587).
                $dsn = "smtp://" . rawurlencode($email_address) . ":" . rawurlencode($email_password)
                    . "@" . $email_smtp . ":" . $port;

                $transport = Transport::fromDsn($dsn);
                $mailer = new Mailer($transport);

                $email = (new Email())
                    ->from(new Address($from, "Notefox"))
                    ->to($recipient)
                    ->subject(testEmailSubject($label))
                    ->text(testEmailTextBody($label))
                    ->html(testEmailHtmlBody($label, $recipient));

                $mailer->send($email);
                $sent = true;
                $resultMsg = "The test email has been sent to " . $recipient
                    . " (sender: " . $from . "). Please note: this only means the message was accepted, "
                    . "final delivery is not guaranteed.";
            } catch (\Throwable $e) {
                $sent = false;
                $resultMsg = "The test email could not be sent: " . $e->getMessage();
            }
        }
    }
}

renderTestEmailPage($label, $description, $sent, $resultMsg, $recipient);
