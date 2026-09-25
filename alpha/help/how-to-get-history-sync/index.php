<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to get the Sync history – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to get the sync history</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2026-09-25</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.6+</span>
        </div>

        <p>
            The <strong>Sync history</strong> is part of <a href="/alpha/help/pro-features/">pro features</a>,
            a set of extra capabilities reserved for people who support the project with a donation.
            It lets you see the previous versions of the notes you synced with your Notefox Account, and
            download one of them again. It is useful when you need to recover a note you deleted or
            overwrote by mistake on one of your devices.
        </p>

        <h2>How it works</h2>
        <p>
            Every account starts without the sync history. While it is not enabled, the history stays
            hidden and the sync keeps working exactly as usual &mdash; only the latest sync is stored.
            Once pro features are granted on your account, the server begins keeping previous versions
            of your synced notes.
        </p>
        <p>
            The history only contains the versions that were actually synced with the Notefox Account,
            so notes you never synced cannot be recovered from it. The data stays encrypted on the
            server, and enabling the sync history does not give anyone else access to your notes.
        </p>

        <h2>How to get it</h2>
        <p>
            The sync history is included in <strong>pro features</strong>. To get it, follow the
            instructions on the <a href="/alpha/help/pro-features/">pro features</a> page: make a
            donation on LiberaPay (via Stripe) and then contact the developer with your Login ID.
        </p>
        <p class="text-center">
            <a href="/alpha/help/pro-features/" class="btn">Pro features &rarr;</a>
        </p>

        <h2>After it is enabled</h2>
        <p>
            Once pro features have been granted, the sync history becomes available in Notefox for
            your account: you can see the dated list of your past synced versions and download the one
            you need. Nothing else changes &mdash; your notes, your password, and the normal syncing
            stay the same. It is always a good idea to keep your own backup too: see
            <a href="/alpha/help/import-export-data/">how to import and export data</a>.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
