<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Support the project";
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
                <h1 class="title-section" data-i18n="donate.title">Support the project</h1>
                <p class="subtitle-section no-bold" data-i18n-html="donate.subtitle">Sav PDF Viewer is built and maintained by a single developer.<br>No
                    company, no investors — just one person and a lot of passion.</p>

                <p class="donate-description" data-i18n="donate.desc">Your donation keeps this project alive, independent, and free of ads. Every
                    contribution — big or small — makes a real difference.</p>

                <div class="trust-badges">
                    <span class="trust-badge" data-i18n="donate.badge_ads">No ads</span>
                    <span class="trust-badge" data-i18n="donate.badge_tracking">No tracking</span>
                    <span class="trust-badge" data-i18n="donate.badge_opensource">Open source</span>
                    <span class="trust-badge" data-i18n="donate.badge_independent">100% independent</span>
                </div>

                <p class="donate-tip" data-i18n-html="donate.tip">
                    I recommend <strong>LiberaPay</strong> — it's safe and <strong>free of fees</strong>,<br>
                    but PayPal works great too
                </p>

                <div class="button-group">
                    <a href="https://liberapay.com/Sav22999/" class="button button-with-icon button-lg">
                        <span class="button__icon button-icon-liberapay"></span>LiberaPay
                    </a>
                    <a href="https://www.paypal.me/saveriomorelli" class="button button-with-icon button-lg">
                        <span class="button__icon button-icon-paypal"></span>PayPal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <section class="faq-section">
        <p class="section-note" data-i18n="donate.other_projects">Check out my other projects</p>

        <div class="projects-buttons">
            <a href="https://emojiaddon.com" class="button button-secondary">Emoji</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/accented-letters/" class="button button-secondary">Accented
                Letters</a>
            <a href="https://notefox.eu" class="button button-secondary">Notefox</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/limite/" class="button button-secondary">Limite</a>
            <a href="https://play.google.com/store/apps/details?id=com.saverio.wordoftheday_en"
               class="button button-secondary">Word of the Day</a>
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
