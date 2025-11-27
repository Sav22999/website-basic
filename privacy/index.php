<html>
<head>
    <?php
    $title = "Privacy policy – Notefox";
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
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Privacy Policy</h1>

            <p><b>Last update:</b> 2 Nov 2025</p>

            <p>
                This Privacy Policy applies to Notefox Account, or if you have enabled the "Telemetry" or "Error logs
                automatically sent" features.
                If you do not use any of these, the Notefox add-on does not collect or send any data to the server.
            </p>

            <p>
                <b>Notefox: websites notes</b> is an open-source project developed by <b>Saverio Morelli</b> (<a
                        href="https://saveriomorelli.com/contact-me" target="_blank">saveriomorelli.com/contact-me</a>),
                based in <b>Italy</b>. The service is hosted on <b>Aruba.it</b> servers, located in Italy.
            </p>

            <h2 class="text-left">1. Notefox Account</h2>
            <p>
                Notefox Account allows you to <b>synchronize your notes across multiple devices</b> and access them
                anywhere.
                When using Notefox Account, the service collects and stores:
            </p>
            <ul class="text-left">
                <li>Your <b>email</b> (encrypted with SHA-512)</li>
                <li>Your <b>public IP address</b> (stored unencrypted)</li>
                <li>Your <b>username</b> and <b>data</b> (such as notes, settings, etc.), encrypted with AES-256</li>
            </ul>
            <p>
                All your data are encrypted with your password*, so only you can access them.
                If you lose or forget your password, you will not be able to recover your data in any way.
            </p>
            <p>
                The service will maintain only your 200 most recent synchronized data entries to optimize storage.
                Older entries will be automatically deleted from the server.
            </p>

            <p>
                The service does not use cookies and does not track users. IP addresses are collected only for <b>security</b>
                and <b>statistical</b> purposes,
                and are not shared with third parties.
            </p>

            <h3 class="text-left">Emails</h3>
            <p>
                Notefox may send you emails only in the following cases:
            </p>
            <ul class="text-left">
                <li>When you sign up: to confirm your account</li>
                <li>When your account is created: confirmation message</li>
                <li>When you log in: to confirm your login (Two-Factor Authentication)</li>
                <li>When your account is accessed: login notification</li>
                <li>When you request account deletion: confirmation email</li>
                <li>When your account is deleted: final confirmation</li>
            </ul>
            <p>
                Notefox will never send spam, marketing, or promotional emails, and will never ask for your data,
                password, or any sensitive information.
            </p>

            <h2 class="text-left">2. Telemetry</h2>
            <p>
                If enabled, Notefox collects <b>anonymous telemetry data</b> about the usage of the add-on, to improve
                the product and fix bugs more effectively.
                Telemetry can be enabled with or without a Notefox Account.
            </p>

            <p>
                Collected data may include:
            </p>
            <ul class="text-left">
                <li>Whether a Notefox Account is active (true/false)</li>
                <li>Anonymous user ID (a random string that does not identify you)</li>
                <li>Date and time (client-side and server-side)</li>
                <li>Browser language</li>
                <li>Action performed (e.g., "save-note", "delete-note")</li>
                <li>Context (e.g., "options-page", "popup")</li>
                <li>Website URL (if applicable)</li>
                <li>Browser name and version</li>
                <li>Notefox version</li>
                <li>Operating system (if detectable)</li>
            </ul>

            <p>
                Telemetry data are <b>used exclusively for anonymous internal statistics</b> to analyze usability and
                the most used features.
                They are not shared with third parties and cannot identify you.
            </p>

            <h2 class="text-left">3. Error Logs</h2>
            <p>
                If enabled, Notefox may collect <b>anonymous error logs</b> to detect and fix bugs more effectively.
                This can also be enabled without a Notefox Account.
            </p>
            <p>
                Collected data may include:
            </p>
            <ul class="text-left">
                <li>Anonymous user ID (non-identifiable)</li>
                <li>Date and time (client-side and server-side)</li>
                <li>Context (e.g., "options-page", "popup")</li>
                <li>Error message</li>
                <li>Website URL (if applicable)</li>
                <li>Notefox version</li>
            </ul>
            <p>
                Error logs data are <b>used only for debugging and technical improvement</b>.
                They are anonymous, not shared with third parties, and not used for statistical or marketing purposes.
            </p>
            <p>
                Error logs older than 30 days are automatically deleted from the server.
            </p>

            <h2 class="text-left">4. Security Notice</h2>
            <p>
                If you notice any suspicious activity, please change your password immediately.
                Notefox will never ask for your password or sensitive information.
            </p>

            <h2 class="text-left">5. Liability</h2>
            <p>
                <b>Saverio Morelli</b> is not responsible for any loss, corruption, or misuse of data, nor for damages
                caused by the use or inability to use the service.
                Users are fully responsible for how they use the Notefox add-on and their stored data.
            </p>

            <h2 class="text-left">6. Changes</h2>
            <p>
                The current Privacy Policy can be updated at any time. It is your responsibility to check it
                periodically.
            </p>

            <p><i>* Passwords and emails are encrypted using SHA-512; user data (notes, settings, etc.) are encrypted
                    with AES-256. IP addresses are stored unencrypted.</i></p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>