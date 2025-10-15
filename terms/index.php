<html>
<head>
    <?php
    $title = "Terms of Service – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "terms";
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
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Terms of Service</h1>

            <p><b>Last update:</b> 14 Oct 2025</p>

            <p>
                Notefox: websites notes is an open-source project developed by <b>Saverio Morelli</b> (<a
                        href="https://saveriomorelli.com/contact-me" target="_blank">saveriomorelli.com/contact-me</a>),
                based in <b>Italy</b>.
                The project is hosted on <b>Aruba.it</b> servers, located in Italy.
            </p>

            <h2 class="text-left">1. Scope</h2>
            <p>
                These Terms of Service apply to the use of the <b>Notefox Account</b>, as well as to the optional <b>Telemetry</b>
                and <b>Error Logs</b> features.
                If you do not use any of these features, the add-on does not send or collect any data.
            </p>

            <h2 class="text-left">2. License</h2>
            <p>
                Notefox is released under the <b>GNU General Public License v3.0</b>.
                You are free to use and modify it, but commercial use or redistribution for profit is not allowed.
            </p>

            <h2 class="text-left">3. Notefox Account</h2>
            <p>
                Notefox Account allows users to <b>synchronize their notes across devices</b> and access them from
                anywhere.
                The account system uses encrypted storage and authentication tokens to protect user data.
            </p>
            <p>
                All data stored server-side are encrypted and cannot be read without the user’s password.
                The service is provided free of charge, but its availability and continuity are not guaranteed.
            </p>

            <h2 class="text-left">4. Data Collection</h2>
            <p>
                The service may collect certain data:
            </p>
            <ul class="text-left">
                <li><b>IP addresses</b> (stored unencrypted, used for security and statistics)</li>
                <li><b>Telemetry data</b> (anonymous usage statistics, optional and user-consented)</li>
                <li><b>Error logs data</b> (anonymous debugging data, optional and user-consented)</li>
            </ul>

            <p>
                Telemetry data are used only for anonymous internal statistics on usability and feature usage,
                while Error logs are used exclusively to identify and fix technical issues.
                All data are anonymized and never shared with third parties.
            </p>

            <h2 class="text-left">5. Responsibility and Disclaimer</h2>
            <p>
                <b>Saverio Morelli</b> does not guarantee that the service will be uninterrupted, error-free, or
                permanently available.
                He is not responsible for any data loss, corruption, or damage resulting from the use or inability to
                use Notefox.
            </p>
            <p>
                Users are solely responsible for how they use the service and for ensuring that their data are backed up
                and handled securely.
            </p>

            <h2 class="text-left">6. Changes to the Service</h2>
            <p>
                The Notefox service may change, be suspended, or terminated at any time without prior notice.
                Users are encouraged to periodically review these Terms of Service.
            </p>

            <h2 class="text-left">7. Contact</h2>
            <p>
                For questions or more details, please visit <a href="https://saveriomorelli.com/contact-me"
                                                               target="_blank">saveriomorelli.com/contact-me</a>.
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>