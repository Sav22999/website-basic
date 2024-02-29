<html>
<head>
    <?php
    $title = "Forbitten";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <h1 class="title-section">Error (HTTP) 403</h1>
            <h2 class="subtitle-section no-bold font-small">Forbitten</h2>
            <br>
            <br>
            <input type="button" class="button" value="Go to homepage"
                   onclick="location.href='/'">
        </div>
    </div>
</main>

</body>
</html>

<?php
?>