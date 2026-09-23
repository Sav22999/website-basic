<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "How to get the Sync history – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>How to get the sync history</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2025-06-01</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 4.6+</span>
        </div>
        <p>
            The <strong>Sync history</strong> lets you see the previous versions of the notes you synced with your
            Notefox Account, and download one of them again. It is useful when you need to recover a note you
            deleted or overwrote by mistake on one of your devices.
        </p>

        <h2>It is not enabled by default</h2>
        <p>
            The Sync history is a <strong>permission granted per account</strong>, not a setting you can turn on
            yourself: there is no button in the extension or on this website to enable it. Every account starts
            without it, and while it is not granted the history stays hidden and the sync keeps working exactly
            as usual.
        </p>
        <p>
            Keeping the previous versions of your notes on the server costs storage and maintenance work, so this
            feature is reserved to the people who <strong>support the project with a donation</strong>. This is
            what makes it possible to keep the sync service running and maintained in the future as well.
        </p>

        <h2>1. Make a donation on LiberaPay</h2>
        <p>
            <a href="https://liberapay.com" target="_blank" rel="noopener">LiberaPay</a> is a free and
            open-source platform for recurring donations, and for this reason it is the preferred way to
            support the project. The donation has to be made on <strong>LiberaPay</strong>, choosing
            <strong>Stripe</strong> as the payment method (credit card or SEPA direct debit). Stripe applies
            much lower processing fees than PayPal, ensuring that almost the entire amount of your donation
            directly reaches the project (with only a minimal fee deducted, much lower than what PayPal
            charges). For this reason, donations made with <strong>PayPal are not eligible</strong> for this
            feature.
        </p>
        <p>
            Both a <strong>one-time donation</strong> and a <strong>yearly subscription</strong> are accepted.
            Regardless of the type you choose, the Sync history permission is <strong>granted for
            lifetime</strong> — it will stay active on your account permanently, even if a subscription
            ends or is cancelled.
        </p>
        <p class="text-center">
            <a href="https://liberapay.com/Sav22999/donate" class="btn" target="_blank" rel="noopener">LiberaPay</a>
        </p>

        <h2>2. Contact the developer</h2>
        <p>
            After the donation you have to <strong>contact the developer</strong>, who will give you further
            information about it and will enable the Sync history on your account. Please mention your
            <strong>Login ID</strong> and tell the date and the amount of the donation, so that
            the right account and the right payment can be found.
        </p>
        <div class="btn-group text-center">
            <a href="https://t.me/sav_projects/7" class="btn" target="_blank" rel="noopener">Telegram</a>
            <a href="/alpha/contact/" class="btn btn--secondary">Email</a>
        </div>

        <h2>Already donated in the past?</h2>
        <p>
            If you have already made a donation on LiberaPay in the past and would like to get the Sync
            history enabled on your account, simply contact the developer through one of the channels above.
            Provide your <strong>Login ID</strong> and the approximate date and amount of your donation so
            it can be verified.
        </p>

        <h2>After it is enabled</h2>
        <p>
            Once the permission has been granted, the Sync history becomes available in Notefox for your account:
            you can see the dated list of your past synced versions and download the one you need. Nothing else
            changes — your notes, your password, and the normal syncing stay the same.
        </p>

        <h2>Important</h2>
        <p>
            The history only contains the versions that were actually synced with the Notefox Account, so notes
            you never synced cannot be recovered from it. The data stays encrypted on the server, and enabling
            the Sync history does not give anyone else access to your notes. It is always a good idea to keep
            your own backup too: see <a href="/alpha/help/import-export-data/">how to import and export data</a>.
        </p>
        <p>
            A donation is a voluntary support to the project and it is not refundable; the Sync history is a
            thank-you for that support, and not a paid service with a warranty. If you have any doubt before
            donating, just ask the developer first.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
