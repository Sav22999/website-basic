<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Switch to Notefox 4.0 – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to switch to Notefox 4.0</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2022-10-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.0</span>
        </div>
        <p>
            In the next days there will be released Notefox 4.0.
        </p>
        <p>
            This version will be the biggest update of Notefox since its creation, and it will bring a lot of new
            features and improvements: new UI and UX, new features, Notefox Account for synchronization, and more.
        </p>
        <p>
            Since the update is so big, it could cause some problems in the data of the users. To avoid this, it is
            recommended to make a backup of your notes before updating to the new version: export your notes to a
            file and save it in a safe place.
        </p>
        <p>
            After the update, if you have any problem with your notes, you can import the backup file to recover
            them.
        </p>
        <p>
            If you want to see the new Notefox 4.0 before the release, you can see the <a
                    href="/alpha/help/notefox-4.0/">preview
                page</a>.
        </p>
        <p>
            If you have any question or problem, you can contact us at <a href="/alpha/help/">this page</a>.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
