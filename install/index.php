<html>
<head>
    <?php
    $title = "Install Sav PDF Viewer";
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
            <h2 class="subtitle-section no-bold font-small">Select your favourite Android app store</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon button-googleplayappstore" value="Google Play"
                   onclick="location.href='https://play.google.com/store/apps/details?id=com.saverio.pdfviewer'">
            <input type="button" class="button button-with-icon button-fdroid" value="F-Droid"
                   onclick="location.href='https://f-droid.org/it/packages/com.saverio.pdfviewer/'">
            <br>
            <h2 class="subtitle-section no-bold font-very-small">you can install the app also via GitHub</h2>
            <br>
            <br><input type="button" class="button button-with-icon-secondary button-secondary button-github"
                       value="GitHub" onclick="location.href='https://github.com/Sav22999/sav-pdf-viewer-pro'">
            <br>
            <br class="big-space">
            <h2 class="subtitle-section no-bold font-very-small">other app stores which could preset an obsolete app
                version</h2>
            <br>
            <br>
            <input type="button" class="button button-with-icon-secondary button-secondary button-huaweiappgallery"
                   value="Huawei AppGallery" onclick="location.href='https://appgallery.huawei.com/#/app/C104418743'">
            <input type="button" class="button button-with-icon-secondary button-secondary button-amazonappstore"
                   value="Amazon AppStore" onclick="location.href='https://www.amazon.com/gp/product/B0974TV679'">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>