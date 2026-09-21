<?php
global $selected_menu;
if (!isset($selected_menu) || $selected_menu == "") {
    $selected_menu = "";
}

function menu_class($name, $selected_menu) {
    return "menu-button" . ($selected_menu == $name ? " selected" : "");
}
?>
<header class="site-header">
    <div class="site-header__inner">
        <a href="/alpha/" class="brand">
            <img src="/images/icon.png" class="brand__icon" alt="Sav PDF Viewer">
            <span class="brand__name">Sav PDF Viewer</span>
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-hidden="true">
        <label for="nav-toggle" class="nav-toggle__label" aria-label="Menu">
            <span></span><span></span><span></span>
        </label>
        <nav class="nav">
            <a href="/alpha/" class="<?php echo menu_class("home", $selected_menu); ?>" data-i18n="nav.home">Home</a>
            <a href="/alpha/install/" class="<?php echo menu_class("install", $selected_menu); ?>" data-i18n="nav.install">Install</a>
            <a href="/alpha/help/" class="<?php echo menu_class("help", $selected_menu); ?>" data-i18n="nav.help">Help</a>
            <a href="/alpha/news/" class="<?php echo menu_class("news", $selected_menu); ?>" data-i18n="nav.news">News</a>
            <a href="/alpha/donate/" class="<?php echo menu_class("donate", $selected_menu); ?>" data-i18n="nav.donate">Donate</a>

            <div class="lang-dropdown" id="lang-dropdown">
                <button type="button" class="lang-trigger" id="lang-trigger" onclick="toggleLangDropdown()">
                    <svg class="lang-trigger__icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12.87 15.07l-2.54-2.51.03-.03A17.52 17.52 0 0014.07 6H17V4h-7V2H8v2H1v2h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg>
                    <span class="lang-trigger__code" id="lang-trigger-code">EN</span>
                    <svg class="lang-trigger__chevron" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5l3 3 3-3"/></svg>
                </button>
                <div class="lang-panel" id="lang-panel">
                    <button type="button" class="lang-item" data-lang="ar" onclick="setLang('ar'); closeLangDropdown()">
                        <span class="lang-item__code">AR</span>
                        <span class="lang-item__name">العربية</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="cs" onclick="setLang('cs'); closeLangDropdown()">
                        <span class="lang-item__code">CS</span>
                        <span class="lang-item__name">Čeština</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="de" onclick="setLang('de'); closeLangDropdown()">
                        <span class="lang-item__code">DE</span>
                        <span class="lang-item__name">Deutsch</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="en" onclick="setLang('en'); closeLangDropdown()">
                        <span class="lang-item__code">EN</span>
                        <span class="lang-item__name">English</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="es" onclick="setLang('es'); closeLangDropdown()">
                        <span class="lang-item__code">ES</span>
                        <span class="lang-item__name">Español</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="fr" onclick="setLang('fr'); closeLangDropdown()">
                        <span class="lang-item__code">FR</span>
                        <span class="lang-item__name">Français</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="it" onclick="setLang('it'); closeLangDropdown()">
                        <span class="lang-item__code">IT</span>
                        <span class="lang-item__name">Italiano</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="ja" onclick="setLang('ja'); closeLangDropdown()">
                        <span class="lang-item__code">JA</span>
                        <span class="lang-item__name">日本語</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="ko" onclick="setLang('ko'); closeLangDropdown()">
                        <span class="lang-item__code">KO</span>
                        <span class="lang-item__name">한국어</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="pl" onclick="setLang('pl'); closeLangDropdown()">
                        <span class="lang-item__code">PL</span>
                        <span class="lang-item__name">Polski</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="pt-BR" onclick="setLang('pt-BR'); closeLangDropdown()">
                        <span class="lang-item__code">PT-BR</span>
                        <span class="lang-item__name">Português (BR)</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="pt-PT" onclick="setLang('pt-PT'); closeLangDropdown()">
                        <span class="lang-item__code">PT-PT</span>
                        <span class="lang-item__name">Português (PT)</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="ru" onclick="setLang('ru'); closeLangDropdown()">
                        <span class="lang-item__code">RU</span>
                        <span class="lang-item__name">Русский</span>
                    </button>
                    <button type="button" class="lang-item" data-lang="zh-CN" onclick="setLang('zh-CN'); closeLangDropdown()">
                        <span class="lang-item__code">ZH-CN</span>
                        <span class="lang-item__name">中文</span>
                    </button>
                </div>
            </div>
        </nav>
    </div>
</header>
<label for="nav-toggle" class="nav-overlay" aria-hidden="true"></label>
