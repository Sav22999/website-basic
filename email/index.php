<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../include/mail_template.php';

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

$email_sent = false;
$error = '';
$secret_key = hash('sha256', __DIR__ . date('Y-m'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot
    if (!empty($_POST['website'])) {
        $error = 'captcha';
    }

    // Verify Altcha
    if (!$error) {
        $altcha = $_POST['altcha'] ?? '';
        $payload = json_decode(base64_decode($altcha), true);
        if (!$payload || !isset($payload['salt'], $payload['number'], $payload['challenge'], $payload['signature'])) {
            $error = 'captcha';
        } else {
            $check = hash('sha256', $payload['salt'] . $payload['number']);
            $expected_sig = hash_hmac('sha256', $check, $secret_key);
            if ($check !== $payload['challenge'] || $expected_sig !== $payload['signature']) {
                $error = 'captcha';
            }
        }
    }

    // Collect & validate
    $name = trim($_POST['name'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    $topic = trim($_POST['topic'] ?? '');
    $topic_other = trim($_POST['topic_other'] ?? '');
    $app_version = trim($_POST['app_version'] ?? '');
    $os = trim($_POST['os'] ?? '');
    $os_other = trim($_POST['os_other'] ?? '');
    $os_version = trim($_POST['os_version'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!$error) {
        if (!$name || !$user_email || !$topic || !$description) {
            $error = 'required';
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'email';
        } elseif ($topic === 'other' && !$topic_other) {
            $error = 'required';
        }
    }

    if (!$error) {
        $mail_config = require __DIR__ . '/../.mail_config.php';
        $topic_label = $topic === 'other' ? $topic_other : ucfirst($topic);
        $os_label = $os ? ($os === 'other' ? $os_other : ucfirst($os)) : '';

        $browser_lang = trim($_POST['browser_lang'] ?? '');
        if (!$browser_lang && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browser_lang = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?: strtok($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',');
        }

        $country = '';
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip) {
            $geo_url = 'http://ip-api.com/json/' . urlencode($ip) . '?fields=country';
            $geo_json = @file_get_contents($geo_url);
            if (!$geo_json && function_exists('curl_init')) {
                $ch = curl_init($geo_url);
                curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 3]);
                $geo_json = curl_exec($ch);
                curl_close($ch);
            }
            $geo = $geo_json ? @json_decode($geo_json, true) : null;
            if ($geo && !empty($geo['country'])) {
                $country = $geo['country'];
            }
        }

        $dsn = sprintf(
                'smtps://%s:%s@%s:%d',
                urlencode($mail_config['smtp_user']),
                urlencode($mail_config['smtp_pass']),
                $mail_config['smtp_host'],
                $mail_config['smtp_port']
        );

        try {
            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);
            $from = new Address($mail_config['smtp_user'], $mail_config['from_name']);

            // Email to developer
            $dev_body = mail_developer_body($name, $user_email, $topic_label, $app_version, $os_label, $os_version, $description, $browser_lang, $country);
            $dev_html = mail_template("[$topic_label] Sav PDF Viewer", $dev_body);

            $dev_email = (new Email())
                    ->from($from)
                    ->to('saverio.morelli@protonmail.com')
                    ->replyTo(new Address($user_email, $name))
                    ->subject("[$topic_label] Sav PDF Viewer")
                    ->html($dev_html);

            $mailer->send($dev_email);

            // Confirmation email to user
            $confirm_body = mail_confirmation_body($name, $topic_label, $app_version, $os_label, $os_version, $description);
            $confirm_html = mail_template("Sav PDF Viewer — Request received", $confirm_body);

            $confirm_email = (new Email())
                    ->from($from)
                    ->to(new Address($user_email, $name))
                    ->subject("Sav PDF Viewer — Request received")
                    ->html($confirm_html);

            $mailer->send($confirm_email);

            $email_sent = true;
        } catch (\Exception $e) {
            $error = 'send';
        }
    }
}

