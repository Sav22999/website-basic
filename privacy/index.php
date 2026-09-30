<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('privacy.title');
$description = t('meta.privacy');
$canonical_path = "/privacy/";
$selected_menu = "privacy";
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
            echo '<div class="lang-notice">' . t('privacy.en_disclaimer') . '</div>';
        }
        ?>
        <h1><?php echo t('privacy.heading'); ?></h1>

        <p><strong><?php echo t('privacy.last_update'); ?></strong> <?php echo t('privacy.last_update_date'); ?></p>

        <p><?php echo t('privacy.intro'); ?></p>

        <p><?php echo t('privacy.developer_info'); ?></p>

        <h2><?php echo t('privacy.s0_title'); ?></h2>
        <p><?php echo t('privacy.s0_text'); ?></p>

        <h2><?php echo t('privacy.s0b_title'); ?></h2>
        <p><?php echo t('privacy.s0b_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s0b_item_consent'); ?></li>
            <li><?php echo t('privacy.s0b_item_contract'); ?></li>
            <li><?php echo t('privacy.s0b_item_interest'); ?></li>
        </ul>

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
        <p><?php echo t('privacy.s1_sessions_retention'); ?></p>
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
        <p><?php echo t('privacy.s2_retention'); ?></p>

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

        <h2><?php echo t('privacy.s_retention_title'); ?></h2>
        <p><?php echo t('privacy.s_retention_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s_retention_item_account'); ?></li>
            <li><?php echo t('privacy.s_retention_item_ip_sessions'); ?></li>
            <li><?php echo t('privacy.s_retention_item_ip_sync'); ?></li>
            <li><?php echo t('privacy.s_retention_item_rate'); ?></li>
            <li><?php echo t('privacy.s_retention_item_errors'); ?></li>
            <li><?php echo t('privacy.s_retention_item_telemetry'); ?></li>
            <li><?php echo t('privacy.s_retention_item_history'); ?></li>
            <li><?php echo t('privacy.s_retention_item_contact'); ?></li>
            <li><?php echo t('privacy.s_retention_item_ip_creation'); ?></li>
        </ul>
        <p><?php echo t('privacy.s_retention_deletion'); ?></p>

        <h2><?php echo t('privacy.s_rights_title'); ?></h2>
        <p><?php echo t('privacy.s_rights_intro'); ?></p>
        <ul>
            <li><?php echo t('privacy.s_rights_item_access'); ?></li>
            <li><?php echo t('privacy.s_rights_item_rectification'); ?></li>
            <li><?php echo t('privacy.s_rights_item_erasure'); ?></li>
            <li><?php echo t('privacy.s_rights_item_restriction'); ?></li>
            <li><?php echo t('privacy.s_rights_item_portability'); ?></li>
            <li><?php echo t('privacy.s_rights_item_objection'); ?></li>
            <li><?php echo t('privacy.s_rights_item_withdraw'); ?></li>
        </ul>
        <p><?php echo t('privacy.s_rights_exercise'); ?></p>
        <p><?php echo t('privacy.s_rights_complaint'); ?></p>

        <h2><?php echo t('privacy.s_automated_title'); ?></h2>
        <p><?php echo t('privacy.s_automated_text'); ?></p>

        <h2><?php echo t('privacy.s_transfers_title'); ?></h2>
        <p><?php echo t('privacy.s_transfers_text'); ?></p>

        <h2><?php echo t('privacy.s7_title'); ?></h2>
        <p><?php echo t('privacy.s7_text'); ?></p>

        <p><em><?php echo t('privacy.footnote'); ?></em></p>
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
