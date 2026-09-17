<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How the search feature works – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/faq/" class="back-link">&larr; Back to FAQ</a>
        <h1>How the search feature works</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2022-11-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0+</span>
        </div>
        <p>
            The search function allows you to search for notes by title, content, or URL. The search is not case
            sensitive and is performed on all notes. The search results are displayed in real time as you type.
        </p>
        <p>
            You can also use multiple search terms separated by semicolon. In this case, the search results will be
            displayed only if at least one of the search terms satisfies the search criteria.
        </p>
        <p>
            An example of a search query is: <code>example;test</code>, in this case the search results will be
            displayed only if the notes contain the word "example" or "test" --or both-- in the title, content, or
            URL.
        </p>
        <p>
            <strong>Important</strong>: do not use spaces between the search terms and the semicolon.
            <code>example;test</code> will search for the words "example" and "test", while <code>example ;
                test</code>
            will search for the words "example " and " test".
        </p>
        <p>
            <span class="notefox-4-0-page">
                <img src="/help/search/search-feature.gif" width="80%" title="Popup with title textbox" alt="Search feature demonstration">
                <small>Demonstration of the search feature</small>
            </span>
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
