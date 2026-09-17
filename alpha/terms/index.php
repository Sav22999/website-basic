<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Terms of Service – Notefox";
$selected_menu = "terms";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1>Terms of Service</h1>

        <p><strong>Last update:</strong> 14 Oct 2025</p>

        <p>Notefox: websites notes is an open-source project developed by <strong>Saverio Morelli</strong>
            (<a href="https://saveriomorelli.com/contact-me" target="_blank" rel="noopener">saveriomorelli.com/contact-me</a>),
            based in <strong>Italy</strong>. The project is hosted on <strong>Aruba.it</strong> servers, located in Italy.</p>

        <h2>1. Scope</h2>
        <p>These Terms of Service apply to the use of the <strong>Notefox Account</strong>, as well as to the optional
            <strong>Telemetry</strong> and <strong>Error Logs</strong> features.
            If you do not use any of these features, the add-on does not send or collect any data.</p>

        <h2>2. License</h2>
        <p>Notefox is released under the <strong>Mozilla Public License v2.0 (MPL v2)</strong>.
            You are free to use, modify, and distribute it under the terms of the MPL v2.</p>

        <h2>3. Notefox Account</h2>
        <p>Notefox Account allows users to <strong>synchronize their notes across devices</strong> and access them from
            anywhere. The account system uses encrypted storage and authentication tokens to protect user data.</p>
        <p>All data stored server-side are encrypted and cannot be read without the user's password.
            The service is provided free of charge, but its availability and continuity are not guaranteed.</p>

        <h2>4. Data Collection</h2>
        <p>The service may collect certain data:</p>
        <ul>
            <li><strong>IP addresses</strong> (stored unencrypted, used for security and statistics)</li>
            <li><strong>Telemetry data</strong> (anonymous usage statistics, optional and user-consented)</li>
            <li><strong>Error logs data</strong> (anonymous debugging data, optional and user-consented)</li>
        </ul>
        <p>Telemetry data are used only for anonymous internal statistics on usability and feature usage,
            while Error logs are used exclusively to identify and fix technical issues.
            All data are anonymized and never shared with third parties.</p>

        <h2>5. Responsibility and Disclaimer</h2>
        <p><strong>Saverio Morelli</strong> does not guarantee that the service will be uninterrupted, error-free, or
            permanently available. He is not responsible for any data loss, corruption, or damage resulting from the use
            or inability to use Notefox.</p>
        <p>Users are solely responsible for how they use the service and for ensuring that their data are backed up
            and handled securely.</p>

        <h2>6. Changes to the Service</h2>
        <p>The Notefox service may change, be suspended, or terminated at any time without prior notice.
            Users are encouraged to periodically review these Terms of Service.</p>

        <h2>7. Contact</h2>
        <p>For questions or more details, please visit
            <a href="https://saveriomorelli.com/contact-me" target="_blank" rel="noopener">saveriomorelli.com/contact-me</a>.</p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
