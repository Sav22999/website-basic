<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = t('home.title');
$selected_menu = "home";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main">
    <section class="page-hero home-hero">
        <div class="home-hero-bg" aria-hidden="true"></div>
        <div class="container text-center" style="position: relative;">
            <img src="/images/icon.svg" alt="Notefox" width="88" height="88" class="hero-icon home-hero-icon">
            <h1 class="page-title">Notefox</h1>
            <p class="page-subtitle"><?php echo t('home.subtitle'); ?></p>

            <a href="/alpha/install/" class="btn hero-cta">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <?php echo t('home.install_notefox'); ?>
            </a>

            <div class="btn-group hero-secondary-links">
                <a href="https://liberapay.com/Sav22999/donate" class="btn btn--secondary" target="_blank"
                   rel="noopener"><span class="btn-icon icon-mask icon-mask--liberapay" aria-hidden="true"></span>
                    LiberaPay</a>
                <a href="https://paypal.me/saveriomorelli" class="btn btn--secondary" target="_blank"
                   rel="noopener"><span class="btn-icon icon-mask icon-mask--paypal" aria-hidden="true"></span>
                    PayPal</a>
                <a href="https://github.com/Sav22999/websites-notes" class="btn btn--secondary" target="_blank"
                   rel="noopener"><span class="btn-icon icon-mask icon-mask--github" aria-hidden="true"></span>
                    GitHub</a>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container">
            <h2 class="home-section-title text-center"><?php echo t('home.how_it_works'); ?></h2>
            <div class="home-steps">
                <div class="home-step">
                    <div class="home-step-number">1</div>
                    <h3><?php echo t('home.step1_title'); ?></h3>
                    <p><?php echo t('home.step1_desc'); ?></p>
                </div>
                <div class="home-step">
                    <div class="home-step-number">2</div>
                    <h3><?php echo t('home.step2_title'); ?></h3>
                    <p><?php echo t('home.step2_desc'); ?></p>
                </div>
                <div class="home-step">
                    <div class="home-step-number">3</div>
                    <h3><?php echo t('home.step3_title'); ?></h3>
                    <p><?php echo t('home.step3_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--alt">
        <div class="container">
            <h2 class="home-section-title text-center"><?php echo t('home.why_notefox'); ?></h2>
            <div class="home-features">
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_private_title'); ?></h3>
                    <p><?php echo t('home.feature_private_desc'); ?></p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <polyline points="16 6 12 2 8 6"/>
                            <line x1="12" y1="2" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_sync_title'); ?></h3>
                    <p><?php echo t('home.feature_sync_desc'); ?></p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_multi_title'); ?></h3>
                    <p><?php echo t('home.feature_multi_desc'); ?></p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_rich_title'); ?></h3>
                    <p><?php echo t('home.feature_rich_desc'); ?></p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_oss_title'); ?></h3>
                    <p><?php echo t('home.feature_oss_desc'); ?></p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                    <h3><?php echo t('home.feature_search_title'); ?></h3>
                    <p><?php echo t('home.feature_search_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container text-center">
            <h2 class="home-section-title"><?php echo t('home.cta_title'); ?></h2>
            <p class="page-subtitle"><?php echo t('home.cta_desc'); ?></p>
            <a href="/alpha/install/" class="btn hero-cta">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <?php echo t('home.install_notefox'); ?>
            </a>
        </div>
    </section>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
