<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Run your own Notefox sync server – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Run your own Notefox sync server</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2024-06-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 5.0+</span>
        </div>
        <p>
            You can host the Notefox sync service on your own server. This requires a PHP-enabled web server,
            a MySQL or MariaDB database, and a copy of the Notefox API files.
        </p>

        <h2>1. Create a database</h2>
        <p>
            Create an empty database in your hosting control panel, phpMyAdmin, or with your MySQL client.
            Create a database user with full access to that database, then keep its host name, database name,
            user name, and password ready for the next step.
        </p>

        <h2>2. Create the Notefox tables</h2>
        <p>
            Open the new database in phpMyAdmin, choose <strong>Import</strong>, and select the SQL file below.
            It creates the tables used by the sync API: <code>users</code>, <code>logins</code>,
            <code>tokens</code>, <code>data</code>, <code>sav_data_current</code>, <code>user_keys</code>,
            and <code>rate_limits</code>. It also includes the optional error-log and telemetry tables.
        </p>
        <p class="text-center">
            <a class="btn" href="/help/own-server-for-notefox-sync/notefox-tables.sql" download>Download the Notefox
                database tables</a>
        </p>
        <p>
            If you use a command-line client instead, import the same file with
            <code>mysql -u YOUR_USER -p YOUR_DATABASE &lt; notefox-tables.sql</code>.
        </p>

        <h2>3. Configure the API</h2>
        <p>
            Copy <a href="https://github.com/Sav22999/website-basic/blob/notefox.eu/include/credentials-sample.php"
                    target="_blank" rel="noopener"><code>include/credentials-sample.php</code></a>
            to <code>include/credentials.php</code> on your server. Enter the database connection details and set
            the table variables to the table names created by the import: <code>data</code>, <code>logins</code>,
            <code>users</code>, and <code>tokens</code>. Do not publish <code>credentials.php</code> or commit it
            to a repository.
        </p>

        <h2>4. Upload and connect</h2>
        <p>
            Upload the Notefox API files to your web server, keeping the
            <a href="https://github.com/Sav22999/website-basic/tree/notefox.eu/api" target="_blank"
               rel="noopener"><code>api/</code></a>
            and <a href="https://github.com/Sav22999/website-basic/tree/notefox.eu/include" target="_blank"
                   rel="noopener"><code>include/</code></a>
            directories in the same document root. Make sure PHP can use the <code>mysqli</code> and
            <code>OpenSSL</code> extensions. Then enter your server's API URL in the Notefox extension settings.
        </p>

        <h2>Important</h2>
        <p>
            Use HTTPS and a strong database password. The server stores encrypted note data, but it still handles
            account information and authentication tokens. Keep the API and its database private, backed up, and
            updated with the Notefox version used by your extension.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
