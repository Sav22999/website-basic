<?php
/**
 * Pagina indice dei test di invio email di notefox.eu.
 *
 * Ogni test (new1, new2, ...) invia la stessa email di prova usando un
 * metodo/servizio open-source diverso, per capire se i problemi di consegna
 * dipendono dal metodo di invio, dal provider di destinazione o altro.
 *
 * ATTENZIONE: qui NON va indicato quale metodo/libreria viene usato da ogni
 * test: si mostrano solo le etichette generiche ("Test 1", "Test 2", ...).
 */

include_once(__DIR__ . "/_shared.php");

$tests = array(
        array("dir" => "new1", "label" => "Test 1"),
        array("dir" => "new2", "label" => "Test 2"),
        array("dir" => "new3", "label" => "Test 3"),
        array("dir" => "new4", "label" => "Test 4"),
);

$root = testEmailRootPath();
$title = "Email tests – Notefox";
$selected_menu = "";
?>
<html>
<head>
    <?php
    include_once($root . "/old/include/header.php");
    echo testEmailPageStyle();
    ?>
    <style>
        .test-email-list {
            margin: 30px 0px;
        }

        .test-email-item {
            display: block;
            text-decoration: none;
            text-align: left;
            background-color: var(--primary-color-transparence-2);
            border-radius: var(--border-radius);
            padding: 15px 25px;
            margin-bottom: 15px;
            transition: var(--transition);
        }

        .test-email-item:hover {
            background-color: var(--primary-color-transparence);
            text-decoration: none;
        }

        .test-email-item .name {
            font-size: var(--font-size-big);
        }

        .test-email-item .hint {
            font-size: var(--font-size-very-small);
            color: var(--on-secondary-color);
        }
    </style>
</head>
<body>
<?php include_once($root . "/old/include/menu.php"); ?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Email tests</h1>
            <p>
                Each test sends the same sample email (&laquo;Testing email from notefox.eu&raquo;) to the address
                you enter, using a different sending configuration.
            </p>
            <p>
                By comparing which emails arrive and which ones don't, it is possible to understand whether the
                problem depends on the sending configuration, on the destination provider or on something else.
                Please check the spam folder too, and note how long each email takes to arrive.
            </p>
            <div class="test-email-list">
                <?php foreach ($tests as $t): ?>
                    <a class="test-email-item" href="<?php echo htmlspecialchars($t["dir"], ENT_QUOTES, "UTF-8"); ?>/">
                        <span class="name"><?php echo htmlspecialchars($t["label"], ENT_QUOTES, "UTF-8"); ?></span><br>
                        <span class="hint">Send a test email with this configuration</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

</body>
</html>
