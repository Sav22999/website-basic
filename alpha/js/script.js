document.addEventListener("DOMContentLoaded", () => {
    initMobileNav();
    initLangDropdown();
    initThemeDropdown();
    initAccordions();
    initHelpSearch();
});

function initMobileNav() {
    const toggle = document.querySelector(".nav-toggle");
    const links = document.querySelector(".nav-links");
    const logo = document.querySelector(".nav-logo");
    const backdrop = document.querySelector(".nav-backdrop");
    if (!toggle || !links) return;

    function isMobile() {
        return window.getComputedStyle(toggle).display !== "none";
    }

    function closeMenu() {
        links.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
        if (backdrop) backdrop.classList.remove("open");
        document.body.style.overflow = "";
        links.querySelectorAll(".theme-toggle-menu, .lang-dropdown-menu").forEach(m => m.classList.remove("open"));
        links.querySelectorAll(".theme-toggle-trigger, .lang-dropdown-trigger").forEach(b => b.setAttribute("aria-expanded", "false"));
    }

    function toggleMenu() {
        const open = links.classList.toggle("open");
        toggle.setAttribute("aria-expanded", String(open));
        if (backdrop) backdrop.classList.toggle("open", open);
        document.body.style.overflow = open ? "hidden" : "";
    }

    toggle.addEventListener("click", toggleMenu);

    if (logo) {
        logo.addEventListener("click", (e) => {
            if (isMobile()) {
                e.preventDefault();
                toggleMenu();
            }
        });
    }

    if (backdrop) {
        backdrop.addEventListener("click", closeMenu);
    }

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".site-nav")) {
            closeMenu();
        }
    });

    links.querySelectorAll(".nav-link").forEach((link) => {
        link.addEventListener("click", () => {
            if (isMobile()) closeMenu();
        });
    });
}

function initLangDropdown() {
    const dropdown = document.querySelector(".lang-dropdown");
    if (!dropdown) return;
    const trigger = dropdown.querySelector(".lang-dropdown-trigger");
    const menu = dropdown.querySelector(".lang-dropdown-menu");
    if (!trigger || !menu) return;

    trigger.addEventListener("click", (e) => {
        e.stopPropagation();
        const themeMenu = document.querySelector(".theme-toggle-menu");
        const themeTrigger = document.querySelector(".theme-toggle-trigger");
        if (themeMenu) { themeMenu.classList.remove("open"); }
        if (themeTrigger) { themeTrigger.setAttribute("aria-expanded", "false"); }
        const open = menu.classList.toggle("open");
        trigger.setAttribute("aria-expanded", String(open));
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            menu.classList.remove("open");
            trigger.setAttribute("aria-expanded", "false");
        }
    });
}

function initThemeDropdown() {
    const container = document.querySelector(".theme-toggle");
    if (!container) return;
    const trigger = container.querySelector(".theme-toggle-trigger");
    const menu = container.querySelector(".theme-toggle-menu");
    if (!trigger || !menu) return;

    function current() {
        try { return localStorage.getItem("nf_theme") || "auto"; } catch (e) { return "auto"; }
    }

    function apply(value) {
        if (value === "light" || value === "dark") {
            document.documentElement.setAttribute("data-theme", value);
        } else {
            document.documentElement.removeAttribute("data-theme");
        }
        try { if (value === "auto") localStorage.removeItem("nf_theme"); else localStorage.setItem("nf_theme", value); } catch (e) {}
        menu.querySelectorAll(".theme-toggle-item").forEach((btn) => {
            btn.classList.toggle("theme-toggle-item--active", btn.getAttribute("data-theme-value") === value);
        });
    }

    apply(current());

    trigger.addEventListener("click", (e) => {
        e.stopPropagation();
        const langMenu = document.querySelector(".lang-dropdown-menu");
        const langTrigger = document.querySelector(".lang-dropdown-trigger");
        if (langMenu) { langMenu.classList.remove("open"); }
        if (langTrigger) { langTrigger.setAttribute("aria-expanded", "false"); }
        const open = menu.classList.toggle("open");
        trigger.setAttribute("aria-expanded", String(open));
    });

    menu.querySelectorAll(".theme-toggle-item").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            apply(btn.getAttribute("data-theme-value"));
            menu.classList.remove("open");
            trigger.setAttribute("aria-expanded", "false");
        });
    });

    document.addEventListener("click", (e) => {
        if (!container.contains(e.target)) {
            menu.classList.remove("open");
            trigger.setAttribute("aria-expanded", "false");
        }
    });
}

function initAccordions() {
    document.querySelectorAll(".accordion-trigger").forEach((btn) => {
        btn.addEventListener("click", () => {
            const expanded = btn.getAttribute("aria-expanded") === "true";
            btn.setAttribute("aria-expanded", String(!expanded));
            const body = btn.nextElementSibling;
            if (body) {
                body.setAttribute("aria-hidden", String(expanded));
            }
        });
    });
}

function initHelpSearch() {
    const input = document.getElementById("help-search");
    const list = document.getElementById("help-list");
    const empty = document.getElementById("help-empty");
    if (!input || !list) return;

    input.addEventListener("input", () => {
        const q = input.value.trim().toLowerCase();
        const items = list.querySelectorAll(".link-list-item");
        let visible = 0;

        items.forEach((item) => {
            const text = (item.getAttribute("data-search") || "") + " " + item.textContent.toLowerCase();
            const match = !q || q.split(/\s+/).every((w) => text.includes(w));
            item.style.display = match ? "" : "none";
            if (match) visible++;
        });

        if (empty) {
            empty.classList.toggle("hidden2", visible > 0);
        }
    });
}
