<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
$title = "Welcome to Notefox";
$selected_menu = "";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <style>
            .welcome-hero {
                text-align: center;
                padding: 32px 0 16px;
            }

            .welcome-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
            }

            .welcome-hero h1 {
                font-size: 1.75rem;
                margin-bottom: 8px;
            }

            .welcome-hero p {
                color: var(--color-text-muted);
                font-size: 1.0625rem;
                max-width: 460px;
                margin: 0 auto;
            }

            .welcome-steps {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 16px;
                margin: 32px 0;
            }

            .welcome-step {
                text-align: center;
                padding: 20px 16px;
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
            }

            .welcome-step-num {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--color-primary);
                color: var(--color-on-primary);
                font-weight: 700;
                font-size: 0.875rem;
                margin-bottom: 12px;
            }

            .welcome-step h3 {
                font-size: 0.9375rem;
                margin: 0 0 6px;
            }

            .welcome-step p {
                font-size: 0.8125rem;
                color: var(--color-text-muted);
                margin: 0;
            }

            .welcome-features {
                margin: 32px 0;
            }

            .welcome-features h2 {
                text-align: center;
                font-size: 1.25rem;
                margin-bottom: 16px;
            }

            .welcome-feature-list {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .welcome-feature-list li {
                display: flex;
                flex-direction: column;
                padding: 20px 16px;
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                font-size: 0.9375rem;
            }

            .welcome-feature-list svg {
                width: 24px;
                height: 24px;
                flex-shrink: 0;
                color: var(--color-text-muted);
                margin: 0 auto 10px;
            }

            .welcome-feature-list strong {
                display: block;
                text-align: center;
                margin-bottom: 4px;
            }

            .welcome-feature-list span {
                font-size: 0.8125rem;
                color: var(--color-text-muted);
                line-height: 1.5;
            }

            .welcome-cta {
                text-align: center;
                padding: 32px 0;
                border-top: 1px solid var(--color-border);
                margin-top: 32px;
            }

            .welcome-cta p {
                color: var(--color-text-muted);
                font-size: 0.9375rem;
                margin-bottom: 16px;
            }

            .welcome-cta .btn-group {
                justify-content: center;
            }

            @media (max-width: 640px) {
                .welcome-steps {
                    grid-template-columns: 1fr;
                }

                .welcome-feature-list {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="welcome-hero">
            <img src="/images/icon.png" alt="Notefox" width="80" height="80" class="welcome-icon">
            <h1>Welcome to Notefox!</h1>
            <p>The extension has been installed. Here's how to get started.</p>
        </div>

        <div class="welcome-steps">
            <div class="welcome-step">
                <span class="welcome-step-num">1</span>
                <h3>Open any website</h3>
                <p>Navigate to any page where you want to save a note.</p>
            </div>
            <div class="welcome-step">
                <span class="welcome-step-num">2</span>
                <h3>Click the Notefox icon</h3>
                <p>Find it in your browser toolbar and click to open the popup.</p>
            </div>
            <div class="welcome-step">
                <span class="welcome-step-num">3</span>
                <h3>Write your note</h3>
                <p>Type your note and it will be saved automatically for that page.</p>
            </div>
        </div>

        <div class="welcome-features">
            <h2>What you can do</h2>
            <ul class="welcome-feature-list">
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <strong>Private by default</strong>
                    <span>Your notes stay on your device, no account required.</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                    </svg>
                    <strong>Rich text editing</strong>
                    <span>Bold, italic, headings, lists, and more.</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <strong>Powerful search</strong>
                    <span>Find any note by title, content, or URL.</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                        <line x1="6" y1="6" x2="6.01" y2="6"/>
                        <line x1="6" y1="18" x2="6.01" y2="18"/>
                    </svg>
                    <strong>Sync across devices</strong>
                    <span>Create a free Notefox Account to keep notes in sync.</span>
                </li>
            </ul>
        </div>

        <div class="welcome-cta">
            <p>Want to sync your notes across browsers? Create a free account.</p>
            <div class="btn-group">
                <a href="/alpha/my/" class="btn">Create an account</a>
                <a href="/alpha/help/" class="btn btn--secondary">Help &amp; guides</a>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
