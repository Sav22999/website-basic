<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Log in – Notefox";
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/" class="back-link">Back</a>

        <div class="auth-card">
            <div class="auth-header">
                <img src="/images/icon.svg" alt="" width="48" height="48">
                <h1>Log in</h1>
                <p>Access your Notefox Account to manage it or download your notes.</p>
            </div>

            <form id="login-form" class="form-container">
                <div id="login-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="login-email">Email</label>
                    <input class="form-input" type="email" id="login-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="login-password">Password</label>
                    <input class="form-input" type="password" id="login-password" name="password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block" id="login-submit">Log in</button>
                </div>
            </form>

            <p class="auth-footer">
                Do not have an account yet? <a href="/alpha/my/signup/">Create one</a>
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

        var flashMessage = consumeLoginMessage();
        if (flashMessage) {
            showFormMessage("login-message", flashMessage, true);
        }

        var form = document.getElementById("login-form");
        var submitButton = document.getElementById("login-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("login-message");

            var email = document.getElementById("login-email").value.trim();
            var password = document.getElementById("login-password").value;

            if (email === "" || password === "") {
                showFormMessage("login-message", "Please fill in all the fields.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/login", {
                "email": email,
                "password": password
            }).then(function (data) {
                if (data["otp-required"]) {
                    sessionStorage.setItem("notefox-pending-login", JSON.stringify({
                        "login-id": data["login-id"],
                        "email": email,
                        "password": password
                    }));
                    location.href = "/alpha/my/login/verify/";
                    return;
                }

                saveSession({
                    "login-id": data["login-id"],
                    "token": data["token"],
                    "username": data["username"]
                });
                location.href = "/alpha/my/account/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("login-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
