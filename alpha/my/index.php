<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('account.landing_title');
$description = t('meta.account');
$canonical_path = "/alpha/my/";
$noindex = true;
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">

        <div id="account-loading" class="text-center" style="padding: 80px 0;">
            <span class="spinner" style="width: 28px; height: 28px; border-width: 3px;"></span>
        </div>

        <div id="account-content" style="display: none;">
            <div class="auth-card">
                <div class="auth-header">
                    <img src="/images/icon.svg" alt="" width="48" height="48">
                    <h1><?php echo t('account.landing_heading'); ?></h1>
                    <p><?php echo t('account.landing_desc'); ?></p>
                </div>

                <div class="form-actions" style="gap: 10px;">
                    <a href="/alpha/my/login/" class="btn btn--block"><?php echo te('account.log_in'); ?></a>
                    <a href="/alpha/my/signup/" class="btn btn--secondary btn--block"><?php echo te('account.create_account'); ?></a>
                </div>
            </div>

            <style>
                .account-benefits {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 16px;
                    margin-top: 32px;
                }

                .account-benefit {
                    text-align: center;
                    padding: 20px 16px;
                    background: var(--color-surface);
                    border: 1px solid var(--color-border);
                    border-radius: var(--radius-md);
                }

                .account-benefit-icon {
                    width: 28px;
                    height: 28px;
                    margin: 0 auto 10px;
                    color: var(--color-text-muted);
                }

                .account-benefit-title {
                    font-weight: 600;
                    font-size: 0.9375rem;
                    margin-bottom: 4px;
                }

                .account-benefit-desc {
                    font-size: 0.8125rem;
                    color: var(--color-text-muted);
                    margin: 0;
                    text-align: left;
                    line-height: 1.5;
                }

                @media (max-width: 640px) {
                    .account-benefits {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <div class="account-benefits">
                <div class="account-benefit">
                    <svg class="account-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                        <line x1="6" y1="6" x2="6.01" y2="6"/>
                        <line x1="6" y1="18" x2="6.01" y2="18"/>
                    </svg>
                    <div class="account-benefit-title"><?php echo te('account.benefit_sync_title'); ?></div>
                    <p class="account-benefit-desc"><?php echo te('account.benefit_sync_desc'); ?></p>
                </div>
                <div class="account-benefit">
                    <svg class="account-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <div class="account-benefit-title"><?php echo te('account.benefit_private_title'); ?></div>
                    <p class="account-benefit-desc"><?php echo te('account.benefit_private_desc'); ?></p>
                </div>
                <div class="account-benefit">
                    <svg class="account-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <div class="account-benefit-title"><?php echo te('account.benefit_free_title'); ?></div>
                    <p class="account-benefit-desc"><?php echo te('account.benefit_free_desc'); ?></p>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var loading = document.getElementById("account-loading");
        var content = document.getElementById("account-content");

        if (typeof hasSession === "function" && hasSession()) {
            location.href = "/alpha/my/account/";
            return;
        }

        loading.style.display = "none";
        content.style.display = "";
    });
</script>
</body>
</html>
