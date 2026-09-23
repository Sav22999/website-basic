<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Donate";
    $description = "Support Sav PDF Viewer development. Donate via LiberaPay (no fees) or PayPal to keep the project alive, independent, and ad-free.";
    $canonical_path = "/alpha/donate/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "donate";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

<main>
    <section class="hero hero--compact">
        <div class="hero__inner">
            <div class="donate-section">
                <div class="donate-heart">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <h1 class="title-section" data-i18n="donate.title">Support the project</h1>
                <p class="subtitle-section no-bold" data-i18n-html="donate.subtitle">Sav PDF Viewer is built and
                    maintained by a single developer.<br>No
                    company, no investors — just one person and a lot of passion.</p>

                <p class="donate-description" data-i18n="donate.desc">Your donation keeps this project alive,
                    independent, and free of ads. Every
                    contribution — big or small — makes a real difference.</p>

                <div class="donate-options">
                    <a href="https://liberapay.com/Sav22999/" class="donate-card donate-card--recommended"
                       target="_blank" rel="noopener noreferrer">
                        <span class="donate-card__badge" data-i18n="donate.recommended">Recommended</span>
                        <span class="donate-card__icon button-icon-liberapay"></span>
                        <span class="donate-card__name">LiberaPay</span>
                        <span class="donate-card__detail" data-i18n="donate.liberapay_detail">Open source, no fees — 100% goes to the developer</span>
                    </a>
                    <a href="https://www.paypal.me/saveriomorelli" class="donate-card" target="_blank"
                       rel="noopener noreferrer">
                        <span class="donate-card__icon button-icon-paypal"></span>
                        <span class="donate-card__name">PayPal</span>
                        <span class="donate-card__detail" data-i18n="donate.paypal_detail">Fast and easy</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="donate-other-ways">
        <h2 class="title-section" data-i18n="donate.other_ways_title">Other ways to help</h2>
        <div class="donate-ways-grid">
            <a href="https://play.google.com/store/apps/details?id=com.saverio.pdfviewer" class="donate-way"
               target="_blank" rel="noopener noreferrer">
                <svg class="donate-way__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <h3 data-i18n="donate.way_review_title">Leave a review</h3>
                <p data-i18n="donate.way_review_desc">Rate the app on Google Play to help others find it</p>
            </a>
            <a href="https://crowdin.com/project/sav-pdf-viewer" class="donate-way" target="_blank"
               rel="noopener noreferrer">
                <svg class="donate-way__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                </svg>
                <h3 data-i18n="donate.way_translate_title">Translate</h3>
                <p data-i18n="donate.way_translate_desc">Help translate the app into your language on Crowdin</p>
            </a>
            <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" class="donate-way" target="_blank"
               rel="noopener noreferrer">
                <svg class="donate-way__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/>
                </svg>
                <h3 data-i18n="donate.way_contribute_title">Contribute</h3>
                <p data-i18n="donate.way_contribute_desc">Report bugs or contribute code on GitHub</p>
            </a>
        </div>
    </section>

    <hr class="section-divider">

    <section class="donate-projects">
        <h2 class="title-section" data-i18n="donate.other_projects">Discover my other projects</h2>
        <div class="projects-buttons">
            <a href="https://emojiaddon.com" class="button button-secondary" target="_blank" rel="noopener noreferrer">Emoji</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/accented-letters/" class="button button-secondary"
               target="_blank" rel="noopener noreferrer">Accented Letters</a>
            <a href="https://notefox.eu" class="button button-secondary" target="_blank" rel="noopener noreferrer">Notefox</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/limite/" class="button button-secondary"
               target="_blank" rel="noopener noreferrer">Limite</a>
            <a href="https://play.google.com/store/apps/details?id=com.saverio.wordoftheday_en"
               class="button button-secondary" target="_blank" rel="noopener noreferrer">Word of the Day</a>
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
