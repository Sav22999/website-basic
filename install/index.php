<html>
<head>
    <?php
    $title = "Install – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "install";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <h1 class="title-section">Install</h1>
            <h2 class="subtitle-section no-bold font-small">Select your web browser</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-mozillafirefox" value="Mozilla Firefox"
                   onclick="location.href='https://addons.mozilla.org/firefox/addon/websites-notes/'">
            <input type="button" class="button button-with-icon button-googlechrome" value="Google Chrome"
                   onclick="location.href='https://chromewebstore.google.com/detail/agcdffobijddcccbfnhfjmaohnljefpm'">
            <input type="button" class="button button-with-icon button-microsoftedge" value="Microsoft Edge"
                   onclick="location.href='https://microsoftedge.microsoft.com/addons/detail/lkahmkadpaibphpoiofpdinacjffddda'">
            <br>
            <h2 class="subtitle-section no-bold font-very-small">you can also install the app from:</h2>
            <br>
            <br><input type="button" class="button button-with-icon-secondary button-secondary button-github"
                       value="GitHub" onclick="location.href='https://github.com/Sav22999/websites-notes'">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>