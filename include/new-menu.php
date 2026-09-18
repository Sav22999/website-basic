<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu == "") {
    $selected_menu = "";
}
?>
<header class="main-menu">
    <a href="/alpha/" class="menu-button <?php if ($selected_menu == "home") {
        echo "selected";
    } ?>">Home</a>
    <a href="/alpha/help/" class="menu-button <?php if ($selected_menu == "help") {
        echo "selected";
    } ?>">Help</a>
    <a href="/alpha/install/" class="menu-button <?php if ($selected_menu == "install") {
        echo "selected";
    } ?>">Install</a>
    <a href="/alpha/news/" class="menu-button <?php if ($selected_menu == "news") {
        echo "selected";
    } ?>">News</a>
</header>