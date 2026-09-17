<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to download the Error logs file – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to download the error logs</h1>
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
            Since the 4.4.4 version, Notefox has a new feature that allows you to download the error logs file. This
            file contains all the errors that Notefox has encountered during its execution. This feature is useful
            for debugging and troubleshooting issues with the add-on.
        </p>
        <p>
            In particular, it is useful for the Notefox developer to understand the problems you are experiencing
            and to
            provide you with the best possible support for your issue and to fix it and improve the add-on.
        </p>
        <p>
            <strong>Download the error logs</strong>:
        </p>
        <ol>
            <li>Open the Settings page (of Notefox)</li>
            <li>Go to the "Advanced" section</li>
            <li>Find the "Error logs" subsection</li>
            <li>Click on the "Show error logs" button</li>
            <li>A new popup panel will open with the error logs, there click on the "Download the logs file" button</li>
            <li>A new file will be downloaded to your computer with the name "notefox_error_logs_XYZ.json"</li>
        </ol>
        <p>
            The downloaded file contains only information about the errors that Notefox has encountered during its
            execution. It does not contain any personal information or sensitive data, although it's advisable to
            share it only with the Notefox developer or trusted people.
        </p>
        <p>
            In particular, the file contains the following information:
        </p>
        <ul>
            <li>Notefox information (version, os, browser)</li>
            <li>Settings configuration (all settings)</li>
            <li>Errors details (datetime, context, error message and web page where the error occurred)</li>
        </ul>
        <p>
            <strong>All the information contained and sent, will be treated with the utmost confidentiality and will not
                be
                shared with third parties.</strong>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
