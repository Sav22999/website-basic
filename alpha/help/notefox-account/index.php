<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Notefox Account – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Notefox Account</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2022-11-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0+</span>
        </div>
        <p>
            <em>Looking for information about <strong>Notefox Account v2 (Sav Account)</strong>? Read the <a
                        href="/alpha/help/notefox-account-v2/">Notefox Account v2 guide</a>.</em>
        </p>
        <p>
            Notefox Account allows you to synchronize your notes between your devices. With a Notefox Account, you
            can access your notes from any device, and they will always be up-to-date.
            This feature is available in Notefox 4.0 and later, it is optional, and it is <strong>totally
                free</strong><sup>*</sup>.
        </p>
        <p>
            To create a Notefox Account, you need to go to the Settings of Notefox, and click on the "Sign in"
            button.
            Then, you need to enter your email and a password, and that's it <sup>**</sup>.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-account/signup.gif" width="80%" alt="Notefox Account - Sign up">
                <small>Sign up process</small>
            </span>
        </p>
        <p>
            If you have already a Notefox Account, you can log in with your email and password, and your notes will
            be synchronized.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-account/login.gif" width="80%" alt="Notefox Account - Log in">
                <small>Log in process</small>
            </span>
        </p>
        <p>
            <strong>Your data are saved totally encrypted</strong>, in particular your password and your email using the
            <strong>SHA-512</strong>
            algorithm. Instead, your other data are encrypted using the <strong>AES-256</strong> algorithm with your
            password
            as the
            key to encrypt and decrypt them.
            This means that, <strong>if you lose your password, you will lose your data</strong>, because it is
            impossible to
            recover it.
        </p>
        <p>
            You can change your password at any time, and you can also delete your account and all your data: once
            you delete your account, it is impossible to recover it.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-account/change-password.gif" width="80%"
                     alt="Notefox Account - Change password">
                <small>Change password process</small>
            </span>
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-account/delete-account.gif" width="80%" alt="Notefox Account - Delete account">
                <small>Delete account process</small>
            </span>
        </p>
        <p>
            Notes with a Notefox Account are automatically synchronized between your devices, although you can force
            the synchronization at any time in "Manage account" in the Settings.
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/notefox-account/force-the-syncing.gif" width="80%"
                     alt="Notefox Account - Force the syncing">
                <small>Force sync process</small>
            </span>
        </p>
        <p>
            <small><sup>*</sup> See the <a href="/alpha/privacy/">Privacy Policy</a> and the <a href="/alpha/terms/">Terms
                    of Service</a> for more information.</small>
        </p>
        <p>
            <small><sup>**</sup> You'll receive a verification code via email to verify your account.</small>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
