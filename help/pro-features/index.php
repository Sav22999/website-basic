<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Advanced features & donations – Notefox";
$selected_menu = "help";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Advanced features &amp; donations</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2026-09-30</span>
        </div>

        <p>
            Notefox is, and will always be, a <strong>free and open-source</strong> project. Creating an account,
            syncing your notes, and using the browser extension costs nothing.
        </p>
        <p>
            Starting today, the features that were previously called "pro features" are now
            <strong>available to all users for free</strong>. Everyone with a Notefox Account can use sync
            history, edit notes from the web, and benefit from any future advanced feature &mdash; no
            donation required.
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
                        overwrite a note, you can go back and download an earlier version.
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
                    <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <div>
                    <div class="pro-feature-title">More to come</div>
                    <p class="pro-feature-desc">
                        New advanced features will be added over time and they will automatically become
                        available to all users &mdash; no extra steps needed.
                    </p>
                </div>
            </li>
        </ul>

        <h2>Why these features are expensive</h2>
        <p>
            These features require significantly more server resources than basic note syncing. Keeping the
            sync history means storing every version of every note for every user &mdash; which multiplies
            the storage and database costs. The web editor adds server-side processing, bandwidth,
            and ongoing maintenance work. All of this runs on infrastructure that the developer pays for
            out of pocket.
        </p>
        <p>
            Making these features free for everyone is a deliberate choice: we believe all Notefox users
            should have the best experience possible. But this is only sustainable if the community helps
            cover the costs.
        </p>

        <h2>Support the project with a donation</h2>
        <p>
            If you use and enjoy these features, please consider making a <strong>donation</strong>.
            Even a small, <strong>periodic contribution</strong> makes a real difference &mdash; it helps
            cover the ongoing server costs and allows the developer to keep improving Notefox without
            charging anyone.
        </p>
        <p>
            A <strong>recurring donation</strong> (e.g. yearly) is especially valuable because it provides
            a predictable income that helps plan ahead and keep the service stable. But one-time donations
            are appreciated just as much.
        </p>

        <h3>How to donate</h3>
        <p>
            <a href="https://liberapay.com" target="_blank" rel="noopener">LiberaPay</a> is a free and open-source
            platform for donations, and for this reason it is the preferred way to support the project. Choose
            <strong>Stripe</strong> as the payment method (credit card or SEPA direct debit) to ensure that
            almost the entire amount directly reaches the project, thanks to Stripe's lower processing fees.
        </p>
        <p class="text-center">
            <a href="https://liberapay.com/Sav22999/donate" class="btn" target="_blank" rel="noopener">Donate on
                LiberaPay</a>
        </p>

        <p>
            If you have questions or want to get in touch with the developer, you can do so through
            one of these channels:
        </p>
        <div class="btn-group text-center">
            <a href="https://t.me/sav_projects/7" class="btn" target="_blank" rel="noopener">Telegram</a>
            <a href="/contact/" class="btn btn--secondary">Email</a>
        </div>

        <h2>Important</h2>
        <p>
            A donation is a voluntary contribution and is not refundable. Notefox is not a paid service &mdash;
            it is a free, open-source project maintained by an independent developer. The advanced features
            are available to everyone regardless of whether they donate. Your support simply helps keep
            the project alive and sustainable for the long term.
        </p>

    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
</body>
</html>
