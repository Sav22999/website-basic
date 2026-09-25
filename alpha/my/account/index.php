<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('account.dashboard_title');
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1 class="text-center"><?php echo te('account.dashboard_heading'); ?></h1>
        <p class="text-center">
            <?php echo te('account.signed_in_as'); ?> <strong id="account-username">...</strong>
        </p>

        <div id="account-message" class="form-message hidden2"></div>

        <div class="account-cards">
            <a href="/alpha/my/account/notes/" class="account-card">
                <svg class="account-card-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                </svg>
                <span class="account-card-title"><?php echo te('account.synced_notes'); ?></span>
                <p class="account-card-desc"><?php echo te('account.synced_notes_desc'); ?></p>
            </a>
            <a href="/alpha/my/account/sync-history/" class="account-card">
                <svg class="account-card-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <span class="account-card-title"><?php echo te('account.sync_history'); ?></span>
                <p class="account-card-desc"><?php echo te('account.sync_history_desc'); ?></p>
            </a>
            <div class="account-card account-card--static" id="otp-card">
                <div class="account-card--static-header">
                    <svg class="account-card-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span class="account-card-title"><?php echo te('account.otp_title'); ?></span>
                    <span id="otp-status" class="account-status"><?php echo te('js.checking'); ?></span>
                </div>
                <div class="account-card--static-body">
                    <div class="account-card--static-inner">
                        <p class="account-card-desc"><?php echo t('account.otp_when_enabled'); ?></p>
                        <a href="/alpha/my/account/two-factor/" class="btn btn--secondary btn--small"><?php echo te('account.otp_manage'); ?></a>
                    </div>
                </div>
            </div>
            <div class="account-card account-card--static" id="login-id-card">
                <div class="account-card--static-header">
                    <svg class="account-card-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="account-card-title">Login ID</span>
                </div>
                <div class="account-card--static-body">
                    <div class="account-card--static-inner">
                        <p class="account-card-desc"><?php echo t('account.login_id_desc'); ?></p>
                        <div class="login-id-box">
                            <code id="account-login-id">...</code>
                            <button type="button" id="copy-login-id" class="login-id-copy" title="<?php echo te('account.login_id_copy'); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="account-actions">
            <a href="/alpha/my/account/password/" class="btn btn--secondary"><?php echo te('account.change_password'); ?></a>
            <a href="/alpha/my/account/delete/" class="btn btn--danger"><?php echo te('account.delete_account'); ?></a>
            <button type="button" id="logout-button" class="btn btn--secondary"><?php echo te('account.log_out'); ?></button>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var session = requireSession();
        if (!session) {
            return;
        }

        document.getElementById("account-username").textContent = session["username"] || "user";
        document.getElementById("account-login-id").textContent = session["login-id"] || "—";

        document.querySelectorAll(".account-card--static").forEach(function (card) {
            card.querySelector(".account-card--static-header").addEventListener("click", function () {
                card.classList.toggle("expanded");
            });
        });

        document.getElementById("copy-login-id").addEventListener("click", function () {
            var id = session["login-id"];
            if (!id) return;
            navigator.clipboard.writeText(id).then(function () {
                var btn = document.getElementById("copy-login-id");
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                setTimeout(function () {
                    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
                }, 1500);
            });
        });

        var statusElement = document.getElementById("otp-status");

        notefoxAuthenticatedApi("/otp/status", {}).then(function (data) {
            var enabled = data["otp-enabled"];
            statusElement.textContent = enabled ? "Enabled" : "Disabled";
            statusElement.classList.toggle("account-status--off", !enabled);
        }).catch(function (error) {
            statusElement.textContent = "Unknown";
            statusElement.classList.add("account-status--off");
            showFormMessage("account-message", notefoxErrorMessage(error), true);
        });

        document.getElementById("logout-button").addEventListener("click", function () {
            logoutSession().then(function () {
                location.href = "/alpha/my/";
            });
        });
    });
</script>
</body>
</html>
