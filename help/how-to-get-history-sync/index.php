<html>
<head>
    <?php
    $title = "How to get the Sync history – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content justify">
            <h1 class="title-section center">How to get the Sync history</h1>
            <p>
                The <strong>Sync history</strong> lets you see the previous versions of the notes you synced with your
                Notefox Account, and download one of them again. It is useful when you need to recover a note you
                deleted or overwrote by mistake on one of your devices.
            </p>

            <h2 class="subtitle-section">It is not enabled by default</h2>
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

            <h2 class="subtitle-section">1. Make a donation on LiberaPay</h2>
            <p>
                The donation has to be made on <strong>LiberaPay</strong>, choosing <strong>Stripe</strong> as the
                payment method (credit card or SEPA direct debit). Stripe applies much lower processing fees than PayPal,
                ensuring that almost the entire amount of your donation directly reaches the project (with only a minimal fee deducted, much lower than what PayPal charges).
                For this reason, donations made with <strong>PayPal are not eligible</strong> for this feature.
            </p>
            <p>
                Both a <strong>one-time donation</strong> and a <strong>yearly subscription</strong> (a donation
                renewed once a year) are accepted. On LiberaPay you can pick the amount you prefer and the renewal
                period; if you choose the yearly subscription, the permission stays active as long as the
                subscription is active.
            </p>
            <p class="center">
                <input type="button" class="button button-with-icon button-liberapay" value="LiberaPay"
                       onclick="location.href='https://liberapay.com/Sav22999/donate'">
            </p>

            <h2 class="subtitle-section">2. Contact the developer</h2>
            <p>
                After the donation you have to <strong>contact the developer</strong>, who will give you further
                information about it and will enable the Sync history on your account. Please write from (or mention)
                the e-mail address of your Notefox Account or your Login ID, and tell the date and the amount of the donation, so that
                the right account and the right payment can be found.
            </p>
            <p class="center">
                <input type="button" class="button button-with-icon button-telegram" value="Telegram"
                       onclick="location.href='https://t.me/sav_projects/7'">
                <input type="button" class="button button-with-icon button-email" value="Email"
                       onclick="location.href='mailto:saverio.morelli@protonmail.com'">
            </p>

            <h2 class="subtitle-section">After it is enabled</h2>
            <p>
                Once the permission has been granted, the Sync history becomes available in Notefox for your account:
                you can see the dated list of your past synced versions and download the one you need. Nothing else
                changes — your notes, your password, and the normal syncing stay the same.
            </p>

            <h2 class="subtitle-section">Important</h2>
            <p>
                The history only contains the versions that were actually synced with the Notefox Account, so notes
                you never synced cannot be recovered from it. The data stays encrypted on the server, and enabling
                the Sync history does not give anyone else access to your notes. It is always a good idea to keep
                your own backup too: see <a href="../import-export-data/">how to import and export data</a>.
            </p>
            <p>
                A donation is a voluntary support to the project and it is not refundable; the Sync history is a
                thank-you for that support, and not a paid service with a warranty. If you have any doubt before
                donating, just ask the developer first.
            </p>
        </div>
    </div>
</main>

</body>
</html>
