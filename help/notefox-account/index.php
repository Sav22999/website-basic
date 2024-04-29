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
            <h1 class="title-section center">Notefox Account</h1>
            <p>
                Notefox Account allows you to synchronize your notes between your devices. With a Notefox Account, you
                can access your notes from any device, and they will always be up-to-date.
                <br>
                This feature is available in Notefox 4.0 and later, it is optional, and it is <b>totally
                    free</b><sup>*</sup>.
            </p>
            <p>
                To create a Notefox Account, you need to go to the Settings of Notefox, and click on the "Sign in"
                button.
                Then, you need to enter your email and a password, and that's it <sup>**</sup>.
                <br>
                <span class="notefox-4-0-page">
                    <img src="./signup.gif" width="80%" alt="Notefox Account – Sign up"/>
                    <br>
                    <small>Sign up process</small>
                </span>
            </p>
            <p>
                If you have already a Notefox Account, you can log in with your email and password, and your notes will
                be synchronized.
                <br>
                <span class="notefox-4-0-page">
                    <img src="./login.gif" width="80%" alt="Notefox Account – Log in"/>
                    <br>
                    <small>Log in process</small>
                </span>
            </p>
            <p>
                <b>Your data are saved totally encrypted</b>, in particular your password and your email using the <b>SHA-512</b>
                algorithm. Instead, your other data are encrypted using the <b>AES-256</b> algorithm with your password
                as the
                key to encrypt and decrypt them.
                <br>
                This means that, <b>if you lose your password, you will lose your data</b>, because it is impossible to
                recover it.
            </p>
            <p>
                You can change your password at any time, and you can also delete your account and all your data: once
                you delete your account, it is impossible to recover it.
                <br>
                <span class="notefox-4-0-page">
                    <img src="./change-password.gif" width="80%" alt="Notefox Account – Change password"/>
                    <br>
                    <small>Change password process</small>
                </span>
                <br>
                <span class="notefox-4-0-page">
                    <img src="./delete-account.gif" width="80%" alt="Notefox Account – Delete account"/>
                    <br>
                    <small>Delete account process</small>
                </span>
            </p>
            <p>
                Notes with a Notefox Account are automatically synchronized between your devices, although you can force
                the synchronization at any time in "Manage account" in the Settings.
                <br>
                <span class="notefox-4-0-page">
                    <img src="./force-the-syncing.gif" width="80%" alt="Notefox Account – Force the syncing"/>
                    <br>
                    <small>Force sync process</small>
                </span>
            </p>
            <p>
                <small><sup>*</sup> See the <a href="/help/privacy/">Privacy Policy</a> and the <a href="/terms/">Terms
                        of Service</a> for more information.</small>
                <br>
                <small><sup>**</sup> You'll receive a verification code via email to verify your account.</small>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>