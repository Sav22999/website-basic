<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu == "") {
    $selected_menu = "";
}
if (isset($_GET["test"])) $selected_menu = $selected_menu . " (test)";
echo '<script>setAction("' . $selected_menu . '");</script>';
?>
<nav class="topbar">
    <div class="menu">
        <a href="/" class="nav-button <?php if ($selected_menu == "home") {
            echo "nav-button-selected";
        } ?>">Home</a>
        <a href="/about/" class="nav-button <?php if ($selected_menu == "about") {
            echo "nav-button-selected";
        } ?>">About</a>
        <a href="/install/" class="nav-button <?php if ($selected_menu == "install") {
            echo "nav-button-selected";
        } ?>">Install</a>
        <a href="/help/" class="nav-button <?php if ($selected_menu == "help") {
            echo "nav-button-selected";
        } ?>">Help</a>
        <a href="/news/" class="nav-button <?php if ($selected_menu == "news") {
            echo "nav-button-selected";
        } ?>">News</a>
        <a href="/donate/" class="nav-button <?php if ($selected_menu == "donate") {
            echo "nav-button-selected";
        } ?>">Donate</a>
        <a href="/docs/" class="nav-button <?php if ($selected_menu == "docs") {
            echo "nav-button-selected";
        } ?>">Docs</a>
    </div>
</nav>