<?php
global $title, $description, $canonical_path, $url_opengraph, $noindex, $english_only;
include_once($root_path . "/alpha/include/maintenance.php");
include_once($root_path . "/alpha/include/i18n.php");
global $i18n_lang, $i18n_dir, $i18n_supported;
if (!isset($title) || $title === "") {
    $title = "Notefox: websites notes";
}
if (!isset($description) || $description === "") {
    $description = t('meta.site_description');
}
if (!isset($canonical_path) || $canonical_path === "") {
    $canonical_path = "/alpha/";
}
if (!isset($url_opengraph) || $url_opengraph === "") {
    $url_opengraph = "https://www.notefox.eu/images/opengraph.png";
}
$base_url = "https://www.notefox.eu";
if (!empty($english_only)) {
    $canonical_url = $base_url . $canonical_path;
} else {
    $canonical_url = $base_url . $canonical_path . ($i18n_lang !== "en" ? "?lang=" . $i18n_lang : "");
}
$og_locales = array(
        "en" => "en_US", "it" => "it_IT", "fr" => "fr_FR", "de" => "de_DE", "es" => "es_ES",
        "ru" => "ru_RU", "pt-BR" => "pt_BR", "pt-PT" => "pt_PT",
        "pl" => "pl_PL", "zh-CN" => "zh_CN", "ja" => "ja_JP", "ar" => "ar_SA", "nl" => "nl_NL"
);
$og_locale = isset($og_locales[$i18n_lang]) ? $og_locales[$i18n_lang] : "en_US";
?>
<!DOCTYPE html>
<html lang="<?php echo $i18n_lang; ?>"<?php if ($i18n_dir === "rtl") echo ' dir="rtl"'; ?>>
<script>(function () {
        try {
            var t = localStorage.getItem("nf_theme");
            if (t === "light" || t === "dark") document.documentElement.setAttribute("data-theme", t);
        } catch (e) {
        }
    })()</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <?php if (!empty($noindex)): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php if (empty($english_only)): ?>
        <?php foreach ($i18n_supported as $lang_code):
            $href = $base_url . $canonical_path . ($lang_code !== "en" ? "?lang=" . $lang_code : "");
            ?>
            <link rel="alternate" hreflang="<?php echo $lang_code; ?>" href="<?php echo htmlspecialchars($href); ?>">
        <?php endforeach; ?>
        <link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($base_url . $canonical_path); ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merienda:wght@700&family=Stack+Sans+Notch:wght@500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/alpha/css/style.css">
    <link rel="icon" href="/images/icon.svg">

    <meta property="og:locale" content="<?php echo empty($english_only) ? $og_locale : "en_US"; ?>">
    <?php if (empty($english_only)): ?>
        <?php foreach ($og_locales as $lc => $olc):
            if ($lc !== $i18n_lang): ?>
                <meta property="og:locale:alternate" content="<?php echo $olc; ?>">
            <?php endif; endforeach; ?>
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:site_name" content="Notefox">
    <meta property="og:image" content="<?php echo $url_opengraph; ?>">
    <meta property="og:image:secure_url" content="<?php echo $url_opengraph; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="twitter:site" content="@Sav22999">
    <meta name="twitter:image" content="<?php echo $url_opengraph; ?>">
    <meta name="twitter:creator" content="@Sav22999">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "WebSite",
                    "name": "Notefox",
                    "url": "https://www.notefox.eu/",
                    "description": <?php echo json_encode(t('meta.site_description'), JSON_UNESCAPED_UNICODE); ?>,
                "inLanguage": <?php echo json_encode(array_values(array_map(function ($c) {
            return str_replace('-', '_', $c);
        }, $i18n_supported))); ?>,
                "publisher": { "@id": "https://www.notefox.eu/#org" }
            },
            {
                "@type": "Organization",
                "@id": "https://www.notefox.eu/#org",
                "name": "Saverio Morelli",
                "url": "https://saveriomorelli.com",
                "logo": "https://www.notefox.eu/images/icon.svg"
            },
            {
                "@type": "SoftwareApplication",
                "name": "Notefox",
                "applicationCategory": "BrowserApplication",
                "operatingSystem": "Windows, macOS, Linux",
                "description": <?php echo json_encode(t('meta.site_description'), JSON_UNESCAPED_UNICODE); ?>,
                "url": "https://www.notefox.eu/alpha/install/",
                "offers": {
                    "@type": "Offer",
                    "price": "0",
                    "priceCurrency": "USD"
                },
                "author": { "@id": "https://www.notefox.eu/#org" },
                "license": "https://opensource.org/licenses/GPL-3.0",
                "isAccessibleForFree": true
            }
        ]
    }
    </script>
</head>
