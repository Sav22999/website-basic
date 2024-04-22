<html>
<head>
    <?php
    $title = "Get help: Notefox account – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-translate";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How the Notefox account works</h1>
            <p>
                Notefox Account is a feature that allows you to synchronize your notes between your devices. With a
                Notefox Account, you can access your notes from any device, and they will always be up to date.
                <br>
                This feature is available in Notefox 4.0 and later, it is optional, and it is <b>totally free</b><sup>*</sup>.
            </p>
            <p>
                To create a Notefox Account, you need to go to the settings of Notefox, and click on the "Sign in"
                button.
                Then, you need to enter your email and a password, and that's it.
            </p>
            <p>
                <b>Your data are saved totally encrypted</b>, in particular your password and your email using the <b>SHA-512</b>
                algorithm. Instead, your other data are encrypted using the <b>AES-256</b> algorithm with your password as the
                key to encrypt and decrypt them.
                <br>
                This means that, <b>if you lose your password, you will lose your data</b>, because it is impossible to
                recover it.
            </p>
            <p>
                You can change your password at any time, and you can also delete your account and all your data: once
                you delete your account, it is impossible to recover it.
            </p>
            <p>
                <small><sup>*</sup> See the <a href="/help/privacy/">Privacy Policy</a> and the <a href="/terms/">Terms of Service</a> for more information.</small>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>