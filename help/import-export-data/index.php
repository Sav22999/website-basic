<html>
<head>
    <?php
    $title = "Get help: import and export data – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-import-export-data";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to import and export data</h1>
            <p>
                In Notefox 4.0 "import" and "export" features have been moved to the Settings page. You can import and
                export data in JSON format. You can also import and export data from/to a JSON file.
            </p>
            <p>
                <span class="notefox-4-0-page">
                    <img src="import.gif" width="80%" title="Import data"/>
                    <br>
                    <small>Import data</small>
                </span>
                <br>
                <span class="notefox-4-0-page">
                    <img src="export.gif" width="80%" title="Export data"/>
                    <br>
                    <small>Export data</small>
                </span>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>