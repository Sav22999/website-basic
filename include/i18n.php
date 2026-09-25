<?php
if (isset($GLOBALS['__i18n_loaded'])) {
    return;
}
$GLOBALS['__i18n_loaded'] = true;

$i18n = array();
$i18n_lang = "en";
$i18n_dir = "ltr";
$i18n_supported = array("en", "it", "fr", "de", "es", "ru", "pt-BR", "pt-PT", "pl", "zh-CN", "ja", "ar", "nl");

function i18n_match_tag($tag, $supported)
{
    $lower = strtolower($tag);
    foreach ($supported as $s) {
        if (strtolower($s) === $lower) {
            return $s;
        }
    }
    $prefix = substr($lower, 0, 2);
    foreach ($supported as $s) {
        if (strtolower(substr($s, 0, 2)) === $prefix) {
            return $s;
        }
    }
    return null;
}

function i18n_detect()
{
    global $i18n_supported;
    if (isset($_GET["lang"])) {
        $match = i18n_match_tag(trim($_GET["lang"]), $i18n_supported);
        if ($match !== null) {
            setcookie("nf_lang", $match, time() + 365 * 86400, "/");
            return $match;
        }
    }
    if (isset($_COOKIE["nf_lang"])) {
        $match = i18n_match_tag($_COOKIE["nf_lang"], $i18n_supported);
        if ($match !== null) {
            return $match;
        }
    }
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
        $langs = array();
        foreach (explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $part) {
            $part = trim($part);
            $q = 1.0;
            if (preg_match('/;q=([0-9.]+)/', $part, $m)) {
                $q = (float)$m[1];
                $part = preg_replace('/;q=.*/', '', $part);
            }
            $tag = trim($part);
            if ($tag !== '' && !isset($langs[$tag])) {
                $langs[$tag] = $q;
            }
        }
        arsort($langs);
        foreach ($langs as $tag => $q) {
            $match = i18n_match_tag($tag, $i18n_supported);
            if ($match !== null) {
                return $match;
            }
        }
    }
    return "en";
}

function i18n_load($lang)
{
    global $root_path, $i18n, $i18n_lang, $i18n_dir;
    $file = $root_path . "/lang/" . $lang . ".json";
    if (!file_exists($file)) {
        $file = $root_path . "/lang/en.json";
        $lang = "en";
    }
    $i18n_lang = $lang;
    $json = file_get_contents($file);
    $i18n = json_decode($json, true);
    if ($i18n === null) {
        $i18n = array();
    }
    $i18n_dir = isset($i18n["dir"]) && $i18n["dir"] === "rtl" ? "rtl" : "ltr";
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
