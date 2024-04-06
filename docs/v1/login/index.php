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
            <h1 class="title-section center">/v1/login/</h1>
            <h2 class="subtitle-section no-bold font-small">
                This permits you to log in to your Notefox account. You will receive a login ID that you can use to
                authenticate your requests.
            </h2>
            <p>
                <b>API Link</b>
                <code>
                    https://notefox.eu/api/v1/login/
                </code>

                <br>

                <b>POST params</b>
                <br>
                <code>
                    {
                    "email": "&lt;YOUR EMAIL&gt;",
                    "password": "&lt;YOUR PASSWORD&gt;"
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
                    https://notefox.eu/api/v1/login/
                </code>
                <br>
                <code>
                    {
                    "email": "example@notefox.eu",
                    "password": "example-password"
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