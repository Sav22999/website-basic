<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu === "") {
    $selected_menu = "";
}

function alpha_nav_link($slug, $label, $selected_menu)
{
    $href = $slug === "" ? "/alpha/" : "/alpha/" . $slug . "/";
    $key = $slug === "" ? "home" : $slug;
    $aria = $selected_menu === $key ? ' aria-current="page"' : '';
    return '<a href="' . $href . '" class="nav-link"' . $aria . '>' . $label . '</a>';
}

?>
<a href="#main" class="skip-link">Skip to content</a>

<header class="site-header">
    <div class="nav-backdrop" aria-hidden="true"></div>
    <nav class="site-nav" aria-label="Main navigation">
        <a href="/alpha/" class="nav-logo" aria-label="Notefox home">
            <img src="/images/icon.svg" alt="" width="24" height="24"> Notefox
        </a>
        <button class="nav-toggle" aria-expanded="false" aria-label="Open menu">
            <svg class="nav-icon-menu" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round">
                <line x1="3" y1="5" x2="17" y2="5"/>
                <line x1="3" y1="10" x2="17" y2="10"/>
                <line x1="3" y1="15" x2="17" y2="15"/>
            </svg>
            <svg class="nav-icon-close" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round">
                <line x1="4" y1="4" x2="16" y2="16"/>
                <line x1="16" y1="4" x2="4" y2="16"/>
            </svg>
        </button>
        <div class="nav-links">
            <?php echo alpha_nav_link("", "Home", $selected_menu); ?>
            <?php echo alpha_nav_link("about", "About", $selected_menu); ?>
            <?php echo alpha_nav_link("help", "Help", $selected_menu); ?>
            <?php echo alpha_nav_link("news", "News", $selected_menu); ?>
            <?php echo alpha_nav_link("donate", "Donate", $selected_menu); ?>
            <?php echo alpha_nav_link("install", "Install", $selected_menu); ?>
            <?php echo alpha_nav_link("my", "Account", $selected_menu); ?>
        </div>
    </nav>
</header>
