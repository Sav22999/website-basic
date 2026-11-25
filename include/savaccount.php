<?php
/**
 * "Continue with SavAccount" in the my/* pages: the texts for js/savaccount.js
 * and the (hidden until it is available) box of the button.
 */

function savaccount_box()
{
    echo '<div class="savaccount-box hidden2" id="savaccount-box">'
        . '<p class="savaccount-or"><span>' . te('savaccount.or') . '</span></p>'
        . '<div class="savaccount-slot"></div></div>';
}

function savaccount_scripts()
{
    $texts = t('savaccount');
    if (!is_array($texts)) {
        $texts = array();
    }
    $texts["log_in"] = t('account.log_in');
    foreach (array("fill_all_fields", "password_too_short", "passwords_dont_match") as $key) {
        $texts[$key] = t('js.' . $key);
    }
    echo '<script>window.NOTEFOX_SAVACCOUNT_TEXT = ' . json_encode($texts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>' . "\n";
    echo '<script src="/js/savaccount.js"></script>' . "\n";
}

?>
