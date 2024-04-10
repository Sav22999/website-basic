<?php
global $title, $url_opengraph;
if (isset($title)) {
    echo "<title>" . $title . "</title>";
} else {
    $title = "?";
    echo "<title>Notefox: websites notes</title>";
}
if (!isset($url_opengraph) || $url_opengraph == "") {
    $url_opengraph = "https://www.notefox.eu/images/opengraph.png";
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
      rel="stylesheet">
<link rel="stylesheet" href="/css/style.css"/>
<link rel="icon" href="/images/icon.png"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://unpkg.com/twemoji@13.1.0/dist/twemoji.min.js"></script>
<script src="/js/script.js"></script>
<meta http-equiv="content-type" content="text/html; charset=UTF-16">
<meta name="viewport" content="width=device-width, initial-scale=0.8"/>

<meta property="og:locale" content="it_IT"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="Notefox"/>
<meta property="og:description"
      content="Notefox: Take notes on every website in a smart and simple way!"/>
<meta property="og:url" content="https://www.notefox.eu/"/>
<meta property="og:site_name" content="Notefox"/>
<meta property="og:image" content="<?php echo $url_opengraph; ?>"/>
<meta property="og:image:secure_url" content="<?php echo $url_opengraph; ?>"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:description"
      content="Notefox: Take notes on every website in a smart and simple way!"/>
<meta name="twitter:title" content="Notefox"/>
<meta name="twitter:site" content="@Sav22999"/>
<meta name="twitter:image" content="<?php echo $url_opengraph; ?>"/>
<meta name="twitter:creator" content="@Sav22999"/>