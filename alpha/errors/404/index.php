<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Page not found – Notefox";
$selected_menu = "";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container text-center">
        <style>
            .error-page { padding: 48px 0; }
            .error-code { font-size: 6rem; font-weight: 700; line-height: 1; color: var(--color-primary); margin-bottom: 8px; }
            .error-icon { margin-bottom: 24px; color: var(--color-text-muted); }
            .error-message { font-size: 1.125rem; color: var(--color-text-muted); margin-bottom: 32px; max-width: 400px; margin-left: auto; margin-right: auto; }
            .error-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        </style>

        <div class="error-page">
            <div class="error-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
                </svg>
            </div>
            <div class="error-code">404</div>
            <h1>Page not found</h1>
            <p class="error-message">The page you are looking for doesn't exist or has been moved.</p>
            <div class="error-actions">
                <a href="/alpha/" class="btn">Go to homepage</a>
                <button onclick="history.back()" class="btn btn--secondary">Go back</button>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
