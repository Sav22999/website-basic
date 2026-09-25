<html>
<head>
    <?php
    $title = "My account – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php");
    ?>
    <script src="/js/account.js"></script>
</head>
<body>
<?php
$selected_menu = "my";
include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">My account</h1>
            <p class="center">
                Signed in as <strong id="account-username">...</strong>
            </p>

            <div id="account-message" class="form-message hidden2"></div>

            <div class="account-section">
                <h2>Two-step verification</h2>
                <div id="otp-status" class="account-status">Checking...</div>
                <p>
                    When it is enabled, every login also needs a code sent to your email address.
                    Changing your password and deleting your account always need a code, whatever this setting.
                </p>
                <a href="/old/my/account/two-factor/" class="button">Manage two-step verification</a>
            </div>

            <div class="account-links">
                <a href="/old/my/account/sync-history/" class="button button-secondary">Sync history</a>
                <a href="/old/my/account/password/" class="button button-secondary">Change password</a>
                <a href="/old/my/account/delete/" class="button button-secondary">Delete account</a>
                <button type="button" id="logout-button" class="button button-secondary">Log out</button>
            </div>
        </div>
    </div>
</main>

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
                location.href = "/old/my/";
            });
        });
    });
</script>
</body>
</html>

<?php
?>
