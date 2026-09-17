<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "News – Notefox";
$selected_menu = "news";
include_once($root_path . "/alpha/include/header.php");

function render_release_item($text)
{
    $map = array(
            "new" => "new", "improved" => "improved", "improvement" => "improved",
            "fixed" => "fixed", "fix" => "fixed",
            "changed" => "changed", "updated" => "updated", "update" => "updated"
    );
    $labels = array(
            "new" => "New", "improved" => "Improved", "fixed" => "Fixed",
            "changed" => "Changed", "updated" => "Updated"
    );
    if (preg_match('/^\[(\w+)\]\s*/', $text, $m)) {
        $key = strtolower($m[1]);
        $rest = substr($text, strlen($m[0]));
        if (isset($map[$key])) {
            $cls = $map[$key];
            return '<span class="release-tag release-tag--' . $cls . '">' . $labels[$cls] . '</span>' . htmlspecialchars($rest);
        }
    }
    return htmlspecialchars($text);
}

$releases = array(
        array("Release 4.7.1 – 9 Apr 2026", array(
                "[new] Now you can set Notefox also in the sidebar view",
                "[updated] Updated translations",
                "[improved] Minor improvements",
                "[fixed] Fixed minor bugs"
        )),
        array("Release 4.7 – 27 Mar 2026", array(
                "[improved] Improved the tag-colour selection",
                "[new] Added tags as text as well",
                "[new] Added folder #120",
                "[new] Added ordered and unordered list",
                "[new] Added option to set default properties of sticky-notes",
                "[improved] Improved aside minimised sticky-notes (it's draggable and you can see the tag-color)",
                "[improved] Improved the link feature",
                "[new] You can now edit also the page/domain link in All notes",
                "[updated] Updated translations",
                "[fixed] Other improvements and bugfix"
        )),
        array("Release 4.6.0.2 – 3 Jan 2026", array(
                "[fixed] Fixed an error with links in the notes"
        )),
        array("Release 4.6, 4.6.0.1 – 27 Nov 2025", array(
                "[changed] New icon and changed the default font-family",
                "[improved] Minor improvements",
                "[new] Added new feature for icon behaviour",
                "[fixed] Minor fixes",
                "[changed] Added splash screen (in this way loading process of the theme is not annoying)",
                "[new] Popup is now resizable",
                "[new] Customise the text-size in the notes (and in All notes)",
                "[fixed] Fixed a bug with \"sections\" and \"parameters\"",
                "[new] Added \"clear formatting\" button",
                "[new] Added \"fullscreen\" in All notes",
                "[new] New section in settings: developer options",
                "[new] You can customise the API endpoint",
                "[fixed] Minor fix with \"notefox server error\""
        )),
        array("Release 4.5.5.3 – 23 Oct 2025", array(
                "[fixed] Bugfix: Deleting a Notefox account works again",
                "[new] You can now disable deleting-confirmation dialog"
        )),
        array("Release 4.5.5.2 – 23 Oct 2025", array(
                "[fixed] Fixed bug with Notefox account",
                "[improved] Minor general improvements"
        )),
        array("Release 4.5.5.1 – 23 Oct 2025", array(
                "[improved] Improved UX/UI: increased the border-radius",
                "[improved] Improvements UI/UX: added spinner during loading",
                "[improved] Improvements of Notefox account: managed the \"Notefox server unreachable\": it permits to log out or continue \"offline\"",
                "[fixed] Minor fixes",
                "[improved] Minor general improvements",
                "[updated] Updated \"Consent Experience\" (ex Privacy Policy)",
                "[updated] Updated languages"
        )),
        array("Release 4.5.3 – 8 Sep 2025", array(
                "[improved] Improvements UI/UX: added spinner during loading",
                "[improved] Improvements of Notefox account: managed the \"Notefox server unreachable\"",
                "[updated] Updated languages",
                "[improved] Minor general improvements"
        )),
        array("Release 4.5.0.3 – 24 Jul 2025", array(
                "[improved] Small improvements",
                "[fixed] Minor fixes"
        )),
        array("Release 4.5, 4.5.0.1, 4.5.0.2 – 23 Jul 2025", array(
                "[new] Option to enable the change of toolbar icon based on the tag colour",
                "[new] Option to enable the change of colour of notes based on the tag colour",
                "[improved] Some improvements to the sidebar (accessibility)",
                "[improved] Some improvements in All notes",
                "[new] Anonymous telemetry (disabled by default)",
                "[new] Automatic error logs sending, to fix bugs (enabled by default)",
                "[improved] Some improvements to the sticky notes",
                "[new] Keyboard shortcut to paste \"without formatting\" (Ctrl/Cmd + Shift + V)",
                "[updated] Languages",
                "[fixed] Bug which caused Firefox crash on Windows"
        )),
        array("Release 4.4.5.5 – 25 May 2025", array(
                "[improved] Small improvements",
                "[fixed] Minor fixes"
        )),
        array("Release 4.4.5.4 – 24 May 2025", array(
                "[fixed] Minor fixes and improvements"
        )),
        array("Release 4.4.5.3 – 11 May 2025", array(
                "[fixed] Bugfix copy from notes",
                "[improved] Images follow the user's choice \"word-wrap\""
        )),
        array("Release 4.4.5.2 – 10 May 2025", array(
                "[fixed] Fixed a bug in sticky notes"
        )),
        array("Release 4.4.5, 4.4.5.1 – 9 May 2025", array(
                "[fixed] Fixed a bug in background & error logs",
                "[new] Added new feature: \"code-block\" and \"mark\" formatting buttons",
                "[new] Added the button \"Delete error logs\" which permits to clear the error logs"
        )),
        array("Release 4.4.4.1 – 4 May 2025", array(
                "[fixed] Fixed a small bug"
        )),
        array("Release 4.4.4 – 4 May 2025", array(
                "[new] Added a \"Error logs\" feature: in Settings > Advanced it's now possible to get the error logs file/details to attach"
        )),
        array("Release 4.4.3.1 – 2 May 2025", array(
                "[fixed] Fixed a bug"
        )),
        array("Release 4.4.3 – 29 Apr 2025", array(
                "[fixed] Fixed a bug in the \"subdomains\" (directories) \"•••\""
        )),
        array("Release 4.4.1, 4.4.2 – 22 Apr 2025", array(
                "[fixed] Fixed an important bug which caused the crash of Firefox"
        )),
        array("Release 4.4 – 18 Apr 2025", array(
                "[new] New features for debugging",
                "[new] Added a \"Privacy policy\" acceptance screen as requested by Mozilla",
                "[improved] Minor improvements"
        )),
        array("Release 4.3.2.1 – 1 Apr 2025", array(
                "[fixed] Minor bugfix and improvements"
        )),
        array("Release 4.3.2 – 12 Mar 2025", array(
                "[fixed] Fixed a small bug in the previous release"
        )),
        array("Release 4.3.1 – 11 Mar 2025", array(
                "[fixed] Fixed a small bug in the previous release"
        )),
        array("Release 4.3 – 11 Mar 2025", array(
                "[new] Extended the \"•••\" URLs (now you can choose also from all the parameters)",
                "[improved] Check for sections' existence",
                "[fixed] Fixed a small bug with \"subdomains\" priority"
        )),
        array("Release 4.2.1 – 17 Feb 2025", array(
                "[new] Sticky-notes available also in the mobile version!",
                "[fixed] Small fix in Settings"
        )),
        array("Release 4.2.0.1 – 11 Feb 2025", array(
                "[fixed] Fixed a minor UI bug",
                "[fixed] Fixed a minor bug with permission"
        )),
        array("Release 4.2 – 7 Feb 2025", array(
                "[new] Added more font families, with a preview of them (Settings > Appearance)",
                "[new] You can save pages content and search in it (Settings > Advanced)",
                "[new] You can customise the datetime to display (Settings > Appearance)",
                "[new] Added option to hide \"Undo\" and \"Redo\" buttons (Settings > Appearance)",
                "[improved] Improved/Refresh the UI/UX of the tabs",
                "[improved] Improvements and optimisations to the code"
        )),
        array("Release 4.1.0.4 – 7 Feb 2025", array(
                "[new] Published the add-on on the Chrome Web Store",
                "[improved] Minor improvements and fixes"
        )),
        array("Release 4.1.0.3 – 21 Oct 2024", array(
                "[fixed] Fixed a bug with the mobile and desktop version about the popup window"
        )),
        array("Release 4.1.0.1, 4.1.0.2 – 29 Aug 2024", array(
                "[fixed] Fixed a bug in the mobile version"
        )),
        array("Release 4.1 – 19 Jul 2024", array(
                "[new] Added mobile version (\"Notefox Android\")",
                "[improved] Some improvements to the UI",
                "[improved] Some minor improvements",
                "[new] Added two new themes: darker and lighter"
        )),
        array("Release 4.0.1.5 – 6 Jun 2024", array(
                "[changed] Now when logout, local data is deleted",
                "[fixed] Fixed minor bugs",
                "[new] Languages: Interlingua is now available",
                "[improved] Minor improvements"
        )),
        array("Release 4.0.1.3, 4.0.1.4 – 21 May 2024", array(
                "[fixed] Fixed a bug with the immersive sticky notes feature"
        )),
        array("Release 4.0.1.2 – 21 May 2024", array(
                "[fixed] Fixed a bug with focus of the cursor"
        )),
        array("Release 4.0.1.1 – 16 May 2024", array(
                "[fixed] Fixed a bug with the new feature (immersive sticky notes)",
                "[updated] Updated some languages"
        )),
        array("Release 4.0.1 – 15 May 2024", array(
                "[improved] General improvements",
                "[fixed] Some fixes",
                "[new] Added new option (enabled by default): immersive sticky notes",
                "[new] Added Interlingua",
                "[updated] Updated languages"
        )),
        array("Release 4.0 – 3 May 2024", array(
                "[changed] Totally redesigned UI and UX: Settings, All notes and Popup",
                "[new] Notefox Account: you can now synchronize your data between your devices",
                "[new] New options in Settings to customise more your experience",
                "[new] New text-formatting buttons: superscript, subscript, headers, small and big text",
                "[new] New sticky notes themes",
                "[new] Now you can edit the title of the note",
                "[new] New inline-edit feature",
                "[improved] Many other new features, improvements and bug fixes"
        )),
        array("Release 3.11.2 – 21 Mar 2024", array(
                "[new] Add option to disable \"Shantell Sans\" font",
                "[updated] Updated languages"
        )),
        array("Release 3.11, 3.11.1 – 18 Mar 2024", array(
                "[changed] Changed the font-family in a handwriting one",
                "[new] Added new button: insert (or remove) link",
                "[fixed] Fixed an important bug present in the previous release"
        )),
        array("Release 3.10.1 – 27 Feb 2024", array(
                "[new] Added new option, which permits to check notes for all supported protocols"
        )),
        array("Release 3.10 – 19 Feb 2024", array(
                "[new] Sticky-notes also for chromium-based!",
                "[improved] Back-end improvements"
        )),
        array("Release 3.9.3 – 29 Jan 2024", array(
                "[improved] Small improvements when there is more than one window opened"
        )),
        array("Release 3.9.2.2 – 20 Dec 2023", array(
                "[fixed] Fixed a minor bug"
        )),
        array("Release 3.9.2, 3.9.2.1 – 16 Nov 2023", array(
                "[fixed] Fixed some bugs"
        )),
        array("Release 3.9.1.2 – 10 Nov 2023", array(
                "[new] Implemented \"insert link\" (Select text > Ctrl+L)"
        )),
        array("Release 3.9.1.1 – 10 Nov 2023", array(
                "[fixed] Some fixes"
        )),
        array("Release 3.9.1 – 8 Nov 2023", array(
                "[new] Links are now supported! Open them pressing and holding Ctrl and then clicking on the link",
                "[new] Added new colours for the tag",
                "[new] Change tag colour directly from popup",
                "[new] Customise when change the addon icon to \"green\"",
                "[changed] You need to copy/paste an \"A\" HTML tag or insert link via the popup feature"
        )),
        array("Release 3.9 – 3 Nov 2023", array(
                "[new] Now the sizes, opacity and coords parameters are saved for each page"
        )),
        array("Release 3.8 – 25 Oct 2023", array(
                "[fixed] Fixed an important bug which caused an abnormal CPU usage",
                "[new] Export to file (require \"downloads\" permission)",
                "[new] Import from file",
                "[updated] All languages",
                "[fixed] Minor fixes",
                "[improved] Minor backend improvements",
                "[changed] Icons for Import, Export and Save"
        )),
        array("Release 3.7.12 – 24 Oct 2023", array(
                "[changed] Restored local fonts and removed @import from Google CDN",
                "[improved] Improved the sticky-notes",
                "[improved] Backend improvements",
                "[improved] Spellcheck (enabled status) updating in live also for sticky-notes"
        )),
        array("Release 3.7.10, 3.7.11 – 23 Oct 2023", array(
                "[fixed] Fixed important bug: sticky and sticky-minimized didn't work correctly!"
        )),
        array("Release 3.7.9 – 19 Oct 2023", array(
                "[improved] Backend improvements",
                "[improved] UI improvements"
        )),
        array("Release 3.7.8 – 18 Oct 2023", array(
                "[fixed] Fixed a bug with setPosition in unsupported-sticky-notes pages",
                "[new] Added tooltip in aside the search-box",
                "[updated] Languages"
        )),
        array("Release 3.7.7 – 17 Oct 2023", array(
                "[new] You can now disable shortcuts",
                "[improved] Some improvements",
                "[fixed] Fixed some bugs",
                "[new] \"Support the project\" shown after 1000, 5000, etc. openings of popup"
        )),
        array("Release 3.7.6 – 16 Oct 2023", array(
                "[improved] Some improvements",
                "[fixed] Some fixes",
                "[new] Added language spellcheck settable",
                "[new] Added title on text-formatting buttons (hover)",
                "[improved] Now you can navigate also in the sub-domains tab using the Tab button",
                "[new] Added multi-keyword searching (values separated by \";\")"
        )),
        array("Release 3.7.5 – 15 Oct 2023", array(
                "[new] Option to enable/disable notes text word-wrap"
        )),
        array("Release 3.7.4 – 15 Oct 2023", array(
                "[new] Added some console.error messages",
                "[new] Added option to disable text-formatting panel in the bottom of popup"
        )),
        array("Release 3.7.3 – 14 Oct 2023", array(
                "[improved] If you want to paste an unsupported element, now the addon pastes the value as text"
        )),
        array("Release 3.7.2 – 14 Oct 2023", array(
                "[new] Now the notes support more HTML tags (h1-h6, strong, em, code, blockquote, cite, etc.)"
        )),
        array("Release 3.7.1 – 14 Oct 2023", array(
                "[new] Spellcheck feature",
                "[updated] Languages"
        )),
        array("Release 3.7 – 14 Oct 2023", array(
                "[new] Subdomains (Advanced managing: you need to enable on Settings)",
                "[new] Bold, italic, underline, images and advanced navigation (undo and redo)",
                "[improved] Some fixes and improvements"
        )),
        array("Release 3.6 – 12 Oct 2023", array(
                "[improved] Other improvements to the search feature",
                "[fixed] Fixed an important bug with storage"
        )),
        array("Release 3.5 – 5 Oct 2023", array(
                "[improved] Improved search feature",
                "[new] Added page also for the moz-extension protocol",
                "[new] Optional permissions",
                "[improved] Other improvements"
        )),
        array("Release 3.4.1.3 – 28 Aug 2023", array(
                "[fixed] Fixed a graphic bug"
        )),
        array("Release 3.4.1.2 – 28 Aug 2023", array(
                "[improved] Improved All notes: now the active section has a bigger border",
                "[fixed] Fixed a color for the Dark theme"
        )),
        array("Release 3.4.1, 3.4.1.1 – 22 Aug 2023", array(
                "[new] Added title parameter (for now it's not editable)",
                "[new] Added Global and Domain for non-http(s) websites"
        )),
        array("Release 3.4.0.1 – 22 Aug 2023", array(
                "[fixed] Fixed a small bug when viewing all notes"
        )),
        array("Release 3.4 – 21 Aug 2023", array(
                "[new] Theme: Light, Dark and Follow the Firefox theme",
                "[new] Sort by feature in All notes",
                "[new] Global notes: visible in all websites",
                "[new] Filter by tag colour and page type",
                "[new] Welcome page on first install",
                "[updated] Updated all languages and screenshots",
                "[fixed] Fixed some bugs"
        )),
        array("Release 3.3.2 – 14 Aug 2023", array(
                "[new] New buttons",
                "[fixed] Fixed issue with icon caused by wrong managing of sections and queries",
                "[improved] Some improvements"
        )),
        array("Release 3.3.1.3 – 3.3.1.8 – 13 Aug 2023", array(
                "[fixed] Important fix about the previous 3.3"
        )),
        array("Release 3.3.1 – 12 Aug 2023", array(
                "[fixed] Fixed a bug with section and queries",
                "[new] Added Minimize sticky and restore sticky",
                "[improved] Improvements to the UI of Sticky-notes"
        )),
        array("Release 3.3 – 11 Aug 2023", array(
                "[changed] Now it's used sync instead of local, so data should sync with Firefox Accounts!",
                "[new] Implemented function which converts local to sync"
        )),
        array("Release 3.2.1, 3.2.1.1 – 11 Aug 2023", array(
                "[new] Show if it's a page or domain in the sticky-notes",
                "[fixed] Fixed some bugs"
        )),
        array("Release 3.2 – 11 Aug 2023", array(
                "[improved] Sticky-notes follow the default preferences"
        )),
        array("Release 3.1 – 11 Aug 2023", array(
                "[new] Sticky-notes mode available also for domains"
        )),
        array("Release 3.0.1 – 11 Aug 2023", array(
                "[fixed] Some bugs fixed"
        )),
        array("Release 3.0 – 11 Aug 2023", array(
                "[new] New sticky-notes mode (only for pages): customisable size, position, opacity",
                "[fixed] Fixed many bugs",
                "[improved] Improved UI/UX and icons"
        )),
        array("Release 2.3.2 – 29 Mar 2023", array(
                "[fixed] Fixed a bug: the opening with default didn't work anymore"
        )),
        array("Release 2.3.1 – 28 Mar 2023", array(
                "[fixed] Fixed some bugs"
        )),
        array("Release 2.3 – 21 Mar 2023", array(
                "[new] Added shortcuts (open-by-default, open-by-domain, open-by-page)"
        )),
        array("Release 2.2.1, 2.2.2 – 18 Nov 2022", array(
                "[fixed] Fixed a bug in the last release"
        )),
        array("Release 2.2 – 17 Nov 2022", array(
                "[new] Added new option in Settings"
        )),
        array("Release 2.1.1 – 20 Oct 2022", array(
                "[improved] Improved search feature",
                "[new] Added \"Translate on Crowdin\" button in Settings",
                "[new] Added \"Settings\" button in All notes"
        )),
        array("Release 2.1 – 18 Oct 2022", array(
                "[new] Added search box feature",
                "[new] Added filter by feature",
                "[updated] Updated all languages",
                "[improved] Other minor improvements"
        )),
        array("Release 2.0 – 13 Oct 2022", array(
                "[improved] Improved the exporting and importing process",
                "[new] Added Settings! Go to Add-ons and themes > Notefox > Settings",
                "[changed] Updated main color (orange!) and icon"
        )),
        array("Release 1.6.0.2 – 15 Dec 2021", array(
                "[updated] Updated all languages (added Spanish)"
        )),
        array("Release 1.3.0.1 – 9 Aug 2021", array(
                "[fixed] Fixed strings"
        )),
        array("Release 1.3 – 3 Aug 2021", array(
                "[new] Added \"Clear notes\" for a specific domain and its pages",
                "[new] Added \"Clear notes\" for a specific page",
                "[new] A different extension-icon when there is a note",
                "[improved] If the notes field is empty, it's removed from the notes list"
        )),
        array("Release 1.2 – 1 Aug 2021", array(
                "[new] Added Import, Export features",
                "[new] Added confirmation dialog before to clear all notes",
                "[changed] New icon"
        )),
        array("Release 1.1 – 31 Jul 2021", array(
                "[new] Added \"All notes\" dedicated page",
                "[fixed] Fixed some bugs"
        )),
        array("Release 1.0 – 30 Jul 2021", array(
                "[new] First release of the app"
        )),
);
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1>News</h1>
        <p>All changes to the add-on, grouped by release.</p>

        <div class="stack-sm" style="margin-top: 24px;">
            <?php foreach ($releases as $release): ?>
                <div class="accordion-item">
                    <button class="accordion-trigger" aria-expanded="false">
                        <?php echo htmlspecialchars($release[0]); ?>
                    </button>
                    <div class="accordion-body" aria-hidden="true">
                        <div class="accordion-content">
                            <ul>
                                <?php foreach ($release[1] as $item): ?>
                                    <li><?php echo render_release_item($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
