<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Local data storage – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Where your data is stored locally</h1>
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
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> All versions</span>
        </div>
        <p>
            Data saved locally with Notefox is stored in the browser's local storage. This means that the data is
            stored on your device as plain text: data on your device is not encrypted! This is why it is better
            to not save sensitive data with Notefox.
        </p>
        <p>
            <strong>Pay attention</strong>: data synced with a Notefox Account is stored in the cloud, and it is
            encrypted
            server-side.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
