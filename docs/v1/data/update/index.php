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
            <h1 class="title-section center">/v1/data/update/</h1>
            <h2 class="subtitle-section no-bold font-small">
                This permits you to update your data to the database. You can update your data by sending a POST request
                and already encrypted data.
            </h2>
            <p>
                <b>API Link</b>
                <code>
                    https://notefox.eu/api/v1/data/update/
                </code>

                <br>

                <b>POST params</b>
                <br>
                <code>
                    {
                    "login-id": "&lt;YOUR LOGIN ID&gt;",
                    "data": "&lt;YOUR DATA&gt;"
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
                    https://notefox.eu/api/v1/data/update/
                </code>
                <br>
                <code>
                    {
                    "login-id": "XYZ123",
                    "data": "{`last-update`:`2024-04-06 10:30:00`}"
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