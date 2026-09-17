<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Notefox 4.0 – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Notefox 4.0</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2022-11-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0</span>
        </div>
        <p>
            A very big update of Notefox is coming: Notefox 4.0. This update will be the biggest update of Notefox
            since its creation, and it will bring a lot of new features and improvements. Below you can see the main
            new features of Notefox 4.0.
        </p>

        <h2>Totally redesigned UI: Settings, All notes and Popup</h2>
        <p>
            It's easier find Settings with the new organised UI. There are improvements in the All notes page and
            in the Popup as well.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/new-ui-settings.gif" width="80%" title="New UI - Settings"
                     alt="New UI - Settings">
                <small>Settings</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/new-ui-all-notes.gif" width="80%" title="New UI - All notes"
                     alt="New UI - All notes">
                <small>All notes</small>
            </span>
        </p>

        <h2>Notefox Account</h2>
        <p>
            You can now synchronize your notes between your devices with a Notefox Account. It is optional and
            totally free<small><sup>*</sup></small>.
        </p>
        <p>
            If you are also logged in with a Firefox Account, so Notefox Account will be automatically sync with all
            your
            devices the first time you log in with a Notefox Account<small><sup>**</sup></small>.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/notefox-account.gif" width="80%" title="Notefox Account"
                     alt="Notefox Account">
                <small>Notefox Account</small>
            </span>
        </p>

        <h2>New options to customise more your experience</h2>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/show-text-formatting-buttons.png" width="80%"
                     title="Show text-formatting buttons" alt="Show text-formatting buttons">
                <small>More text-formatting buttons --added superscript, subscript, headers (h1, ..., h6), small and
                    big text-- and now you can choose what text-formatting buttons show on popup</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/show-title-textbox.png" width="80%" title="Show title textbox"
                     alt="Show title textbox">
                <img src="/help/notefox-4.0/popup-with-title-textbox.png" width="20%" title="Popup with title textbox"
                     alt="Popup with title textbox">
                <small>Show title textbox in popup, to customise notes title</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/choose-sticky-notes-preview.gif" width="80%"
                     title="Choose sticky notes theme" alt="Choose sticky notes theme">
                <small>Choose the sticky notes theme</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/choose-theme-preview.gif" width="80%" title="Choose popup theme"
                     alt="Choose popup theme">
                <small>Now you can choose the addon theme with the theme preview</small>
            </span>
        </p>

        <h2>Import, Export and Delete all data moved</h2>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-4.0/data-and-sync.png" width="80%" title="Import, export and delete all data"
                     alt="Import, export and delete all data">
                <small>Import, export and delete all data features are moved to the Settings page --in the Data & Sync section-- from All notes
                    page</small>
            </span>
        </p>
        <p>
            <small><sup>*</sup> See the <a href="/alpha/privacy/">Privacy Policy</a> and the <a href="/alpha/terms/">Terms
                    of Service</a> for more information.</small>
        </p>
        <p>
            <small><sup>**</sup> The token and the login-id used for requests with Notefox Account are saved on the
                sync storage of Firefox, so you don't need to log in with a Notefox Account on all your devices.</small>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
