<?php
global $selected_menu, $i18n_lang, $i18n_supported;
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

$lang_labels = array(
        "en" => "English", "it" => "Italiano", "fr" => "Français", "de" => "Deutsch", "es" => "Español",
        "ru" => "Русский", "pt-BR" => "Português (BR)", "pt-PT" => "Português (PT)",
        "pl" => "Polski", "zh-CN" => "中文(简体)", "ja" => "日本語", "ar" => "العربية", "nl" => "Nederlands"
);
$lang_short = array(
        "en" => "EN", "it" => "IT", "fr" => "FR", "de" => "DE", "es" => "ES",
        "ru" => "RU", "pt-BR" => "BR", "pt-PT" => "PT",
        "pl" => "PL", "zh-CN" => "ZH", "ja" => "JA", "ar" => "AR", "nl" => "NL"
);
?>
<a href="#main" class="skip-link"><?php echo t('common.skip_to_content'); ?></a>

<header class="site-header">
    <div class="nav-backdrop" aria-hidden="true"></div>
    <nav class="site-nav" aria-label="Main navigation">
        <a href="/alpha/" class="nav-logo" aria-label="Notefox home">
            <img src="/images/icon.svg" alt="" width="24" height="24"> Notefox
        </a>
        <button class="nav-toggle" aria-expanded="false" aria-label="<?php echo te('nav.open_menu'); ?>">
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
            <?php echo alpha_nav_link("", t('nav.home'), $selected_menu); ?>
            <?php echo alpha_nav_link("about", t('nav.about'), $selected_menu); ?>
            <?php echo alpha_nav_link("help", t('nav.help'), $selected_menu); ?>
            <?php echo alpha_nav_link("news", t('nav.news'), $selected_menu); ?>
            <?php echo alpha_nav_link("donate", t('nav.donate'), $selected_menu); ?>
            <?php echo alpha_nav_link("install", t('nav.install'), $selected_menu); ?>
            <?php echo alpha_nav_link("my", t('nav.account'), $selected_menu); ?>
            <div class="nav-separator" aria-hidden="true"></div>
            <div class="theme-toggle">
                <button class="theme-toggle-trigger" aria-expanded="false" aria-haspopup="true"
                        aria-label="<?php echo te('nav.theme'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <defs>
                            <clipPath id="theme-clip">
                                <rect x="12" y="0" width="12" height="24"/>
                            </clipPath>
                        </defs>
                        <circle cx="12" cy="12" r="5"/>
                        <line x1="12" y1="1" x2="12" y2="3"/>
                        <line x1="12" y1="21" x2="12" y2="23"/>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        <line x1="1" y1="12" x2="3" y2="12"/>
                        <line x1="21" y1="12" x2="23" y2="12"/>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                        <circle cx="12" cy="12" r="5" fill="currentColor" clip-path="url(#theme-clip)"/>
                    </svg>
                    <span class="theme-toggle-label"><?php echo te('nav.theme'); ?></span>
                    <svg class="theme-toggle-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="theme-toggle-menu">
                    <button class="theme-toggle-item"
                            data-theme-value="auto"><?php echo te('nav.theme_auto'); ?></button>
                    <button class="theme-toggle-item"
                            data-theme-value="light"><?php echo te('nav.theme_light'); ?></button>
                    <button class="theme-toggle-item"
                            data-theme-value="dark"><?php echo te('nav.theme_dark'); ?></button>
                </div>
            </div>
            <div class="lang-dropdown">
                <button class="lang-dropdown-trigger" aria-expanded="false" aria-haspopup="true">
                    <svg class="lang-dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <span><?php echo $lang_short[$i18n_lang]; ?></span>
                    <svg class="lang-dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="lang-dropdown-menu">
                    <?php
                    $sorted_langs = $i18n_supported;
                    usort($sorted_langs, function ($a, $b) use ($lang_labels) {
                        return strcasecmp($lang_labels[$a], $lang_labels[$b]);
                    });
                    foreach ($sorted_langs as $code): ?>
                        <a href="?lang=<?php echo $code; ?>"
                           class="lang-dropdown-item<?php echo $code === $i18n_lang ? ' lang-dropdown-item--active' : ''; ?>">
                            <span class="lang-dropdown-item-label"><?php echo $lang_labels[$code]; ?></span>
                            <span class="lang-dropdown-item-code"><?php echo $lang_short[$code]; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
