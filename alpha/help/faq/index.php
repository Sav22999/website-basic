<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Guides & FAQ – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/" class="back-link">Back to Help</a>
        <h1>Guides &amp; FAQ</h1>
        <p>Find answers to common questions and step-by-step guides for using Notefox.</p>

        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" class="search-input" id="help-search" placeholder="Search guides..."
                   autocomplete="off">
        </div>

        <div id="faq-sections">
            <div class="faq-section">
                <h2>Getting started</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="notefox account how works login signup"><a
                                href="/alpha/help/notefox-account/" class="link-list-link">How the Notefox Account
                            works</a>
                    </li>
                    <li class="link-list-item" data-search="notefox account v2 sav account what's new"><a
                                href="/alpha/help/notefox-account-v2/" class="link-list-link">Notefox Account v2 (Sav
                            Account)</a></li>
                    <li class="link-list-item" data-search="account verification email code confirm signup"><a
                                href="/alpha/help/account-verification/" class="link-list-link">Account verification
                            procedure</a>
                    </li>
                    <li class="link-list-item" data-search="import export data backup"><a
                                href="/alpha/help/import-export-data/"
                                class="link-list-link">How to import
                            and export data</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Features</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="manage notes web browser online view download"><a
                                href="/alpha/help/manage-notes-from-web/" class="link-list-link">How to manage notes
                            from the
                            web</a></li>
                    <li class="link-list-item" data-search="search feature find notes"><a href="/alpha/help/search/"
                                                                                          class="link-list-link">How the
                            search
                            feature works</a></li>
                    <li class="link-list-item" data-search="inline edit note all notes"><a
                                href="/alpha/help/inline-edit/"
                                class="link-list-link">How to edit a
                            note inline</a></li>
                    <li class="link-list-item" data-search="local data storage where saved"><a
                                href="/alpha/help/local-data-storage/" class="link-list-link">Where your data is stored
                            locally</a></li>
                    <li class="link-list-item" data-search="sync history get download"><a
                                href="/alpha/help/how-to-get-history-sync/" class="link-list-link">How to get the sync
                            history</a></li>
                    <li class="link-list-item" data-search="pro features donate liberapay premium sync history edit notes web"><a
                                href="/alpha/help/pro-features/" class="link-list-link">Pro features</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Version highlights</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="notefox 4.6 overview new version features"><a
                                href="/alpha/help/notefox-4.6/" class="link-list-link">Notefox 4.6 &mdash; What's
                            new</a></li>
                    <li class="link-list-item" data-search="notefox 4.0 overview new version features account"><a
                                href="/alpha/help/notefox-4.0/" class="link-list-link">Notefox 4.0 &mdash; What's
                            new</a></li>
                    <li class="link-list-item" data-search="switch upgrade migrate 4.0"><a
                                href="/alpha/help/switch-to-4-0/"
                                class="link-list-link">How to switch
                            to Notefox 4.0</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Troubleshooting</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="download error logs file"><a
                                href="/alpha/help/download-error-logs/"
                                class="link-list-link">How to download
                            the error logs</a></li>
                    <li class="link-list-item" data-search="delete error logs clear"><a
                                href="/alpha/help/delete-error-logs/"
                                class="link-list-link">How to delete the
                            error logs</a></li>
                    <li class="link-list-item" data-search="data debugging get"><a
                                href="/alpha/help/get-data-for-debugging/"
                                class="link-list-link">How to get data for
                            debugging</a></li>
                    <li class="link-list-item" data-search="console panel open developer"><a
                                href="/alpha/help/open-console/"
                                class="link-list-link">How to open
                            the console panel</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Advanced</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="translate language crowdin"><a href="/alpha/help/translate/"
                                                                                           class="link-list-link">How to
                            translate Notefox</a></li>
                    <li class="link-list-item" data-search="own server sync self-hosted"><a
                                href="/alpha/help/own-server-for-notefox-sync/" class="link-list-link">How to run your
                            own sync
                            server</a></li>
                </ul>
            </div>
        </div>

        <div class="search-empty hidden2" id="help-empty">
            Can't find what you're looking for?
            <a href="/alpha/contact/">Contact us</a>
            or <a href="https://github.com/Sav22999/websites-notes/issues" target="_blank" rel="noopener">open an issue
                on GitHub</a>.
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
