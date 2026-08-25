<html>
<head>
    <?php
    $title = "Run your own Notefox sync server – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content justify">
            <h1 class="title-section center">Run your own Notefox sync server</h1>
            <p>
                You can host the Notefox sync service on your own server. This requires a PHP-enabled web server,
                a MySQL or MariaDB database, and a copy of the Notefox API files.
            </p>

            <h2 class="subtitle-section">1. Create a database</h2>
            <p>
                Create an empty database in your hosting control panel, phpMyAdmin, or with your MySQL client.
                Create a database user with full access to that database, then keep its host name, database name,
                user name, and password ready for the next step.
            </p>

            <h2 class="subtitle-section">2. Create the Notefox tables</h2>
            <p>
                Open the new database in phpMyAdmin, choose <strong>Import</strong>, and select the SQL file below.
                It creates the tables used by the sync API: <code>users</code>, <code>logins</code>,
                <code>tokens</code>, and <code>data</code>. It also includes the optional error-log and telemetry
                tables used by the full Notefox service.
            </p>
            <p class="center">
                <a class="button" href="./notefox-tables.sql" download>Download the Notefox database tables</a>
            </p>
            <p>
                If you use a command-line client instead, import the same file with
                <code>mysql -u YOUR_USER -p YOUR_DATABASE &lt; notefox-tables.sql</code>.
            </p>

            <h2 class="subtitle-section">3. Configure the API</h2>
            <p>
                Copy <code>include/credentials-sample.php</code> to <code>include/credentials.php</code> on your
                server. Enter the database connection details and set the table variables to the table names created
                by the import: <code>data</code>, <code>logins</code>, <code>users</code>, and <code>tokens</code>.
                Do not publish <code>credentials.php</code> or commit it to a repository.
            </p>

            <h2 class="subtitle-section">4. Upload and connect</h2>
            <p>
                Upload the Notefox API files to your web server, keeping the <code>api/</code> and <code>include/</code>
                directories in the same document root. Make sure PHP can use the <code>mysqli</code> and
                <code>OpenSSL</code> extensions. Then enter your server's API URL in the Notefox extension settings.
            </p>

            <h2 class="subtitle-section">Important</h2>
            <p>
                Use HTTPS and a strong database password. The server stores encrypted note data, but it still handles
                account information and authentication tokens. Keep the API and its database private, backed up, and
                updated with the Notefox version used by your extension.
            </p>
        </div>
    </div>
</main>

</body>
</html>
