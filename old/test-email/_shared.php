<?php
/**
 * Funzioni condivise dalle pagine di test dell'invio email (test-email/newX).
 *
 * Ogni sottocartella (new1, new2, ...) usa un metodo/servizio open-source diverso
 * per spedire la stessa email di prova, in modo da capire se i problemi di
 * consegna dipendono dal metodo di invio, dal provider di destinazione o altro.
 *
 * NOTA: nelle pagine e nelle email NON va mai indicato quale metodo/libreria
 * viene realmente usato: si mostra solo l'etichetta generica ("Test 1", ...).
 *
 * Le credenziali SMTP sono lette da include/credentials.php:
 *   $email_address, $email_password, $email_smtp, $email_smtp_port
 */

/**
 * Radice del sito (per includere i file di include/ anche fuori dal web server).
 */
function testEmailRootPath()
{
    if (isset($_SERVER['DOCUMENT_ROOT']) && is_file($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php")) {
        return $_SERVER['DOCUMENT_ROOT'];
    }
    return dirname(__DIR__);
}

// Carica le credenziali del progetto (stesso file usato dalle API).
$__credentials_path = testEmailRootPath() . "/include/credentials.php";
if (is_file($__credentials_path)) {
    include_once($__credentials_path);
}
global $email_address, $email_password, $email_smtp, $email_smtp_port;

/**
 * Carica l'autoload di Composer (vendor/autoload.php) per i test che usano
 * librerie esterne, senza mai interrompere la pagina.
 *
 * Composer genera un platform check che lancia un'eccezione se la versione di
 * PHP del server non soddisfa i requisiti delle dipendenze installate: in quel
 * caso (o se vendor/ manca) la pagina deve restare utilizzabile e mostrare solo
 * un messaggio di errore generico, senza dettagli tecnici o nomi di librerie.
 *
 * Ritorna null se l'autoload e' stato caricato, altrimenti il messaggio da
 * mostrare all'utente.
 */
function testEmailLoadVendorAutoload()
{
    $autoload = __DIR__ . "/vendor/autoload.php";
    if (!is_file($autoload)) {
        error_log("[test-email] vendor/autoload.php not found: run 'composer install' in test-email/");
        return "This test is not available at the moment. Please try another test.";
    }
    // Il platform check di Composer, quando fallisce, stampa direttamente il
    // proprio messaggio e imposta lo stato HTTP 500: si bufferizza l'output e si
    // ripristina lo stato, per non mostrare nulla di tutto questo all'utente.
    ob_start();
    try {
        require_once($autoload);
        ob_end_flush();
    } catch (\Throwable $e) {
        $output = ob_get_clean();
        error_log("[test-email] autoload failed: " . $e->getMessage()
            . ($output !== "" ? " | output: " . trim($output) : ""));
        if (!headers_sent()) {
            header("HTTP/1.1 200 OK", true, 200);
            http_response_code(200);
        }
        return "This test is not available at the moment. Please try another test.";
    }
    return null;
}

/**
 * Indirizzo mittente da usare per le email di test.
 * Usa l'indirizzo configurato in credentials.php, con fallback a no-reply@notefox.eu.
 */
function testEmailFromAddress()
{
    global $email_address;
    $from = isset($email_address) ? trim((string)$email_address) : "";
    if ($from === "" || strpos($from, "<EMAIL") !== false) {
        return "no-reply@notefox.eu";
    }
    return $from;
}

/**
 * Oggetto dell'email di test: contiene solo l'etichetta generica (es. "Test 1").
 */
function testEmailSubject($label)
{
    return "Testing email from notefox.eu (" . $label . ")";
}

/**
 * Legge il template HTML usato dalle email reali di Notefox
 * (lo stesso file usato da getEmailTemplate() in include/api-functions.php).
 */
function testEmailTemplate()
{
    return file_get_contents(__DIR__ . "/../include/email-template.php");
}

/**
 * Corpo HTML dell'email di test, costruito con LO STESSO template (UI/UX)
 * usato per le email reali di codice di verifica.
 * Indica l'etichetta generica del test (es. "Test 1"), non il metodo usato.
 */
function testEmailHtmlBody($label, $recipient = "")
{
    $section_1 = "Testing email from notefox.eu";
    $section_2 = "This is a test email sent from notefox.eu to check email delivery.<br>"
        . "Reference of this test: <strong>" . $label . "</strong>.";
    $section_3 = "If you received this email, this test can correctly deliver messages to your provider.<br>"
        . "Sent on " . date("Y-m-d H:i:s") . ".";

    $username = ($recipient !== "") ? $recipient : "there";

    $message = testEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    // Il blocco "code" viene usato per mostrare in evidenza l'etichetta del test.
    $message = str_replace("{{hidden-code}}", "", $message);
    $message = str_replace("{{code}}", $label, $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    // Nessun indirizzo IP da mostrare per l'email di test.
    $message = str_replace("{{hidden-ip-address}}", "hidden", $message);
    $message = str_replace("{{ip-address}}", "", $message);
    return $message;
}

/**
 * Corpo testo semplice dell'email di test (fallback per client senza HTML).
 */
function testEmailTextBody($label)
{
    return "Testing email from notefox.eu\n\n"
        . "This is a test email sent from notefox.eu to check email delivery.\n"
        . "Reference of this test: " . $label . ".\n\n"
        . "If you received this email, this test can correctly deliver "
        . "messages to your provider.\n\n"
        . "Sent on " . date("Y-m-d H:i:s") . "\n";
}

/**
 * Legge e valida l'indirizzo email inviato dal form (campo POST "email").
 * Ritorna l'indirizzo valido oppure null.
 */
function testEmailRequestedRecipient()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return null;
    }
    $email = isset($_POST["email"]) ? trim((string)$_POST["email"]) : "";
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return null;
    }
    return $email;
}

