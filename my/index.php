<html>
<head>
    <?php
    $title = "My Account – Notefox";
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
            <h1 class="title-section center">My Account</h1>
            <p>
                With a Notefox Account you can sync your notes across your devices. Log in to your account to manage it,
                or create a new one if you do not have one yet.
            </p>
            <div class="account-links">
                <a href="/my/login/" class="button">Log in</a>
                <a href="/my/signup/" class="button button-secondary">Create an account</a>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (hasSession()) {
            location.href = "/my/account/";
        }
    });
</script>
</body>
</html>

<?php
?>
