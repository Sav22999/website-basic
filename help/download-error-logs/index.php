<html>
<head>
    <?php
    $title = "Get help: download error logs – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-download-error-logs";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to download the Error logs file</h1>
            <p>
                Since the 4.4.4 version, Notefox has a new feature that allows you to download the error logs file. This
                file contains all the errors that Notefox has encountered during its execution. This feature is useful
                for debugging and troubleshooting issues with the add-on.
                <br>
                In particular, it is useful for the Notefox developer to understand the problems you are experiencing
                and to
                provide you with the best possible support for your issue and to fix it and improve the add-on.
            </p>
            <p>
                <b>Download the error logs</b>:
                <br>
                <br>
                1. Open the Settings page (of Notefox)
                <br>
                2. Go to the "Advanced" section
                <br>
                3. Find the "Error logs" subsection
                <br>
                4. Click on the "Show error logs" button
                <br>
                5. A new popup panel will open with the error logs, there click on the "Download the logs file" button
                <br>
                6. A new file will be downloaded to your computer with the name "notefox_error_logs_XYZ.json"
            </p>
            <p>
                The downloaded file contains only information about the errors that Notefox has encountered during its
                execution. It does not contain any personal information or sensitive data, although it's advisable to
                share it only with the Notefox developer or trusted people.
                <br>
                In particular, the file contains the following information:
                <br>
                • Notefox information (version, os, browser)
                <br>
                • Settings configuration (all settings)
                <br>
                • Errors details (datetime, context, error message and web page where the error occurred)
                <br>
                <b>All the information contained and sent, will be treated with the utmost confidentiality and will not be
                    shared with third parties.</b>
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>