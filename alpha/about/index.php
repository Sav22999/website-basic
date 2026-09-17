<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "About – Notefox";
$selected_menu = "about";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1>About</h1>

        <p>Notefox is a simple note-taking web extension for Firefox. It is a free and open-source project.
            It permits you to take notes in a simple and fast way, and it is designed to be easy to use.</p>

        <p>The main features of Notefox are: take notes, manage notes, search notes, export notes, import notes,
            sticky notes, text formatting, and more. Notefox is designed to be fast and lightweight, and it is
            designed to be compatible with all desktop platforms: Windows, macOS, and Linux.</p>

        <p>Since Notefox 4.0, it is possible to synchronize notes between devices with a Notefox Account.</p>

        <p>The project was born in 2021, and it is developed by Saverio Morelli. The project is developed in
            JavaScript, HTML and CSS, and it uses the WebExtensions API provided by Firefox and other browsers.</p>

        <p>Notefox is available on the Firefox Add-ons website, and it is also available on GitHub. It's available
            also a Chrome and Edge version of Notefox, although I suggest using Firefox!</p>

        <p>Many users contributed to the project by translating Notefox in their language, by suggesting new
            features, or by reporting bugs. I want to thank all the users that contributed to the project.</p>

        <p>If you want to contribute to the project, you can do it on GitHub. You can also translate Notefox in your
            language <a href="https://crowdin.com/project/notefox" target="_blank" rel="noopener">on Crowdin</a>, or you
            can suggest new features or improvements.
            You can also support the project by making a donation: LiberaPay and PayPal are available.</p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
