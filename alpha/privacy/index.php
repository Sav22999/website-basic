<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('privacy.title');
$description = t('meta.privacy');
$canonical_path = "/alpha/privacy/";
$english_only = true;
$selected_menu = "privacy";
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
        <h1><?php echo t('privacy.heading'); ?></h1>

        <p><strong><?php echo t('privacy.last_update'); ?></strong> <?php echo t('privacy.last_update_date'); ?></p>

        <p><?php echo t('privacy.intro'); ?></p>

        <p><?php echo t('privacy.developer_info'); ?></p>

        <h2><?php echo t('privacy.s1_title'); ?></h2>
        <p><?php echo t('privacy.s1_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s1_item_email'); ?></li>
            <li><?php echo t('privacy.s1_item_ip'); ?></li>
            <li><?php echo t('privacy.s1_item_data'); ?></li>
        </ul>
        <p><?php echo t('privacy.s1_encryption'); ?></p>
        <p><?php echo t('privacy.s1_otp'); ?></p>
        <p><?php echo t('privacy.s1_sessions'); ?></p>
        <p><?php echo t('privacy.s1_retention'); ?></p>
        <p><?php echo t('privacy.s1_no_cookies'); ?></p>

        <h3><?php echo t('privacy.s1_emails_title'); ?></h3>
        <p><?php echo t('privacy.s1_emails_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s1_email_signup'); ?></li>
            <li><?php echo t('privacy.s1_email_created'); ?></li>
            <li><?php echo t('privacy.s1_email_login'); ?></li>
            <li><?php echo t('privacy.s1_email_accessed'); ?></li>
            <li><?php echo t('privacy.s1_email_pwd_change'); ?></li>
            <li><?php echo t('privacy.s1_email_otp_change'); ?></li>
            <li><?php echo t('privacy.s1_email_delete_req'); ?></li>
            <li><?php echo t('privacy.s1_email_deleted'); ?></li>
        </ul>
        <p><?php echo t('privacy.s1_no_spam'); ?></p>

        <h3><?php echo t('privacy.s1_v1_title'); ?></h3>
        <p><?php echo t('privacy.s1_v1_text'); ?></p>

        <h2><?php echo t('privacy.s2_title'); ?></h2>
        <p><?php echo t('privacy.s2_intro'); ?></p>
        <p><?php echo t('privacy.s2_collected'); ?></p>
        <ul>
            <li><?php echo t('privacy.s2_item_account'); ?></li>
            <li><?php echo t('privacy.s2_item_anon_id'); ?></li>
            <li><?php echo t('privacy.s2_item_datetime'); ?></li>
            <li><?php echo t('privacy.s2_item_language'); ?></li>
            <li><?php echo t('privacy.s2_item_action'); ?></li>
            <li><?php echo t('privacy.s2_item_context'); ?></li>
            <li><?php echo t('privacy.s2_item_url'); ?></li>
            <li><?php echo t('privacy.s2_item_browser'); ?></li>
            <li><?php echo t('privacy.s2_item_version'); ?></li>
            <li><?php echo t('privacy.s2_item_os'); ?></li>
        </ul>
        <p><?php echo t('privacy.s2_usage'); ?></p>

        <h2><?php echo t('privacy.s3_title'); ?></h2>
        <p><?php echo t('privacy.s3_intro'); ?></p>
        <p><?php echo t('privacy.s3_collected'); ?></p>
        <ul>
            <li><?php echo t('privacy.s3_item_anon_id'); ?></li>
            <li><?php echo t('privacy.s3_item_datetime'); ?></li>
            <li><?php echo t('privacy.s3_item_context'); ?></li>
            <li><?php echo t('privacy.s3_item_error'); ?></li>
            <li><?php echo t('privacy.s3_item_url'); ?></li>
            <li><?php echo t('privacy.s3_item_version'); ?></li>
        </ul>
        <p><?php echo t('privacy.s3_usage'); ?></p>
        <p><?php echo t('privacy.s3_retention'); ?></p>

        <h2><?php echo t('privacy.s4_title'); ?></h2>
        <p><?php echo t('privacy.s4_text'); ?></p>

        <h2><?php echo t('privacy.s5_title'); ?></h2>
        <p><?php echo t('privacy.s5_text'); ?></p>

        <h2><?php echo t('privacy.s6_title'); ?></h2>
        <p><?php echo t('privacy.s6_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s6_item_name'); ?></li>
            <li><?php echo t('privacy.s6_item_email'); ?></li>
            <li><?php echo t('privacy.s6_item_topic'); ?></li>
            <li><?php echo t('privacy.s6_item_version'); ?></li>
            <li><?php echo t('privacy.s6_item_browser'); ?></li>
            <li><?php echo t('privacy.s6_item_os'); ?></li>
            <li><?php echo t('privacy.s6_item_message'); ?></li>
            <li><?php echo t('privacy.s6_item_lang'); ?></li>
            <li><?php echo t('privacy.s6_item_timezone'); ?></li>
        </ul>
        <p><?php echo t('privacy.s6_usage'); ?></p>
        <p><?php echo t('privacy.s6_captcha'); ?></p>

        <h2><?php echo t('privacy.s7_title'); ?></h2>
        <p><?php echo t('privacy.s7_text'); ?></p>

        <p><em><?php echo t('privacy.footnote'); ?></em></p>
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
