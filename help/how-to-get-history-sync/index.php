<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to get the Sync history – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
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
            The <strong>Sync history</strong> lets you see the previous versions of the notes you
            synced with your Notefox Account, and download one of them again. It is useful when you
            need to recover a note you deleted or overwrote by mistake on one of your devices.
        </p>
        <p>
            The sync history is <strong>available to all users</strong> with a Notefox Account &mdash;
            no extra steps are needed.
        </p>

        <h2>How it works</h2>
        <p>
            Every time you sync your notes, the server keeps a copy of the previous version. You can
            browse the dated list of past synced versions and download the one you need. The data stays
            encrypted on the server, and no one else can access your notes.
        </p>
        <p>
            The history only contains the versions that were actually synced with the Notefox Account,
            so notes you never synced cannot be recovered from it.
        </p>

        <h2>How to access it</h2>
        <p>
            Log in to your <a href="/my/account/">account dashboard</a> and navigate to the
            <strong>Sync history</strong> section. From there you can see the dated list of your past
            synced versions and download any of them.
        </p>
        <p>
            It is always a good idea to keep your own backup too: see
            <a href="/help/import-export-data/">how to import and export data</a>.
        </p>

        <h2>Help keep this feature free</h2>
        <p>
            Storing every version of every note for all users requires significant server resources.
            If you find the sync history useful, consider
            <a href="/help/pro-features/">supporting the project with a donation</a> &mdash; even a
            small periodic contribution helps cover the costs and keep Notefox free for everyone.
        </p>
        <p class="text-center">
            <a href="/help/pro-features/" class="btn">Support Notefox &rarr;</a>
        </p>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
