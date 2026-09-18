<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('terms.title');
$selected_menu = "terms";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1><?php echo t('terms.heading'); ?></h1>

        <p><strong><?php echo t('terms.last_update'); ?></strong> <?php echo t('terms.last_update_date'); ?></p>

        <p><?php echo t('terms.intro'); ?></p>

        <h2><?php echo t('terms.s1_title'); ?></h2>
        <p><?php echo t('terms.s1_text'); ?></p>

        <h2><?php echo t('terms.s2_title'); ?></h2>
        <p><?php echo t('terms.s2_text'); ?></p>

        <h2><?php echo t('terms.s3_title'); ?></h2>
        <p><?php echo t('terms.s3_p1'); ?></p>
        <p><?php echo t('terms.s3_p2'); ?></p>

        <h2><?php echo t('terms.s4_title'); ?></h2>
        <p><?php echo t('terms.s4_intro'); ?></p>
        <ul>
            <li><?php echo t('terms.s4_item_ip'); ?></li>
            <li><?php echo t('terms.s4_item_telemetry'); ?></li>
            <li><?php echo t('terms.s4_item_errors'); ?></li>
        </ul>
        <p><?php echo t('terms.s4_usage'); ?></p>

        <h2><?php echo t('terms.s5_title'); ?></h2>
        <p><?php echo t('terms.s5_p1'); ?></p>
        <p><?php echo t('terms.s5_p2'); ?></p>

        <h2><?php echo t('terms.s6_title'); ?></h2>
        <p><?php echo t('terms.s6_text'); ?></p>

        <h2><?php echo t('terms.s7_title'); ?></h2>
        <p><?php echo t('terms.s7_text'); ?></p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
