<html>
<head>
    <?php
    $title = "Log in – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
    <script src="/js/account.js"></script>
</head>
<body>
<?php
$selected_menu = "my";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Log in</h1>
            <p>
                Log in to your Notefox Account to manage it or download your notes.
            </p>

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
                    <button type="submit" class="button" id="login-submit">Log in</button>
                </div>
            </form>

            <p class="center">
                Do not have an account yet? <a href="/my/signup/">Create one</a>
            </p>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (hasSession()) {
            location.href = "/my/account/";
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
                    location.href = "/my/login/verify/";
                    return;
                }

                saveSession({
                    "login-id": data["login-id"],
                    "token": data["token"],
                    "username": data["username"]
                });
                location.href = "/my/account/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("login-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>

<?php
?>
