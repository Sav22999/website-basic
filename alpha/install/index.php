<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Install Sav PDF Viewer";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "install";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

<main>
    <section class="hero">
        <div class="hero__inner">
            <img src="/images/icon.png" class="hero-icon" alt="Sav PDF Viewer">
            <h1 class="title-section" data-i18n="install.title">Install</h1>
            <p class="subtitle-section no-bold platform-hide-ios"
               data-i18n="install.subtitle"
               data-platform-android="Get it now from your favourite store"
               data-platform-desktop="Install Sav PDF Viewer on your Android device"
               data-platform-ios="">Get it now from your favourite store</p>
            <p class="subtitle-section no-bold platform-ios-only" style="display:none;"
               data-i18n-html="install.ios_notice">
                Sav PDF Viewer is not available on iOS.<br>
                Install it on your Android device from one of these stores.
            </p>

            <div class="button-group">
                <a href="https://play.google.com/store/apps/details?id=com.saverio.pdfviewer" class="button button-with-icon button-lg">
                    <span class="button__icon button-icon-googleplayappstore"></span>Google Play
                </a>
            </div>

            <div class="stats-banner">
                <span data-i18n="install.stats_number">100,000+</span> <span data-i18n="install.stats_text">people already use it!</span>
            </div>
            <p class="stats-note" data-i18n="install.stats_note">across Google Play, Amazon AppStore, Huawei AppGallery, and GitHub</p>
        </div>
    </section>

    <section class="install-alt">
        <p class="install-alt__label" data-i18n="install.alt_label">Also available on</p>
        <div class="button-group">
            <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" class="button button-secondary button-with-icon-secondary">
                <span class="button__icon button-icon-github"></span>GitHub
            </a>
            <a href="https://appgallery.huawei.com/#/app/C104418743" class="button button-secondary button-with-icon-secondary">
                <span class="button__icon button-icon-huaweiappgallery"></span>Huawei AppGallery
            </a>
            <a href="https://www.amazon.com/gp/product/B0974TV679" class="button button-secondary button-with-icon-secondary">
                <span class="button__icon button-icon-amazonappstore"></span>Amazon AppStore
            </a>
        </div>
    </section>
</main>

<footer>
    <span data-i18n="footer.developed">Developed with</span>
    <span class="image-heart image-background-primary image-square-20px"></span>
    <span data-i18n="footer.by">by</span> <a href="https://saveriomorelli.com" class="author-name">Saverio Morelli</a>
</footer>

</body>
</html>
