<?php
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store");
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
include_once($root_path . "/include/savaccount.php");
$title = t('savaccount.title');
$canonical_path = "/my/savaccount/";
$noindex = true;
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <div class="auth-card" aria-live="polite">

            <div id="savaccount-working" class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <p><?php echo te('savaccount.loading'); ?></p>
            </div>

            <div id="savaccount-elsewhere" class="auth-header hidden2">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('savaccount.elsewhere_heading'); ?></h1>
                <p><?php echo te('savaccount.elsewhere_desc'); ?></p>
            </div>

            <div id="savaccount-problem" class="hidden2">
                <div class="auth-header">
                    <img src="/images/icon.svg" alt="" width="48" height="48">
                    <h1>SavAccount</h1>
                </div>
                <div id="savaccount-problem-message" class="form-message hidden2" role="alert"></div>
                <div class="form-actions">
                    <a href="/my/login/" id="savaccount-retry"
                       class="btn btn--block"><?php echo te('savaccount.try_again'); ?></a>
                </div>
            </div>

            <div id="savaccount-link" class="hidden2">
                <div class="auth-header">
                    <img src="/images/icon.svg" alt="" width="48" height="48">
                    <h1 id="savaccount-link-heading"></h1>
                    <p id="savaccount-link-desc"></p>
                </div>
                <form id="savaccount-link-form" class="form-container">
                    <div id="savaccount-link-message" class="form-message hidden2" role="alert"></div>
                    <input type="email" id="savaccount-link-email" autocomplete="username" readonly hidden>
                    <div class="form-field">
                        <label class="form-label"
                               for="savaccount-link-password"><?php echo te('savaccount.password'); ?></label>
                        <input class="form-input" type="password" id="savaccount-link-password"
                               autocomplete="current-password" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn--block" id="savaccount-link-submit"></button>
                    </div>
                </form>
            </div>

            <div id="savaccount-signup" class="hidden2">
                <div class="auth-header">
                    <img src="/images/icon.svg" alt="" width="48" height="48">
                    <h1><?php echo te('account.signup_heading'); ?></h1>
                    <p id="savaccount-signup-desc"></p>
                </div>
                <form id="savaccount-signup-form" class="form-container" novalidate>
                    <div id="savaccount-signup-message" class="form-message hidden2" role="alert"></div>
                    <input type="email" id="savaccount-signup-email" autocomplete="username" readonly hidden>
                    <div class="form-field">
                        <label class="form-label"
                               for="savaccount-signup-username"><?php echo te('account.signup_username'); ?></label>
                        <input class="form-input" type="text" id="savaccount-signup-username" maxlength="256"
                               autocomplete="nickname" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label"
                               for="savaccount-signup-password"><?php echo te('common.password'); ?></label>
                        <input class="form-input" type="password" id="savaccount-signup-password" minlength="8"
                               autocomplete="new-password" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label"
                               for="savaccount-signup-confirm"><?php echo te('account.signup_confirm_password'); ?></label>
                        <input class="form-input" type="password" id="savaccount-signup-confirm"
                               autocomplete="new-password" required>
                    </div>
                    <p class="contact-consent"><?php echo t('account.signup_consent'); ?></p>
                    <div class="form-actions">
                        <button type="submit"
                                class="btn btn--block"><?php echo te('account.signup_submit'); ?></button>
                    </div>
                </form>
                <p class="auth-footer"><a href="/my/login/"><?php echo te('savaccount.other_account'); ?></a></p>
            </div>

        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
<script src="/js/account.js"></script>
<?php savaccount_scripts(); ?>
<script>
    document.addEventListener("DOMContentLoaded", savaccountFinish);
</script>
</body>
</html>
