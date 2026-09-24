<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('terms.title');
$description = t('meta.terms');
$canonical_path = "/alpha/terms/";
$english_only = true;
$selected_menu = "terms";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>
<?php
$_saved_i18n = $i18n;
$_saved_lang = $i18n_lang;
i18n_load("en");
?>

<main id="main" class="page">
    <div class="container">
        <?php $i18n = $_saved_i18n;
        $i18n_lang = $_saved_lang;
        i18n_english_only_notice();
        i18n_load("en"); ?>
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
        <p><?php echo t('terms.s3_p3'); ?></p>

        <h3><?php echo t('terms.s3_v1_title'); ?></h3>
        <p><?php echo t('terms.s3_v1_text'); ?></p>

        <h2><?php echo t('terms.s4_title'); ?></h2>
        <p><?php echo t('terms.s4_rate'); ?></p>

        <h2><?php echo t('terms.s5_title'); ?></h2>
        <p><?php echo t('terms.s5_intro'); ?></p>
        <ul>
            <li><?php echo t('terms.s5_item_ip'); ?></li>
            <li><?php echo t('terms.s5_item_telemetry'); ?></li>
            <li><?php echo t('terms.s5_item_errors'); ?></li>
        </ul>
        <p><?php echo t('terms.s5_usage'); ?></p>

        <h2><?php echo t('terms.s6_title'); ?></h2>
        <p><?php echo t('terms.s6_p1'); ?></p>
        <p><?php echo t('terms.s6_p2'); ?></p>

        <h2><?php echo t('terms.s7_title'); ?></h2>
        <p><?php echo t('terms.s7_text'); ?></p>

        <h2><?php echo t('terms.s8_title'); ?></h2>
        <p><?php echo t('terms.s8_text'); ?></p>
    </div>
</main>

<?php
$i18n = $_saved_i18n;
$i18n_lang = $_saved_lang;
include_once($root_path . "/alpha/include/footer.php");
?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
