<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu == "") {
    $selected_menu = "";
}
?>
    <nav class="topbar">
        <div class="menu">
            <div onclick="<?php echo navigateTo('') ?>" class="nav-button <?php if ($selected_menu == "home") {
                echo "sel";
            } ?>">Home
            </div>
            <div onclick="<?php echo navigateTo('about') ?>" class="nav-button <?php if ($selected_menu == "about") {
                echo "sel";
            } ?>">About
            </div>
            <div onclick="<?php echo navigateTo('install') ?>"
                 class="nav-button hide-on-mobile <?php if ($selected_menu == "install") {
                     echo "sel";
                 } ?>">Install
            </div>
            <div onclick="<?php echo navigateTo('help') ?>" class="nav-button <?php if ($selected_menu == "help") {
                echo "sel";
            } ?>">Help
            </div>
            <div onclick="<?php echo navigateTo('news') ?>" class="nav-button <?php if ($selected_menu == "news") {
                echo "sel";
            } ?>">News
            </div>
            <div onclick="<?php echo navigateTo('donate') ?>"
                 class="nav-button <?php if ($selected_menu == "donate") {
                     echo "sel";
                 } ?>">Donate
            </div>
            <div onclick="<?php echo navigateTo('my') ?>"
                 class="nav-button <?php if ($selected_menu == "my") {
                     echo "sel";
                 } ?>">My Account
            </div>
            <div onclick="<?php echo navigateTo('docs') ?>"
                 class="nav-button hide-on-mobile <?php if ($selected_menu == "docs") {
                     echo "sel";
                 } ?>">Docs
            </div>
        </div>
    </nav>

<?php
function navigateTo($tab)
{
    if ($tab === "") {
        return "location.href='/old/'";
    }
    return "location.href='/old/" . $tab . "/'";
}

?>
<div style="position:sticky;top:0;z-index:9999;background:#fff3cd;color:#856404;text-align:center;padding:10px 16px;font-size:14px;border-bottom:1px solid #ffc107;font-family:'Open Sans',sans-serif;pointer-events:none;opacity:0.5;">
    This is an outdated version of the website.
    <a href="/" style="color:#856404;font-weight:600;text-decoration:underline;font-size:14px;margin-left:8px;">Go to the new site &rarr;</a>
</div>