/**
 * Messaggio (in inglese, generico) usato quando l'indirizzo inserito non e' valido.
 */
function testEmailInvalidAddressMessage()
{
    return "Invalid email address.";
}

/**
 * Verifica che le credenziali di invio siano configurate (necessarie per alcuni test).
 * Ritorna null se tutto ok, oppure un messaggio di errore.
 */
function testEmailCheckSmtpCredentials()
{
    global $email_address, $email_password, $email_smtp, $email_smtp_port;
    $missing = array();
    if (empty($email_smtp) || strpos((string)$email_smtp, "<EMAIL") !== false) {
        $missing[] = 'server';
    }
    if (empty($email_smtp_port) || strpos((string)$email_smtp_port, "<EMAIL") !== false) {
        $missing[] = 'port';
    }
    if (empty($email_address) || strpos((string)$email_address, "<EMAIL") !== false) {
        $missing[] = 'address';
    }
    if (empty($email_password) || strpos((string)$email_password, "<EMAIL") !== false) {
        $missing[] = 'password';
    }
    if (!empty($missing)) {
        return "Sending is not configured on the server (missing: " . implode(", ", $missing) . ").";
    }
    return null;
}

/**
 * Stile comune alle pagine di test (il resto della grafica arriva da /css/style.css).
 */
