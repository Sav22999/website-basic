<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Support Notefox – Donate";
$selected_menu = "donate";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <div class="donate-hero">
            <svg class="donate-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <h1>Support Notefox</h1>
            <p>Notefox is free, open source, and built by one person. If it is useful to you, a small donation helps keep it alive and improving.</p>
        </div>

        <div class="donate-why">
            <ul class="donate-perks">
                <li class="donate-perk">
                    <svg class="donate-perk-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <p class="donate-perk-label">No ads, no tracking</p>
                </li>
                <li class="donate-perk">
                    <svg class="donate-perk-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>
                    </svg>
                    <p class="donate-perk-label">Server &amp; hosting costs</p>
                </li>
                <li class="donate-perk">
                    <svg class="donate-perk-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <p class="donate-perk-label">Fuels new features</p>
                </li>
            </ul>
        </div>

        <div class="donate-methods">
            <a href="https://liberapay.com/Sav22999/" class="donate-method" target="_blank" rel="noopener">
                <span class="donate-method-icon icon-mask icon-mask--liberapay" aria-hidden="true"></span>
                <div>
                    <div class="donate-method-name">LiberaPay</div>
                    <div class="donate-method-note">No fees &middot; Recurring or one-time</div>
                </div>
                <span class="donate-recommended">Recommended</span>
            </a>
            <a href="https://www.paypal.me/saveriomorelli" class="donate-method" target="_blank" rel="noopener">
                <span class="donate-method-icon icon-mask icon-mask--paypal" aria-hidden="true"></span>
                <div>
                    <div class="donate-method-name">PayPal</div>
                    <div class="donate-method-note">Quick &amp; easy</div>
                </div>
            </a>
        </div>

        <p class="text-center" style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 8px;">
            Even a small contribution makes a difference. Thank you!
        </p>

        <hr>

        <p class="donate-section-title text-center">My other projects</p>

        <div class="projects-grid">
            <a href="https://emojiaddon.com" class="project-link" target="_blank" rel="noopener">Emoji</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/accented-letters/" class="project-link" target="_blank" rel="noopener">Accented Letters</a>
            <a href="https://savpdfviewer.com" class="project-link" target="_blank" rel="noopener">Sav PDF Viewer</a>
            <a href="https://addons.mozilla.org/it/firefox/addon/limite/" class="project-link" target="_blank" rel="noopener">Limite</a>
            <a href="https://play.google.com/store/apps/details?id=com.saverio.wordoftheday_en" class="project-link" target="_blank" rel="noopener">Word of the Day</a>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
