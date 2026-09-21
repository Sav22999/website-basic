<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu == "") {
    $selected_menu = "";
}
if (isset($_GET["test"])) $selected_menu = $selected_menu . " (test)";
echo '<script>setAction("' . $selected_menu . '");</script>';

function menu_class($name, $selected_menu) {
    return "menu-button" . ($selected_menu == $name ? " selected" : "");
}
?>
<header class="site-header">
    <div class="site-header__inner">
        <a href="/alpha/" class="brand">
            <img src="/images/icon.png" class="brand__icon" alt="">
            <span class="brand__name">Sav PDF Viewer</span>
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle__label" aria-label="Apri il menu">
            <span></span><span></span><span></span>
        </label>

        <nav class="nav">
            <a href="/alpha/" class="<?php echo menu_class("home", $selected_menu); ?>">Home</a>
            <a href="/alpha/install/" class="<?php echo menu_class("install", $selected_menu); ?>">Install</a>
            <a href="/alpha/help/" class="<?php echo menu_class("help", $selected_menu); ?>">Help</a>
            <a href="/alpha/news/" class="<?php echo menu_class("news", $selected_menu); ?>">News</a>
            <a href="/alpha/donate/" class="<?php echo menu_class("donate", $selected_menu); ?>">Donate</a>
            <a href="/alpha/reviews/" class="<?php echo menu_class("reviews", $selected_menu); ?>">Reviews</a>
        </nav>
    </div>
</header>
