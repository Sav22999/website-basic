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
    <div class="horizontal-center">
        <h1 class="title-section">Privacy policy</h1>
    </div>
    <br class="big-space">
    <p class="horizontal-center">
        <i>Notefox: websites notes</i> is an open-source project developed by Saverio Morelli.
        <br>
        It does not collect any personal data, except for your email and the data that you decide to save in the add-on.
        <br>
        It saves your email (plaintext), your password (encrypted), and the notes that you decide to save.
        <br>
        Your email is saved not encrypted because it is used to identify you in the database.
        <br>
        All your notes are encrypted with your password, so only you can read them.
        <br class="big-space">
        To get more details, please <a href="/help/">contact me</a>.
    </p>
</main>


</body>
</html>

<?php
?>