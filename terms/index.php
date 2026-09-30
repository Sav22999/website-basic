<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('terms.title');
$description = t('meta.terms');
$canonical_path = "/terms/";
$selected_menu = "terms";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>
<?php
$_saved_i18n = $i18n;
$_saved_lang = $i18n_lang;
$_content_lang = ($i18n_lang === "it") ? "it" : "en";
i18n_load($_content_lang);
?>

<main id="main" class="page">
    <div class="container">
        <?php
        if ($_saved_lang !== "it" && $_saved_lang !== "en") {
            $i18n_tmp = $i18n;
            $i18n = $_saved_i18n;
            $i18n_lang = $_saved_lang;
            i18n_english_only_notice();
            $i18n = $i18n_tmp;
            $i18n_lang = $_content_lang;
        }
        if ($_content_lang === "en") {
            echo '<div class="lang-notice">' . t('terms.en_disclaimer') . '</div>';
        }
        ?>
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
        <p><?php echo t('terms.s5_retention_intro'); ?></p>
        <ul>
            <li><?php echo t('terms.s5_retention_ip_sessions'); ?></li>
            <li><?php echo t('terms.s5_retention_ip_sync'); ?></li>
            <li><?php echo t('terms.s5_retention_rate'); ?></li>
            <li><?php echo t('terms.s5_retention_errors'); ?></li>
            <li><?php echo t('terms.s5_retention_telemetry'); ?></li>
            <li><?php echo t('terms.s5_retention_history'); ?></li>
            <li><?php echo t('terms.s5_retention_account'); ?></li>
            <li><?php echo t('terms.s5_retention_ip_creation'); ?></li>
        </ul>

        <h2><?php echo t('terms.s6_title'); ?></h2>
        <p><?php echo t('terms.s6_p1'); ?></p>
        <p><?php echo t('terms.s6_p2'); ?></p>

        <h2><?php echo t('terms.s7_title'); ?></h2>
        <p><?php echo t('terms.s7_p1'); ?></p>
        <p><?php echo t('terms.s7_p2'); ?></p>
        <p><?php echo t('terms.s7_p3'); ?></p>

        <h2><?php echo t('terms.s8_title'); ?></h2>
        <p><?php echo t('terms.s8_text'); ?></p>

        <h2><?php echo t('terms.s9_title'); ?></h2>
        <p><?php echo t('terms.s9_p1'); ?></p>
        <p><?php echo t('terms.s9_p2'); ?></p>

        <h2><?php echo t('terms.s10_title'); ?></h2>
        <p><?php echo t('terms.s10_text'); ?></p>
    </div>
</main>

<?php
$i18n = $_saved_i18n;
$i18n_lang = $_saved_lang;
include_once($root_path . "/include/footer.php");
?>
<script src="/js/script.js"></script>
</body>
</html>
