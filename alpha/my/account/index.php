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

        <div class="account-section">
            <h2><?php echo te('account.otp_title'); ?></h2>
            <div id="otp-status" class="account-status"><?php echo te('js.checking'); ?></div>
            <p>
                <?php echo t('account.otp_when_enabled'); ?>
            </p>
            <a href="/alpha/my/account/two-factor/" class="btn"><?php echo te('account.otp_manage'); ?></a>
        </div>

        <div class="account-links">
            <a href="/alpha/my/account/sync-history/" class="btn btn--secondary"><?php echo te('account.sync_history'); ?></a>
            <a href="/alpha/my/account/password/" class="btn btn--secondary"><?php echo te('account.change_password'); ?></a>
            <a href="/alpha/my/account/delete/" class="btn btn--secondary"><?php echo te('account.delete_account'); ?></a>
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
