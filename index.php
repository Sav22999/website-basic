<html>
<head>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php"); ?>
</head>
<body>
<?php
$selected_menu = "home";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <img src="/images/icon.png" class="image-square-100px">
            <h1 class="title-section">Sav PDF Viewer</h1>
            <h2 class="subtitle-section no-bold font-small">Android app to read your PDFs.<br><i>Your privacy is
                    important. <b>You are important</b>.</i></h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-install" value="Install"
                   onclick="location.href='/install/'">
            <br>
            <br>
            <input type="button" class="button button-with-icon-secondary button-secondary button-liberapay"
                   value="LiberaPay" onclick="location.href='https://liberapay.com/Sav22999/donate'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-paypal"
                   value="PayPal" onclick="location.href='https://paypal.me/saveriomorelli'">
            <br>
            <br>
            <input type="button" class="button button-with-icon-secondary button-secondary button-github"
                   value="GitHub" onclick="location.href='https://github.com/Sav22999/sav-pdf-viewer-pro'">
        </div>
    </div>
</main>

<footer class="font-color-tertiary-color-variant horizontal-center font-very-small">
    Developed with
    <div class="image-heart image-background-primary image-square-20px"></div>
    by <a href="https://saveriomorelli.com">Saverio Morelli</a>
</footer>

</body>
</html>

<?php
?>