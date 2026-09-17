<html>
<head>
    <?php
    $title = "Create an account – Notefox";
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
            <h1 class="title-section center">Create an account</h1>
            <p>
                Create a Notefox Account to sync your notes across your devices. You will receive an email with a
                verification code to confirm your address.
            </p>

            <form id="signup-form" class="form-container">
                <div id="signup-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="signup-username">Username</label>
                    <input class="form-input" type="text" id="signup-username" name="username" maxlength="256" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-email">Email</label>
                    <input class="form-input" type="email" id="signup-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-password">Password</label>
                    <input class="form-input" type="password" id="signup-password" name="password" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="signup-password-confirm">Confirm password</label>
                    <input class="form-input" type="password" id="signup-password-confirm" name="password-confirm"
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button" id="signup-submit">Create account</button>
                </div>
            </form>

            <p class="center">
                Already have an account? <a href="/my/login/">Log in</a>
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
                sessionStorage.setItem("notefox-pending-signup-email", email);
                location.href = "/my/signup/verify/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("signup-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>

<?php
?>
