<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.password_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/" class="back-link"><?php echo te('account.back_to_account'); ?></a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('account.password_heading'); ?></h1>
                <p><?php echo t('account.password_desc'); ?></p>
            </div>

            <form id="password-form" class="form-container">
                <div id="password-message" class="form-message hidden2"></div>

                <div class="form-field" id="password-email-field">
                    <label class="form-label" for="password-email"><?php echo te('common.email'); ?></label>
                    <input class="form-input" type="email" id="password-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field" id="password-current-field">
                    <label class="form-label"
                           for="password-current"><?php echo te('account.password_current'); ?></label>
                    <input class="form-input" type="password" id="password-current" name="password" required>
                </div>

                <div class="form-field" id="password-new-field">
                    <label class="form-label" for="password-new"><?php echo te('account.password_new'); ?></label>
                    <input class="form-input" type="password" id="password-new" name="new-password" required>
                </div>

                <div class="form-field" id="password-new-confirm-field">
                    <label class="form-label"
                           for="password-new-confirm"><?php echo te('account.password_new_confirm'); ?></label>
                    <input class="form-input" type="password" id="password-new-confirm" name="new-password-confirm"
                           required>
                </div>

                <div class="form-field hidden2" id="password-code-field">
                    <label class="form-label"
                           for="password-code"><?php echo te('account.signup_verify_code'); ?></label>
                    <input class="form-input" type="text" id="password-code" name="verification-code" maxlength="64">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block"
                            id="password-submit"><?php echo te('account.password_send_code'); ?></button>
                </div>
            </form>

            <p class="auth-footer" id="password-cancel-row">
                <a href="/my/account/"><?php echo te('common.cancel'); ?></a>
            </p>
            <p class="auth-footer hidden2" id="password-resend-row">
                <?php echo t('account.didnt_get_code'); ?> <a href="#"
                                                              id="password-resend-code"><?php echo te('account.send_new_code'); ?></a>
            </p>
        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
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
        var cancelRow = document.getElementById("password-cancel-row");

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
                    cancelRow.classList.add("hidden2");
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
                    location.href = "/my/account/";
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
