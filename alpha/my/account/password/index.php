<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Change password – Notefox";
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/account/" class="back-link">&larr; Back to account</a>
        <h1 class="text-center">Change password</h1>
        <p>
            Your notes are never re-encrypted: only the encryption key of your account is updated, so the change is
            quick and safe. After it you will have to log in again on every other device.
        </p>
        <p>
            Changing your password always needs a code sent to your email address, even when two-step verification
            is disabled: nothing is changed until you confirm that code.
        </p>

        <form id="password-form" class="form-container">
            <div id="password-message" class="form-message hidden2"></div>

            <div class="form-field" id="password-email-field">
                <label class="form-label" for="password-email">Email</label>
                <input class="form-input" type="email" id="password-email" name="email" maxlength="320" required>
            </div>

            <div class="form-field" id="password-current-field">
                <label class="form-label" for="password-current">Current password</label>
                <input class="form-input" type="password" id="password-current" name="password" required>
            </div>

            <div class="form-field" id="password-new-field">
                <label class="form-label" for="password-new">New password</label>
                <input class="form-input" type="password" id="password-new" name="new-password" required>
            </div>

            <div class="form-field" id="password-new-confirm-field">
                <label class="form-label" for="password-new-confirm">Confirm new password</label>
                <input class="form-input" type="password" id="password-new-confirm" name="new-password-confirm"
                       required>
            </div>

            <div class="form-field hidden2" id="password-code-field">
                <label class="form-label" for="password-code">Verification code</label>
                <input class="form-input" type="text" id="password-code" name="verification-code" maxlength="64">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" id="password-submit">Send the verification code</button>
                <a href="/alpha/my/account/" class="btn btn--secondary">Cancel</a>
            </div>
        </form>

        <p class="text-center hidden2" id="password-resend-row">
            Didn't get the code?
            <a href="#" id="password-resend-code">Send a new code</a>
        </p>
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

        var form = document.getElementById("password-form");
        var submitButton = document.getElementById("password-submit");
        var codeField = document.getElementById("password-code-field");
        var codeInput = document.getElementById("password-code");
        var resendRow = document.getElementById("password-resend-row");

        // The passwords are never stored anywhere: both steps read them from
        // the form, which stays on the page until the change is confirmed.
        var codeRequested = false;

        function readFields() {
            return {
                "email": document.getElementById("password-email").value.trim(),
                "password": document.getElementById("password-current").value,
                "new-password": document.getElementById("password-new").value,
                "new-password-confirm": document.getElementById("password-new-confirm").value
            };
        }

        function validate(fields) {
            if (fields["email"] === "" || fields["password"] === "" || fields["new-password"] === "") {
                showFormMessage("password-message", "Please fill in all the fields.", true);
                return false;
            }
            if (fields["new-password"] !== fields["new-password-confirm"]) {
                showFormMessage("password-message", "The two new passwords do not match.", true);
                return false;
            }
            if (fields["new-password"].length < 8) {
                showFormMessage("password-message", "The new password must be at least 8 characters long.", true);
                return false;
            }
            return true;
        }

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("password-message");

            var fields = readFields();
            if (!validate(fields)) {
                return;
            }

            submitButton.disabled = true;

            if (!codeRequested) {
                notefoxAuthenticatedApi("/password/edit", {
                    "email": fields["email"],
                    "password": fields["password"],
                    "new-password": fields["new-password"]
                }).then(function () {
                    submitButton.disabled = false;
                    codeRequested = true;
                    codeField.classList.remove("hidden2");
                    codeInput.required = true;
                    resendRow.classList.remove("hidden2");
                    submitButton.textContent = "Update password";

                    var emailInput = document.getElementById("password-email");
                    var currentInput = document.getElementById("password-current");
                    var newInput = document.getElementById("password-new");
                    var confirmInput = document.getElementById("password-new-confirm");

                    document.getElementById("password-email-field").classList.add("hidden2");
                    document.getElementById("password-current-field").classList.add("hidden2");
                    document.getElementById("password-new-field").classList.add("hidden2");
                    document.getElementById("password-new-confirm-field").classList.add("hidden2");

                    emailInput.readOnly = true;
                    currentInput.readOnly = true;
                    newInput.readOnly = true;
                    confirmInput.readOnly = true;

                    emailInput.required = false;
                    currentInput.required = false;
                    newInput.required = false;
                    confirmInput.required = false;

                    codeInput.focus();
                    showFormMessage("password-message", "A verification code has been sent to your email address.", false);
                }).catch(function (error) {
                    submitButton.disabled = false;
                    showFormMessage("password-message", notefoxErrorMessage(error), true);
                });
                return;
            }

            var code = codeInput.value.trim();
            if (code === "") {
                submitButton.disabled = false;
                showFormMessage("password-message", "Please enter the verification code you received by email.", true);
                return;
            }

            notefoxAuthenticatedApi("/password/edit/verify", {
                "email": fields["email"],
                "password": fields["password"],
                "new-password": fields["new-password"],
                "verification-code": code
            }).then(function (data) {
                saveSession({
                    "login-id": data["login-id"],
                    "token": data["token"],
                    "username": session["username"]
                });
                showFormMessage("password-message", "Password updated successfully.", false);
                setTimeout(function () {
                    location.href = "/alpha/my/account/";
                }, 1500);
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("password-message", notefoxErrorMessage(error), true);
            });
        });

        document.getElementById("password-resend-code").addEventListener("click", function (event) {
            event.preventDefault();
            hideFormMessage("password-message");

            var fields = readFields();
            if (fields["email"] === "" || fields["password"] === "") {
                showFormMessage("password-message", "Enter your email and password to receive a new code.", true);
                return;
            }

            notefoxAuthenticatedApi("/password/edit/get-new-code", {
                "email": fields["email"],
                "password": fields["password"]
            }).then(function () {
                showFormMessage("password-message", "A new code has been sent.", false);
            }).catch(function (error) {
                showFormMessage("password-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
