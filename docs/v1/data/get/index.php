<html>
<head>
    <?php
    $docs_title = "/v1/data/get/";

    global $docs_end_point;

    $title = "Docs: $docs_title – Notefox";
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
            <h1 class="title-section center"><?php echo $docs_title; ?></h1>
            <h2 class="subtitle-section no-bold font-small">
                This API permits to get data from the database. Data is returned in JSON format.
            </h2>
            <p>
                <b>API Link</b>
                <code>
                    <?php echo $docs_end_point . $docs_title; ?>
                </code>

                <br>
                <b>Method</b>
                <code>POST</code>
                <br>
                <b>Headers</b>
                <code>{ "Content-Type": "application/json" }</code>
            </p>
            <p>
                <b>Request</b>
                <br>
                <code>login-id</code> (STRING): the login-id generated during the login
                <br>
                <code>token</code> (STRING): the token generated during the login
            </p>
            <p>
                <b>Response (success: code 200)</b>
                <br>
                <code>data</code> (JSON): the data from the database
                <code>updated-locally</code> (DATETIME): the last time the data was updated locally
                <code>updated-server</code> (DATETIME): the last time the data was updated on the server (uploaded
                datetime)
            </p>
            <p>
                <b>Response (error)</b>
                <br>
                <code>code </code> (type): description
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>