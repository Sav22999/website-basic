<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to delete the Error logs file – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to delete the error logs</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2023-09-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.4.4+</span>
        </div>
        <p>
            Since the 4.4.4 version, Notefox has a new feature that allows you to download the error logs file.
        </p>
        <p>
            If you are looking for the article on how to download the error logs file, please go to the
            <a href="/alpha/help/download-error-logs/">Download the error logs file</a> page.
        </p>
        <p>
            Sometimes, the error logs file can become very large, especially if you have been using Notefox for a long
            time
            and you have encountered many errors. In this case, it may be useful to delete the error logs file to
            free up space on your computer.
        </p>
        <p>
            <strong>Delete the error logs file</strong>
        </p>
        <ol>
            <li>Open the Settings page (of Notefox)</li>
            <li>Go to the "Advanced" section</li>
            <li>Find the "Error logs" subsection</li>
            <li>Click on the "Delete the error logs" button</li>
            <li>Confirm the deletion by clicking on "OK" in the pop-up window that appears</li>
        </ol>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
