<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "News";
    $description = "Latest news and changelog for Sav PDF Viewer. See what's new in each release.";
    $canonical_path = "/news/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "news";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<script>
    document.addEventListener("readystatechange", function (event) {
        if (document.readyState === "complete") {
            expandContainer();
        }
    });
</script>

<main>
    <section class="hero hero--compact">
        <div class="hero__inner">
            <h1 class="title-section" data-i18n="news.title">News</h1>
            <p class="subtitle-section no-bold" data-i18n="news.subtitle">All news about the app, grouped by release</p>
        </div>
    </section>

    <hr class="section-divider">

    <section class="faq-section">
        <div class="expanding-container">
            <div class="lang-notice" data-i18n="news.lang_notice" style="display: none;">This page is available in
                English only
            </div>
            <button type="button" class="expanding-item">Release 2.4 – 20 Aug 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Toggle toolbar on tap</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Minor improvements</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 2.3 – 13 Aug 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--improved">improved</span> Search feature now
                        supports multi-word queries
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Navbar</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Minor improvements</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 2.2.2 – 14 Jul 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Panel was behind the system
                        navigation bar on Android API 35+
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> All pages were loaded on
                        startup causing slow loading
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Double-byte characters not
                        showing correctly in file names
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Optimized reverse (RTL)
                        swiping to reuse stored page count
                    </li>
                    <li><span class="changelog-label changelog-label--new">new</span> Added total_pages column with DB
                        migration to version 5
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Minor improvements</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 2.2 – 8 Jul 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Support for AES-256 protection
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Separated per-ABI builds
                    </li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated languages</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 2.1 – 6 Jul 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Print button in the topbar</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Swiping bottom-up and
                        right-left
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Changed main library to
                        FOSS and removed unnecessary permissions
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Many bug fixes</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Many improvements</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated languages</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 2.0 – 25 Mar 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Text selection, copy, and filters
                    </li>
                    <li><span class="changelog-label changelog-label--new">new</span> Search feature with filters</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Settings page</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Many bug fixes</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Many improvements</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated languages</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.17 – 12 Mar 2026</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Search feature in the menu panel
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bug fixes</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.16 – 6 Aug 2025</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Lock rotation feature in the menu
                        panel
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Moved share button to
                        the topbar
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bug fixes</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.15.1.1 – 10 Nov 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Confirmation dialog before opening
                        links
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bug fixes</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.15.1 – 2 Nov 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Light-on/off filter</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Forced-dark filter</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Tap on PDF to show the topbar</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.15 – 31 Oct 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--improved">improved</span> Minor improvements</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Icon now supports monochrome theme
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.14.2 – 28 Oct 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bugs from the previous
                        version
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.14.1 – 27 Oct 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bugs from the previous
                        version
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.14 – 27 Oct 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Support for links in PDFs</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Horizontal scrolling mode</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Forced-dark filter</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Single page scrolling mode</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bug fixes</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Backend improvements
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.13.2 – 12 Oct 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Closure when PDFs were opened
                        by external apps
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.13 – 1 Aug 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--improved">improved</span> Share feature: now you
                        can share every opened file
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Other fixes</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.12.0.2 – 9 Jun 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some fixes</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> minSDK and targetSDK</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.12 – 6 Jun 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Zoom control feature</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Updated some icons</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some fixes</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated the library</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.11.1 – 13 Apr 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Zoom bug</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.11 – 12 Apr 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Tap on page to hide/show the
                        topbar
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Gestures</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some fixes</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated the library</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.10.4 – 30 Mar 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Get help in the menu panel</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Night theme</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Min SDK now 24 (was 16)</li>
                    <li><span class="changelog-label changelog-label--other">other</span> Updated languages</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.10.3 – 28 Mar 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--improved">improved</span> Icon gestures</li>
                    <li><span class="changelog-label changelog-label--new">new</span> New icon for bookmarks</li>
                    <li><span class="changelog-label changelog-label--new">new</span> All bookmarks button in the menu
                        panel
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.10.2 – 28 Mar 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Scrollbar bug</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Page number shown during scrollbar
                        navigation
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.10.1 – 28 Mar 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Some bug fixes</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.10 – 27 Mar 2023</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Scrollbar button for faster
                        navigation
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Many bug fixes</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Bookmark animations and
                        gestures
                    </li>
                    <li><span class="changelog-label changelog-label--other">other</span> Disabled Instagram popup</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.9 – 24 Apr 2022</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Bookmarks: add, remove and manage
                        bookmarks
                    </li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Backend code and
                        frontend UI
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.8.0.2 – 12 Oct 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--other">other</span> Published on F-Droid</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.8.0.1 – 2 Oct 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Small bug fix</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.8 – 1 Oct 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Menu panel for small-display
                        devices
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Night light bug</li>
                    <li><span class="changelog-label changelog-label--improved">improved</span> Performance</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.7 – 30 Sep 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Top bar disappears after 5 seconds
                        of inactivity
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.6.3 / 1.6.4 – 30 Aug 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Page restored after
                        orientation change
                    </li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Share feature not working
                        correctly
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.6.2 – 21 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--other">other</span> Added translations</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.6.1 – 21 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Fit width when changing
                        orientation
                    </li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.6 – 11 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Night light</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.5.1 – 10 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Open new file button</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.5 – 8 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Support for password-protected
                        PDFs
                    </li>
                    <li><span class="changelog-label changelog-label--new">new</span> Go to the top button</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.4.2 – 8 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Go to page feature</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.4.1 – 8 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Last position bug</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.4 – 7 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Total pages counter</li>
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Orientation bug</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.3 – 6 Jun 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Top bar</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Full screen mode</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.2 – 1 May 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--fixed">fixed</span> Crash on Android 11</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.1.1 – 11 Feb 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> Share button</li>
                    <li><span class="changelog-label changelog-label--new">new</span> Review message dialog</li>
                </ul>
            </div>

            <button type="button" class="expanding-item">Release 1.0 – 20 Jan 2021</button>
            <div class="expanding-item--expanded-details hidden">
                <ul class="changelog-list">
                    <li><span class="changelog-label changelog-label--new">new</span> First release of the app</li>
                </ul>
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
        <a href="/privacy/" data-i18n="footer.privacy_policy">Privacy policy</a>
        <span class="footer-sep">·</span>
        <a href="/terms/" data-i18n="footer.terms">Terms of service</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

</body>
</html>
