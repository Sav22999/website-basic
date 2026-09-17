<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Notefox 4.6 – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Notefox 4.6</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2024-06-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.6</span>
        </div>
        <p>
            A view of the new features and changes in Notefox 4.6. This version is rich in new features,
            improvements and bug fixes.
        </p>

        <h2>You can resize your popup width</h2>
        <p>
            You can now resize the popup width dragging the bottom-right corner of the popup.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/enable-resizing-popup.gif" width="80%" title="Enable resizing popup"
                     alt="Enable resizing popup">
                <small>Settings > Enable resizing popup</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/resizing-popup.gif" width="80%" title="Resizing popup" alt="Resizing popup">
                <small>Popup > Resizing popup</small>
            </span>
        </p>

        <h2>Fullscreen notes</h2>
        <p>
            You can view notes in fullscreen, after enabled the option in Settings.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/fullscreen.gif" width="80%" title="Fullscreen notes" alt="Fullscreen notes">
                <small>All notes > Fullscreen</small>
            </span>
        </p>

        <h2>Toolbar badge</h2>
        <p>
            Now, you can enable the toolbar badge number to know how many notes are saved -- for the currect page,
            domain, global or subpage.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/enable-toolbar-badge.gif" width="80%" title="Enable toolbar badge"
                     alt="Enable toolbar badge">
                <small>Settings > Enable badge number</small>
            </span>
        </p>

        <h2>Clear formatting button</h2>
        <p>
            New button to clear formatting: you need to enable the correct option in Settings before.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/clear-formatting.gif" width="80%" title="Clear formatting button"
                     alt="Clear formatting button">
                <small>Popup > Clear formatting</small>
            </span>
        </p>

        <h2>Change font-size notes</h2>
        <p>
            You can change the font-size in notes.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/change-font-size.gif" width="80%" title="Change font-size notes"
                     alt="Change font-size notes">
                <small>Settings > Font-size</small>
            </span>
        </p>

        <h2>Change API endpoint server</h2>
        <p>
            <strong>Warning</strong>: This is a dangerous feature. Use it only if you know what you're doing.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-4.6/change-api-endpoint.gif" width="80%" title="Change API endpoint server"
                     alt="Change API endpoint server">
                <small>Settings > Change API Endpoint</small>
            </span>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
