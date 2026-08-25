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
            <div class="center">
                <input type="button" class="button button-with-icon button-telegram" value="Telegram"
                       onclick="location.href='https://t.me/sav_projects/7'">
                <input type="button" class="button button-with-icon button-email" value="Email"
                       onclick="location.href='mailto:saverio.morelli@protonmail.com'">
            </div>
            <hr class="hr-big-space">
            <button type="button" class="help-faq-item" onclick="goto('./download-error-logs')">How to download the Error logs file</button>
            <button type="button" class="help-faq-item" onclick="goto('./delete-error-logs')">How to delete the Error logs file</button>
            <button type="button" class="help-faq-item" onclick="goto('./get-data-for-debugging')">How to get data for debugging</button>
            <button type="button" class="help-faq-item" onclick="goto('./open-console')">How to open the Console panel</button>
            <button type="button" class="help-faq-item" onclick="goto('./notefox-4.0/')">Notefox 4.0 overview</button>
            <button type="button" class="help-faq-item" onclick="goto('./import-export-data/')">How to import and export data</button>
            <button type="button" class="help-faq-item" onclick="goto('./local-data-storage/')">Local data storage</button>
            <button type="button" class="help-faq-item" onclick="goto('./inline-edit/')">How to edit inline a note</button>
            <button type="button" class="help-faq-item" onclick="goto('./search/')">How the search feature works
            </button>
            <button type="button" class="help-faq-item" onclick="goto('./translate/')">How to translate Notefox</button>
            <button type="button" class="help-faq-item" onclick="goto('./own-server-for-notefox-sync/')">How to run your own Notefox sync server</button>
            <button type="button" class="help-faq-item" onclick="goto('/sticky-notes/')">Simulate the sticky-notes
                feature
            </button>
            <button type="button" class="help-faq-item" onclick="goto('./notefox-account/')">How the Notefox Account works</button>
            <button type="button" class="help-faq-item" onclick="goto('/privacy/')">Privacy policy</button>
            <button type="button" class="help-faq-item" onclick="goto('/terms/')">Terms of service</button>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>
