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
        return "location.href='/'";
    }
    return "location.href='/" . $tab . "/'";
}

?>