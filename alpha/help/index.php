<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Help";
    $description = "Get help with Sav PDF Viewer. Find answers to frequently asked questions or contact support via Telegram or email.";
    $canonical_path = "/alpha/help/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
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
            <h1 class="title-section" data-i18n="help.title">Get help</h1>
            <p class="subtitle-section no-bold" data-i18n="help.subtitle">To get help, please contact me via Telegram or
                e-mail</p>

            <p class="support-lang-note" data-i18n="help.support_lang">Support is available in English only</p>

            <div class="button-group">
                <a href="https://t.me/sav_projects" class="button button-with-icon" target="_blank"
                   rel="noopener noreferrer">
                    <span class="button__icon button-icon-telegram"></span>Telegram
                </a>
                <a href="/alpha/email/" class="button button-with-icon">
                    <span class="button__icon button-icon-email"></span>Email
                </a>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <section class="faq-section">
        <h2 class="title-section" data-i18n="help.faq_title">FAQ</h2>
        <p class="subtitle-section no-bold" data-i18n="help.faq_subtitle">Frequently asked questions</p>

        <div class="expanding-container">
            <div class="faq-search">
                <svg class="faq-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" class="faq-search__input" id="faq-search" data-i18n-placeholder="help.faq_search"
                       placeholder="Search FAQ...">
            </div>
            <button type="button" class="expanding-item" data-i18n="help.faq1_q">What can I do with Sav PDF Viewer?
            </button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq1_a">Sav PDF Viewer lets you open and read PDF files quickly and simply. You can
                    search text, select and copy text, create bookmarks, resume reading from where you left off, open
                    password-protected PDFs, use night mode, open links within documents, and share files. It is not a
                    PDF editor — it's a viewer, and it does that one thing well.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq2_q">Is the app open source?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq2_a">Yes. The entire source code is available on GitHub. Sav PDF Viewer is
                    completely independent — it doesn't rely on Google, Huawei, or any third-party service to work.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq3_q">Does the app collect my data?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq3_a">No. Sav PDF Viewer does not collect, store, or transmit any personal data.
                    There is no analytics, no tracking, and no telemetry. Your documents stay on your device and are
                    never sent anywhere.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq4_q">Why does the app require no
                permissions?
            </button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq4_a">Sav PDF Viewer uses Android's built-in file picker to open documents. You
                    choose which file to open, and the app only accesses that specific file. No broad storage permission
                    is needed — you can verify this yourself under "App details > Permissions" on the Play Store
                    listing.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq5_q">Does it support password-protected
                PDFs?
            </button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq5_a">Yes. If you open a password-protected PDF, the app will ask you for the
                    password. Once entered, you can read the document normally.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq6_q">Can I search and select text?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq6_a">Yes. You can search for text within a PDF, select text, copy it, and share
                    it. These features work on PDFs that contain actual text data. Scanned documents that are
                    essentially images may not support text search or selection.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq7_q">Is there a night mode?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq7_a">Yes. Night mode inverts the colors to protect your eyes when reading in the
                    dark. You can toggle it from the toolbar.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq8_q">Where can I get the app?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq8_a">You can get Sav PDF Viewer from Google Play or GitHub. It's also listed on
                    Amazon AppStore and Huawei AppGallery, but those versions may be outdated and may not receive
                    updates.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq9_q">Is the app available on iOS?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq9_a">No, Sav PDF Viewer is available only on Android.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq10_q">Can I edit PDFs with this app?
            </button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq10_a">No. Sav PDF Viewer is a viewer, not an editor. It is designed to open and
                    read PDF documents — nothing more, nothing less.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq11_q">How can I support the project?
            </button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq11_a">You can support Sav PDF Viewer by donating via LiberaPay (recommended — no
                    fees) or PayPal. You can also leave a review on Google Play, report bugs, help translate the app on
                    Crowdin, or contribute to the code on GitHub.</p>
            </div>

            <button type="button" class="expanding-item" data-i18n="help.faq12_q">Where can I report a bug?</button>
            <div class="expanding-item--expanded-details hidden">
                <p data-i18n="help.faq12_a">You can report bugs by opening an issue on the GitHub repository, or by
                    contacting me directly via Telegram or email using the buttons above.</p>
            </div>

            <div class="faq-no-results" id="faq-no-results" style="display:none">
                <p data-i18n-html="help.faq_no_results">Can't find what you're looking for? <a href="/alpha/email/">Contact
                        me</a>.</p>
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

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "What can I do with Sav PDF Viewer?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sav PDF Viewer lets you open and read PDF files quickly and simply. You can search text, select and copy text, create bookmarks, resume reading from where you left off, open password-protected PDFs, use night mode, open links within documents, and share files. It is not a PDF editor — it's a viewer."
                }
            },
            {
                "@type": "Question",
                "name": "Is the app open source?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. The entire source code is available on GitHub under GPL-3.0. Sav PDF Viewer is completely independent — it doesn't rely on Google, Huawei, or any third-party service."
                }
            },
            {
                "@type": "Question",
                "name": "Does the app collect my data?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No. Sav PDF Viewer does not collect, store, or transmit any personal data. There is no analytics, no tracking, and no telemetry. Your documents stay on your device."
                }
            },
            {
                "@type": "Question",
                "name": "Why does the app require no permissions?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sav PDF Viewer uses Android's built-in file picker to open documents. You choose which file to open, and the app only accesses that specific file. No broad storage permission is needed."
                }
            },
            {
                "@type": "Question",
                "name": "Is the app available on iOS?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No, Sav PDF Viewer is available only on Android."
                }
            },
            {
                "@type": "Question",
                "name": "Can I edit PDFs with this app?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No. Sav PDF Viewer is a viewer, not an editor. It is designed to open and read PDF documents — nothing more, nothing less."
                }
            }
        ]
    }
</script>

<script>
    (function () {
        var input = document.getElementById('faq-search');
        if (!input) return;
        var noResults = document.getElementById('faq-no-results');
        input.addEventListener('input', function () {
            var query = this.value.toLowerCase().trim();
            var items = document.querySelectorAll('.expanding-item');
            var anyMatch = false;
            items.forEach(function (btn) {
                var details = btn.nextElementSibling;
                if (details && details.id === 'faq-no-results') return;
                var title = btn.textContent.toLowerCase();
                var desc = details ? details.textContent.toLowerCase() : '';
                var match = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;
                if (match) anyMatch = true;
                btn.style.display = match ? '' : 'none';
                if (details) {
                    if (!match) {
                        details.style.display = 'none';
                        details.classList.add('hidden');
                        btn.classList.remove('expanding-item--expanded');
                    } else {
                        details.style.display = '';
                    }
                }
            });
            if (noResults) noResults.style.display = (!anyMatch && query) ? '' : 'none';
        });
    })();
</script>

</body>
</html>
