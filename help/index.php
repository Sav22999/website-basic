<html>
<head>
    <?php
    $title = "Get help – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <h1 class="title-section">Get help</h1>
            <h2 class="subtitle-section no-bold font-small">To get help, please contact me via Telegram or e-mail</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-telegram" value="Telegram"
                   onclick="location.href='https://t.me/sav_projects/7'">
            <input type="button" class="button button-with-icon button-email" value="Email"
                   onclick="location.href='mailto:saverio.morelli@protonmail.com'">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>