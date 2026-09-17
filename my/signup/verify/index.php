<html>
<head>
    <?php
    $title = "Verify your account – Notefox";
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
            <h1 class="title-section center">Verify your account</h1>
            <p>
                We sent you an email with a verification code. Enter it below together with your password to complete
                the sign up.
            </p>

            <form id="verify-form" class="form-container">
                <div id="verify-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="verify-email">Email</label>
                    <input class="form-input" type="email" id="verify-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="verify-password">Password</label>
                    <input class="form-input" type="password" id="verify-password" name="password" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="verify-code">Verification code</label>
                    <input class="form-input" type="text" id="verify-code" name="verification-code" maxlength="64"
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button" id="verify-submit">Verify account</button>
                </div>
            </form>

            <p class="center">
                Did not receive the code?
                <a href="#" id="resend-code">Send a new code</a>
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

        var pendingEmail = sessionStorage.getItem("notefox-pending-signup-email");
        if (pendingEmail) {
            document.getElementById("verify-email").value = pendingEmail;
        }

        var form = document.getElementById("verify-form");
        var submitButton = document.getElementById("verify-submit");

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage("verify-message");

            var email = document.getElementById("verify-email").value.trim();
            var password = document.getElementById("verify-password").value;
            var code = document.getElementById("verify-code").value.trim();

            if (email === "" || password === "" || code === "") {
                showFormMessage("verify-message", "Please fill in all the fields.", true);
                return;
            }

            submitButton.disabled = true;

            notefoxApi("/signup/verify", {
                "email": email,
                "password": password,
                "verification-code": code
            }).then(function () {
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

            var email = document.getElementById("verify-email").value.trim();
            var password = document.getElementById("verify-password").value;

            if (email === "" || password === "") {
                showFormMessage("verify-message", "Enter your email and password to receive a new code.", true);
                return;
            }

            notefoxApi("/signup/verify/get-new-code", {
                "email": email,
                "password": password
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

<?php
?>
