<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('errors.401_title');
$noindex = true;
$selected_menu = "";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

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
        </style>

        <div class="error-page">
            <div class="error-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <div class="error-code"><?php echo t('errors.401_code'); ?></div>
            <h1><?php echo t('errors.401_heading'); ?></h1>
            <p class="error-message"><?php echo t('errors.401_message'); ?></p>
            <div class="error-actions">
                <a href="/" class="btn"><?php echo t('errors.go_to_homepage'); ?></a>
                <button onclick="history.back()" class="btn btn--secondary"><?php echo t('common.go_back'); ?></button>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
