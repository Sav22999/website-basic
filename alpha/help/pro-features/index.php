<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Pro features – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Pro features</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Published: 2026-09-24</span>
        </div>

        <p>
            Notefox is, and will always be, a <strong>free and open-source</strong> project. Creating an account,
            syncing your notes, and using the browser extension costs nothing. However, some extra features require
            additional server resources and maintenance work, so they are reserved for people who
            <strong>support the project with a donation</strong>.
        </p>
        <p>
            These are called <strong>pro features</strong>. Once enabled on your account, they stay active
            <strong>permanently</strong> &mdash; there is no expiry date, no subscription to renew, and no risk
            of losing access.
        </p>

        <h2>What is included</h2>

        <style>
            .pro-features-list {
                list-style: none;
                padding: 0;
                margin: 20px 0;
            }

            .pro-feature-item {
                display: flex;
                gap: 14px;
                padding: 16px;
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                margin-bottom: 10px;
            }

            .pro-feature-icon {
                flex-shrink: 0;
                color: var(--color-primary);
                margin-top: 2px;
            }

            .pro-feature-title {
                font-weight: 600;
                margin-bottom: 4px;
            }

            .pro-feature-desc {
                font-size: 0.875rem;
                color: var(--color-text-muted);
                line-height: 1.5;
                margin: 0;
            }
        </style>

        <ul class="pro-features-list">
            <li class="pro-feature-item">
                <svg class="pro-feature-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <div>
                    <div class="pro-feature-title">Sync history</div>
                    <p class="pro-feature-desc">
                        Keep previous versions of your synced notes on the server. If you accidentally delete or
                        overwrite a note, you can go back and download an earlier version. Without pro features, only
                        the latest sync is stored.
                    </p>
                </div>
            </li>
            <li class="pro-feature-item">
                <svg class="pro-feature-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <div>
                    <div class="pro-feature-title">View and edit notes from the web</div>
                    <p class="pro-feature-desc">
                        View and edit your synced notes directly from the Notefox website, without the browser
                        extension. Useful when you are on a device where the extension is not available. Browse
                        all your notes with search and sorting, and edit them with the full set of features:
                        rich text formatting, tags, colours, and folders.
                    </p>
                </div>
            </li>
            <li class="pro-feature-item">
                <svg class="pro-feature-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <div>
                    <div class="pro-feature-title">Future features</div>
                    <p class="pro-feature-desc">
                        Pro features is a growing set. New capabilities will be added over time and they will
                        automatically become available to everyone who already has pro features enabled &mdash;
                        no extra steps needed.
                    </p>
                </div>
            </li>
        </ul>

        <h2>How to get pro features</h2>

        <h3>1. Make a donation on LiberaPay</h3>
        <p>
            <a href="https://liberapay.com" target="_blank" rel="noopener">LiberaPay</a> is a free and open-source
            platform for donations, and for this reason it is the preferred way to support the project. The donation
            has to be made on <strong>LiberaPay</strong>, choosing <strong>Stripe</strong> as the payment method
            (credit card or SEPA direct debit). Stripe applies much lower processing fees than PayPal, ensuring that
            almost the entire amount of your donation directly reaches the project. For this reason, donations made
            with <strong>PayPal are not eligible</strong> for pro features.
        </p>
        <p>
            Both a <strong>one-time donation</strong> and a <strong>yearly subscription</strong> are accepted.
            Regardless of the type you choose, pro features are <strong>granted for lifetime</strong> &mdash;
            they will stay active on your account permanently, even if a subscription ends or is cancelled.
        </p>
        <p class="text-center">
            <a href="https://liberapay.com/Sav22999/donate" class="btn" target="_blank" rel="noopener">Donate on LiberaPay</a>
        </p>

        <h3>2. Contact the developer</h3>
        <p>
            After the donation, <strong>contact the developer</strong> to have pro features enabled on your account.
            Please mention your <strong>Login ID</strong> and tell the date and the amount of the donation, so
            that the right account and the right payment can be found.
        </p>
        <p>
            Your Login ID is shown in the
            <a href="/alpha/my/account/">account dashboard</a> once you are logged in. It is not sensitive
            information and can be safely shared with the developer.
        </p>
        <div class="btn-group text-center">
            <a href="https://t.me/sav_projects/7" class="btn" target="_blank" rel="noopener">Telegram</a>
            <a href="/alpha/contact/" class="btn btn--secondary">Email</a>
        </div>

        <h2>Already donated in the past?</h2>
        <p>
            If you have already made a donation on LiberaPay in the past, simply contact the developer through one
            of the channels above. Provide your <strong>Login ID</strong> and the approximate date and amount of
            your donation so it can be verified. If you already have sync history enabled, you already have pro
            features &mdash; both permissions are granted together.
        </p>

        <h2>After it is enabled</h2>
        <p>
            Once pro features have been granted, all the included services become available immediately. Nothing
            else changes &mdash; your notes, your password, and the normal syncing stay the same.
        </p>

        <h2>Important</h2>
        <p>
            A donation is a voluntary support to the project and it is not refundable; pro features are a thank-you
            for that support, and not a paid service with a warranty. The core functionality of Notefox &mdash;
            note-taking, syncing, and the browser extension &mdash; remains free for everyone. If you have any doubt
            before donating, just ask the developer first.
        </p>

    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
