<html>
<head>
    <?php
    $title = "Delete account – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php");
    ?>
    <script src="/js/account.js"></script>
</head>
<body>
<?php
$selected_menu = "my";
include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Delete account</h1>
            <p>
                Deleting your account is permanent: every synced note and all the data of your account will be erased.
                We will email you a confirmation code before anything is deleted.
            </p>
            <p>
                Deleting your account always needs that code, even when two-step verification is disabled.
            </p>

            <form id="delete-form" class="form-container">
                <div id="delete-message" class="form-message hidden2"></div>

                <div class="form-field">
                    <label class="form-label" for="delete-email">Email</label>
                    <input class="form-input" type="email" id="delete-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="delete-password">Password</label>
                    <input class="form-input" type="password" id="delete-password" name="password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button" id="delete-submit">Request the deletion</button>
                    <a href="/old/my/account/" class="button button-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

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
                location.href = "/old/my/account/delete/verify/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("delete-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>

<?php
?>
