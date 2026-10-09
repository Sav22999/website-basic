<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Guides & FAQ – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/" class="back-link">Back to Help</a>
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
                                href="/help/notefox-account/" class="link-list-link">How the Notefox Account
                            works</a>
                    </li>
                    <li class="link-list-item" data-search="notefox account v2 sav account what's new"><a
                                href="/help/notefox-account-v2/" class="link-list-link">Notefox Account v2 (Sav
                            Account)</a></li>
                    <li class="link-list-item" data-search="account verification email code confirm signup"><a
                                href="/help/account-verification/" class="link-list-link">Account verification
                            procedure</a>
                    </li>
                    <li class="link-list-item" data-search="import export data backup"><a
                                href="/help/import-export-data/"
                                class="link-list-link">How to import
                            and export data</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Features</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="manage notes web browser online view download"><a
                                href="/help/manage-notes-from-web/" class="link-list-link">How to manage notes
                            from the
                            web</a></li>
                    <li class="link-list-item" data-search="search feature find notes"><a href="/help/search/"
                                                                                          class="link-list-link">How the
                            search
                            feature works</a></li>
                    <li class="link-list-item" data-search="inline edit note all notes"><a
                                href="/help/inline-edit/"
                                class="link-list-link">How to edit a
                            note inline</a></li>
                    <li class="link-list-item" data-search="local data storage where saved"><a
                                href="/help/local-data-storage/" class="link-list-link">Where your data is stored
                            locally</a></li>
                    <li class="link-list-item" data-search="sync history get download"><a
                                href="/help/how-to-get-history-sync/" class="link-list-link">How to get the sync
                            history</a></li>
                    <li class="link-list-item"
                        data-search="pro features donate liberapay premium sync history edit notes web advanced donation support"><a
                                href="/help/pro-features/" class="link-list-link">Advanced features &amp; donations</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Version highlights</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="notefox 5.0 overview new version features free pro web edit"><a
                                href="/help/notefox-5.0/" class="link-list-link">Notefox 5.0 &mdash; What's
                            new</a></li>
                    <li class="link-list-item" data-search="notefox 4.6 overview new version features"><a
                                href="/help/notefox-4.6/" class="link-list-link">Notefox 4.6 &mdash; What's
                            new</a></li>
                    <li class="link-list-item" data-search="notefox 4.0 overview new version features account"><a
                                href="/help/notefox-4.0/" class="link-list-link">Notefox 4.0 &mdash; What's
                            new</a></li>
                    <li class="link-list-item" data-search="switch upgrade migrate 4.0"><a
                                href="/help/switch-to-4-0/"
                                class="link-list-link">How to switch
                            to Notefox 4.0</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Troubleshooting</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="download error logs file"><a
                                href="/help/download-error-logs/"
                                class="link-list-link">How to download
                            the error logs</a></li>
                    <li class="link-list-item" data-search="delete error logs clear"><a
                                href="/help/delete-error-logs/"
                                class="link-list-link">How to delete the
                            error logs</a></li>
                    <li class="link-list-item" data-search="data debugging get"><a
                                href="/help/get-data-for-debugging/"
                                class="link-list-link">How to get data for
                            debugging</a></li>
                    <li class="link-list-item" data-search="console panel open developer"><a
                                href="/help/open-console/"
                                class="link-list-link">How to open
                            the console panel</a></li>
                </ul>
            </div>

            <div class="faq-section">
                <h2>Advanced</h2>
                <ul class="link-list">
                    <li class="link-list-item" data-search="translate language crowdin"><a href="/help/translate/"
                                                                                           class="link-list-link">How to
                            translate Notefox</a></li>
                    <li class="link-list-item" data-search="own server sync self-hosted"><a
                                href="/help/own-server-for-notefox-sync/" class="link-list-link">How to run your
                            own sync
                            server</a></li>
                </ul>
            </div>
        </div>

        <div class="search-empty hidden2" id="help-empty">
            <div class="search-empty-card">
                <svg class="search-empty-icon" width="40" height="40" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    <line x1="8" y1="11" x2="14" y2="11"/>
                </svg>
                <p class="search-empty-title">Can't find what you're looking for?</p>
                <p class="search-empty-desc">Try a different search term, or reach out to us directly.</p>
                <div class="search-empty-actions">
                    <a href="/contact/" class="btn btn--small">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Contact us
                    </a>
                    <a href="https://github.com/Sav22999/websites-notes/issues" target="_blank" rel="noopener"
                       class="btn btn--secondary btn--small">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
                        </svg>
                        Open an issue
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
