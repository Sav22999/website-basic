# Test invio email — notefox.eu

Pagine di diagnostica per capire perché alcune email di Notefox non arrivano:
ogni test invia la **stessa** email di prova (`Testing email from notefox.eu`)
usando un **metodo/servizio open-source diverso**. Confrontando quali email
arrivano e quali no, si può capire se il problema dipende dal metodo di invio,
dal provider di destinazione o dalla configurazione del server.

Apri `https://notefox.eu/test-email/` per l'elenco dei test.

Le pagine (e le email) sono **tutte in inglese** e mostrano **solo l'etichetta
generica** del test (`Test 1`, `Test 2`, ...): il metodo/libreria realmente usato
**non va mai indicato pubblicamente**. La grafica riprende quella del portale
(`include/header.php`, `include/menu.php`, `/css/style.css`).

## Test disponibili (mappatura interna, da non pubblicare)

| Cartella | Etichetta pubblica | Metodo | Libreria / tecnica |
|----------|--------------------|--------|--------------------|
| `new1`   | `Test 1` | `mail()` nativa di PHP | Nessuna (metodo attuale di Notefox) |
| `new2`   | `Test 2` | PHPMailer via SMTP | [`phpmailer/phpmailer`](https://github.com/PHPMailer/PHPMailer) |
| `new3`   | `Test 3` | Symfony Mailer via SMTP | [`symfony/mailer`](https://github.com/symfony/mailer) |
| `new4`   | `Test 4` | SMTP nativo via `fsockopen` | Nessuna libreria (transcript SMTP nel log PHP) |

Ogni pagina ha una casella di testo: inserisci un indirizzo email e premi
"Send test email".

## Credenziali

I test SMTP (`new2`, `new3`, `new4`) usano le credenziali già presenti in
`include/credentials.php`:

```php
$email_address   // account/username SMTP e mittente
$email_password  // password SMTP
$email_smtp      // server SMTP
$email_smtp_port // porta SMTP (465 = SSL implicito, 587 = STARTTLS)
```

Il test `new1` (`mail()`) non usa SMTP: dipende dall'MTA/sendmail del server.

## Dipendenze (Composer)

Le librerie PHPMailer e Symfony Mailer sono già incluse nella cartella
`vendor/` (committata), quindi i test funzionano appena caricati sul server,
senza bisogno di eseguire Composer.

Per aggiornarle:

```bash
cd test-email
composer update
```

### Versione di PHP del server

Il server di notefox.eu gira con una versione di PHP **precedente a 8.2**, quindi
le dipendenze sono vincolate a versioni compatibili con **PHP 7.4+**
(`symfony/mailer ^5.4`, non `^6.4` che richiede PHP >= 8.1/8.2). In
`composer.json` è impostato anche:

```json
"config": { "platform": { "php": "7.4.33" } }
```

così Composer risolve i pacchetti (e genera `vendor/composer/platform_check.php`)
per PHP 7.4 anche se localmente si usa una versione più recente. Senza questo
vincolo i test `new2` e `new3` mostrano l'errore:

> Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.2.0".

Se in futuro il server passerà a PHP 8.2+, si può alzare il valore di
`config.platform.php` (o rimuoverlo) e riportare `symfony/mailer` a `^6.4`,
rieseguendo `composer update`.

## Aggiungere un nuovo test

1. Crea una cartella `newN/` con un `index.php`.
2. Includi `../_shared.php` (form, corpo email, credenziali, validazione).
3. Definisci `$label = "Test N"` (etichetta generica, in inglese), implementa
   l'invio con la nuova libreria/servizio e chiama `renderTestEmailPage(...)`.
4. Aggiungi la riga corrispondente nell'array `$tests` in `index.php` e nella
   tabella di mappatura qui sopra.
5. Non inserire mai il nome della libreria nei testi mostrati in pagina o
   nell'email.
