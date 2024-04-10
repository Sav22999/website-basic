<html>
<head>
    <?php
    $docs_title = "/v1/password/edit/";

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
                This API permits to edit the password of the user. The password is hashed before being stored in the
                database.
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
                <br>
                <code>password</code> (STRING): the current password
                <br>
                <code>new-password</code> (STRING): the new password
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
                <code>402</code>: <?php echo getErrorDescription(402); ?>
                <br>
                <code>410</code>: <?php echo getErrorDescription(410); ?>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>