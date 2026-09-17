<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
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
            <p class="page-subtitle"><strong>Take notes</strong> on every website in a <strong>smart</strong> and
                <strong>simple</strong> way!</p>

            <a href="/alpha/install/" class="btn hero-cta">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Install Notefox
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
            <h2 class="home-section-title text-center">How it works</h2>
            <div class="home-steps">
                <div class="home-step">
                    <div class="home-step-number">1</div>
                    <h3>Install the extension</h3>
                    <p>Add Notefox to Firefox, Chrome, or Edge with a single click.</p>
                </div>
                <div class="home-step">
                    <div class="home-step-number">2</div>
                    <h3>Write your notes</h3>
                    <p>Click the Notefox icon on any page and start typing. Notes are saved automatically.</p>
                </div>
                <div class="home-step">
                    <div class="home-step-number">3</div>
                    <h3>Find them again</h3>
                    <p>Visit the page again and your notes are right there, or search across all your notes.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--alt">
        <div class="container">
            <h2 class="home-section-title text-center">Why Notefox?</h2>
            <div class="home-features">
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3>Private by design</h3>
                    <p>No ads, no tracking, no analytics. Your data stays on your device or encrypted on our
                        servers.</p>
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
                    <h3>Sync across devices</h3>
                    <p>Create a free Notefox Account to keep your notes in sync on every browser and every device.</p>
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
                    <h3>Multi-browser</h3>
                    <p>Available for Firefox, Chrome, and Edge. Same great experience everywhere you browse.</p>
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
                    <h3>Rich text editing</h3>
                    <p>Bold, italic, headings, lists, and more. Format your notes however you like.</p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </div>
                    <h3>Free &amp; open source</h3>
                    <p>100% open source on GitHub. Review the code, contribute, or run your own sync server.</p>
                </div>
                <div class="home-feature">
                    <div class="home-feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                    <h3>Powerful search</h3>
                    <p>Find any note instantly by title, content, or URL. Multi-term search with real-time results.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container text-center">
            <h2 class="home-section-title">Ready to get started?</h2>
            <p class="page-subtitle">Install Notefox for free and start taking notes on any website.</p>
            <a href="/alpha/install/" class="btn hero-cta">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Install Notefox
            </a>
        </div>
    </section>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
