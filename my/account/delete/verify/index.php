<html>
<head>
    <?php
    $title = "Confirm the deletion of your account – Notefox";
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
            <h1 class="title-section center">Confirm the deletion of your account</h1>
            <p>
                We sent you an email with a confirmation code. Enter it below, together with your password, to delete
                your account permanently.
            </p>

            <form id="delete-verify-form" class="form-container">
                <div id="delete-verify-message" class="form-message hidden2"></div>

                <div class="form-field" id="delete-verify-email-field">
                    <label class="form-label" for="delete-verify-email">Email</label>
                    <input class="form-input" type="email" id="delete-verify-email" name="email" maxlength="320"
                           required>
                </div>

                <div class="form-field" id="delete-verify-password-field">
                    <label class="form-label" for="delete-verify-password">Password</label>
                    <input class="form-input" type="password" id="delete-verify-password" name="password" required>
                </div>

                <div class="form-field" id="delete-verify-code-field">
                    <label class="form-label" for="delete-verify-code">Confirmation code</label>
                    <input class="form-input" type="text" id="delete-verify-code" name="deleting-code" maxlength="64"
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button" id="delete-verify-submit">Delete permanently</button>
                    <a href="/my/account/" class="button button-secondary">Cancel</a>
                </div>
            </form>

            <p class="center">
                Didn't get the code?
                <a href="#" id="delete-resend-code">Send a new code</a>
            </p>
        </div>
    </div>
</main>

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

        if (emailInput.value && passwordInput.value) {
            emailField.classList.add("hidden2");
            passwordField.classList.add("hidden2");
            emailInput.readOnly = true;
            passwordInput.readOnly = true;
            emailInput.required = false;
            passwordInput.required = false;
            codeInput.focus();
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

<?php
?>
