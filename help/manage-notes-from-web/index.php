<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to manage notes from the web – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to manage notes from the web</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2026-09-20</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 5.0+</span>
        </div>

        <p>
            Starting from <strong>Notefox 5.0</strong>, you can view and manage your synced notes directly from the
            Notefox website, without needing the browser extension installed. This is useful when you are on a device
            where the extension is not available, or when you simply want quick access to your notes from any browser.
        </p>

        <h2>Requirements</h2>
        <ul>
            <li>A <strong>Notefox Account</strong> (Sav Account) with at least one synced note.</li>
            <li><strong>Notefox 5.0</strong> or later must have been used to sync the notes (earlier versions use a
                different sync format).
            </li>
        </ul>

        <h2>How to access your notes</h2>
        <ol>
            <li>Go to <a href="/my/">notefox.eu/my</a> and log in with your Notefox Account credentials.</li>
            <li>Once logged in, navigate to your account dashboard.</li>
            <li>From there, you can browse your synced notes, view their content, and download them.</li>
        </ol>

        <h2>What you can do</h2>
        <ul>
            <li><strong>View notes</strong> &mdash; Read all your synced notes organized by website.</li>
            <li><strong>Download notes</strong> &mdash; Export your current notes or any previous version from the sync
                history in JSON format.
            </li>
            <li><strong>Sync history</strong> &mdash; Access past versions of your synced data (if <a
                        href="/help/how-to-get-history-sync/">sync history</a> is enabled for your account).
            </li>
        </ul>

        <h2>Limitations</h2>
        <ul>
            <li>Editing notes from the web requires <a href="/help/pro-features/">pro features</a> to be enabled
                on your account. Without it, the web interface is read-only.
            </li>
            <li>Only notes that have been <strong>synced</strong> with a Notefox Account are visible from the web. Notes
                stored locally only (without an account) cannot be accessed this way.
            </li>
            <li>Your notes are encrypted server-side with your password. Decryption happens when you are authenticated,
                so you must be logged in to view them.
            </li>
        </ul>

    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
