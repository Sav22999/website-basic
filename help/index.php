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

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Get help</h1>
            <h2 class="subtitle-section no-bold font-small">To get help, please contact me via Telegram or e-mail</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-telegram" value="Telegram"
                   onclick="location.href='https://t.me/sav_projects/7'">
            <input type="button" class="button button-with-icon button-email" value="Email"
                   onclick="location.href='mailto:saverio.morelli@protonmail.com'">
            <hr class="hr-big-space">
            <button type="button" class="help-faq-item" onclick="goto('./first-run/')">First run tutorial</button>
            <button type="button" class="help-faq-item" onclick="goto('./search/')">How the search feature works</button>
            <button type="button" class="help-faq-item" onclick="goto('./translate/')">How to translate Notefox</button>
            <button type="button" class="help-faq-item" onclick="goto('/sticky-notes/')">Simulate the sticky-notes feature</button>
            <button type="button" class="help-faq-item" onclick="goto('/privacy/')">Privacy policy</button>
            <button type="button" class="help-faq-item" onclick="goto('/terms/')">Terms of service</button>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>