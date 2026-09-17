<?php
global $title, $url_opengraph;
if (!isset($title) || $title === "") {
    $title = "Notefox: websites notes";
}
if (!isset($url_opengraph) || $url_opengraph === "") {
    $url_opengraph = "https://www.notefox.eu/images/opengraph.png";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merienda:wght@700&family=Stack+Sans+Notch:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/alpha/css/style.css">
    <link rel="icon" href="/images/icon.svg">

    <meta property="og:locale" content="it_IT">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="Notefox: Take notes on every website in a smart and simple way!">
    <meta property="og:url" content="https://www.notefox.eu/">
    <meta property="og:site_name" content="Notefox">
    <meta property="og:image" content="<?php echo $url_opengraph; ?>">
    <meta property="og:image:secure_url" content="<?php echo $url_opengraph; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="Notefox: Take notes on every website in a smart and simple way!">
    <meta name="twitter:title" content="Notefox">
    <meta name="twitter:site" content="@Sav22999">
    <meta name="twitter:image" content="<?php echo $url_opengraph; ?>">
    <meta name="twitter:creator" content="@Sav22999">
</head>
