<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.delete_title');
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
                <h1><?php echo te('account.delete_heading'); ?></h1>
                <p><?php echo t('account.delete_desc'); ?></p>
            </div>

            <form id="delete-form" class="form-container">
                <div id="delete-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="delete-email"><?php echo te('common.email'); ?></label>
                    <input class="form-input" type="email" id="delete-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="delete-password"><?php echo te('common.password'); ?></label>
                    <input class="form-input" type="password" id="delete-password" name="password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block btn--danger"
                            id="delete-submit"><?php echo te('account.delete_request'); ?></button>
                </div>
            </form>

            <p class="auth-footer">
                <a href="/my/account/"><?php echo te('common.cancel'); ?></a>
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

        var form = document.getElementById("delete-form");
        var submitButton = document.getElementById("delete-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("delete-message");

            var email = document.getElementById("delete-email").value.trim();
            var password = document.getElementById("delete-password").value;

            if (email === "" || password === "") {
                showFormMessage("delete-message", "Please fill in all the fields.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/delete", {
                "email": email,
                "password": password
            }).then(function () {
                sessionStorage.setItem("notefox-pending-delete", JSON.stringify({
                    "email": email,
                    "password": password
                }));
                location.href = "/my/account/delete/verify/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("delete-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
