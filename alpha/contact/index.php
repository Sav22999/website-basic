<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/alpha/include/i18n.php");
include_once($root_path . "/alpha/include/altcha.php");

$title = t('contact.title');
$description = t('meta.contact');
$canonical_path = "/alpha/contact/";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");

$config_path = $root_path . '/alpha/include/mail-config.php';
$hmac_key = '';
if (file_exists($config_path)) {
    $cfg = include $config_path;
    $hmac_key = $cfg['altcha_hmac_key'];
}
$challenge_json = altcha_create_challenge($hmac_key);
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/" class="back-link"><?php echo te('common.back'); ?></a>

        <div id="contact-form-wrapper" class="auth-card" style="max-width: 560px;">
            <div class="auth-header">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <h1><?php echo te('contact.heading'); ?></h1>
                <p><?php echo t('contact.subtitle'); ?></p>
            </div>

            <form id="contact-form" class="form-container" novalidate>
                <div id="contact-message" class="form-message hidden2"></div>

                <input type="text" name="website" tabindex="-1" autocomplete="off"
                       style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
                <input type="hidden" name="browser_lang" id="contact-browser-lang">
                <input type="hidden" name="timezone" id="contact-timezone">

                <div class="form-field">
                    <label class="form-label" for="contact-name"><?php echo te('contact.name_label'); ?> <span
                                class="form-hint form-hint--required"><?php echo te('contact.required_hint'); ?></span></label>
                    <input class="form-input" type="text" id="contact-name" name="name" maxlength="128" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-email"><?php echo te('contact.email_label'); ?> <span
                                class="form-hint form-hint--required"><?php echo te('contact.required_hint'); ?></span></label>
                    <input class="form-input" type="email" id="contact-email" name="email" maxlength="320" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-topic"><?php echo te('contact.topic_label'); ?> <span
                                class="form-hint form-hint--required"><?php echo te('contact.required_hint'); ?></span></label>
                    <select class="form-input" id="contact-topic" name="topic" required>
                        <option value="" disabled selected>—</option>
                        <option value="bug"><?php echo te('contact.topic_bug'); ?></option>
                        <option value="report"><?php echo te('contact.topic_report'); ?></option>
                        <option value="suggestion"><?php echo te('contact.topic_suggestion'); ?></option>
                        <option value="other"><?php echo te('contact.topic_other'); ?></option>
                    </select>
                </div>
                <div class="form-field form-field--conditional" id="topic-other-wrap" style="display:none;">
                    <input class="form-input" type="text" id="contact-topic-other" name="topic_other" maxlength="128"
                           placeholder="<?php echo te('contact.specify_placeholder'); ?>">
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-version"><?php echo te('contact.version_label'); ?> <span
                                class="form-hint form-hint--optional"><?php echo te('contact.optional_hint'); ?></span></label>
                    <input class="form-input" type="text" id="contact-version" name="version" maxlength="32"
                           placeholder="<?php echo te('contact.version_placeholder'); ?>">
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-browser"><?php echo te('contact.browser_label'); ?> <span
                                class="form-hint form-hint--required"><?php echo te('contact.required_hint'); ?></span></label>
                    <select class="form-input" id="contact-browser" name="browser" required>
                        <option value="" disabled selected>—</option>
                        <option value="firefox">Firefox</option>
                        <option value="chrome">Chrome</option>
                        <option value="edge">Edge</option>
                        <option value="other"><?php echo te('contact.topic_other'); ?></option>
                    </select>
                </div>
                <div class="form-field form-field--conditional" id="browser-other-wrap" style="display:none;">
                    <input class="form-input" type="text" id="contact-browser-other" name="browser_other" maxlength="64"
                           placeholder="<?php echo te('contact.specify_placeholder'); ?>">
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-os"><?php echo te('contact.os_label'); ?> <span
                                class="form-hint form-hint--optional"><?php echo te('contact.optional_hint'); ?></span></label>
                    <select class="form-input" id="contact-os" name="os">
                        <option value="" selected>—</option>
                        <option value="windows">Windows</option>
                        <option value="macos">macOS</option>
                        <option value="linux">Linux</option>
                        <option value="other"><?php echo te('contact.topic_other'); ?></option>
                    </select>
                </div>
                <div class="form-field form-field--conditional" id="os-other-wrap" style="display:none;">
                    <input class="form-input" type="text" id="contact-os-other" name="os_other" maxlength="64"
                           placeholder="<?php echo te('contact.specify_placeholder'); ?>">
                </div>

                <div class="form-field">
                    <label class="form-label" for="contact-message-input"><?php echo te('contact.message_label'); ?>
                        <span class="form-hint form-hint--required"><?php echo te('contact.required_hint'); ?></span></label>
                    <textarea class="form-input form-textarea" id="contact-message-input" name="message" rows="6"
                              required></textarea>
                </div>

                <div class="captcha-card">
                    <div class="captcha-main">
                        <altcha-widget
                                challengejson='<?php echo htmlspecialchars($challenge_json, ENT_QUOTES, 'UTF-8'); ?>'
                                hidefooter hidelogo></altcha-widget>
                    </div>
                </div>
                <a href="https://altcha.org" target="_blank" rel="noopener" class="captcha-footer">
                    Protected by
                    <svg class="captcha-footer-logo" width="16" height="16" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.33955 16.4279C5.88954 20.6586 12.1971 21.2105 16.4279 17.6604C18.4699 15.947 19.6548 13.5911 19.9352 11.1365L17.9886 10.4279C17.8738 12.5624 16.909 14.6459 15.1423 16.1284C11.7577 18.9684 6.71167 18.5269 3.87164 15.1423C1.03163 11.7577 1.4731 6.71166 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577C16.9767 5.86872 17.5322 7.02798 17.804 8.2324L19.9522 9.01429C19.7622 7.07737 19.0059 5.17558 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956C-0.658625 5.88958 -1.21046 12.1971 2.33955 16.4279Z"
                              fill="currentColor"/>
                        <path d="M3.57212 2.33956C1.65755 3.94607 0.496389 6.11731 0.12782 8.40523L2.04639 9.13961C2.26047 7.15832 3.21057 5.25375 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577L13.8302 6.78606L19.9633 9.13364C19.7929 7.15555 19.0335 5.20847 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956Z"
                              fill="currentColor"/>
                        <path d="M7 10H5C5 12.7614 7.23858 15 10 15C12.7614 15 15 12.7614 15 10H13C13 11.6569 11.6569 13 10 13C8.3431 13 7 11.6569 7 10Z"
                              fill="currentColor"/>
                    </svg>
                    Altcha
                </a>

                <p class="contact-consent"><?php echo t('contact.consent_text'); ?></p>

                <div class="form-actions">
                    <button type="submit" class="btn btn--block" id="contact-submit"
                            disabled><?php echo te('contact.submit'); ?></button>
                </div>
            </form>
        </div>

        <div id="contact-success" class="auth-card text-center" style="max-width: 560px; display: none;">
            <div style="padding: 16px 0 8px;">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>
            <h2 style="margin-top: 12px;"><?php echo te('contact.success_title'); ?></h2>
            <p id="contact-success-text"></p>
            <div class="form-actions">
                <a href="/alpha/help/" class="btn"><?php echo te('contact.success_back'); ?></a>
            </div>
        </div>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/altcha/dist/altcha.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.getElementById("contact-form");
        var wrapper = document.getElementById("contact-form-wrapper");
        var success = document.getElementById("contact-success");
        var successText = document.getElementById("contact-success-text");
        var submitBtn = document.getElementById("contact-submit");
        var msgEl = document.getElementById("contact-message");
        var widget = document.querySelector("altcha-widget");
        var successMsg = <?php echo json_encode(t('contact.success_message'), JSON_UNESCAPED_UNICODE); ?>;

        document.getElementById("contact-browser-lang").value = navigator.language || "";
        try {
            document.getElementById("contact-timezone").value = Intl.DateTimeFormat().resolvedOptions().timeZone || "";
        } catch (e) {
        }

        function setupConditional(selectId, wrapId, inputId) {
            var sel = document.getElementById(selectId);
            var wrap = document.getElementById(wrapId);
            var inp = document.getElementById(inputId);
            if (!sel || !wrap || !inp) return;
            sel.addEventListener("change", function () {
                var show = sel.value === "other";
                wrap.style.display = show ? "" : "none";
                if (show) {
                    inp.required = true;
                    inp.focus();
                } else {
                    inp.required = false;
                    inp.value = "";
                }
            });
        }

        setupConditional("contact-topic", "topic-other-wrap", "contact-topic-other");
        setupConditional("contact-browser", "browser-other-wrap", "contact-browser-other");
        setupConditional("contact-os", "os-other-wrap", "contact-os-other");

        if (widget) {
            widget.addEventListener("statechange", function (e) {
                submitBtn.disabled = e.detail.state !== "verified";
            });
        }

        function showMsg(text, isError) {
            msgEl.textContent = text;
            msgEl.className = "form-message" + (isError ? " form-message--error" : " form-message--success");
        }

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            msgEl.className = "form-message hidden2";

            var name = document.getElementById("contact-name").value.trim();
            var email = document.getElementById("contact-email").value.trim();
            var topic = document.getElementById("contact-topic").value;
            var browser = document.getElementById("contact-browser").value;
            var message = document.getElementById("contact-message-input").value.trim();

            if (!name || !email || !topic || !browser || !message) {
                showMsg(<?php echo json_encode(t('contact.error_fields'), JSON_UNESCAPED_UNICODE); ?>, true);
                return;
            }
            if (topic === "other" && !document.getElementById("contact-topic-other").value.trim()) {
                showMsg(<?php echo json_encode(t('contact.error_fields'), JSON_UNESCAPED_UNICODE); ?>, true);
                return;
            }
            if (browser === "other" && !document.getElementById("contact-browser-other").value.trim()) {
                showMsg(<?php echo json_encode(t('contact.error_fields'), JSON_UNESCAPED_UNICODE); ?>, true);
                return;
            }

            submitBtn.disabled = true;

            var fd = new FormData(form);
            fetch("/alpha/contact/send.php", {method: "POST", body: fd})
                .then(function (r) {
                    return r.json().then(function (d) {
                        return {status: r.status, data: d};
                    });
                })
                .then(function (res) {
                    if (res.data.ok) {
                        wrapper.style.display = "none";
                        successText.innerHTML = successMsg.replace("{email}", "<strong>" + email.replace(/</g, "&lt;") + "</strong>");
                        success.style.display = "";
                    } else {
                        var errors = {
                            "missing_fields": <?php echo json_encode(t('contact.error_fields'), JSON_UNESCAPED_UNICODE); ?>,
                            "invalid_email": <?php echo json_encode(t('contact.error_email'), JSON_UNESCAPED_UNICODE); ?>,
                            "captcha_failed": <?php echo json_encode(t('contact.error_captcha'), JSON_UNESCAPED_UNICODE); ?>
                        };
                        showMsg(errors[res.data.error] || <?php echo json_encode(t('contact.error_generic'), JSON_UNESCAPED_UNICODE); ?>, true);
                        submitBtn.disabled = false;
                    }
                })
                .catch(function () {
                    showMsg(<?php echo json_encode(t('contact.error_generic'), JSON_UNESCAPED_UNICODE); ?>, true);
                    submitBtn.disabled = false;
                });
        });
    });
</script>
</body>
</html>
