<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('account.signup_title');
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/" class="back-link"><?php echo te('common.back'); ?></a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1><?php echo te('account.signup_heading'); ?></h1>
                <p><?php echo t('account.signup_desc'); ?></p>
            </div>

            <form id="signup-form" class="form-container">
                <div id="signup-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="signup-username"><?php echo te('account.signup_username'); ?></label>
                    <input class="form-input" type="text" id="signup-username" name="username" maxlength="256" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-email"><?php echo te('common.email'); ?></label>
                    <input class="form-input" type="email" id="signup-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-password"><?php echo te('common.password'); ?></label>
                    <input class="form-input" type="password" id="signup-password" name="password" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-password-confirm"><?php echo te('account.signup_confirm_password'); ?></label>
                    <input class="form-input" type="password" id="signup-password-confirm" name="password-confirm"
                           required>
                </div>

                <p class="contact-consent"><?php echo t('account.signup_consent'); ?></p>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block" id="signup-submit"><?php echo te('account.signup_submit'); ?></button>
                </div>
            </form>

            <p class="auth-footer">
                <?php echo t('account.signup_has_account'); ?> <a href="/alpha/my/login/"><?php echo te('account.signup_login'); ?></a>
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

        var form = document.getElementById("signup-form");
        var submitButton = document.getElementById("signup-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("signup-message");

            var username = document.getElementById("signup-username").value.trim();
            var email = document.getElementById("signup-email").value.trim();
            var password = document.getElementById("signup-password").value;
            var passwordConfirm = document.getElementById("signup-password-confirm").value;

            if (username === "" || email === "" || password === "") {
                showFormMessage("signup-message", "Please fill in all the fields.", true);
                return;
            }
            if (password !== passwordConfirm) {
                showFormMessage("signup-message", "The two passwords do not match.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/signup", {
                "username": username,
                "email": email,
                "password": password
            }).then(function () {
                sessionStorage.removeItem("notefox-pending-signup-email");
                sessionStorage.setItem("notefox-pending-signup", JSON.stringify({
                    "email": email,
                    "password": password
                }));
                location.href = "/alpha/my/signup/verify/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("signup-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
