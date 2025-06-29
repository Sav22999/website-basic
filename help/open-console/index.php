<html>
<head>
    <?php
    $title = "Get help: open console – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-open-console";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to open the Console panel</h1>
            <p>
                The Console panel is a tool included in your web browser that allows you to view and debug some developer
                data. This data is useful for developers to understand how the application works and to identify any
                problems.
            </p>
            <p>
                <b>Firefox users</b>:
                <br>
                <br>
                1. Open the "hamburger" menu (three horizontal lines) in the top right corner of the browser window.
                <br>
                2. Select "More tools" from the menu.
                <br>
                3. Click on "Web Developer Tools"
                <br>
                4. In the Web Developer Tools panel, click on the "Console" tab.
            </p>
            <p>
                <b>Chrome users</b>:
                <br>
                <br>
                1. Open the "kebab" menu (three vertical dots) in the top right corner of the browser window.
                <br>
                2. Select "More tools" from the menu.
                <br>
                3. Click on "Developer tools"
                <br>
                4. In the Developer Tools panel, click on the "Console" tab.
            </p>
            <p>
                <b>Edge users</b>:
                <br>
                <br>
                1. Open the "meatball" menu (three horizontal dots) in the top right corner of the browser window.
                <br>
                2. Select "More tools" from the menu.
                <br>
                3. Click on "Developer tools"
                <br>
                4. In the Developer Tools panel, click on the "Console" tab.
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>