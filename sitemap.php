<?php
header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$base = "https://www.notefox.eu";
$langs = array("en", "it", "fr", "de", "es", "ru", "pt-BR", "pt-PT", "pl", "zh-CN", "ja", "ar", "nl");
$pages = array(
        array("path" => "/", "priority" => "1.0", "changefreq" => "weekly"),
        array("path" => "/about/", "priority" => "0.7", "changefreq" => "monthly"),
        array("path" => "/install/", "priority" => "0.9", "changefreq" => "monthly"),
        array("path" => "/donate/", "priority" => "0.5", "changefreq" => "monthly"),
        array("path" => "/help/", "priority" => "0.7", "changefreq" => "monthly"),
        array("path" => "/news/", "priority" => "0.8", "changefreq" => "weekly"),
        array("path" => "/docs/", "priority" => "0.6", "changefreq" => "monthly"),
        array("path" => "/contact/", "priority" => "0.5", "changefreq" => "monthly"),
);
$english_only_pages = array(
        array("path" => "/privacy/", "priority" => "0.3", "changefreq" => "yearly"),
        array("path" => "/terms/", "priority" => "0.3", "changefreq" => "yearly"),
);
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <?php foreach ($pages as $page): ?>
        <?php foreach ($langs as $lang):
            $loc = $base . $page["path"] . ($lang !== "en" ? "?lang=" . $lang : "");
            ?>
            <url>
                <loc><?php echo htmlspecialchars($loc); ?></loc>
                <?php foreach ($langs as $alt):
                    $href = $base . $page["path"] . ($alt !== "en" ? "?lang=" . $alt : "");
                    ?>
                    <xhtml:link rel="alternate" hreflang="<?php echo $alt; ?>"
                                href="<?php echo htmlspecialchars($href); ?>"/>
                <?php endforeach; ?>
                <xhtml:link rel="alternate" hreflang="x-default"
                            href="<?php echo htmlspecialchars($base . $page["path"]); ?>"/>
                <changefreq><?php echo $page["changefreq"]; ?></changefreq>
                <priority><?php echo $page["priority"]; ?></priority>
            </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php foreach ($english_only_pages as $page): ?>
        <url>
            <loc><?php echo htmlspecialchars($base . $page["path"]); ?></loc>
            <changefreq><?php echo $page["changefreq"]; ?></changefreq>
            <priority><?php echo $page["priority"]; ?></priority>
        </url>
    <?php endforeach; ?>
</urlset>
