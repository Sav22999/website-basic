<html>
<head>
    <?php
    $docs_title = "/v1/token/set-expiry/";

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
                This API permits to set the expiry date of a token. The token is used to authenticate the user in the
                system and the default expiry date is "undefined" (null).
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
                <code>login-id</code> (type): the login-id generated during the login
                <br>
                <code>token</code> (type): the token generated during the login
                <br>
                <code>expiry</code> (DATETIME): the new expiry date of the token
            </p>
            <p>
                <b>Response (success: code 200)</b>
                <br>
                <code>old-expiry</code> (DATETIME): the old expiry date of the token
                <br>
                <code>new-expiry</code> (DATETIME): the new expiry date of the token (the same as the request)
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
                <code>403</code>: <?php echo getErrorDescription(403); ?>
                <br>
                <code>404</code>: <?php echo getErrorDescription(404); ?>
                <br>
                <code>405</code>: <?php echo getErrorDescription(405); ?>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>