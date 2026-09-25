<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $description = "Sav PDF Viewer — a fast, private, open-source PDF reader for Android. No ads, no tracking, no permissions. Read your PDFs, nothing more.";
    $canonical_path = "/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "home";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <!-- Hero -->
    <section class="hero">
        <div class="hero__inner">
            <img src="/images/icon.png" class="hero-icon" alt="Sav PDF Viewer">
            <h1 class="title-section">Sav PDF Viewer</h1>
            <p class="hero-tagline" data-i18n="home.tagline">Read your PDFs. Nothing more, nothing less.</p>
            <p class="hero-claim">
                <em data-i18n-html="home.claim">Your privacy is important. <strong>You are important</strong>.</em>
            </p>

            <div class="trust-badges">
                <span class="trust-badge" data-i18n="home.badge_ads">No ads</span>
                <span class="trust-badge" data-i18n="home.badge_permissions">No permissions</span>
                <span class="trust-badge" data-i18n="home.badge_tracking">No tracking</span>
                <span class="trust-badge" data-i18n="home.badge_opensource">Open source</span>
            </div>

            <div class="button-group">
                <a href="/install/" class="button button-with-icon button-lg">
                    <span class="button__icon button-icon-install"></span><span
                            data-i18n="home.install_btn"
                            data-platform-android="Install now"
                            data-platform-desktop="Install on your device"
                            data-platform-ios="Install on Android">Install</span>
                </a>
            </div>
            <p class="platform-notice" style="display:none;"></p>

            <p class="hero-social-proof" data-i18n="home.social_proof">100,000+ people already use it</p>
        </div>

        <div class="scroll-hint" id="scroll-hint">
            <div class="scroll-hint__mouse">
                <div class="scroll-hint__wheel"></div>
            </div>
            <span class="scroll-hint__text" data-i18n="home.scroll_down">Scroll down</span>
        </div>
    </section>

    <script>
        (function () {
            var hint = document.getElementById('scroll-hint');
            if (!hint) return;
            var fadeEnd = 200;
            window.addEventListener('scroll', function () {
                var y = window.scrollY || window.pageYOffset;
                hint.style.opacity = Math.max(0, 0.5 - (y / fadeEnd) * 0.5);
            }, {passive: true});
        })();
    </script>

    <!-- Feature: Fast & flexible -->
    <section class="feature-section">
        <div class="feature__inner">
            <div class="feature-content">
                <h2 class="title-section" data-i18n="home.feature_lightweight_title">Fast & flexible</h2>
                <p data-i18n="home.feature_lightweight_desc">A tiny app that opens your PDFs in a blink — and lets you
                    make it yours. Night mode, scroll direction, zoom level, and more: everything is adjustable for a
                    comfortable reading experience.</p>
            </div>
            <div class="feature-visual">
                <div class="phone-duo">
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-reading.png"
                             data-i18n-src="/images/home/{lang}/screen-reading.png" alt="Clean reading">
                    </div>
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-settings.png"
                             data-i18n-src="/images/home/{lang}/screen-settings.png" alt="Settings">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature: Search & select -->
    <section class="feature-section feature-section--reverse">
        <div class="feature__inner">
            <div class="feature-content">
                <h2 class="title-section" data-i18n="home.feature_search_title">Search & select text</h2>
                <p data-i18n="home.feature_search_desc">Find what you need quickly with the built-in search. Select and
                    copy text directly from your PDFs
                    with ease.</p>
            </div>
            <div class="feature-visual">
                <div class="phone-duo">
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-search.png"
                             data-i18n-src="/images/home/{lang}/screen-search.png" alt="Search">
                    </div>
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-select.png"
                             data-i18n-src="/images/home/{lang}/screen-select.png" alt="Select text">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature: Bookmarks & share -->
    <section class="feature-section">
        <div class="feature__inner">
            <div class="feature-content">
                <h2 class="title-section" data-i18n="home.feature_bookmarks_title">Bookmarks & share</h2>
                <p data-i18n="home.feature_bookmarks_desc">Bookmark your favourite pages and jump back to them
                    instantly. Share any PDF with your contacts in
                    just a couple of taps.</p>
            </div>
            <div class="feature-visual">
                <div class="phone-duo">
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-bookmarks.png"
                             data-i18n-src="/images/home/{lang}/screen-bookmarks.png" alt="Bookmarks">
                    </div>
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-share.png"
                             data-i18n-src="/images/home/{lang}/screen-share.png" alt="Share">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature: Recent & protected files -->
    <section class="feature-section feature-section--reverse">
        <div class="feature__inner">
            <div class="feature-content">
                <h2 class="title-section" data-i18n="home.feature_recent_title">Your files, always ready</h2>
                <p data-i18n="home.feature_recent_desc">Quickly reopen any document you've read recently, right where
                    you left off. Need to open an encrypted PDF? Just enter the password and start reading.</p>
            </div>
            <div class="feature-visual">
                <div class="phone-duo">
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-recents.png"
                             data-i18n-src="/images/home/{lang}/screen-recents.png" alt="Recent files">
                    </div>
                    <div class="phone-mockup">
                        <img src="/images/home/en/screen-password.png"
                             data-i18n-src="/images/home/{lang}/screen-password.png" alt="Password-protected PDF">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy & open source banner (no screenshot) -->
    <section class="feature-section feature-section--highlight">
        <div class="feature__inner feature__inner--stacked">
            <div class="feature-content" style="text-align: center; max-width: 640px; margin: 0 auto;">
                <h2 class="title-section" data-i18n="home.privacy_title">Privacy first. Zero permissions. Open
                    source.</h2>
                <p data-i18n="home.privacy_desc">Sav PDF Viewer doesn't collect any data, doesn't track you, and
                    requires absolutely no permissions to
                    work. The entire source code is on GitHub — full transparency, no hidden surprises.</p>
                <div class="button-group button-group--tight">
                    <a href="https://github.com/Sav22999/sav-pdf-viewer-pro"
                       class="button button-secondary button-with-icon-secondary" target="_blank"
                       rel="noopener noreferrer">
                        <span class="button__icon button-icon-github"></span><span data-i18n="home.privacy_github">View on GitHub</span>
                    </a>
                    <a href="/privacy/" class="button button-secondary" data-i18n="home.privacy_policy">Privacy
                        policy</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section class="reviews-section">
        <h2 class="title-section" data-i18n="home.reviews_title">What people say</h2>
        <p class="subtitle-section no-bold" data-i18n="home.reviews_subtitle">Real reviews from real users</p>

        <div class="reviews-carousel" id="reviews-carousel">
            <div id="all-reviews"></div>
            <div class="review-dots" id="review-dots"></div>
        </div>
    </section>

    <script>
        (function () {
            var reviews = [];
            var current = 0;
            var timer = null;
            var INTERVAL = 6000;
            var carousel = null;
            var dotsContainer = null;
            var reviewsEl = null;
            var touchStartX = 0;
            var touchDelta = 0;
            var animating = false;

            function goTo(index, direction) {
                if (animating) return;
                var prev = current;
                if (index < 0) index = reviews.length - 1;
                if (index >= reviews.length) index = 0;
                if (index === current) return;

                if (!direction) direction = index > prev ? 'left' : 'right';

                animating = true;
                reviewsEl.classList.add('fade-out-' + direction);

                setTimeout(function () {
                    current = index;
                    loadReview(reviews, current);
                    renderDots();

                    reviewsEl.classList.remove('fade-out-' + direction);
                    var enterDir = direction === 'left' ? 'left' : 'right';
                    reviewsEl.classList.add('fade-enter-' + enterDir);

                    setTimeout(function () {
                        reviewsEl.classList.remove('fade-enter-' + enterDir);
                        animating = false;
                    }, 50);
                }, 350);

                resetTimer();
            }

            function renderDots() {
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';
                for (var i = 0; i < reviews.length; i++) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'review-dot' + (i === current ? ' review-dot--active' : '');
                    dot.setAttribute('aria-label', 'Review ' + (i + 1));
                    var fill = document.createElement('span');
                    fill.className = 'review-dot__fill';
                    dot.appendChild(fill);
                    dot.addEventListener('click', (function (idx) {
                        return function () {
                            goTo(idx);
                        };
                    })(i));
                    dotsContainer.appendChild(dot);
                }
            }

            function resetTimer() {
                if (timer) clearTimeout(timer);
                timer = setTimeout(function () {
                    goTo(current + 1, 'left');
                }, INTERVAL);
            }

            function pauseTimer() {
                if (timer) clearTimeout(timer);
                timer = null;
                if (carousel) carousel.classList.add('reviews-carousel--paused');
            }

            function resumeTimer() {
                if (carousel) carousel.classList.remove('reviews-carousel--paused');
                resetTimer();
            }

            document.addEventListener("DOMContentLoaded", function () {
                reviews = getAllReviews();
                reviews.sort(function () {
                    return Math.random() - 0.5;
                });
                reviews = reviews.slice(0, 8);
                carousel = document.getElementById('reviews-carousel');
                dotsContainer = document.getElementById('review-dots');
                reviewsEl = document.getElementById('all-reviews');

                loadFirstReview(reviews);
                renderDots();
                resetTimer();

                carousel.addEventListener('mouseenter', pauseTimer);
                carousel.addEventListener('mouseleave', resumeTimer);

                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) {
                        pauseTimer();
                    } else {
                        animating = false;
                        resumeTimer();
                    }
                });

                carousel.addEventListener('touchstart', function (e) {
                    touchStartX = e.touches[0].clientX;
                    touchDelta = 0;
                    pauseTimer();
                }, {passive: true});

                carousel.addEventListener('touchmove', function (e) {
                    touchDelta = e.touches[0].clientX - touchStartX;
                }, {passive: true});

                carousel.addEventListener('touchend', function () {
                    if (Math.abs(touchDelta) > 40) {
                        if (touchDelta < 0) goTo(current + 1, 'left');
                        else goTo(current - 1, 'right');
                    }
                    resumeTimer();
                });
            });
        })();
    </script>

    <!-- CTA -->
    <section class="cta-section">
        <h2 class="title-section" data-i18n="home.cta_title">What are you waiting for?</h2>
        <p data-i18n="home.cta_desc">Try Sav PDF Viewer now — private, open source, and independent.</p>

        <div class="button-group">
            <a href="/install/" class="button button-with-icon">
                <span class="button__icon button-icon-install"></span><span
                        data-i18n="home.cta_install"
                        data-platform-android="Install now"
                        data-platform-desktop="Install on your device"
                        data-platform-ios="Install on Android">Install now</span>
            </a>
        </div>
        <p class="platform-notice platform-notice--cta" style="display:none;"></p>
    </section>
