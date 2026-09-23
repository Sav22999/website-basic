<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Install";
    $description = "Download Sav PDF Viewer for Android from Google Play or GitHub. A private, open-source PDF reader with no ads and no tracking.";
    $canonical_path = "/alpha/install/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "install";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

<main>
    <section class="hero hero--compact">
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
                <a href="https://play.google.com/store/apps/details?id=com.saverio.pdfviewer"
                   class="button button-with-icon button-lg" target="_blank" rel="noopener noreferrer">
                    <span class="button__icon button-icon-googleplayappstore"></span>Google Play
                </a>
            </div>

            <div class="stats-banner">
                <span data-i18n="install.stats_number">100,000+</span> <span data-i18n="install.stats_text">people already use it!</span>
            </div>
        </div>
    </section>

    <section class="install-features">
        <div class="install-features__grid">
            <div class="install-feature-card">
                <svg class="install-feature-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <h3 data-i18n="install.feature_privacy_title">Private by design</h3>
                <p data-i18n="install.feature_privacy_desc">No data collected, no tracking, no analytics. Your documents
                    stay on your device.</p>
            </div>
            <div class="install-feature-card">
                <svg class="install-feature-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                </svg>
                <h3 data-i18n="install.feature_viewer_title">A viewer, done right</h3>
                <p data-i18n="install.feature_viewer_desc">Search, select text, bookmarks, night mode,
                    password-protected PDFs — everything you need.</p>
            </div>
            <div class="install-feature-card">
                <svg class="install-feature-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 1v4M12 19v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M1 12h4M19 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                <h3 data-i18n="install.feature_independent_title">Fully independent</h3>
                <p data-i18n="install.feature_independent_desc">No Google services, no third-party dependencies. Open
                    source under GPL-3.0.</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <span data-i18n="footer.developed">Developed with</span>
    <span class="image-heart image-background-primary image-square-20px"></span>
    <span data-i18n="footer.by">by</span> <a href="https://saveriomorelli.com" class="author-name" target="_blank"
                                             rel="noopener noreferrer">Saverio Morelli</a>
    <div class="footer-links">
        <a href="/alpha/privacy/" data-i18n="footer.privacy_policy">Privacy policy</a>
        <span class="footer-sep">·</span>
        <a href="/alpha/terms/" data-i18n="footer.terms">Terms of service</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

</body>
</html>
