<html>
<head>
    <?php
    $title = "News – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "news";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<script>
    document.addEventListener("readystatechange", (event) => {
        switch (document.readyState) {
            case "complete":
                expandContainer();
                break;
        }
    });
</script>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <h1 class="title-section">News</h1>
        <h2 class="subtitle-section no-bold font-small">In this page you'll find all news about the add-on, grouped by
            release</h2>
    </div>
    <br class="big-space">
    <div class="expanding-container">
        <button type="button" class="expanding-item">Release 3.10.1 – 27 Feb 2024</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added new option, which permits to check notes for all supported protocols
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.10 – 19 Feb 2024</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Sticky-notes also for chromium-based!
                <br>
                • Back-end improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.3 – 29 Jan 2024</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Small improvements when there is more than one window opened
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.2.2 – 20 Dec 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a minor bug
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.2, 3.9.2.1 – 16 Nov 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.1.2 – 10 Nov 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Implemented "insert link" (Select text > Ctrl+L, if it's a correct link it will be transformed in "html link" otherwise nothing).
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.1.1 – 10 Nov 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Some fixes
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9.1 – 8 Nov 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Links are now supported*! Open them pressing and holding “Ctrl”<sup>**</sup> and then clicking on the link!
                <br>
                • Added new colours for the tag
                <br>
                • Change tag colour directly from popup
                <br>
                • Customise when change the addon icon to “green” (default: when exists Global or Domain or Page or Subdomain note)
                <br>
                • You need to copy/paste an “A” HTML tag or insert link via the popup feature
                <br>
                <br><sup>**</sup> on macOS you can use “Control” or “Meta”
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.9 – 3 Nov 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Now the "sizes", "opacity" and "coords" parameters are saved for each pages and not anymore as "global values"
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.8 – 25 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [Fixed] Fixed an important bug which caused an abnormal CPU usage
                <br>
                • [New] Export to file (require "downloads" permission)
                <br>
                • [New] Import from file
                <br>
                • [Updated] All languages
                <br>
                • [Fixed] Minor fixes
                <br>
                • [Improvements] Minor backend improvements
                <br>
                • [Changed] Icons for "Import", "Export" and "Save"
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.12 – 24 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Restored local fonts and removed @import ... from Google CDN
                <br>
                • Improved the sticky-notes
                <br>
                • Backend improvements
                <br>
                • Spellcheck (enabled status) updating in live also for sticky-notes
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.10, 3.7.11 – 23 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fix important bug: "sticky" and "sticky-minimized" didn't work correctly!
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.9 – 19 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [Fixed] Backend improvements
                <br>
                • [Fixed] UI improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.8 – 18 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [Fixed] Fixed a bug with "setPosition" in unsupported-sticky-notes pages
                <br>
                • [New] Added tooltip in aside the search-box
                <br>
                • [Updated] Languages
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.7 – 17 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] You can now disable shortcuts
                <br>
                • [Improved] Some improvements
                <br>
                • [Fix] Fixed some bugs
                <br>
                • [New] It will be shown "support the project" after 1000, 5000, etc. openings of popup
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.6 – 16 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [Improved] Some improvements
                <br>
                • [Fix] Some fixes
                <br>
                • [New] Added "language spellcheck" settable
                <br>
                • [New] Added "title" on text-formatting buttons (hover)
                <br>
                • [Improved] Now you can navigate also in the sub-domains tab using the "Tab" button on shortcut
                <br>
                • [New] Added "multi-keyword" searching (values separated by ";" – not insert the space!)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.5 – 15 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Option to enable/disable notes text word-wrap
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.4 – 15 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added some console.error messages
                <br>
                • Added option to disable text-formatting panel in the bottom of popup
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.3 – 14 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • If you want to paste an unsupported element, now the addon paste the value as text
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.2 – 14 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Now the notes supports more html tags (h1-h6, strong, em, code, blockquote, cite, etc.)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7.1 – 14 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Spellcheck feature
                <br>
                • [Updated] Languages
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.7 – 14 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Subdomains (Advanced managing: you need to enable on Settings)
                <br>
                • [New] Bold, italic, underline, images and advanced navigation (undo and redo)
                <br>
                • [Update] Some fixes and improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.6 – 12 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Other improvements to the search feature
                <br>
                • Fixed an important bug with storage
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.5 – 5 Oct 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Improved search feature
                <br>
                • Added "page" also for the "moz-extension" protocol
                <br>
                • Optional permissions
                <br>
                • Other improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.4.1.3 – 28 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fix a graphic bug
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.4.1.2 – 28 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Improved "All notes": now the active section as a bigger border
                <br>
                • Fixed a color for the Dark theme
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.4.1 & 3.4.1.1 – 22 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Added "title" parameter (for now it's not editable)
                <br>
                • [New] Added "Global" and "Domain" for non-http(s) websites
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.4.0.1 – 22 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a small bug when viewing all notes
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.4 – 21 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • [New] Theme of the add-on: "Light", "Dark" and "Follow the Firefox theme"
                <br>
                • [New] "Sort by" feature in "All notes": "Name AZ", "Name ZA", "Date 09" and "Date 90"
                <br>
                • [New] "Global" notes: this notes are visible in all websites
                <br>
                • [New] "Filter…" featute: now you can filter by "tag colour" (red, yellow, ...) and "page type" (global, domain, page)
                <br>
                • [New] the first time you install the add-on it's shown a "Welcome page"
                <br>
                • [Update] Updated all languages
                <br>
                • [Update] Updated all screenshots
                <br>
                • [Fix] Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.3.2 – 14 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • New buttons
                <br>
                • Fixed issue with icon: it was caused to a wrong managing of "#sections" and "?queries". Now it's fixed and the icon works well again
                <br>
                • Some improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.3.1.3, 3.3.1.4, 3.3.1.6, 3.3.1.7, 3.3.1.8  – 13 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Important fix about the previous 3.3
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.3.1 – 12 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a bug with "section" and "queries" (the icon wasn't set correctly)
                <br>
                • Added "Minimize" sticky and "restore sticky"
                <br>
                • Improvements to the UI of Sticky-notes
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.3 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Now it's used "sync" instead of "local", so data should be sync with Firefox Accounts!
                <br>
                • Implemented function which converts local to sync
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.2.1 & 3.2.1.1 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Show if it's a page or domain in the sticky-notes
                <br>
                • Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.2 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Sticky-notes follow the default preferences (if there are both "domain" and "page" notes)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.1 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Sticky-notes mode available also for domains
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.0.1 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Some bugs fixed
            </p>
        </div>

        <button type="button" class="expanding-item">Release 3.0 – 11 Aug 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • New sticky-notes mode (only for pages): it's customisable (you can change the size, the position, the opacity), there you can edit text too and you can see the tag category
                <br>
                • Fixed many bugs
                <br>
                • Improved UI/UX and icons
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.3.2 – 29 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a bug: the opening with default didn't work anymore
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.3.1 – 28 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.3 – 21 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added shortcuts (open-by-default, open-by-domain, open-by-page)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.2.1 & 2.2.2 – 18 Nov 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a bug in the last release
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.2 – 17 Nov 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added new option in Settings
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.1.1 – 20 Oct 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Improved search feature
                <br>
                • Added "Translate on Crowdin" button in Settings
                <br>
                • Added "Settings" button in All notes
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.1 – 18 Oct 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "search box" feature
                <br>
               • Added "filter by" feature
                <br>
                • Updated all languages
                <br>
                • Other minor improvements
            </p>
        </div>

        <button type="button" class="expanding-item">Release 2.0 – 13 Oct 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Improved the exporting and importing process (more information)
                <br>
                • Added Settings! -> Go to Add-ons and themes > Notefox > Settings
                <br>
                • Updated main color (orange!) and icon
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.6.0.2 – 15 Dec 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Updated all languages (added Spanish)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.3.0.1 – 9 Aug 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed strings
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.3 – 3 Aug 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Clear notes" for a specific whole domain (and its pages)
                <br>
                • Added "Clear notes" for a specific page
                <br>
                • Added a different extension-icon when there is a notes
                <br>
                • If the notes field is empty, it's removed from the notes list
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.2 – 1 Aug 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Import", "Export" features
                <br>
                • Added confirmation dialog before to clear all notes
                <br>
                • New icon
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.1 – 31 Jul 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "All notes" dedicated page
                <br>
                • Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.0 – 30 Jul 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                First release of the app
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>