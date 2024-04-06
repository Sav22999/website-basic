<html>
<head>
    <?php
    $title = "Get help: search – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help-search";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">How the search feature works</h1>
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
                displayed only if the notes contain the word "example" or "test" –or both– in the title, content, or
                URL.
            </p>
            <p>
                <b>Important</b>: do not use spaces between the search terms and the semicolon.
                <br>
                <code>example;test</code> will search for the words "example" and "test", while <code>example ;
                    test</code>
                will search for the words "example " and " test".
            </p>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>