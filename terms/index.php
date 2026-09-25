<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Terms of service";
    $description = "Terms of service for Sav PDF Viewer. Read about the license, usage terms, and disclaimers.";
    $canonical_path = "/terms/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "terms";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="page-content">
    <div class="horizontal-center">
        <h1 class="title-section" data-i18n="terms.title">Terms of service</h1>
    </div>
    <br class="big-space">
    <div class="horizontal-center width-80-perc">
        <div class="lang-notice" data-i18n="terms.lang_notice" style="display: none;">This page is available in English
            only
        </div>
        <p class="justify">
            These Terms of Service govern your use of the Sav PDF Viewer application and the website savpdfviewer.com.
            By using the app or accessing this website, you agree to these terms.
        </p>
        <p class="justify">
            <b>1. License</b>
            <br>
            Sav PDF Viewer is open-source software released under the GNU General Public License v3.0 (GPL-3.0). You may
            use, copy, modify, and distribute the software in accordance with the terms of the GPL-3.0 license. The full
            license text is available at <a href="https://www.gnu.org/licenses/gpl-3.0.html" target="_blank"
                                            rel="noopener noreferrer">gnu.org/licenses/gpl-3.0</a> and in the project's
            GitHub repository.
        </p>
        <p class="justify">
            <b>2. Use of the application</b>
            <br>
            Sav PDF Viewer is provided as a PDF viewer for Android devices. The app is designed to open and read PDF
            files — it is not a PDF editor. You may use the app for any lawful purpose. The app requires no permissions
            and does not collect any user data.
        </p>
        <p class="justify">
            <b>3. Intellectual property</b>
            <br>
            The source code of Sav PDF Viewer is licensed under GPL-3.0. The name "Sav PDF Viewer", the app icon, and
            the website design are the intellectual property of Saverio Morelli and may not be used to misrepresent the
            origin of derivative works.
        </p>
        <p class="justify">
            <b>4. Distribution</b>
            <br>
            The official distribution channel for Sav PDF Viewer is Google Play. While the source code is publicly
            available on GitHub, redistribution of the compiled application outside of official channels should comply
            with the GPL-3.0 license terms.
        </p>
        <p class="justify">
            <b>5. Donations</b>
            <br>
            Donations made through LiberaPay, PayPal, or any other supported platform are voluntary and non-refundable.
            Donations do not grant any additional rights, features, or privileges beyond those available to all users.
        </p>
        <p class="justify">
            <b>6. Disclaimer of warranties</b>
            <br>
            Sav PDF Viewer is provided "as is", without warranty of any kind, express or implied, including but not
            limited to the warranties of merchantability, fitness for a particular purpose, and non-infringement. The
            developer does not guarantee that the app will be error-free or uninterrupted.
        </p>
        <p class="justify">
            <b>7. Limitation of liability</b>
            <br>
            In no event shall Saverio Morelli be liable for any direct, indirect, incidental, special, or consequential
            damages arising out of or in connection with the use or inability to use the application or this website.
        </p>
        <p class="justify">
            <b>8. Contact form</b>
            <br>
            By using the contact form on this website, you agree that the information you provide (name, email address,
            and message details) will be used solely to handle your request. Your browser's default language and
            approximate country (derived from your IP address) are also collected automatically to help us respond
            appropriately; your IP address is not stored. Your message will not be published or shared with third
            parties. A confirmation email will be sent to the address you provide. For more details, see the <a
                    href="/privacy/">Privacy Policy</a>.
        </p>
        <p class="justify">
            <b>9. Third-party services</b>
            <br>
            The app does not use any third-party services. However, downloading the app from Google Play is subject to
            Google's own terms of service. This website does not use cookies or third-party tracking.
        </p>
        <p class="justify">
            <b>10. Changes to these terms</b>
            <br>
            We may update these Terms of Service from time to time. Any changes will be reflected on this page with an
            updated revision date. Continued use of the app or website after changes constitutes acceptance of the new
            terms.
        </p>
        <p class="justify">
            <b>11. Governing law</b>
            <br>
            These Terms of Service are governed by the laws of Italy and, where applicable, by the regulations of the
            European Union.
        </p>
        <p class="justify">
            <b>12. Contact</b>
            <br>
            If you have any questions about these Terms of Service, please contact:
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
        <a href="/privacy/" data-i18n="footer.privacy_policy">Privacy policy</a>
        <span class="footer-sep">·</span>
        <a href="/terms/" data-i18n="footer.terms">Terms of service</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

</body>
</html>
