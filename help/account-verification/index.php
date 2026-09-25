<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Account Verification – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Account Verification</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2026-09-18</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0+</span>
        </div>

        <p>
            When you create a Notefox Account, a <strong>verification code</strong> is sent to your email address.
            You must enter this code to confirm your identity and activate your account.
            This article explains how the verification process works and what happens if you don't verify.
        </p>

        <h2>How Verification Works</h2>
        <ol>
            <li><strong>Sign up</strong> with your email address, a username, and a password on the <a
                        href="/my/signup/">signup page</a>.
            </li>
            <li>A <strong>one-time verification code</strong> is sent to the email address you provided.</li>
            <li>Enter the code on the verification screen that appears after signup.</li>
            <li>Once verified, your account is active and you can log in and start syncing your notes.</li>
        </ol>

        <h2>What if I Don't Receive the Code?</h2>
        <ul>
            <li>Check your <strong>spam/junk</strong> folder.</li>
            <li>Make sure you entered the correct email address during signup.</li>
            <li>On the verification screen, click <strong>"Send a new code"</strong> to request a fresh code.</li>
            <li>Wait a few minutes &mdash; email delivery can sometimes be delayed.</li>
        </ul>

        <h2>What Happens if I Don't Verify?</h2>
        <p>
            If you do not verify your account, it remains in an <strong>unconfirmed</strong> state.
            An unconfirmed account cannot be used to log in or synchronize data.
        </p>
        <p>
            Unconfirmed accounts are <strong>not automatically deleted</strong>.
            However, if someone signs up again with the same email address, the previous unconfirmed entry
            is overwritten with the new credentials and a new verification code is sent.
            This means you can simply sign up again if you missed the original verification window.
        </p>

        <h2>Can I Resend the Verification Code?</h2>
        <p>
            Yes. On the verification screen (shown after signup), click the <strong>"Send a new code"</strong> link.
            A new code will be sent to your email address. The previous code will no longer be valid.
        </p>

        <h2>I Verified but Still Can't Log In</h2>
        <p>
            If you verified your account but are unable to log in, double-check that you are using the
            correct email and password. If the problem persists, you can try signing up again with the same email
            &mdash; if your account is in an inconsistent state, this will reset the registration process.
        </p>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
