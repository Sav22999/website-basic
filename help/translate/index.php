<html>
<head>
    <?php
    $title = "Get help: translate – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-translate";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to translate Notefox</h1>
            <p>
                Notefox is available in multiple languages, and you can help to translate it into your language. To
                translate Notefox you must use the Crowdin platform, which is a collaborative translation tool.
            </p>
            <p>
                If the language you want to translate Notefox into is not available on Crowdin, please contact me via
                Telegram or e-mail.
            </p>
            <br>
            <input type="button" class="button" value="Translate on Crowdin"
                   onclick="goto('https://crowdin.com/project/notefox')">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>