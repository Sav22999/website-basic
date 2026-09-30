<!DOCTYPE html>
<html lang="it">
<head>
    <?php
    $title = "Termini di servizio";
    $description = "Termini di servizio di Sav PDF Viewer. Informazioni sulla licenza, condizioni d'uso e limitazioni di responsabilità.";
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
        <h1 class="title-section" data-i18n="terms.title">Termini di servizio</h1>
    </div>
    <br class="big-space">
    <div class="horizontal-center width-80-perc">

        <div class="legal-lang-tabs">
            <button type="button" class="legal-lang-tab active" onclick="switchLegalLang('it', this)">Italiano</button>
            <button type="button" class="legal-lang-tab" onclick="switchLegalLang('en', this)">English</button>
        </div>

        <!-- VERSIONE ITALIANA (primaria) -->
        <div class="legal-lang-content active" id="legal-it">
            <p class="justify">
                I presenti Termini di servizio regolano l'utilizzo dell'applicazione Sav PDF Viewer e del sito web savpdfviewer.com. Utilizzando l'app o accedendo a questo sito web, si accettano i presenti termini.
            </p>
            <p class="justify">
                <b>1. Licenza</b>
                <br>
                Sav PDF Viewer &egrave; un software open source rilasciato sotto la GNU General Public License v3.0 (GPL-3.0). &Egrave; consentito utilizzare, copiare, modificare e distribuire il software in conformit&agrave; con i termini della licenza GPL-3.0. Il testo completo della licenza &egrave; disponibile su <a href="https://www.gnu.org/licenses/gpl-3.0.html" target="_blank" rel="noopener noreferrer">gnu.org/licenses/gpl-3.0</a> e nel repository GitHub del progetto.
            </p>
            <p class="justify">
                <b>2. Utilizzo dell'applicazione</b>
                <br>
                Sav PDF Viewer &egrave; fornito come visualizzatore di PDF per dispositivi Android. L'app &egrave; progettata per aprire e leggere file PDF &mdash; non &egrave; un editor di PDF. L'app pu&ograve; essere utilizzata per qualsiasi scopo lecito. L'app non richiede permessi e non raccoglie alcun dato degli utenti.
            </p>
            <p class="justify">
                <b>3. Propriet&agrave; intellettuale</b>
                <br>
                Il codice sorgente di Sav PDF Viewer &egrave; concesso in licenza GPL-3.0. Il nome &ldquo;Sav PDF Viewer&rdquo;, l'icona dell'app e il design del sito web sono propriet&agrave; intellettuale di Saverio Morelli e non possono essere utilizzati per rappresentare in modo fuorviante l'origine di opere derivate.
            </p>
            <p class="justify">
                <b>4. Distribuzione</b>
                <br>
                Il canale di distribuzione ufficiale di Sav PDF Viewer &egrave; Google Play. Sebbene il codice sorgente sia pubblicamente disponibile su GitHub, la redistribuzione dell'applicazione compilata al di fuori dei canali ufficiali deve essere conforme ai termini della licenza GPL-3.0.
            </p>
            <p class="justify">
                <b>5. Donazioni</b>
                <br>
                Le donazioni effettuate tramite LiberaPay, PayPal o qualsiasi altra piattaforma supportata sono volontarie e non rimborsabili. Le donazioni non conferiscono alcun diritto aggiuntivo, funzionalit&agrave; o privilegio oltre a quelli disponibili per tutti gli utenti.
            </p>
            <p class="justify">
                <b>6. Esclusione di garanzie</b>
                <br>
                Sav PDF Viewer &egrave; fornito &ldquo;cos&igrave; com'&egrave;&rdquo;, senza alcuna garanzia di alcun tipo, espressa o implicita, incluse, a titolo esemplificativo, le garanzie di commerciabilit&agrave;, idoneit&agrave; per un particolare scopo e non violazione. Lo sviluppatore non garantisce che l'app sar&agrave; priva di errori o che funzioner&agrave; senza interruzioni.
            </p>
            <p class="justify">
                <b>7. Limitazione di responsabilit&agrave;</b>
                <br>
                In nessun caso Saverio Morelli potr&agrave; essere ritenuto responsabile per danni diretti, indiretti, incidentali, speciali o consequenziali derivanti da o connessi all'uso o all'impossibilit&agrave; di utilizzo dell'applicazione o di questo sito web.
            </p>
            <p class="justify">
                <b>8. Modulo di contatto</b>
                <br>
                Utilizzando il modulo di contatto presente su questo sito web, l'utente accetta che le informazioni fornite (nome, indirizzo email e dettagli del messaggio) saranno utilizzate esclusivamente per gestire la richiesta. La lingua predefinita del browser e il Paese approssimativo (ricavato dall'indirizzo IP) vengono inoltre raccolti automaticamente per consentire una risposta appropriata; l'indirizzo IP non viene conservato. Il messaggio non sar&agrave; pubblicato n&eacute; condiviso con terze parti. Un'email di conferma sar&agrave; inviata all'indirizzo fornito. Per maggiori dettagli, consultare l'<a href="/privacy/">Informativa sulla privacy</a>.
            </p>
            <p class="justify">
                <b>9. Servizi di terze parti</b>
                <br>
                L'app non utilizza alcun servizio di terze parti. Tuttavia, il download dell'app da Google Play &egrave; soggetto ai termini di servizio di Google. Questo sito web non utilizza cookie n&eacute; tracciamento di terze parti.
            </p>
            <p class="justify">
                <b>10. Modifiche ai presenti termini</b>
                <br>
                Potremmo aggiornare i presenti Termini di servizio periodicamente. Eventuali modifiche saranno riportate su questa pagina con una data di revisione aggiornata. L'uso continuato dell'app o del sito web dopo le modifiche costituisce accettazione dei nuovi termini.
            </p>
            <p class="justify">
                <b>11. Legge applicabile</b>
                <br>
                I presenti Termini di servizio sono disciplinati dalle leggi italiane e, ove applicabile, dai regolamenti dell'Unione Europea.
            </p>
            <p class="justify">
                <b>12. Contatti</b>
                <br>
                Per qualsiasi domanda relativa ai presenti Termini di servizio, contattare:
                <br>
                Saverio Morelli &mdash; <a href="https://saveriomorelli.com/contact" target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
            </p>
            <p class="justify">
                <em>Ultimo aggiornamento: 30 settembre 2026</em>
            </p>
        </div>

        <!-- ENGLISH VERSION (courtesy translation) -->
        <div class="legal-lang-content" id="legal-en">
            <div class="legal-courtesy-notice">
                This is a courtesy translation. In case of any discrepancy between this English version and the Italian version, the Italian version shall prevail.
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
                <em>Last update: 30 September 2026</em>
            </p>
        </div>

    </div>
</main>

<footer>
    <span data-i18n="footer.developed">Sviluppato con</span>
    <span class="image-heart image-background-primary image-square-20px"></span>
    <span data-i18n="footer.by">da</span> <a href="https://saveriomorelli.com" class="author-name" target="_blank"
                                             rel="noopener noreferrer">Saverio Morelli</a>
    <div class="footer-links">
        <a href="/privacy/" data-i18n="footer.privacy_policy">Informativa sulla privacy</a>
        <span class="footer-sep">·</span>
        <a href="/terms/" data-i18n="footer.terms">Termini di servizio</a>
        <span class="footer-sep">·</span>
        <a href="https://github.com/Sav22999/sav-pdf-viewer-pro" target="_blank" rel="noopener noreferrer">GitHub</a>
    </div>
</footer>

<script>
function switchLegalLang(lang, btn) {
    document.querySelectorAll('.legal-lang-tab').forEach(function(t) { t.classList.remove('active'); });
    document.querySelectorAll('.legal-lang-content').forEach(function(c) { c.classList.remove('active'); });
    btn.classList.add('active');
    var target = document.getElementById('legal-' + lang);
    if (target) target.classList.add('active');
}
</script>

</body>
</html>
