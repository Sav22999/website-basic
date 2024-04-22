<html>
<head>
    <?php
    $title = "Get help: Notefox 4.0 – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-translate";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Notefox 4.0</h1>
            <p>
                A very big update of Notefox is coming: Notefox 4.0. This update will be the biggest update of Notefox
                since its creation, and it will bring a lot of new features and improvements. Below you can see the main
                new features of Notefox 4.0.
            </p>
            <h2>Totally redesigned UI: Settings, All notes and Popup</h2>
            <p>
                It's easier find Settings with the new organised UI. There are improvements in the All notes page and
                in the Popup as well.
                <br>
                <span class="notefox-4-0-page">
                    <img src="new-ui-settings.gif" width="80%" title="New UI - Settings"/>
                    <br>
                    <small>Settings</small>
                </span>
                <br><br>
                <span class="notefox-4-0-page">
                    <img src="new-ui-all-notes.gif" width="80%" title="New UI - All notes"/>
                    <br>
                    <small>All notes</small>
                </span>
            </p>
            <h2>Notefox Account</h2>
            <p>
                You can now synchronize your notes between your devices with a Notefox Account. It is optional and
                totally free<small><sup>*</sup></small>.
                <br>
                If you are also logged in with a Firefox Account, so Notefox Account will be automatically sync with all your
                devices the first time you log in with a Notefox Account<small><sup>**</sup></small>.
                <br>
                <span class="notefox-4-0-page">
                    <img src="notefox-account.gif" width="80%" title="Notefox Account"/>
                    <br>
                    <small>Notefox Account</small>
                </span>
            </p>
            <h2>New options to customise more your experience</h2>
            <p>
                <span class="notefox-4-0-page">
                    <img src="show-text-formatting-buttons.png" width="80%" title="Show text-formatting buttons"/>
                    <br>
                    <small>More text-formatting buttons –added superscript, subscript, headers (h1, …, h6), small and
                        big text– and now you can choose what text-formatting buttons show on popup</small>
                </span>
                <br>
                <span class="notefox-4-0-page">
                    <img src="show-title-textbox.png" width="80%" title="Show title textbox"/>
                    <br>
                    <img src="popup-with-title-textbox.png" width="20%" title="Popup with title textbox"/>
                    <br>
                    <small>Show title textbox in popup, to customise notes title</small>
                </span>
                <br>
                <span class="notefox-4-0-page">
                    <img src="choose-sticky-notes-preview.gif" width="80%" title="Choose sticky notes theme"/>
                    <br>
                    <small>Choose the sticky notes theme</small>
                </span>
                <br>
                <span class="notefox-4-0-page">
                    <img src="choose-theme-preview.gif" width="80%" title="Choose popup theme"/>
                    <br>
                    <small>Now you can choose the addon theme with the theme preview</small>
                </span>
            </p>
            <h2>Import, Export and Delete all data moved</h2>
            <p>
                <span class="notefox-4-0-page">
                    <img src="data-and-sync.png" width="80%" title="Import, export and delete all data"/>
                    <br>
                    <small>Import, export and delete all data features are moved to the Settings page –in the Data & Sync section– from All notes
                        page</small>
                </span>
            </p>
            <p>
                <small><sup>*</sup> See the <a href="/help/privacy/">Privacy Policy</a> and the <a href="/terms/">Terms
                        of Service</a> for more information.</small>
                <br>
                <small><sup>**</sup> The token and the login-id used for requests with Notefox Account are saved on the
                    sync storage of Firefox, so you don't need to log in with a Notefox Account on all your devices.</small>

            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>