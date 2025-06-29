<html>
<head>
    <?php
    $title = "Get help: get data for debugging – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-data-debugging";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How to get data for debugging</h1>
            <p>
                In the release 4.4 it's been implemented shortcuts to get some data useful for debugging from the Settings page in the <a href="/help/open-console/">Console</a>.
            </p>
            <p>
                <b>Pay attention to share that data because they can contain important and personal data.</b>
            </p>
            <hr class="hr-big-space">
            <p>
                <b>General data</b> <code>non-sensitive</code>
                <br>
                This doesn't return any personal data.
                <br>
                To get this data, follow this step:
                <br>
                1. Go the Settings page of Notefox
                <br>
                2. Open the Console panel
                <br>
                3. Make five clicks in maximum 5 seconds on the "Notefox" icon (at the bottom right on Desktop)
                <br>
                4. You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=GENERAL=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
                <br class="big-space">
                <b>Data contained</b>
                <br>
                It should appear something like:
                <br>
                • <code>notefox-version</code>: version of the add-on
                <br>
                • <code>web-browser</code>: web browser used
                <br>
                • <code>installation</code>: some data about when the add-on has been installed
                <br>
                • <code>privacy-acceptance</code>: some data about when the privacy has been accepted
                <br>
                • <code>settings</code>: user's settings
            </p>
            <hr>
            <p>
                <b>Notefox account data</b> <code>non-sensitive</code>
                <br>
                This doesn't return sensitive data (the <b>token</b> is automatically removed)
                <br>
                To get this data, follow this step:
                <br>
                1. Go the Settings page of Notefox
                <br>
                2. Open the Console panel
                <br>
                3. Make five clicks in maximum 5 seconds on the "Notefox Account" label in the "Data & Sync" section
                <br>
                4. You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=NOTEFOX-ACCOUNT=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
                <br class="big-space">
                <b>Data contained</b>
                <br>
                It should appear something like:
                <br>
                • <code>notefox-account</code>: data about the notefox account useful to inspect the issue
                <br>
                • <code>last-update</code>: data about when the user makes the last edit
                <br>
                • <code>last-sync</code>: data about the add-on synced last time
            </p>
            <hr>
            <p>
                <b>Notefox account token</b> <code>sensitive</code>
                <br>
                This returns a <b>very</b> sensitive data: the token is the key to encrypt and decrypt data!
                <br>
                You should <b>never</b> share this data with anyone, neither with the developer!
                <br>
                To get this data, follow this step:
                <br>
                1. Go the Settings page of Notefox
                <br>
                2. Open the Console panel
                <br>
                3. Make eight clicks in maximum 5 seconds on the "Manage account" in the "Notefox Account" panel in the "Data & Sync" section
                <br>
                4. You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=NOTEFOX-ACCOUNT-TOKEN=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
                <br class="big-space">
                <b>Data contained</b>
                <br>
                It should appear something like:
                <br>
                • <code>notefox-account-token</code>: your (temporarily) token
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>