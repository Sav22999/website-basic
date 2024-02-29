<html>
<head>
    <?php
    $title = "Buy me a coffee – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "donate";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <h1 class="title-section">Buy me a coffee</h1>
            <h2 class="subtitle-section no-bold font-small">If you like the app, consider to buy me a coffee</h2>
            <h2 class="subtitle-section no-bold font-small">I advise you to use LiberaPay which is <b>safe</b> and <b>free
                    of fees</b>, but PayPal is great as well</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-liberapay" value="LiberaPay"
                   onclick="location.href='https://liberapay.com/Sav22999/'">
            <input type="button" class="button button-with-icon button-paypal" value="PayPal"
                   onclick="location.href='https://www.paypal.me/saveriomorelli'">
            <br>
            <br class="big-space">
            <h2 class="subtitle-section no-bold font-very-small">and remember to look for my other projects</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon-secondary button-secondary button-emoji"
                   value="Emoji" onclick="location.href='https://emojiaddon.com'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-cvproject"
                   value="CV Project" onclick="location.href='https://www.saveriomorelli.com/commonvoice/'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-accentedletters"
                   value="Accented Letters"
                   onclick="location.href='https://addons.mozilla.org/it/firefox/addon/accented-letters/'">
            <br>
            <input type="button" class="button button-with-icon-secondary button-secondary button-savpdfviewer"
                   value="Sav PDF Viewer" onclick="location.href='https://savpdfviewer.com'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-limite"
                   value="Limite" onclick="location.href='https://addons.mozilla.org/it/firefox/addon/limite/'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-wordoftheday"
                   value="Word of the Day"
                   onclick="location.href='https://play.google.com/store/apps/details?id=com.saverio.wordoftheday_en'">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>