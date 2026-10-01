<!DOCTYPE html>
<html lang="it">
<head>
    <?php
    $title = "Informativa sulla privacy";
    $description = "Informativa sulla privacy di Sav PDF Viewer. L'app non raccoglie dati, non richiede permessi e non ha tracciamento o analisi.";
    $canonical_path = "/privacy/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "privacy";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="page-content">
    <div class="horizontal-center">
        <h1 class="title-section" data-i18n="privacy.title">Informativa sulla privacy</h1>
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
                Titolare del trattamento: Saverio Morelli
                <br>
                Contatto: <a href="https://saveriomorelli.com/contact" target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
            </p>
            <p class="justify">
                <b>1. Quali dati raccogliamo</b>
                <br>
                Sav PDF Viewer non raccoglie alcun dato personale dai propri utenti. Nessun dato di utilizzo,
                identificativo personale, dato di localizzazione, informazione sul dispositivo o qualsiasi altra forma
                di dato personale viene raccolto, conservato o trasmesso a server esterni. L'app funziona in modo
                completamente offline per quanto riguarda i dati degli utenti.
            </p>
            <p class="justify">
                <b>2. Servizi di terze parti</b>
                <br>
                Non utilizziamo alcun servizio di analisi, pubblicit&agrave;, segnalazione di crash o tracciamento di
                terze parti che raccolga dati personali degli utenti. L'app non include alcun SDK o libreria che
                trasmetta dati a terze parti. Qualora in futuro venissero introdotte librerie di terze parti,
                aggiorneremo la presente informativa di conseguenza e ne daremo comunicazione agli utenti tramite un
                aggiornamento dell'app.
            </p>
            <p class="justify">
                <b>3. Permessi</b>
                <br>
                Sav PDF Viewer non richiede alcun permesso per funzionare. L'app utilizza il selettore di file integrato
                di Android (Storage Access Framework) per consentire la scelta del file da aprire. Solo il file
                selezionato viene consultato &mdash; l'app non pu&ograve; esplorare la memoria del dispositivo n&eacute;
                accedere ad altri file.
            </p>
            <p class="justify">
                <b>4. Conservazione locale dei dati</b>
                <br>
                L'app conserva i seguenti dati esclusivamente in locale sul dispositivo:
                <br>
                - Elenco dei file recenti (nomi dei file e ultima posizione di lettura)
                <br>
                - Segnalibri creati dall'utente
                <br>
                - Preferenze e impostazioni dell'utente
                <br>
                Questi dati non vengono mai trasmessi, sincronizzati o sottoposti a backup su alcun server. Rimangono
                sul dispositivo e vengono eliminati quando si disinstalla l'app o se ne cancellano i dati.
            </p>
            <p class="justify">
                <b>5. Modulo di contatto ed email</b>
                <br>
                Quando si utilizza il modulo di contatto presente su questo sito web, raccogliamo le informazioni
                fornite volontariamente dall'utente: nome, indirizzo email e dettagli della richiesta (argomento,
                versione dell'app, sistema operativo e descrizione). Inoltre, la lingua predefinita del browser e il
                Paese approssimativo (ricavato dall'indirizzo IP tramite un servizio di geolocalizzazione di terze
                parti) vengono raccolti automaticamente per consentirci di rispondere nella lingua appropriata.
                L'indirizzo IP non viene conservato.
                <br><br>
                Questi dati vengono utilizzati esclusivamente per rispondere alla richiesta. Un'email di conferma viene
                inviata all'indirizzo fornito e una copia del messaggio (incluse le informazioni raccolte
                automaticamente) viene inviata allo sviluppatore. Il messaggio non viene pubblicato, condiviso con terze
                parti o utilizzato per scopi diversi dalla gestione della richiesta. Le email vengono conservate nella
                casella di posta dello sviluppatore per il tempo necessario alla risoluzione della richiesta, dopodiché
                vengono eliminate.
            </p>
            <p class="justify">
                <b>6. Base giuridica</b>
                <br>
                L'app non raccoglie n&eacute; tratta alcun dato personale. Per il modulo di contatto presente su questo
                sito web, la base giuridica del trattamento &egrave; il consenso dell'utente (art. 6, par. 1, lett. a)
                del GDPR), prestato al momento dell'invio del modulo. Il consenso pu&ograve; essere revocato in
                qualsiasi momento contattandoci.
            </p>
            <p class="justify">
                <b>7. Diritti dell'utente</b>
                <br>
                Ai sensi del GDPR e delle leggi applicabili, l'utente conserva i seguenti diritti:
                <br>
                - Diritto di accesso: &egrave; possibile chiederci quali dati deteniamo.
                <br>
                - Diritto alla cancellazione: &egrave; possibile richiedere la cancellazione di qualsiasi dato personale
                in nostro possesso.
                <br>
                - Diritto di contattarci: &egrave; possibile raggiungerci in qualsiasi momento tramite <a
                        href="https://saveriomorelli.com/contact" target="_blank" rel="noopener noreferrer">https://saveriomorelli.com/contact</a>.
                <br>
                - Diritto all'informazione: &egrave; possibile richiedere informazioni su eventuali future modifiche
                alla presente informativa sulla privacy.
            </p>
            <p class="justify">
                <b>8. Privacy dei minori</b>
                <br>
                Sav PDF Viewer non raccoglie consapevolmente alcun dato da minori o da qualsiasi altro utente. Il modulo
                di contatto non raccoglie consapevolmente dati da minori di 16 anni. Se si ritiene che un minore abbia
                inviato dati personali tramite il modulo di contatto, si prega di contattarci affinch&eacute; possiamo
                procedere alla cancellazione.
            </p>
            <p class="justify">
                <b>9. Sicurezza</b>
                <br>
                Poich&eacute; non raccogliamo n&eacute; conserviamo alcun dato personale su server esterni, il rischio
                di violazione dei dati &egrave; minimo. I messaggi inviati tramite il modulo di contatto vengono
                trasmessi tramite email crittografata (TLS) e conservati esclusivamente nella casella di posta dello
                sviluppatore. Seguiamo le migliori pratiche nello sviluppo di app per garantire che l'app rimanga sicura
                e non introduca vulnerabilit&agrave; sul dispositivo dell'utente.
            </p>
            <p class="justify">
                <b>10. Modifiche alla presente informativa</b>
                <br>
                Potremmo aggiornare la presente Informativa sulla Privacy periodicamente. Eventuali modifiche saranno
                riportate su questa pagina con una data di revisione aggiornata. Invitiamo gli utenti a consultare
                periodicamente questa pagina.
            </p>
            <p class="justify">
                <b>11. Legge applicabile</b>
                <br>
                La presente Informativa sulla Privacy &egrave; disciplinata dalle leggi applicabili in Italia e, ove
                applicabile, dal Regolamento generale sulla protezione dei dati (GDPR) dell'Unione Europea.
            </p>
            <p class="justify">
                <b>12. Contatti</b>
                <br>
                Per qualsiasi domanda o dubbio relativo alla presente Informativa sulla Privacy, contattare:
                <br>
                Saverio Morelli &mdash; <a href="https://saveriomorelli.com/contact" target="_blank"
                                           rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
            </p>
            <p class="justify">
                <em>Ultimo aggiornamento: 30 settembre 2026</em>
            </p>
        </div>

        <!-- ENGLISH VERSION (courtesy translation) -->
        <div class="legal-lang-content" id="legal-en">
            <div class="legal-courtesy-notice">
                This is a courtesy translation. In case of any discrepancy between this English version and the Italian
                version, the Italian version shall prevail.
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
                location data, device information, or any other form of personally identifiable information is
                collected,
                stored, or transmitted to external servers. The app operates entirely offline with respect to user data.
            </p>
            <p class="justify">
                <b>2. Third-party services</b>
                <br>
                We do not use any third-party analytics, advertising, crash-reporting, or tracking services that collect
                personal user data. The app does not include any SDK or library that transmits data to third parties. If
                in
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
                This data is never transmitted, synced, or backed up to any server. It remains on your device and is
                deleted
                when you uninstall the app or clear its data.
            </p>
            <p class="justify">
                <b>5. Contact form and emails</b>
                <br>
                When you use the contact form on this website, we collect the information you voluntarily provide: your
                name, email address, and the details of your request (topic, app version, operating system, and
                description). In addition, your browser's default language and your approximate country (derived from
                your
                IP address via a third-party geolocation service) are automatically collected to help us respond in the
                appropriate language. Your IP address is not stored.
                <br><br>
                This data is used exclusively to respond to your request. A confirmation email is sent to the address
                you
                provide, and a copy of your message (including the automatically collected information) is sent to the
                developer. Your message is not published, shared with third parties, or used for any purpose other than
                handling your request. Emails are stored only in the developer's mailbox for as long as necessary to
                resolve
                your inquiry, and are then deleted.
            </p>
            <p class="justify">
                <b>6. Legal basis</b>
                <br>
                The app does not collect or process any personal data. For the contact form on this website, the legal
                basis
                for processing is your consent (Art. 6(1)(a) GDPR), given when you submit the form. You may withdraw
                your
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
                Sav PDF Viewer does not knowingly collect any data from children or any other users. The contact form
                does
                not knowingly collect data from children under 16. If you believe a child has submitted personal data
                through the contact form, please contact us so we can delete it.
            </p>
            <p class="justify">
                <b>9. Security</b>
                <br>
                Because we do not collect or store any personal data on external servers, the risk of data breach is
                minimal. Contact form submissions are transmitted via encrypted email (TLS) and stored only in the
                developer's mailbox. We follow best practices in app development to ensure the app itself remains secure
                and
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
                Saverio Morelli — <a href="https://saveriomorelli.com/contact" target="_blank"
                                     rel="noopener noreferrer">https://saveriomorelli.com/contact</a>
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
        document.querySelectorAll('.legal-lang-tab').forEach(function (t) {
            t.classList.remove('active');
        });
        document.querySelectorAll('.legal-lang-content').forEach(function (c) {
            c.classList.remove('active');
        });
        btn.classList.add('active');
        var target = document.getElementById('legal-' + lang);
        if (target) target.classList.add('active');
    }
</script>

</body>
</html>
