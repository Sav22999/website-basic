<html>
<head>
    <?php
    $title = "Get help: local data storage – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-local-data-storage";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Local data storage</h1>
            <p>
                Data saved locally with Notefox is stored in the browser's local storage. This means that the data is
                stored on your device as plain text: data on your device is not encrypted! This is why it is better
                to not save sensitive data with Notefox.
            </p>
            <p>
                <b>Pay attention</b>: data synced with a Notefox Account is stored in the cloud, and it is encrypted
                server-side.
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>