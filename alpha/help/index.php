<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Help – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <h1>Help</h1>
        <p>Need help with Notefox? Find guides, check service status, or get in touch.</p>

        <style>
            .help-cards {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
                margin: 24px 0;
            }

            .help-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
                padding: 24px 16px;
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                text-decoration: none;
                color: var(--color-text);
                box-shadow: var(--shadow-glow);
                transition: border-color var(--transition), box-shadow var(--transition);
                text-align: center;
            }

            .help-card:hover {
                border-color: var(--color-primary);
                box-shadow: var(--shadow-glow-hover);
                text-decoration: none;
                color: var(--color-text);
            }

            .help-card-icon {
                color: var(--color-primary);
            }

            .help-card-title {
                font-weight: 600;
                font-size: 1rem;
            }

            .help-card-desc {
                font-size: 0.875rem;
                color: var(--color-text-muted);
                margin: 0;
                text-align: left;
                align-self: stretch;
            }

            @media (max-width: 640px) {
                .help-cards {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="help-cards">
            <a href="/alpha/help/status/" class="help-card">
                <svg class="help-card-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <span class="help-card-title">Service status</span>
                <p class="help-card-desc">Check if Notefox Account services are running correctly.</p>
            </a>
            <a href="/alpha/help/faq/" class="help-card">
                <svg class="help-card-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span class="help-card-title">Guides &amp; FAQ</span>
                <p class="help-card-desc">Step-by-step guides, tips, and answers to common questions.</p>
            </a>
            <a href="/alpha/docs/" class="help-card">
                <svg class="help-card-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 18 22 12 16 6"/>
                    <polyline points="8 6 2 12 8 18"/>
                </svg>
                <span class="help-card-title">API documentation</span>
                <p class="help-card-desc">Full reference for the Sav Account API v2.</p>
            </a>
            <a href="https://github.com/Sav22999/websites-notes/issues" class="help-card" target="_blank"
               rel="noopener">
                <svg class="help-card-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <span class="help-card-title">Report an issue</span>
                <p class="help-card-desc">Found a bug? Open an issue on GitHub.</p>
            </a>
        </div>

        <hr>

        <h2>Contact</h2>
        <p>Can't find what you need? Reach out directly:</p>

        <div class="btn-group" style="margin-bottom: 32px;">
            <a href="https://t.me/sav_projects/7" class="btn" target="_blank" rel="noopener">Telegram</a>
            <a href="mailto:saverio.morelli@protonmail.com" class="btn btn--secondary">Email</a>
        </div>

        <hr>

        <h2>Quick links</h2>
        <ul class="link-list">
            <li class="link-list-item"><a href="/alpha/help/notefox-account-v2/" class="link-list-link">Notefox Account
                    v2 (Sav Account)</a></li>
            <li class="link-list-item"><a href="/alpha/help/import-export-data/" class="link-list-link">How to import
                    and export data</a></li>
            <li class="link-list-item"><a href="/alpha/help/search/" class="link-list-link">How the search feature
                    works</a></li>
            <li class="link-list-item"><a href="/alpha/help/own-server-for-notefox-sync/" class="link-list-link">How to
                    run your own sync server</a></li>
            <li class="link-list-item"><a href="/alpha/privacy/" class="link-list-link">Privacy policy</a></li>
            <li class="link-list-item"><a href="/alpha/terms/" class="link-list-link">Terms of service</a></li>
        </ul>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
