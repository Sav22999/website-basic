<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Two-step verification – Notefox";
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/account/" class="back-link">&larr; Back to account</a>
        <h1 class="text-center">Two-step verification</h1>
        <p>
            When two-step verification is enabled, every login needs both your password and a code sent to your
            email address. Turning it off makes your account less safe, so it has to be confirmed with a code.
        </p>
        <p>
            Changing your password and deleting your account always need a code sent by email, whatever this
            setting: this option only concerns the login.
        </p>

        <div class="account-section">
            <h2>Current state</h2>
            <div id="otp-status" class="account-status">Checking...</div>
            <p id="otp-hint">Reading the state of your account...</p>
        </div>

        <form id="otp-form" class="form-container hidden2">
            <div id="otp-message" class="form-message hidden2"></div>

            <div class="form-field" id="otp-email-field">
                <label class="form-label" for="otp-email">Email</label>
                <input class="form-input" type="email" id="otp-email" name="email" maxlength="320" required>
            </div>

            <div class="form-field" id="otp-password-field">
                <label class="form-label" for="otp-password">Password</label>
                <input class="form-input" type="password" id="otp-password" name="password" required>
            </div>

            <div class="form-field hidden2" id="otp-code-field">
                <label class="form-label" for="otp-code">Verification code</label>
                <input class="form-input" type="text" id="otp-code" name="verification-code" maxlength="64">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" id="otp-submit">Continue</button>
                <a href="/alpha/my/account/" class="btn btn--secondary">Cancel</a>
            </div>
        </form>
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

        var statusElement = document.getElementById("otp-status");
        var hintElement = document.getElementById("otp-hint");
        var form = document.getElementById("otp-form");
        var submitButton = document.getElementById("otp-submit");
        var emailField = document.getElementById("otp-email-field");
        var passwordField = document.getElementById("otp-password-field");
        var emailInput = document.getElementById("otp-email");
        var passwordInput = document.getElementById("otp-password");
        var codeField = document.getElementById("otp-code-field");
        var codeInput = document.getElementById("otp-code");

        // null until the state is known; "enable", "disable" or
        // "disable-verify" (a code has already been emailed).
        var action = null;

        function render(enabled, codePending) {
            statusElement.textContent = enabled ? "Enabled" : "Disabled";
            statusElement.classList.toggle("account-status--off", !enabled);

            if (codePending) {
                action = "disable-verify";
                hintElement.textContent = "We sent a code to your email address: enter it below to turn two-step verification off.";
                submitButton.textContent = "Turn off";
                codeField.classList.remove("hidden2");
                codeInput.required = true;
                emailField.classList.add("hidden2");
                passwordField.classList.add("hidden2");
                emailInput.readOnly = true;
                passwordInput.readOnly = true;
                emailInput.required = false;
                passwordInput.required = false;
                codeInput.focus();
            } else if (enabled) {
                action = "disable";
                hintElement.textContent = "Enter your email and your password to receive the code needed to turn two-step verification off.";
                submitButton.textContent = "Turn off";
                codeField.classList.add("hidden2");
                codeInput.required = false;
                emailField.classList.remove("hidden2");
                passwordField.classList.remove("hidden2");
                emailInput.readOnly = false;
                passwordInput.readOnly = false;
                emailInput.required = true;
                passwordInput.required = true;
            } else {
                action = "enable";
                hintElement.textContent = "Enter your email and your password to turn two-step verification on.";
                submitButton.textContent = "Turn on";
                codeField.classList.add("hidden2");
                codeInput.required = false;
                emailField.classList.remove("hidden2");
                passwordField.classList.remove("hidden2");
                emailInput.readOnly = false;
                passwordInput.readOnly = false;
                emailInput.required = true;
                passwordInput.required = true;
            }

            form.classList.remove("hidden2");
        }

        notefoxAuthenticatedApi("/otp/status", {}).then(function (data) {
            render(data["otp-enabled"], false);
        }).catch(function (error) {
            statusElement.textContent = "Unknown";
            statusElement.classList.add("account-status--off");
            hintElement.textContent = "";
            showFormMessage("otp-message", notefoxErrorMessage(error), true);
            form.classList.remove("hidden2");
        });

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("otp-message");

            var email = document.getElementById("otp-email").value.trim();
            var password = document.getElementById("otp-password").value;
            var code = codeInput.value.trim();

            if (email === "" || password === "") {
                showFormMessage("otp-message", "Please fill in all the fields.", true);
                return;
            }
            if (action === "disable-verify" && code === "") {
                showFormMessage("otp-message", "Please enter the verification code you received by email.", true);
                return;
            }

            submitButton.disabled = true;

            if (action === "enable") {
                notefoxAuthenticatedApi("/otp/enable", {
                    "email": email,
                    "password": password
                }).then(function () {
                    submitButton.disabled = false;
                    render(true, false);
                    showFormMessage("otp-message", "Two-step verification is now enabled.", false);
                }).catch(function (error) {
                    submitButton.disabled = false;
                    showFormMessage("otp-message", notefoxErrorMessage(error), true);
                });
                return;
            }

            if (action === "disable") {
                notefoxAuthenticatedApi("/otp/disable", {
                    "email": email,
                    "password": password
                }).then(function () {
                    submitButton.disabled = false;
                    render(true, true);
                    showFormMessage("otp-message", "A verification code has been sent to your email address.", false);
                }).catch(function (error) {
                    submitButton.disabled = false;
                    showFormMessage("otp-message", notefoxErrorMessage(error), true);
                });
                return;
            }

            notefoxAuthenticatedApi("/otp/disable/verify", {
                "email": email,
                "password": password,
                "verification-code": code
            }).then(function () {
                submitButton.disabled = false;
                codeInput.value = "";
                render(false, false);
                showFormMessage("otp-message", "Two-step verification is now disabled.", false);
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("otp-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
