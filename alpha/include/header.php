<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/alpha/include/maintenance.php';
global $title, $description, $url_opengraph, $canonical_path;

$site_name = "Sav PDF Viewer";
$site_url = "https://www.savpdfviewer.com";
$default_description = "Sav PDF Viewer — a fast, private, open-source PDF reader for Android. No ads, no tracking, no permissions required.";

if (isset($title) && $title !== "") {
    $full_title = $title . " — " . $site_name;
} else {
    $title = $site_name;
    $full_title = $site_name . " — Private, open-source PDF reader for Android";
}
echo "<title>" . htmlspecialchars($full_title) . "</title>";

if (!isset($description) || $description === "") {
    $description = $default_description;
}
if (!isset($url_opengraph) || $url_opengraph === "") {
    $url_opengraph = $site_url . "/alpha/images/opengraph.png";
}
if (!isset($canonical_path) || $canonical_path === "") {
    $canonical_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}
$canonical_url = $site_url . $canonical_path;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="description" content="<?php echo htmlspecialchars($description); ?>"/>

<link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>"/>
<link rel="icon" href="/images/icon.png"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Stack+Sans+Notch:wght@500&display=swap"
      rel="stylesheet">
<link rel="stylesheet" href="/alpha/css/style.css"/>
<script src="/alpha/js/script.js" defer></script>
<script src="/alpha/js/i18n.js" defer></script>

<meta property="og:locale" content="en_US"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?php echo htmlspecialchars($full_title); ?>"/>
<meta property="og:description" content="<?php echo htmlspecialchars($description); ?>"/>
<meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>"/>
<meta property="og:site_name" content="<?php echo $site_name; ?>"/>
<meta property="og:image" content="<?php echo htmlspecialchars($url_opengraph); ?>"/>
<meta property="og:image:secure_url" content="<?php echo htmlspecialchars($url_opengraph); ?>"/>

<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?php echo htmlspecialchars($full_title); ?>"/>
<meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>"/>
<meta name="twitter:site" content="@Sav22999"/>
<meta name="twitter:image" content="<?php echo htmlspecialchars($url_opengraph); ?>"/>
<meta name="twitter:creator" content="@Sav22999"/>
