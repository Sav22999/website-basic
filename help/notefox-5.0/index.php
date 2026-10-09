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
            access to sync history and web note editing.
            If you find them useful, please consider <a href="/help/pro-features/">supporting the project</a>.
        </p>

        <h2>Manage notes from the web</h2>
        <p>
            View, browse, and edit all your synced notes directly from the
            <a href="/my/account/notes/">Notefox website</a>, without the browser extension installed.
            The web interface supports search, sorting, rich text formatting, tags, colours, folders, and
            multiple notes for the same page.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/synced-notes-web.gif" width="80%" title="Synced notes from the web"
                     alt="Synced notes from the web">
                <small>Account > Synced notes</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/edit-notes-web.gif" width="80%" title="Edit notes from the web"
                     alt="Edit notes from the web">
                <small>Synced notes > Edit a note directly from the web</small>
            </span>
        </p>

        <h2>Sync history, on the web and in the add-on</h2>
        <p>
            Browse the previous versions of your synced notes and download them from the
            <a href="/my/account/sync-history/">account portal</a>, or open the sync history directly from the
            add-on and restore a previous version in a few clicks.
            Learn more in <a href="/help/how-to-get-history-sync/">how to get the sync history</a>.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/sync-history-web.gif" width="80%" title="Sync history from the web"
                     alt="Sync history from the web">
                <small>Account > Sync history</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/synced-history-addon.gif" width="80%" title="Sync history in the add-on"
                     alt="Sync history in the add-on">
                <small>Settings > Data &amp; Sync > Sync history</small>
            </span>
        </p>

        <h2>Multiple notes per type</h2>
        <p>
            Create multiple notes for the same type (global, domain, or page). A "New note" button lets you
            add extra notes, and a list view shows all notes for the current URL. Each note has its own
            title, content, colour, and sticky-note parameters.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/multiple-notes-option.gif" width="80%" title="Enable multiple notes per type"
                     alt="Enable multiple notes per type">
                <small>Settings > Enable multiple notes per type</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/multiple-notes.gif" width="80%" title="Multiple notes per type"
                     alt="Multiple notes per type">
                <small>Create and switch between multiple notes for the same page</small>
            </span>
        </p>

        <h2>Multiple sticky notes</h2>
        <p>
            Each note can have its own independent sticky note on the page, with its own position, size,
            opacity, and colour.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/multiple-sticky-notes.gif" width="80%" title="Multiple sticky notes"
                     alt="Multiple sticky notes">
                <small>Multiple independent sticky notes on the same page</small>
            </span>
        </p>

        <h2>Pin sticky notes</h2>
        <p>
            Sticky notes can now be <strong>pinned</strong> to stay fixed on screen while you scroll, or unpinned
            to scroll with the page. A new option in Settings lets you choose whether sticky notes open pinned
            by default.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/pin-unpin-sticky-notes.gif" width="80%" title="Pin and unpin sticky notes"
                     alt="Pin and unpin sticky notes">
                <small>Pin and unpin sticky notes</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/pinned-by-default-option.gif" width="80%" title="Pinned by default"
                     alt="Pinned by default">
                <small>Settings > Pinned by default</small>
            </span>
        </p>

        <h2>Context menu: create note from selected text</h2>
        <p>
            A new option in Settings enables a "Create note via Notefox" entry in the right-click context menu.
            Select text on any page and right-click to add it instantly to the note for that page.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/created-from-selected-text-option.gif" width="80%" title="Create note from selected text"
                     alt="Create note from selected text">
                <small>Settings > Create note from selected text</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/created-from-selected-text.gif" width="80%" title="Context menu: create note"
                     alt="Context menu: create note">
                <small>Right-click > Create note via Notefox</small>
            </span>
        </p>

        <h2>Expand all websites by default</h2>
        <p>
            A new option in Settings lets you expand all domain groups by default in "All notes",
            so you can see all your notes at a glance without clicking each domain.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/all-websites-expanded-by-default-option.gif" width="80%" title="Expand all websites by default"
                     alt="Expand all websites by default">
                <small>Settings > Expand all websites by default</small>
            </span>
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/all-websites-collapsed-expanded.gif" width="80%" title="Collapsed and expanded websites"
                     alt="Collapsed and expanded websites">
                <small>All notes > Websites collapsed and expanded</small>
            </span>
        </p>

        <h2>Search in settings</h2>
        <p>
            The Settings page now has a search bar to quickly find the option you are looking for.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/settings-search.gif" width="80%" title="Search in settings"
                     alt="Search in settings">
                <small>Settings > Search bar</small>
            </span>
        </p>

        <h2>Improved search and filters</h2>
        <p>
            The search bar now uses a chip-based input: press Enter to add a search term, Backspace to
            remove the last one. Type and colour filters support multi-selection. Folder and tag filters
            use autocomplete suggestions.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/new-search.gif" width="80%" title="Chip-based search and filters"
                     alt="Chip-based search and filters">
                <small>All notes > Search and filter with chips</small>
            </span>
        </p>

        <h2>Note editor improvements</h2>
        <p>
            The folder and tag fields in the note editor now use the same chip-based input with
            autocomplete suggestions from your existing folders and tags.
        </p>

        <h2>Active sessions management</h2>
        <p>
            View and revoke active sessions from the <a href="/my/account/sessions/">account portal</a>.
            Each session shows IP address, login date, and expiry. Sessions expire after 30 days.
        </p>
        <p>
            <span class="notefox-new-version-page">
                <img src="/help/notefox-5.0/active-sessions-web.gif" width="80%" title="Active sessions management"
                     alt="Active sessions management">
                <small>Account > Active sessions</small>
            </span>
        </p>

        <h2>Updated Terms of Service and Privacy Policy</h2>
        <p>
            The <a href="/terms/">Terms of Service</a> and the <a href="/privacy/">Privacy Policy</a>
            have been updated for full GDPR and Italian privacy compliance, with clear data retention
            periods, legal basis, and a comprehensive "Your Rights" section.
            Both pages now feature an Italian / English tab bar.
        </p>

        <h2>Other improvements</h2>
        <ul>
            <li>Improved account dashboard with direct access to synced notes, sync history, and active sessions.</li>
            <li>Updated note card design with a rounded colour bar matching the extension style.</li>
            <li>Updated help articles and FAQ to reflect the new features.</li>
            <li>The website is now available in 13 languages.</li>
        </ul>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
