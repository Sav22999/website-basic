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
        <a href="/old/" class="nav-button <?php if ($selected_menu == "home") {
            echo "nav-button-selected";
        } ?>">Home</a>
        <a href="/old/install/" class="nav-button <?php if ($selected_menu == "install") {
            echo "nav-button-selected";
        } ?>">Install</a>
        <a href="/old/help/" class="nav-button <?php if ($selected_menu == "help") {
            echo "nav-button-selected";
        } ?>">Help</a>
        <a href="/old/news/" class="nav-button <?php if ($selected_menu == "news") {
            echo "nav-button-selected";
        } ?>">News</a>
        <a href="/old/donate/" class="nav-button <?php if ($selected_menu == "donate") {
            echo "nav-button-selected";
        } ?>">Donate</a>
        <a href="/old/reviews/" class="nav-button <?php if ($selected_menu == "reviews") {
            echo "nav-button-selected";
        } ?>">Reviews</a>
    </div>
</nav>
<div style="position:fixed;top:0;left:0;right:0;z-index:10000;background:#b71c1c;color:#fff;display:flex;align-items:center;justify-content:center;gap:16px;padding:10px 16px;font-family:'Open Sans',sans-serif;font-size:14px;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
    <span>This is an outdated version of the website</span>
    <a href="/" style="background:#fff;color:#b71c1c;padding:6px 18px;border-radius:20px;text-decoration:none;font-size:13px;font-weight:700;white-space:nowrap;">Go to new site &rarr;</a>
</div>