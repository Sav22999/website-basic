<html>
<head>
    <?php
    $title = "Privacy policy";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "privacy";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<script>
    document.addEventListener("readystatechange", (event) => {
        switch (document.readyState) {
            case "complete":
                expandContainer();
                break;
        }
    });
</script>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <h1 class="title-section">Privacy policy</h1>
    </div>
    <br class="big-space">
    <p class="horizontal-center">
        Sav PDF Viewer <b>doesn't</b> collect any personal data.
        <br>
        If you use Google Play, you must read Google Privacy Policy.
        <br class="big-space">
        To get more detailed about the app, read the file <a href="https://github.com/Sav22999/sav-pdf-viewer-pro/blob/main/README.md">"READ ME" on GitHub</a>.
    </p>
</main>


</body>
</html>

<?php
?>