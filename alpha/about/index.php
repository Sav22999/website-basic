<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('about.title');
$description = t('meta.about');
$canonical_path = "/alpha/about/";
$selected_menu = "about";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1><?php echo t('about.heading'); ?></h1>

        <p><?php echo t('about.p1'); ?></p>
        <p><?php echo t('about.p2'); ?></p>
        <p><?php echo t('about.p3'); ?></p>
        <p><?php echo t('about.p4'); ?></p>
        <p><?php echo t('about.p5'); ?></p>
        <p><?php echo t('about.p6'); ?></p>
        <p><?php echo t('about.p7'); ?></p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
