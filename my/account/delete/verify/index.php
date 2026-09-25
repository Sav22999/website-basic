<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.delete_verify_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/delete/" class="back-link"><?php echo te('common.back'); ?></a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('account.delete_verify_heading'); ?></h1>
                <p><?php echo t('account.delete_verify_desc'); ?></p>
            </div>

            <form id="delete-verify-form" class="form-container">
                <div id="delete-verify-message" class="form-message hidden2"></div>

                <div class="form-field hidden2" id="delete-verify-email-field">
                    <label class="form-label" for="delete-verify-email"><?php echo te('common.email'); ?></label>
                    <input class="form-input" type="email" id="delete-verify-email" name="email" maxlength="320">
                </div>

                <div class="form-field hidden2" id="delete-verify-password-field">
                    <label class="form-label" for="delete-verify-password"><?php echo te('common.password'); ?></label>
                    <input class="form-input" type="password" id="delete-verify-password" name="password">
                </div>

                <div class="form-field" id="delete-verify-code-field">
                    <label class="form-label"
                           for="delete-verify-code"><?php echo te('account.delete_verify_code'); ?></label>
                    <input class="form-input" type="text" id="delete-verify-code" name="deleting-code" maxlength="64"
                           required autofocus>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block btn--danger"
                            id="delete-verify-submit"><?php echo te('account.delete_verify_submit'); ?></button>
                </div>
            </form>

            <p class="auth-footer">
                <?php echo t('account.didnt_get_code'); ?> <a href="#"
                                                              id="delete-resend-code"><?php echo te('account.send_new_code'); ?></a>
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

        var pendingRaw = sessionStorage.getItem("notefox-pending-delete");
        var pending = null;
        try {
            pending = pendingRaw ? JSON.parse(pendingRaw) : null;
        } catch (e) {
            pending = null;
        }
        if (!pending) {
            var oldEmail = sessionStorage.getItem("notefox-pending-delete-email");
            if (oldEmail) {
                pending = {"email": oldEmail};
            }
        }

        var emailInput = document.getElementById("delete-verify-email");
        var passwordInput = document.getElementById("delete-verify-password");
        var codeInput = document.getElementById("delete-verify-code");
        var emailField = document.getElementById("delete-verify-email-field");
        var passwordField = document.getElementById("delete-verify-password-field");

        if (pending && pending["email"]) {
            emailInput.value = pending["email"];
        }
        if (pending && pending["password"]) {
            passwordInput.value = pending["password"];
        }

        if (!emailInput.value || !passwordInput.value) {
            emailField.classList.remove("hidden2");
            passwordField.classList.remove("hidden2");
            emailInput.required = true;
            passwordInput.required = true;
        }

        var form = document.getElementById("delete-verify-form");
        var submitButton = document.getElementById("delete-verify-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("delete-verify-message");

            var email = document.getElementById("delete-verify-email").value.trim();
            var password = document.getElementById("delete-verify-password").value;
            var code = document.getElementById("delete-verify-code").value.trim();

            if (email === "" || password === "" || code === "") {
                showFormMessage("delete-verify-message", "Please fill in all the fields.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/delete/verify", {
                "email": email,
                "password": password,
                "deleting-code": code
            }).then(function () {
                sessionStorage.removeItem("notefox-pending-delete-email");
                clearSession();
                location.href = "/my/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("delete-verify-message", notefoxErrorMessage(error), true);
            });
        });

        document.getElementById("delete-resend-code").addEventListener("click", function (event) {
            event.preventDefault();
            hideFormMessage("delete-verify-message");

            var email = document.getElementById("delete-verify-email").value.trim();
            var password = document.getElementById("delete-verify-password").value;

            if (email === "" || password === "") {
                showFormMessage("delete-verify-message", "Enter your email and password to receive a new code.", true);
                return;
            }

            notefoxApi("/delete/verify/get-new-code", {
                "email": email,
                "password": password
            }).then(function () {
                showFormMessage("delete-verify-message", "A new code has been sent.", false);
            }).catch(function (error) {
                showFormMessage("delete-verify-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
