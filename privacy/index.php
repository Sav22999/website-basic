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
                It does not collect any personal data, except for your email and the data that you decide to save in the add-on, which are anyway <b>encrypted</b><sup>*</sup>.
                <br>
                All your data are encrypted with your password, so only you can read them.
                <br>
                If you lose or forget your password, you will not be able to recover your data in any way.
            </p>
            <p>
                To get more details, please <a href="/help/">contact me</a>.
            </p>
            <p>
                <sup>*</sup> Your data are encrypted with the AES-256 algorithm with your password as the key. The password is hashed with the SHA-512 algorithm.
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>