<html>
<head>
    <?php
    $title = "Get help: inline edit – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-inline-edit";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to edit inline a note</h1>
            <p>
                In Notefox 4.0 you can edit <b>inline</b>: this means you can edit the note directly in the All notes
                page without opening the Popup. To edit a note inline, click on the "Edit inline" button of the note you
                want to edit, and the note and title will become editable.
            </p>
            <p>
                <span class="notefox-4-0-page">
                    <img src="inline-edit.gif" width="80%" title="Edit inline"/>
                    <br>
                    <small>Demonstration of the inline edit feature</small>
                </span>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>