<html>
<head>
    <?php
    $title = "Docs – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "docs";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">/v1/last-update/get/</h1>
            <h2 class="subtitle-section no-bold font-small">
                This permits you to get the last Notefox account update datetime from the database.
            </h2>
            <p>
                <b>API Link</b>
                <code>
                    https://notefox.eu/api/v1/last-update/get/
                </code>

                <br>

                <b>POST params</b>
                <br>
                <code>
                    {
                    "login-id": "&lt;YOUR LOGIN TOKEN&gt;"
                    }
                </code>

                <br>

                <b>Possible responses</b>
                <br>
                <code>
                    {
                    "": "",
                    "": ""
                    }
                </code>
            </p>
            <p>
                <b>Example</b>
                <br>
                <code>
                    https://notefox.eu/api/v1/last-update/get/
                </code>
                <br>
                <code>
                    {
                    "login-id": "XYZ123"
                    }
                </code>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>