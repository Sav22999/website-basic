<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Unauthorized – Notefox";
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
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
            </div>
            <div class="error-code">401</div>
            <h1>Unauthorized</h1>
            <p class="error-message">You need to sign in to access this page.</p>
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