function testEmailPageStyle()
{
    return "<style>\n"
        . ".test-email-form { margin: 30px 0px; }\n"
        . ".test-email-form label { display: block; font-size: var(--font-size-small); margin-bottom: 10px; }\n"
        . ".test-email-form input[type=email] { width: 100%; padding: 10px 20px; margin-bottom: 20px;\n"
        . "    border-radius: var(--border-radius); border: 0px solid transparent;\n"
        . "    background-color: var(--secondary-color-variant); color: var(--on-secondary-color);\n"
        . "    font-size: var(--font-size-normal); }\n"
        . ".test-email-result { border-radius: var(--border-radius); padding: 15px 20px; margin: 20px 0px;\n"
        . "    font-size: var(--font-size-small); text-align: left; word-break: break-word;\n"
        . "    background-color: var(--primary-color-transparence); }\n"
        . ".test-email-result.ko { background-color: var(--secondary-color-variant-transparence); }\n"
        . ".test-email-label { display: inline-block; border-radius: var(--border-radius);\n"
        . "    background-color: var(--primary-color-transparence); color: var(--on-primary-color);\n"
        . "    padding: 3px 15px; font-size: var(--font-size-very-small); }\n"
        . ".test-email-details { background-color: var(--secondary-color-variant); border-radius: var(--border-radius);\n"
        . "    padding: 15px 20px; margin: 20px 0px; text-align: left; }\n"
        . ".test-email-details pre { font-family: var(--font-family-mono); font-size: var(--font-size-very-very-small);\n"
        . "    white-space: pre-wrap; word-break: break-word; margin: 10px 0px 0px; }\n"
        . "a.button, a.button:visited, a.test-email-item, a.test-email-item:visited { text-decoration: none; }\n"
        . "</style>\n";
}

/**
 * Stampa l'intera pagina HTML del test: form con casella email + eventuale esito.
 * La grafica segue quella del resto del portale (header.php, menu.php, style.css).
 *
 * @param string $label Etichetta generica del test (es. "Test 1").
 * @param string $description Descrizione (generica, in inglese) del test.
 * @param bool|null $sent true se inviata, false se errore, null se nessun invio.
 * @param string $resultMsg Messaggio di esito da mostrare.
 * @param string $recipient Indirizzo usato (per ripopolare il campo).
 * @param string $extra HTML aggiuntivo (gia' pronto) da mostrare sotto il form.
 */
function renderTestEmailPage($label, $description, $sent, $resultMsg, $recipient, $extra = "")
{
    global $title, $selected_menu;

    $label_safe = htmlspecialchars($label, ENT_QUOTES, "UTF-8");
    $desc_safe = htmlspecialchars($description, ENT_QUOTES, "UTF-8");
    $recipient_safe = htmlspecialchars((string)$recipient, ENT_QUOTES, "UTF-8");
    $result_safe = htmlspecialchars((string)$resultMsg, ENT_QUOTES, "UTF-8");

    $root = testEmailRootPath();
    $title = $label_safe . " – Email tests – Notefox";
    $selected_menu = "";

    echo "<html>\n<head>\n";
    include_once($root . "/old/include/header.php");
    echo testEmailPageStyle();
    echo "</head>\n<body>\n";
    include_once($root . "/old/include/menu.php");
    echo "<main class=\"padding-top-menu\">\n";
    echo "<div class=\"horizontal-center\">\n";
    echo "<div class=\"center-content\">\n";
    echo "<h1 class=\"title-section center\">" . $label_safe . "</h1>\n";
    echo "<p class=\"center\"><span class=\"test-email-label\">Email delivery test</span></p>\n";
    echo "<p>" . $desc_safe . "</p>\n";

    if ($sent === true) {
        echo "<div class=\"test-email-result ok\">" . $result_safe . "</div>\n";
    } elseif ($sent === false) {
        echo "<div class=\"test-email-result ko\">" . $result_safe . "</div>\n";
    }

    echo "<form class=\"test-email-form\" method=\"post\" action=\"\">\n";
    echo "<label for=\"email\">Recipient email address</label>\n";
    echo "<input type=\"email\" id=\"email\" name=\"email\" placeholder=\"name@example.com\" value=\"" . $recipient_safe . "\" required>\n";
    echo "<div class=\"center\"><button type=\"submit\" class=\"button\">Send test email</button></div>\n";
    echo "</form>\n";

    if ($extra !== "") {
        echo $extra;
    }

    echo "<p class=\"center\"><a class=\"button button-secondary\" href=\"../\">All email tests</a></p>\n";
    echo "</div>\n";
    echo "</div>\n";
    echo "</main>\n";
    echo "</body>\n</html>\n";
}
