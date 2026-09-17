<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Install – Notefox";
$selected_menu = "install";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container text-center">
        <h1>Install Notefox</h1>
        <p class="page-subtitle">Select your browser to get started</p>

        <div class="install-grid">
            <a href="https://addons.mozilla.org/firefox/addon/websites-notes/" class="install-card" target="_blank" rel="noopener">
                <span class="install-card-icon icon-mask icon-mask--firefox" aria-hidden="true"></span>
                <span>Mozilla Firefox</span>
            </a>
            <a href="https://chromewebstore.google.com/detail/agcdffobijddcccbfnhfjmaohnljefpm" class="install-card" target="_blank" rel="noopener">
                <span class="install-card-icon icon-mask icon-mask--chrome" aria-hidden="true"></span>
                <span>Google Chrome</span>
            </a>
            <a href="https://microsoftedge.microsoft.com/addons/detail/lkahmkadpaibphpoiofpdinacjffddda" class="install-card" target="_blank" rel="noopener">
                <span class="install-card-icon icon-mask icon-mask--edge" aria-hidden="true"></span>
                <span>Microsoft Edge</span>
            </a>
        </div>

        <hr>

        <p style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 12px;">You can also get the source code from GitHub</p>
        <a href="https://github.com/Sav22999/websites-notes" class="btn btn--secondary" target="_blank" rel="noopener">View on GitHub</a>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
