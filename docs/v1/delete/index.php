<html>
<head>
    <?php
    $docs_title = "/v1/delete/";

    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/docs-functions.php");
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
                This API permits to delete an account. The user must insert its credentials to delete the account.
                It's enabled the confirmation of the deletion by email.
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
                <code>email</code> (STRING): the email you want to use
                <br>
                <code>password</code> (STRING): the password you use to log in
            </p>
            <p>
                <b>Response (success: code 200)</b>
                <br>
                <code>null</code>
            </p>
            <p>
                <b>Response (error)</b>
                <br>
                <code>400</code>: <?php echo getErrorDescription(400); ?>
                <br>
                <code>401</code>: <?php echo getErrorDescription(401); ?>
                <br>
                <code>410</code>: <?php echo getErrorDescription(410); ?>
                <br>
                <code>411</code>: <?php echo getErrorDescription(411); ?>
                <br>
                <code>452</code>: <?php echo getErrorDescription(452); ?>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>