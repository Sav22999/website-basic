<html>
<head>
    <?php
    $docs_title = "/v1/";

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
                <code></code> (type): description
                <br>
                <code></code> (type): description
            </p>
            <p>
                <b>Response (success)</b>
                <br>
                <code></code> (type): description
            </p>
            <p>
                <b>Response (error)</b>
                <br>
                <code></code> (type): description
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>