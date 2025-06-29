<html>
<head>
    <?php
    $title = "Privacy policy – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "privacy";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<script>
    document.addEventListener("readystatechange", (event) => {
        switch (document.readyState) {
            case "complete":
                expandContainer();
                break;
        }
    });
</script>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Privacy policy</h1>
            <p>
                <i>Notefox: websites notes</i> is an open-source project developed by Saverio Morelli.
            </p>
            <p>
                <b>The following privacy policy refeeres to Notefox Account. If you don't use it, the add-on won't collect any data at all – data is not sent to the server.</b>
            </p>
            <p>
                It does not collect any personal data, except for your email, your public ip-address and the data that
                you decide to save in the add-on, which are anyway <b>encrypted</b><sup>*</sup>.
                <br>
                All your data are encrypted with your password<sup>*</sup>, so only you can read them.
                <br>
                If you lose or forget your password, you will not be able to recover your data in any way.
            </p>
            <p>
                The service doesn't use cookies, and it doesn't track you in any way.
                <br>
                The ip-addresses are collected for security purposes and for statistics, and they are not shared with
                third parties.
            </p>
            <p>
                Notefox will send you emails only in these cases:
                <br>
                • When you sign up: to confirm your account
                <br>
                • When you signed up: to inform you that your account have been created
                <br>
                • When you log in: to confirm your login (<i>Two Factor Authenticator</i>)
                <br>
                • When you logged in: to inform you that your account have been logged in
                <br>
                • When you ask to delete your account: to confirm the deleting of your data
                <br>
                • When you deleted your account: to inform you that your account have been deleted
                <br>
                Notefox will never save unencrypted your email, so you'll never receive spam emails, marketing emails,
                or any other type of emails.
                <br>
                <b>Pay attention</b>: Notefox will never ask you for your data, your password, or other sensitive data,
                via email or other communication channels.
            </p>
            <p>
                If you see any suspicious activity, please change your password as soon as possible.
                <br>
                And remember, <b>if you lose your password, you will not be able to recover your data</b>.
            </p>
            <p>
                To get more details, please <a href="/help/">contact me</a>.

            <p>
                The current Privacy Policy can be changed at any time, and it is your responsibility to check it
                periodically.
                <br>
                Last update: 16 Apr 2024
            </p>
            
            <p class="font-very-small">
                <sup>*</sup> Your password and your email are stored encrypted with the SHA-512 algorithm. Your IP
                address
                is stored as unencrypted data. Your username and your data (notes, settings, etc.) are stored encrypted
                with the AES-256 algorithm, and they can be decrypted only with your password.
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>