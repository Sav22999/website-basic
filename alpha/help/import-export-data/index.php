<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to import and export data – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to import and export data</h1>
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
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0+</span>
        </div>
        <p>
            In Notefox 4.0 "import" and "export" features have been moved to the Settings page. You can import and
            export data in JSON format. You can also import and export data from/to a JSON file.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/import-export-data/import.gif" width="80%" title="Import data" alt="Import data">
                <small>Import data</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/import-export-data/export.gif" width="80%" title="Export data" alt="Export data">
                <small>Export data</small>
            </span>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
