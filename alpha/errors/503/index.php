<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('errors.503_title');
$noindex = true;
$selected_menu = "";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container text-center">
        <style>
            .error-page {
                padding: 48px 0;
            }

            .error-code {
                font-size: 6rem;
                font-weight: 700;
                line-height: 1;
                color: var(--color-primary);
                margin-bottom: 8px;
            }

            .error-icon {
                margin-bottom: 24px;
                color: var(--color-text-muted);
            }

            .error-message {
                font-size: 1.125rem;
                color: var(--color-text-muted);
                margin-bottom: 32px;
                max-width: 400px;
                margin-left: auto;
                margin-right: auto;
            }

            .error-actions {
                display: flex;
                gap: 12px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .error-icon svg {
                animation: gearSpin 60s linear infinite;
            }

            @keyframes gearSpin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>

        <div class="error-page">
            <div class="error-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </div>
            <div class="error-code"><?php echo t('errors.503_code'); ?></div>
            <h1><?php echo t('errors.503_heading'); ?></h1>
            <p class="error-message"><?php echo t('errors.503_message'); ?></p>
            <div class="error-actions">
                <button onclick="location.reload()" class="btn"><?php echo t('errors.refresh'); ?></button>
                <a href="https://t.me/sav_projects/7" class="btn btn--secondary" target="_blank"
                   rel="noopener"><?php echo t('errors.service_status'); ?></a>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
