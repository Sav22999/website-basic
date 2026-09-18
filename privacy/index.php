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
    <div class="horizontal-center width-80-perc">
        <p class="justify hidden">
            Sav PDF Viewer <b>doesn't</b> collect any personal data.
            <br>
            If you use Google Play, you must read Google Privacy Policy.
            <br>
            To get more detailed about the app, read the file <a
                    href="https://github.com/Sav22999/sav-pdf-viewer-pro/blob/main/README.md">"READ ME" on GitHub</a>.
        </p>
        <p class="justify">
            Data Controller: Saverio Morelli
            <br>
            Contact: https://saveriomorelli.com/contact
        </p>
        <p class="justify">
            <b>1. What data we collect</b>
            <br>
            Sav PDF Viewer does not collect any personal data from users. No usage data, personal identifiers, or
            location data are collected, stored, or transmitted to any external servers.
        </p>
        <p class="justify">
            <b>2. Third-party services</b>
            <br>
            We do not use any third-party analytics, advertising, or crash-reporting services that collect personal user
            data. (If in the future third-party libraries are introduced, we will update this policy accordingly.)
        </p>
        <p class="justify">
            <b>3. Legal basis</b>
            <br>
            Since no personal data is collected or processed, we do not rely on a specific legal basis under GDPR for
            data processing.
        </p>
        <p class="justify">
            <b>4. User rights</b>
            <br>
            Even though we don’t collect personal data, you have the following rights:
            <br>
            - Right to contact us: you can reach out via https://saveriomorelli.com/contact
            <br>
            - You can request information about any future changes to this privacy policy.
        </p>
        <p class="justify">
            <b>5. Security</b>
            <br>
            Because we do not collect or store any personal data, the risk of data breach is minimal. We do, however,
            follow best practices in app development to ensure that the app remains secure.
        </p>
        <p class="justify">
            <b>7. Governing Law</b>
            <br>
            This Privacy Policy is governed by the applicable laws in Italy and, where applicable, by the European
            Union’s GDPR.
        </p>
        <p class="justify">
            <b>8. Contact</b>
            <br>
            If you have any questions or concerns about this Privacy Policy, please contact:
            <br>
            Saverio Morelli — via https://saveriomorelli.com/contact
        </p>
        <p class="justify">
            <i>Last update: 22 November 2025</i>
        </p>
    </div>
</main>


</body>
</html>

<?php
?>