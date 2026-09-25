<html>
<head>
    <?php
    $title = "First run – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "first-run";
include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="vertical-middle">
        <div class="horizontal-center">
            <img src="/images/icon.png" class="image-square-100px">
            <h1 class="title-section">Notefox successfully installed!</h1>
            <h2 class="subtitle-section">Now you can start using it.</h2>
            <hr class="hr-big-space">
            <!--<p class="center">
                If you'll use a Notefox Account, you'll need to accept the <a href="/old/terms/">terms of service</a> and the <a href="/old/privacy/">privacy policy</a>.
                <br>
                <b>You can also use Notefox without an account</b>
            </p>-->
            <p class="center">
                To learn more about Notefox and get support, visit the <a href="/old/help/">help page</a>.
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>