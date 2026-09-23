<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Privacy policy";
    $description = "Sav PDF Viewer privacy policy. The app collects no data, requires no permissions, and has no tracking or analytics.";
    $canonical_path = "/alpha/privacy/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "privacy";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

<main class="page-content">
    <div class="horizontal-center">
        <h1 class="title-section" data-i18n="privacy.title">Privacy policy</h1>
    </div>
    <br class="big-space">
    <div class="horizontal-center width-80-perc">
        <div class="lang-notice" data-i18n="privacy.lang_notice" style="display: none;">This page is available in
            English only
        </div>
        <p class="justify">
            Data Controller: Saverio Morelli
            <br>
            Contact: <a href="https://saveriomorelli.com/contact" target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
        </p>
        <p class="justify">
            <b>1. What data we collect</b>
            <br>
            Sav PDF Viewer does not collect any personal data from its users. No usage data, personal identifiers,
            location data, device information, or any other form of personally identifiable information is collected,
            stored, or transmitted to external servers. The app operates entirely offline with respect to user data.
        </p>
        <p class="justify">
            <b>2. Third-party services</b>
            <br>
            We do not use any third-party analytics, advertising, crash-reporting, or tracking services that collect
            personal user data. The app does not include any SDK or library that transmits data to third parties. If in
            the future third-party libraries are introduced, we will update this policy accordingly and notify users
            through an app update.
        </p>
        <p class="justify">
            <b>3. Permissions</b>
            <br>
            Sav PDF Viewer requires no permissions to function. The app uses Android's built-in file picker (Storage
            Access Framework) to let you choose which file to open. Only the file you select is accessed — the app
            cannot browse your storage or access other files.
        </p>
        <p class="justify">
            <b>4. Local data storage</b>
            <br>
            The app stores the following data locally on your device only:
            <br>
            - Recent files list (file names and last read position)
            <br>
            - Bookmarks you create
            <br>
            - Your preferences and settings
            <br>
            This data is never transmitted, synced, or backed up to any server. It remains on your device and is deleted
            when you uninstall the app or clear its data.
        </p>
        <p class="justify">
            <b>5. Contact form and emails</b>
            <br>
            When you use the contact form on this website, we collect the information you voluntarily provide: your
            name, email address, and the details of your request (topic, app version, operating system, and
            description). In addition, your browser's default language and your approximate country (derived from your
            IP address via a third-party geolocation service) are automatically collected to help us respond in the
            appropriate language. Your IP address is not stored.
            <br><br>
            This data is used exclusively to respond to your request. A confirmation email is sent to the address you
            provide, and a copy of your message (including the automatically collected information) is sent to the
            developer. Your message is not published, shared with third parties, or used for any purpose other than
            handling your request. Emails are stored only in the developer's mailbox for as long as necessary to resolve
            your inquiry, and are then deleted.
        </p>
        <p class="justify">
            <b>6. Legal basis</b>
            <br>
            The app does not collect or process any personal data. For the contact form on this website, the legal basis
            for processing is your consent (Art. 6(1)(a) GDPR), given when you submit the form. You may withdraw your
            consent at any time by contacting us.
        </p>
        <p class="justify">
            <b>7. User rights</b>
            <br>
            Under the GDPR and applicable laws you retain the following rights:
            <br>
            - Right of access: you may ask us what data we hold about you.
            <br>
            - Right to erasure: you may request the deletion of any personal data we hold.
            <br>
            - Right to contact us: you can reach out at any time via <a href="https://saveriomorelli.com/contact"
                                                                        target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>.
            <br>
            - Right to be informed: you can request information about any future changes to this privacy policy.
        </p>
        <p class="justify">
            <b>8. Children's privacy</b>
            <br>
            Sav PDF Viewer does not knowingly collect any data from children or any other users. The contact form does
            not knowingly collect data from children under 16. If you believe a child has submitted personal data
            through the contact form, please contact us so we can delete it.
        </p>
        <p class="justify">
            <b>9. Security</b>
            <br>
            Because we do not collect or store any personal data on external servers, the risk of data breach is
            minimal. Contact form submissions are transmitted via encrypted email (TLS) and stored only in the
            developer's mailbox. We follow best practices in app development to ensure the app itself remains secure and
            does not introduce vulnerabilities on your device.
        </p>
        <p class="justify">
            <b>10. Changes to this policy</b>
            <br>
            We may update this Privacy Policy from time to time. Any changes will be reflected on this page with an
            updated revision date. We encourage you to review this page periodically.
        </p>
        <p class="justify">
            <b>11. Governing Law</b>
            <br>
            This Privacy Policy is governed by the applicable laws in Italy and, where applicable, by the European
            Union's General Data Protection Regulation (GDPR).
        </p>
        <p class="justify">
            <b>12. Contact</b>
            <br>
            If you have any questions or concerns about this Privacy Policy, please contact:
            <br>
            Saverio Morelli — <a href="https://saveriomorelli.com/contact" target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
        </p>
        <p class="justify">
            <em>Last update: 24 September 2026</em>
        </p>
    </div>
</main>

<footer>
    <span data-i18n="footer.developed">Developed with</span>
    <span class="image-heart image-background-primary image-square-20px"></span>
    <span data-i18n="footer.by">by</span> <a href="https://saveriomorelli.com" class="author-name" target="_blank"
                                             rel="noopener noreferrer">Saverio Morelli</a>
    <div class="footer-links">
        <a href="/alpha/privacy/" data-i18n="footer.privacy_policy">Privacy policy</a>
        <span class="footer-sep">·</span>
        <a href="/alpha/terms/" data-i18n="footer.terms">Terms of service</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

</body>
</html>
