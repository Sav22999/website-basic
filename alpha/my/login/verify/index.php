<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Verify your login – Notefox";
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/login/" class="back-link">Back to login</a>
        <h1 class="text-center">Verify your login</h1>
        <p>
            Your account has two-step verification enabled. We sent you an email with a verification code: enter it
            below together with your password to complete the login.
        </p>

        <form id="login-verify-form" class="form-container">
            <div id="login-verify-message" class="form-message hidden2"></div>

            <div class="form-field" id="login-verify-email-field">
                <label class="form-label" for="login-verify-email">Email</label>
                <input class="form-input" type="email" id="login-verify-email" name="email" maxlength="320"
                       required>
            </div>

            <div class="form-field" id="login-verify-password-field">
                <label class="form-label" for="login-verify-password">Password</label>
                <input class="form-input" type="password" id="login-verify-password" name="password" required>
            </div>

            <div class="form-field" id="login-verify-code-field">
                <label class="form-label" for="login-verify-code">Verification code</label>
                <input class="form-input" type="text" id="login-verify-code" name="verification-code" maxlength="64"
                       required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" id="login-verify-submit">Verify</button>
            </div>
        </form>

        <p class="text-center" style="margin-top: 24px;">
            Did not receive the code?
            <a href="#" id="login-resend-code">Send a new code</a>
        </p>
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

        if (emailInput.value && passwordInput.value) {
            emailField.classList.add("hidden2");
            passwordField.classList.add("hidden2");
            emailInput.readOnly = true;
            passwordInput.readOnly = true;
            emailInput.required = false;
            passwordInput.required = false;
            codeInput.focus();
        }

        var form = document.getElementById("login-verify-form");
        var submitButton = document.getElementById("login-verify-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("login-verify-message");

            var email = document.getElementById("login-verify-email").value.trim();
            var password = document.getElementById("login-verify-password").value;
            var code = document.getElementById("login-verify-code").value.trim();

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

            var email = document.getElementById("login-verify-email").value.trim();
            var password = document.getElementById("login-verify-password").value;

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
