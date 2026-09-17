<?php
/**
 * new2 - Invio email di test con PHPMailer (phpmailer/phpmailer) via SMTP.
 *
 * Libreria open-source: https://github.com/PHPMailer/PHPMailer
 * Usa le credenziali SMTP configurate in include/credentials.php:
 *   $email_address, $email_password, $email_smtp, $email_smtp_port
 *
 * ATTENZIONE: nella pagina e nell'email non va indicato il metodo usato,
 * ma solo l'etichetta generica ("Test 2").
 */

include_once(__DIR__ . "/../_shared.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// L'autoload di Composer puo' fallire (es. dipendenze installate per una
// versione di PHP diversa da quella del server): in quel caso la pagina resta
// utilizzabile e mostra solo un messaggio di errore generico.
$autoloadError = testEmailLoadVendorAutoload();

$label = "Test 2";
$description = "Send a test email to the address you enter below, using the second sending configuration. "
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

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $email_smtp;
                $mail->SMTPAuth = true;
                $mail->Username = $email_address;
                $mail->Password = $email_password;
                // Porta 465 -> SMTPS (SSL implicito); altrimenti STARTTLS.
                if ($port === 465) {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                } else {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                }
                $mail->Port = $port;
                $mail->CharSet = "UTF-8";

                $mail->setFrom($from, "Notefox");
                $mail->addAddress($recipient);
                $mail->isHTML(true);
                $mail->Subject = testEmailSubject($label);
                $mail->Body = testEmailHtmlBody($label, $recipient);
                $mail->AltBody = testEmailTextBody($label);

                $mail->send();
                $sent = true;
                $resultMsg = "The test email has been sent to " . $recipient
                    . " (sender: " . $from . "). Please note: this only means the message was accepted, "
                    . "final delivery is not guaranteed.";
            } catch (Exception $e) {
                $sent = false;
                $resultMsg = "The test email could not be sent: " . $mail->ErrorInfo;
            }
        }
    }
}

renderTestEmailPage($label, $description, $sent, $resultMsg, $recipient);