// Generate Altcha challenge
$salt = bin2hex(random_bytes(12));
$number = random_int(1000, 100000);
$challenge = hash('sha256', $salt . $number);
$signature = hash_hmac('sha256', $challenge, $secret_key);
$challenge_json = json_encode([
        'algorithm' => 'SHA-256',
        'challenge' => $challenge,
        'maxnumber' => 100000,
        'salt' => $salt,
        'signature' => $signature
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Contact";
    $description = "Contact the developer of Sav PDF Viewer via email. Report bugs, suggest features, or ask for help.";
    $canonical_path = "/email/";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
    <script async defer src="https://cdn.jsdelivr.net/npm/altcha/dist/altcha.min.js" type="module"></script>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main>
    <?php if ($email_sent): ?>

        <section class="contact-success width-80-perc">
            <div class="contact-success__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <h2 class="title-section" data-i18n="email.success_title">Request sent!</h2>
            <p class="contact-success__text">
                <span data-i18n="email.success_text">You will receive a confirmation email at</span>
                <strong><?= htmlspecialchars($user_email) ?></strong>
            </p>
            <a href="/help/" class="button" data-i18n="email.success_back">Back to Help</a>
        </section>

    <?php else: ?>

        <section class="hero hero--compact">
            <div class="hero__inner">
                <h1 class="title-section" data-i18n="email.title">Contact via email</h1>
                <p class="subtitle-section no-bold" data-i18n-html="email.subtitle">Fill out the form below — your email
                    client will open with the details pre-filled</p>
                <p class="support-lang-note" data-i18n="help.support_lang">Support is available in English only</p>
            </div>
        </section>

        <section class="contact-form-section">
            <?php if ($error): ?>
                <div class="contact-error width-80-perc" id="contact-error">
                    <p>
                        <?php if ($error === 'required'): ?>
                            <span data-i18n="email.error_required">Please fill in all required fields.</span>
                        <?php elseif ($error === 'email'): ?>
                            <span data-i18n="email.error_email">Please enter a valid email address.</span>
                        <?php elseif ($error === 'captcha'): ?>
                            <span data-i18n="email.error_captcha">Verification failed. Please try again.</span>
                        <?php elseif ($error === 'send'): ?>
                            <span data-i18n="email.error_send">Could not send the email. Please try again later.</span>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <form id="contact-form" class="contact-form width-80-perc" method="post" novalidate>
                <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
                <input type="hidden" name="browser_lang" id="browser-lang">

                <div class="form-field">
                    <label for="contact-name"><span data-i18n="email.name">Full name</span> <span class="field-required"
                                                                                                  data-i18n="email.required">(required)</span></label>
                    <input type="text" name="name" id="contact-name" required
                           data-i18n-placeholder="email.name_placeholder" placeholder="Name and surname"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-email"><span data-i18n="email.email_addr">Email address</span> <span
                                class="field-required" data-i18n="email.required">(required)</span></label>
                    <input type="email" name="user_email" id="contact-email" required
                           data-i18n-placeholder="email.email_placeholder" placeholder="your@email.com"
                           value="<?= htmlspecialchars($_POST['user_email'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-topic"><span data-i18n="email.topic">Topic</span> <span class="field-required"
                                                                                                data-i18n="email.required">(required)</span></label>
                    <select name="topic" id="contact-topic" required>
                        <option value="" data-i18n="email.topic_select">Select a topic...</option>
                        <option value="bug" <?= ($_POST['topic'] ?? '') === 'bug' ? 'selected' : '' ?>
                                data-i18n="email.topic_bug">Bug
                        </option>
                        <option value="problem" <?= ($_POST['topic'] ?? '') === 'problem' ? 'selected' : '' ?>
                                data-i18n="email.topic_problem">Problem
                        </option>
                        <option value="suggestion" <?= ($_POST['topic'] ?? '') === 'suggestion' ? 'selected' : '' ?>
                                data-i18n="email.topic_suggestion">Suggestion
                        </option>
                        <option value="feature" <?= ($_POST['topic'] ?? '') === 'feature' ? 'selected' : '' ?>
                                data-i18n="email.topic_feature">Feature request
                        </option>
                        <option value="other" <?= ($_POST['topic'] ?? '') === 'other' ? 'selected' : '' ?>
                                data-i18n="email.other">Other
                        </option>
                    </select>
                </div>

                <div class="form-field" id="topic-other-field"
                     style="<?= ($_POST['topic'] ?? '') === 'other' ? '' : 'display:none' ?>">
                    <label for="contact-topic-other"><span data-i18n="email.topic_other_label">Specify the topic</span>
                        <span class="field-required" data-i18n="email.required">(required)</span></label>
                    <input type="text" name="topic_other" id="contact-topic-other"
                           value="<?= htmlspecialchars($_POST['topic_other'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-app-version">
                        <span data-i18n="email.app_version">Sav PDF Viewer version</span>
                        <span class="field-optional" data-i18n="email.optional">(optional)</span>
                        <button type="button" class="help-btn" onclick="openHelpPopup('app-version')">?</button>
                    </label>
                    <input type="text" name="app_version" id="contact-app-version"
                           data-i18n-placeholder="email.app_version_placeholder" placeholder="e.g. 3.2.1"
                           value="<?= htmlspecialchars($_POST['app_version'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-os">
                        <span data-i18n="email.os">Operating system</span>
                        <span class="field-optional" data-i18n="email.optional">(optional)</span>
                        <button type="button" class="help-btn" onclick="openHelpPopup('os')">?</button>
                    </label>
                    <select name="os" id="contact-os">
                        <option value="" data-i18n="email.os_select">Select...</option>
                        <option value="android" <?= ($_POST['os'] ?? '') === 'android' ? 'selected' : '' ?>
                                data-i18n="email.os_android">Android
                        </option>
                        <option value="other" <?= ($_POST['os'] ?? '') === 'other' ? 'selected' : '' ?>
                                data-i18n="email.other">Other
                        </option>
                    </select>
                </div>

                <div class="form-field" id="os-other-field"
                     style="<?= ($_POST['os'] ?? '') === 'other' ? '' : 'display:none' ?>">
                    <label for="contact-os-other"><span
                                data-i18n="email.os_other_label">Specify the operating system</span> <span
                                class="field-optional" data-i18n="email.optional">(optional)</span></label>
                    <input type="text" name="os_other" id="contact-os-other"
                           value="<?= htmlspecialchars($_POST['os_other'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-os-version">
                        <span data-i18n="email.os_version">Operating system version</span>
                        <span class="field-optional" data-i18n="email.optional">(optional)</span>
                        <button type="button" class="help-btn" onclick="openHelpPopup('os-version')">?</button>
                    </label>
                    <input type="text" name="os_version" id="contact-os-version"
                           data-i18n-placeholder="email.os_version_placeholder" placeholder="e.g. 14"
                           value="<?= htmlspecialchars($_POST['os_version'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="contact-description"><span data-i18n="email.description">Description</span> <span
                                class="field-required" data-i18n="email.required">(required)</span></label>
                    <textarea name="description" id="contact-description" required rows="6"
                              data-i18n-placeholder="email.description_placeholder"
                              placeholder="Describe your problem or suggestion in detail..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="form-field form-field--captcha">
                    <div class="captcha-card">
                        <div class="captcha-card__main">
                            <altcha-widget challengejson='<?= htmlspecialchars($challenge_json) ?>'
                                           hidefooter></altcha-widget>
                        </div>
                        <div class="captcha-card__divider"></div>
                        <div class="captcha-card__footer">
                            <span data-i18n="email.captcha_powered">Powered by</span>
                            <a href="https://altcha.org" target="_blank" rel="noopener noreferrer"
                               class="captcha-card__brand">
                                <svg class="captcha-card__logo" viewBox="0 0 20 20" fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.34 16.43C5.89 20.66 12.2 21.21 16.43 17.66C18.47 15.95 19.65 13.59 19.94 11.14L17.99 10.43C17.87 12.56 16.91 14.65 15.14 16.13C11.76 18.97 6.71 18.53 3.87 15.14C1.03 11.76 1.47 6.71 4.86 3.87C8.24 1.03 13.29 1.47 16.13 4.86C16.98 5.87 17.53 7.03 17.8 8.23L19.95 9.01C19.76 7.08 19.01 5.18 17.66 3.57C14.11-.66 7.8-1.21 3.57 2.34C-.66 5.89-1.21 12.2 2.34 16.43Z"/>
                                    <path d="M7 10H5C5 12.76 7.24 15 10 15C12.76 15 15 12.76 15 10H13C13 11.66 11.66 13 10 13C8.34 13 7 11.66 7 10Z"/>
                                </svg>
                                Altcha
                            </a>
                        </div>
                    </div>
                </div>

                <p class="form-consent" data-i18n-html="email.consent">By sending this email you accept the <a
                            href="/terms/">terms of service</a> and <a href="/privacy/">privacy policy</a>.
                </p>

                <button type="submit" class="button" id="contact-submit" disabled>
                    <span data-i18n="email.submit">Send request</span>
                </button>
            </form>
        </section>

    <?php endif; ?>
</main>

<div class="help-popup-overlay" id="help-popup-overlay" onclick="closeHelpPopup()">
    <div class="help-popup" onclick="event.stopPropagation()">
        <h3 class="help-popup__title" id="help-popup-title"></h3>
        <p class="help-popup__text" id="help-popup-text"></p>
        <button type="button" class="button button--small" onclick="closeHelpPopup()" data-i18n="email.popup_close">Got
            it
        </button>
    </div>
</div>

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

<script>
    (function () {
        var topicSelect = document.getElementById('contact-topic');
        var topicOtherField = document.getElementById('topic-other-field');
        var topicOtherInput = document.getElementById('contact-topic-other');
        var osSelect = document.getElementById('contact-os');
        var osOtherField = document.getElementById('os-other-field');
        var osOtherInput = document.getElementById('contact-os-other');
        var form = document.getElementById('contact-form');

        if (topicSelect) {
            topicSelect.addEventListener('change', function () {
                var isOther = this.value === 'other';
                topicOtherField.style.display = isOther ? '' : 'none';
                topicOtherInput.required = isOther;
                if (!isOther) topicOtherInput.value = '';
            });
            if (topicSelect.value === 'other') topicOtherInput.required = true;
        }

        if (osSelect) {
            osSelect.addEventListener('change', function () {
                var isOther = this.value === 'other';
                osOtherField.style.display = isOther ? '' : 'none';
                if (!isOther) osOtherInput.value = '';
            });
        }

        if (form) {
            form.addEventListener('submit', function () {
                form.classList.add('attempted');
            });
        }

        var langField = document.getElementById('browser-lang');
        if (langField) langField.value = navigator.language || navigator.userLanguage || '';

        var submitBtn = document.getElementById('contact-submit');
        var widget = document.querySelector('altcha-widget');
        if (widget && submitBtn) {
            widget.addEventListener('statechange', function (e) {
                submitBtn.disabled = e.detail.state !== 'verified';
            });
        }
    })();

    function openHelpPopup(type) {
        var overlay = document.getElementById('help-popup-overlay');
        var title = document.getElementById('help-popup-title');
        var text = document.getElementById('help-popup-text');
        var s = window._i18n || {};

        if (type === 'app-version') {
            title.textContent = s['email.app_version_help_title'] || 'How to find the app version';
            text.innerHTML = s['email.app_version_help_text'] || 'Open Sav PDF Viewer, go to <strong>Settings</strong> and scroll to the bottom of the page. The version number is displayed there.';
        } else if (type === 'os') {
            title.textContent = s['email.os_help_title'] || 'How to find your operating system';
            text.innerHTML = s['email.os_help_text'] || 'On your Android device, go to <strong>Settings → About phone</strong>. The operating system is listed under <strong>Software information</strong>.';
        } else {
            title.textContent = s['email.os_version_help_title'] || 'How to find your OS version';
            text.innerHTML = s['email.os_version_help_text'] || 'On your Android device, go to <strong>Settings → About phone</strong>. The version number is shown under <strong>Android version</strong> (e.g. 14, 13).';
        }
        overlay.classList.add('active');
    }

    function closeHelpPopup() {
        document.getElementById('help-popup-overlay').classList.remove('active');
    }
</script>

</body>
</html>
