<html>
<head>
    <?php
    $title = "Get help: Notefox 4.6 – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-notefox-6-6";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Notefox 4.6</h1>
            <p>
                A view of the new features and changes in Notefox 4.6. This version is rich in new features,
                improvements and bug fixes.
            </p>
            <h2>You can resize your popup width</h2>
            <p>
                You can now resize the popup width dragging the bottom-right corner of the popup.
                <br>
                <span class="notefox-new-version-page">
                    <img src="enable-resizing-popup.gif" width="80%" title="Enable resizing popup"/>
                    <br>
                    <small>Settings > Enable resizing popup</small>
                </span>
                <br><br>
                <span class="notefox-new-version-page">
                    <img src="resizing-popup.gif" width="80%" title="Resizing popup"/>
                    <br>
                    <small>Popup > Resizing popup</small>
                </span>
            </p>
            <h2>Fullscreen notes</h2>
            <p>
                You can view notes in fullscreen, after enabled the option in Settings.
                <br>
                <span class="notefox-new-version-page">
                    <img src="fullscreen.gif" width="80%" title="Fullscreen notes"/>
                    <br>
                    <small>All notes > Fullscreen</small>
                </span>
            </p>
            <h2>Toolbar badge</h2>
            <p>
                Now, you can enable the toolbar badge number to know how many notes are saved – for the currect page,
                domain, global or subpage.
                <br>
                <span class="notefox-new-version-page">
                    <img src="enable-toolbar-badge.gif" width="80%" title="Enable toolbar badge"/>
                    <br>
                    <small>Settings > Enable badge number</small>
                </span>
            </p>
            <h2>Clear formatting button</h2>
            <p>
                New button to clear formatting: you need to enable the correct option in Settings before.
                <br>
                <span class="notefox-new-version-page">
                    <img src="clear-formatting.gif" width="80%" title="Clear formatting button"/>
                    <br>
                    <small>Popup > Clear formatting</small>
                </span>
            </p>
            <h2>Change font-size notes</h2>
            <p>
                You can change the font-size in notes.
                <br>
                <span class="notefox-new-version-page">
                    <img src="change-font-size.gif" width="80%" title="Change font-size notes"/>
                    <br>
                    <small>Settings > Font-size</small>
                </span>
            </p>
            <h2>Change API endpoint server</h2>
            <p>
                <b>Warning</b>: This is a dangerous feature. Use it only if you know what you're doing.
                <br>
                <span class="notefox-new-version-page">
                    <img src="change-api-endpoint.gif" width="80%" title="Change API endpoint server"/>
                    <br>
                    <small>Settings > Change API Endpoint</small>
                </span>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>