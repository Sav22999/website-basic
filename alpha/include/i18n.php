<?php
if (isset($GLOBALS['__i18n_loaded'])) {
    return;
}
$GLOBALS['__i18n_loaded'] = true;

$i18n = array();
$i18n_lang = "en";
$i18n_supported = array("en", "it");

function i18n_detect()
{
    global $i18n_supported;
    if (isset($_GET["lang"])) {
        $lang = strtolower(substr($_GET["lang"], 0, 2));
        if (in_array($lang, $i18n_supported)) {
            setcookie("nf_lang", $lang, time() + 365 * 86400, "/");
            return $lang;
        }
    }
    if (isset($_COOKIE["nf_lang"]) && in_array($_COOKIE["nf_lang"], $i18n_supported)) {
        return $_COOKIE["nf_lang"];
    }
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
        $browser = strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2));
        if (in_array($browser, $i18n_supported)) {
            return $browser;
        }
    }
    return "en";
}

function i18n_load($lang)
{
    global $root_path, $i18n, $i18n_lang;
    $file = $root_path . "/alpha/lang/" . $lang . ".json";
    if (!file_exists($file)) {
        $file = $root_path . "/alpha/lang/en.json";
        $lang = "en";
    }
    $i18n_lang = $lang;
    $json = file_get_contents($file);
    $i18n = json_decode($json, true);
    if ($i18n === null) {
        $i18n = array();
    }
}

function t($key)
{
    global $i18n;
    $parts = explode(".", $key);
    $val = $i18n;
    foreach ($parts as $p) {
        if (!is_array($val) || !isset($val[$p])) {
            return $key;
        }
        $val = $val[$p];
    }
    return $val;
}

function te($key)
{
    return htmlspecialchars(t($key), ENT_QUOTES, "UTF-8");
}

function i18n_english_only_notice()
{
    global $i18n_lang;
    if ($i18n_lang !== "en") {
        echo '<div class="lang-notice">' . htmlspecialchars(t('common.english_only_notice'), ENT_QUOTES, "UTF-8") . '</div>';
    }
}

i18n_load(i18n_detect());
