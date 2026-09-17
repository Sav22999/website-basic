<?php
/**
 * new4 - Invio email di test con un client SMTP nativo (socket puri, fsockopen).
 *
 * NON usa librerie esterne: implementa direttamente il dialogo SMTP
 * (EHLO / STARTTLS / AUTH LOGIN / MAIL FROM / RCPT TO / DATA).
 * Utile per isolare i problemi di rete/porta/TLS dal codice delle librerie.
 *
 * Usa le credenziali SMTP configurate in include/credentials.php:
 *   $email_address, $email_password, $email_smtp, $email_smtp_port
 *
 * ATTENZIONE: nella pagina e nell'email non va indicato il metodo usato,
 * ma solo l'etichetta generica ("Test 4"). Il transcript del dialogo SMTP
 * NON viene mostrato pubblicamente: viene solo scritto nel log PHP.
 */

include_once(__DIR__ . "/../_shared.php");

$label = "Test 4";
$description = "Send a test email to the address you enter below, using the fourth sending configuration. "
    . "Check whether the email arrives (also in the spam folder) and how long it takes.";

/**
 * Legge una risposta SMTP (gestisce le risposte multi-linea).
 */
function smtpRead($fp, &$transcript)
{
    $data = "";
    while (($line = fgets($fp, 515)) !== false) {
        $data .= $line;
        $transcript .= "S: " . rtrim($line) . "\n";
        // La riga finale ha il formato "NNN " (spazio dopo il codice).
        if (isset($line[3]) && $line[3] === " ") {
            break;
        }
    }
    return $data;
}

/**
 * Invia un comando SMTP.
 */
function smtpWrite($fp, $cmd, &$transcript, $hide = false)
{
    $transcript .= "C: " . ($hide ? "********" : rtrim($cmd)) . "\n";
    fwrite($fp, $cmd . "\r\n");
}

/**
 * Verifica che il codice di risposta SMTP inizi con una delle cifre attese.
 */
function smtpExpect($response, $expectedPrefix)
{
    return substr(trim($response), 0, strlen($expectedPrefix)) === $expectedPrefix;
}

$sent = null;
$resultMsg = "";
$recipient = "";
$transcript = "";

$requested = testEmailRequestedRecipient();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($requested === null) {
        $sent = false;
        $resultMsg = testEmailInvalidAddressMessage();
        $recipient = isset($_POST["email"]) ? $_POST["email"] : "";
    } else {
        $recipient = $requested;
        $credError = testEmailCheckSmtpCredentials();
        if ($credError !== null) {
            $sent = false;
            $resultMsg = $credError;
        } else {
            global $email_address, $email_password, $email_smtp, $email_smtp_port;
            $from = testEmailFromAddress();
            $port = (int)$email_smtp_port;
            $useImplicitTls = ($port === 465); // 465 = SSL implicito, altrimenti STARTTLS
            $host = $useImplicitTls ? ("ssl://" . $email_smtp) : $email_smtp;
            $hostname = gethostname() ?: "notefox.eu";

            $errno = 0;
            $errstr = "";
            $fp = @fsockopen($host, $port, $errno, $errstr, 15);
            if (!$fp) {
                $sent = false;
                $resultMsg = "The test email could not be sent: connection failed (" . $errno . " " . $errstr . ").";
            } else {
                stream_set_timeout($fp, 15);
                try {
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "220")) {
                        throw new RuntimeException("unexpected server greeting.");
                    }

                    smtpWrite($fp, "EHLO " . $hostname, $transcript);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "250")) {
                        throw new RuntimeException("handshake refused.");
                    }

                    // STARTTLS quando non si usa SSL implicito (tipicamente porta 587).
                    if (!$useImplicitTls) {
                        smtpWrite($fp, "STARTTLS", $transcript);
                        $r = smtpRead($fp, $transcript);
                        if (!smtpExpect($r, "220")) {
                            throw new RuntimeException("secure connection not supported.");
                        }
                        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                            throw new RuntimeException("secure connection negotiation failed.");
                        }
                        // Dopo STARTTLS bisogna ripetere EHLO.
                        smtpWrite($fp, "EHLO " . $hostname, $transcript);
                        $r = smtpRead($fp, $transcript);
                        if (!smtpExpect($r, "250")) {
                            throw new RuntimeException("handshake refused after securing the connection.");
                        }
                    }

                    // Autenticazione AUTH LOGIN.
                    smtpWrite($fp, "AUTH LOGIN", $transcript);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "334")) {
                        throw new RuntimeException("authentication not accepted.");
                    }
                    smtpWrite($fp, base64_encode($email_address), $transcript, true);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "334")) {
                        throw new RuntimeException("authentication refused.");
                    }
                    smtpWrite($fp, base64_encode($email_password), $transcript, true);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "235")) {
                        throw new RuntimeException("authentication failed.");
                    }

                    // Busta SMTP.
                    smtpWrite($fp, "MAIL FROM:<" . $from . ">", $transcript);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "250")) {
                        throw new RuntimeException("sender address refused.");
                    }
                    smtpWrite($fp, "RCPT TO:<" . $recipient . ">", $transcript);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "250") && !smtpExpect($r, "251")) {
                        throw new RuntimeException("recipient address refused.");
                    }

                    // Corpo del messaggio.
                    smtpWrite($fp, "DATA", $transcript);
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "354")) {
                        throw new RuntimeException("message transfer not accepted.");
                    }

                    $subject = testEmailSubject($label);
                    $body = testEmailHtmlBody($label, $recipient);
                    $headers = "From: Notefox <" . $from . ">\r\n";
                    $headers .= "To: <" . $recipient . ">\r\n";
                    $headers .= "Subject: " . $subject . "\r\n";
                    $headers .= "Date: " . date("r") . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                    // Dot-stuffing: le righe che iniziano con "." vanno raddoppiate.
                    $dataBody = preg_replace('/^\./m', '..', $body);
                    fwrite($fp, $headers . "\r\n" . $dataBody . "\r\n.\r\n");
                    $transcript .= "C: [message body]\n";
                    $r = smtpRead($fp, $transcript);
                    if (!smtpExpect($r, "250")) {
                        throw new RuntimeException("the message was not accepted.");
                    }

                    smtpWrite($fp, "QUIT", $transcript);
                    smtpRead($fp, $transcript);

                    $sent = true;
                    $resultMsg = "The test email has been sent to " . $recipient
                        . " (sender: " . $from . "). Please note: this only means the message was accepted, "
                        . "final delivery is not guaranteed.";
                } catch (\Throwable $e) {
                    $sent = false;
                    $resultMsg = "The test email could not be sent: " . $e->getMessage();
                }
                fclose($fp);
            }
        }
    }
}

// Il transcript del dialogo SMTP non viene mostrato in pagina (contiene dettagli
// del server): viene registrato nel log PHP per la diagnostica.
if ($transcript !== "") {
    error_log("[test-email/new4] SMTP transcript:\n" . $transcript);
}

renderTestEmailPage($label, $description, $sent, $resultMsg, $recipient);
