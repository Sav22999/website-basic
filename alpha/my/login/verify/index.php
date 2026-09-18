<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('account.login_verify_title');
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/login/" class="back-link"><?php echo te('account.back_to_login'); ?></a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('account.login_verify_heading'); ?></h1>
                <p><?php echo t('account.login_verify_desc'); ?></p>
            </div>

            <form id="login-verify-form" class="form-container">
                <div id="login-verify-message" class="form-message hidden2"></div>

                <div class="form-field hidden2" id="login-verify-email-field">
                    <label class="form-label" for="login-verify-email"><?php echo te('common.email'); ?></label>
                    <input class="form-input" type="email" id="login-verify-email" name="email" maxlength="320">
                </div>

                <div class="form-field hidden2" id="login-verify-password-field">
                    <label class="form-label" for="login-verify-password"><?php echo te('common.password'); ?></label>
                    <input class="form-input" type="password" id="login-verify-password" name="password">
                </div>

                <div class="form-field" id="login-verify-code-field">
                    <label class="form-label" for="login-verify-code"><?php echo te('account.signup_verify_code'); ?></label>
                    <input class="form-input" type="text" id="login-verify-code" name="verification-code" maxlength="64"
                           required autofocus>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block" id="login-verify-submit"><?php echo te('account.login_verify_submit'); ?></button>
                </div>
            </form>

            <p class="auth-footer">
                <?php echo t('account.did_not_receive_code'); ?> <a href="#" id="login-resend-code"><?php echo te('account.send_new_code'); ?></a>
            </p>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (hasSession()) {
            location.href = "/alpha/my/account/";
            return;
        }

        var pendingRaw = sessionStorage.getItem("notefox-pending-login");
        var pending = null;
        try {
            pending = pendingRaw ? JSON.parse(pendingRaw) : null;
        } catch (e) {
            pending = null;
        }

        if (!pending || !pending["login-id"]) {
            location.href = "/alpha/my/login/";
            return;
        }

        var emailInput = document.getElementById("login-verify-email");
        var passwordInput = document.getElementById("login-verify-password");
        var codeInput = document.getElementById("login-verify-code");
        var emailField = document.getElementById("login-verify-email-field");
        var passwordField = document.getElementById("login-verify-password-field");

        if (pending["email"]) {
            emailInput.value = pending["email"];
        }
        if (pending["password"]) {
            passwordInput.value = pending["password"];
        }

        if (!emailInput.value || !passwordInput.value) {
            emailField.classList.remove("hidden2");
            passwordField.classList.remove("hidden2");
            emailInput.required = true;
            passwordInput.required = true;
        }

        var form = document.getElementById("login-verify-form");
        var submitButton = document.getElementById("login-verify-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("login-verify-message");

            var email = emailInput.value.trim();
            var password = passwordInput.value;
            var code = codeInput.value.trim();

            if (email === "" || password === "" || code === "") {
                showFormMessage("login-verify-message", "Please fill in all the fields.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/login/verify", {
                "login-id": pending["login-id"],
                "email": email,
                "password": password,
                "verification-code": code
            }).then(function (data) {
                sessionStorage.removeItem("notefox-pending-login");
                saveSession({
                    "login-id": data["login-id"],
                    "token": data["token"],
                    "username": data["username"]
                });
                location.href = "/alpha/my/account/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("login-verify-message", notefoxErrorMessage(error), true);
            });
        });

        document.getElementById("login-resend-code").addEventListener("click", function (event) {
            event.preventDefault();
            hideFormMessage("login-verify-message");

            var email = emailInput.value.trim();
            var password = passwordInput.value;

            if (email === "" || password === "") {
                showFormMessage("login-verify-message", "Enter your email and password to receive a new code.", true);
                return;
            }

            notefoxApi("/login/verify/get-new-code", {
                "login-id": pending["login-id"],
                "email": email,
                "password": password
            }).then(function () {
                showFormMessage("login-verify-message", "A new code has been sent.", false);
            }).catch(function (error) {
                showFormMessage("login-verify-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