</main>

<footer>
    <span data-i18n="footer.developed">Developed with</span>
    <span class="image-heart image-background-primary image-square-20px"></span>
    <span data-i18n="footer.by">by</span> <a href="https://saveriomorelli.com" class="author-name" target="_blank"
                                             rel="noopener noreferrer">Saverio Morelli</a>
    <div class="footer-links">
        <a href="/privacy/" data-i18n="footer.privacy_policy">Privacy policy</a>
        <span class="footer-sep">·</span>
        <a href="/terms/" data-i18n="footer.terms">Terms of service</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Sav PDF Viewer",
        "operatingSystem": "Android",
        "applicationCategory": "UtilitiesApplication",
        "description": "A fast, private, open-source PDF reader for Android. No ads, no tracking, no permissions required.",
        "url": "https://www.savpdfviewer.com",
        "author": {
            "@type": "Person",
            "name": "Saverio Morelli",
            "url": "https://saveriomorelli.com"
        },
        "license": "https://opensource.org/licenses/GPL-3.0",
        "offers": {
            "@type": "Offer",
            "price": "2.49",
            "priceCurrency": "EUR",
            "url": "https://play.google.com/store/apps/details?id=com.saverio.pdfviewer"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.5",
            "ratingCount": "1000"
        },
        "installUrl": "https://play.google.com/store/apps/details?id=com.saverio.pdfviewer",
        "screenshot": "https://www.savpdfviewer.com/images/opengraph.png",
        "softwareVersion": "2.4",
        "permissions": "none"
    }
</script>

</body>
</html>
