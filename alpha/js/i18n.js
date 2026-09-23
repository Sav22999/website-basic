(function () {
    var SUPPORTED_LANGS = ['en', 'it', 'ar', 'cs', 'de', 'es', 'fr', 'ja', 'ko', 'pl', 'pt-BR', 'pt-PT', 'ru', 'zh-CN'];
    var DEFAULT_LANG = 'en';
    var strings = {};

    function detectLang() {
        var stored = null;
        try {
            stored = localStorage.getItem('lang');
        } catch (e) {
        }
        if (stored && SUPPORTED_LANGS.indexOf(stored) !== -1) return stored;

        var navLang = (navigator.language || navigator.userLanguage || '').toLowerCase();

        for (var i = 0; i < SUPPORTED_LANGS.length; i++) {
            if (SUPPORTED_LANGS[i].toLowerCase() === navLang) return SUPPORTED_LANGS[i];
        }

        var base = navLang.split('-')[0];
        for (var i = 0; i < SUPPORTED_LANGS.length; i++) {
            if (SUPPORTED_LANGS[i].split('-')[0].toLowerCase() === base) return SUPPORTED_LANGS[i];
        }

        return DEFAULT_LANG;
    }

    function t(key) {
        return strings[key] || key;
    }

    function applyTranslations() {
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            var key = el.getAttribute('data-i18n');
            if (strings[key]) el.textContent = strings[key];
        });

        document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-html');
            if (strings[key]) el.innerHTML = strings[key];
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-placeholder');
            if (strings[key]) el.placeholder = strings[key];
        });

        document.querySelectorAll('[data-platform-android]').forEach(function (el) {
            var base = el.getAttribute('data-i18n');
            if (!base) return;
            ['android', 'desktop', 'ios'].forEach(function (p) {
                var attr = 'data-platform-' + p;
                var pKey = base + '_' + p;
                if (strings[pKey] && el.hasAttribute(attr)) {
                    el.setAttribute(attr, strings[pKey]);
                }
            });
        });

    }

    function updateSelector(lang) {
        var code = document.getElementById('lang-trigger-code');
        if (code) code.textContent = lang.toUpperCase();

        document.querySelectorAll('.lang-item').forEach(function (el) {
            el.classList.toggle('lang-item--active', el.getAttribute('data-lang') === lang);
        });
    }

    function toggleLangDropdown() {
        var dd = document.getElementById('lang-dropdown');
        if (!dd) return;
        dd.classList.toggle('open');
        if (dd.classList.contains('open')) {
            var active = dd.querySelector('.lang-item--active');
            var panel = dd.querySelector('.lang-panel');
            if (active && panel) {
                setTimeout(function () {
                    panel.scrollTop = active.offsetTop - panel.offsetHeight / 2 + active.offsetHeight / 2;
                }, 10);
            }
        }
    }

    function closeLangDropdown() {
        var dd = document.getElementById('lang-dropdown');
        if (dd) dd.classList.remove('open');
    }

    function setLang(lang) {
        if (SUPPORTED_LANGS.indexOf(lang) === -1) lang = DEFAULT_LANG;
        try {
            localStorage.setItem('lang', lang);
        } catch (e) {
        }
        document.documentElement.setAttribute('lang', lang);
        loadLang(lang);
    }

    function loadLang(lang) {
        var basePath = document.querySelector('meta[name="i18n-base"]');
        var base = basePath ? basePath.getAttribute('content') : '/alpha';
        var url = base + '/lang/' + lang + '.json';

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                try {
                    strings = JSON.parse(xhr.responseText);
                } catch (e) {
                    strings = {};
                }
            } else {
                strings = {};
            }
            window._i18n = strings;
            window._i18nLang = lang;

            var dir = strings._dir || 'ltr';
            document.documentElement.setAttribute('dir', dir);

            applyTranslations();
            updateSelector(lang);

            if (typeof applyPlatformMessages === 'function') {
                applyPlatformMessages();
            }

            updateLocalizedImages(lang);
            updateLangNotices(lang);
        };
        xhr.send();
    }

    function updateLangNotices(lang) {
        document.querySelectorAll('.lang-notice').forEach(function (el) {
            el.style.display = lang === DEFAULT_LANG ? 'none' : '';
        });
    }

    function updateLocalizedImages(lang) {
        document.querySelectorAll('img[data-i18n-src]').forEach(function (el) {
            var template = el.getAttribute('data-i18n-src');
            var src = template.replace('{lang}', lang);
            el.setAttribute('src', src);
            el.onerror = function () {
                var fallback = src.replace('/images/home/' + lang + '/', '/images/home/en/');
                if (fallback !== src) {
                    el.onerror = null;
                    el.setAttribute('src', fallback);
                }
            };
        });
    }

    window.setLang = setLang;
    window.toggleLangDropdown = toggleLangDropdown;
    window.closeLangDropdown = closeLangDropdown;
    window._i18n = strings;
    window._i18nT = t;

    document.addEventListener('DOMContentLoaded', function () {
        var lang = detectLang();
        setLang(lang);

        document.addEventListener('click', function (e) {
            var dd = document.getElementById('lang-dropdown');
            if (dd && !dd.contains(e.target)) {
                dd.classList.remove('open');
            }
        });
    });
})();
