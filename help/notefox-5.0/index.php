<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Notefox 5.0 – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Notefox 5.0</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2026-09-30</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 5.0</span>
        </div>
        <p>
            Notefox 5.0 is a major update focused on making the best features available to <strong>everyone</strong>,
            improving the web experience, and strengthening transparency and privacy compliance.
        </p>

        <h2>All advanced features are now free</h2>
        <p>
            The features previously known as "pro features" &mdash; which required a donation to unlock &mdash;
            are now <strong>available to all users for free</strong>. Every Notefox Account now has full
            access to:
        </p>
        <ul>
            <li><strong>Sync history</strong> &mdash; browse and download previous versions of your synced
                notes to recover data you accidentally deleted or overwrote.</li>
            <li><strong>View and edit notes from the web</strong> &mdash; access your synced notes directly
                from the Notefox website, with full editing capabilities including rich text formatting,
                tags, colours, and folders.</li>
        </ul>
        <p>
            These features require significant server resources to run. If you find them useful, please
            consider <a href="/help/pro-features/">supporting the project with a donation</a> &mdash;
            even a small periodic contribution helps keep Notefox free and sustainable for everyone.
        </p>

        <h2>Manage notes from the web</h2>
        <p>
            Starting from Notefox 5.0, you can <strong>view, browse, and edit</strong> all your synced
            notes directly from the <a href="/my/account/notes/">Notefox website</a>, without the browser
            extension installed. This is especially useful when you are on a device where the extension
            is not available.
        </p>
        <p>
            The web interface supports the full set of features: search, sorting, rich text formatting,
            tags, colours, and folders. Learn more in the
            <a href="/help/manage-notes-from-web/">dedicated guide</a>.
        </p>

        <h2>Updated Terms of Service and Privacy Policy</h2>
        <p>
            The <a href="/terms/">Terms of Service</a> and the <a href="/privacy/">Privacy Policy</a>
            have been updated to be fully transparent and compliant with the <strong>GDPR</strong> and
            Italian privacy regulations. Key additions include:
        </p>
        <ul>
            <li>Clear <strong>data retention periods</strong> for every type of data collected.</li>
            <li>Explicit <strong>legal basis</strong> for all data processing activities.</li>
            <li>A comprehensive <strong>Your Rights</strong> section covering access, rectification,
                erasure, portability, and the right to lodge a complaint with the Italian Data Protection
                Authority (Garante Privacy).</li>
            <li>A <strong>Prohibited Use</strong> clause making it clear that the service must not be
                used for illegal or illegitimate purposes.</li>
            <li>Explicit statement that Italian and EU law apply, and that the developer assumes no
                responsibility for misuse of the service.</li>
        </ul>

        <h2>Other improvements</h2>
        <ul>
            <li>Improved account dashboard with direct access to synced notes and sync history.</li>
            <li>Updated help articles and FAQ to reflect the new features and policies.</li>
            <li>The website is now available in 13 languages.</li>
        </ul>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
