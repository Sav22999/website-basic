<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.signup_verify_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/signup/" class="back-link"><?php echo te('account.back_to_signup'); ?></a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('account.signup_verify_heading'); ?></h1>
                <p><?php echo t('account.signup_verify_desc'); ?></p>
            </div>

            <form id="verify-form" class="form-container">
                <div id="verify-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="verify-code"><?php echo te('account.signup_verify_code'); ?></label>
                    <input class="form-input" type="text" id="verify-code" name="verification-code" maxlength="64"
                           required autofocus>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block"
                            id="verify-submit"><?php echo te('account.signup_verify_submit'); ?></button>
                </div>
            </form>

            <p class="auth-footer">
                <?php echo t('account.did_not_receive_code'); ?> <a href="#"
                                                                    id="resend-code"><?php echo te('account.send_new_code'); ?></a>
            </p>
        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (hasSession()) {
            location.href = "/my/account/";
            return;
        }

        var pendingRaw = sessionStorage.getItem("notefox-pending-signup");
        var pending = null;
        try {
            pending = pendingRaw ? JSON.parse(pendingRaw) : null;
        } catch (e) {
            pending = null;
        }
        if (!pending) {
            var oldEmail = sessionStorage.getItem("notefox-pending-signup-email");
            if (oldEmail) {
                pending = {"email": oldEmail};
            }
        }
        if (!pending || !pending["email"]) {
            location.href = "/my/signup/";
            return;
        }

        var form = document.getElementById("verify-form");
        var submitButton = document.getElementById("verify-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("verify-message");

            var code = document.getElementById("verify-code").value.trim();

            if (code === "") {
                showFormMessage("verify-message", "Please enter the verification code.", true);
                return;
            }
            if (!pending["password"]) {
                showFormMessage("verify-message", "Session expired. Please go back and sign up again.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/signup/verify", {
                "email": pending["email"],
                "password": pending["password"],
                "verification-code": code
            }).then(function () {
                sessionStorage.removeItem("notefox-pending-signup");
                sessionStorage.removeItem("notefox-pending-signup-email");
                showFormMessage("verify-message", "Account verified successfully! You can now log in.", false);
                setTimeout(function () {
                    location.href = "/my/login/";
                }, 1500);
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("verify-message", notefoxErrorMessage(error), true);
            });
        });

        document.getElementById("resend-code").addEventListener("click", function (event) {
            event.preventDefault();
            hideFormMessage("verify-message");

            if (!pending["email"] || !pending["password"]) {
                showFormMessage("verify-message", "Session expired. Please go back and sign up again.", true);
                return;
            }

            notefoxApi("/signup/verify/get-new-code", {
                "email": pending["email"],
                "password": pending["password"]
            }).then(function () {
                showFormMessage("verify-message", "If the account exists, a new code has been sent.", false);
            }).catch(function (error) {
                showFormMessage("verify-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
