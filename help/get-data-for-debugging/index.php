<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to get data for debugging – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to get data for debugging</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2023-06-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.4+</span>
        </div>
        <p>
            In the release 4.4 it's been implemented shortcuts to get some data useful for debugging from the Settings
            page in the <a href="/help/open-console/">Console</a>.
        </p>
        <p>
            <strong>Pay attention to share that data because they can contain important and personal data.</strong>
        </p>

        <hr>

        <h2>General data <code>non-sensitive</code></h2>
        <p>
            This doesn't return any personal data.
        </p>
        <p>
            To get this data, follow this step:
        </p>
        <ol>
            <li>Go the Settings page of Notefox</li>
            <li>Open the Console panel</li>
            <li>Make five clicks in maximum 5 seconds on the "Notefox" icon (at the bottom right on Desktop)</li>
            <li>You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=GENERAL=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
            </li>
        </ol>
        <p>
            <strong>Data contained</strong>
        </p>
        <p>
            It should appear something like:
        </p>
        <ul>
            <li><code>notefox-version</code>: version of the add-on</li>
            <li><code>web-browser</code>: web browser used</li>
            <li><code>installation</code>: some data about when the add-on has been installed</li>
            <li><code>privacy-acceptance</code>: some data about when the privacy has been accepted</li>
            <li><code>settings</code>: user's settings</li>
        </ul>

        <hr>

        <h2>Notefox account data <code>non-sensitive</code></h2>
        <p>
            This doesn't return sensitive data (the <strong>token</strong> is automatically removed)
        </p>
        <p>
            To get this data, follow this step:
        </p>
        <ol>
            <li>Go the Settings page of Notefox</li>
            <li>Open the Console panel</li>
            <li>Make five clicks in maximum 5 seconds on the "Notefox Account" label in the "Data & Sync" section</li>
            <li>You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=NOTEFOX-ACCOUNT=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
            </li>
        </ol>
        <p>
            <strong>Data contained</strong>
        </p>
        <p>
            It should appear something like:
        </p>
        <ul>
            <li><code>notefox-account</code>: data about the notefox account useful to inspect the issue</li>
            <li><code>last-update</code>: data about when the user makes the last edit</li>
            <li><code>last-sync</code>: data about the add-on synced last time</li>
        </ul>

        <hr>

        <h2>Notefox account token <code>sensitive</code></h2>
        <p>
            This returns a <strong>very</strong> sensitive data: the token is the key to encrypt and decrypt data!
        </p>
        <p>
            You should <strong>never</strong> share this data with anyone, neither with the developer!
        </p>
        <p>
            To get this data, follow this step:
        </p>
        <ol>
            <li>Go the Settings page of Notefox</li>
            <li>Open the Console panel</li>
            <li>Make eight clicks in maximum 5 seconds on the "Manage account" in the "Notefox Account" panel in the
                "Data & Sync" section
            </li>
            <li>You will see in the Console panel a text which should start with <code>//∨∨∨∨∨∨∨∨∨∨∨∨=NOTEFOX-ACCOUNT-TOKEN=∨∨∨∨∨∨∨∨∨∨∨∨//</code>
            </li>
        </ol>
        <p>
            <strong>Data contained</strong>
        </p>
        <p>
            It should appear something like:
        </p>
        <ul>
            <li><code>notefox-account-token</code>: your (temporarily) token</li>
        </ul>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
