<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How the search feature works – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How the search feature works</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2026-10-03</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 5.0+</span>
        </div>

        <h2>Search bar</h2>
        <p>
            The search bar in the "All notes" page lets you find notes by typing keywords.
            Search results update in real time as you type.
        </p>
        <p>
            Search terms are <strong>chip-based</strong>: type a keyword and press <kbd>Enter</kbd> to add it as a search chip.
            You can add multiple chips to refine your search. Press <kbd>Backspace</kbd> on an empty search field to remove the last chip.
            You can also click the <strong>&times;</strong> button on any chip to remove it.
        </p>
        <p>
            Text typed in the search field (not yet confirmed as a chip) is also used for live filtering.
        </p>

        <h2>What is searched</h2>
        <p>
            Each search term is matched against the following fields:
        </p>
        <ul>
            <li><strong>Note content</strong> (text of the note)</li>
            <li><strong>Title</strong></li>
            <li><strong>URL</strong></li>
            <li><strong>Domain</strong></li>
            <li><strong>Folder</strong> name</li>
            <li><strong>Tags</strong></li>
            <li><strong>Last update</strong> date</li>
            <li><strong>Page content</strong> (if the "Search in page content" option is enabled in Settings)</li>
        </ul>
        <p>
            The search is <strong>not case sensitive</strong>.
        </p>

        <h2>How multiple terms work</h2>
        <p>
            When you have multiple search chips, a note is shown if <strong>any</strong> of the terms matches
            at least one of the fields listed above (OR logic). This makes it easy to find notes across different
            topics or URLs at once.
        </p>

        <h2>Filters</h2>
        <p>
            In addition to the search bar, you can use <strong>filters</strong> to narrow down the results.
            Click the <strong>Filter</strong> button to open the filter panel. Available filters:
        </p>
        <ul>
            <li><strong>Type</strong> &mdash; filter by note type: Global, Domain, or Page. Multiple types can be selected at once.</li>
            <li><strong>Colour</strong> &mdash; filter by note colour. Multiple colours can be selected at once.</li>
            <li><strong>Folder</strong> &mdash; filter by folder name. Type to search and select from suggestions, or type a custom value.</li>
            <li><strong>Tags</strong> &mdash; filter by tags. Same chip-based input as folder.</li>
        </ul>
        <p>
            Filters and search terms work together: a note must match <strong>all</strong> active filters
            <strong>and</strong> at least one search term (if any) to be displayed.
        </p>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
