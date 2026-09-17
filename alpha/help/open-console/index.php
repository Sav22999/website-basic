<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to open the Console panel – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to open the console panel</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2023-06-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> All browsers</span>
        </div>
        <p>
            The Console panel is a tool included in your web browser that allows you to view and debug some developer
            data. This data is useful for developers to understand how the application works and to identify any
            problems.
        </p>

        <h2>Firefox users</h2>
        <ol>
            <li>Open the "hamburger" menu (three horizontal lines) in the top right corner of the browser window.</li>
            <li>Select "More tools" from the menu.</li>
            <li>Click on "Web Developer Tools"</li>
            <li>In the Web Developer Tools panel, click on the "Console" tab.</li>
        </ol>

        <h2>Chrome users</h2>
        <ol>
            <li>Open the "kebab" menu (three vertical dots) in the top right corner of the browser window.</li>
            <li>Select "More tools" from the menu.</li>
            <li>Click on "Developer tools"</li>
            <li>In the Developer Tools panel, click on the "Console" tab.</li>
        </ol>

        <h2>Edge users</h2>
        <ol>
            <li>Open the "meatball" menu (three horizontal dots) in the top right corner of the browser window.</li>
            <li>Select "More tools" from the menu.</li>
            <li>Click on "Developer tools"</li>
            <li>In the Developer Tools panel, click on the "Console" tab.</li>
        </ol>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
