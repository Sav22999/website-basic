<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Delete account – Notefox";
$selected_menu = "my";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/my/account/" class="back-link">&larr; Back to account</a>
        <h1 class="text-center">Delete account</h1>
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
                <button type="submit" class="btn btn--danger" id="delete-submit">Request the deletion</button>
                <a href="/alpha/my/account/" class="btn btn--secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
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
                location.href = "/alpha/my/account/delete/verify/";
            }).catch(function (error) {
                submitButton.disabled = false;
                showFormMessage("delete-message", notefoxErrorMessage(error), true);
            });
        });
    });
</script>
</body>
</html>
