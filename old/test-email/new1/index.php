<?php
/**
 * new1 - Invio email di test con la funzione nativa mail() di PHP.
 *
 * Questo e' lo STESSO metodo attualmente usato da Notefox (funzioni sendEmail*
 * in include/api-functions.php): serve come baseline per confrontarlo con le
 * altre librerie/servizi.
 *
 * ATTENZIONE: nella pagina e nell'email non va indicato il metodo usato,
 * ma solo l'etichetta generica ("Test 1").
 */

include_once(__DIR__ . "/../_shared.php");

$label = "Test 1";
$description = "Send a test email to the address you enter below, using the first sending configuration. "
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

        $from = testEmailFromAddress();
        $subject = testEmailSubject($label);
        $body = testEmailHtmlBody($label, $recipient);

        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";

        $ok = @mail($recipient, $subject, $body, $headers);
        if ($ok) {
            $sent = true;
            $resultMsg = "The test email has been accepted for delivery to " . $recipient
                . " (sender: " . $from . "). Please note: this only means the message was accepted, "
                . "final delivery is not guaranteed.";
        } else {
            $sent = false;
            $resultMsg = "The message could not be accepted for delivery. Please try another test.";
        }
    }
}

renderTestEmailPage($label, $description, $sent, $resultMsg, $recipient);
