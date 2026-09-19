document.addEventListener("DOMContentLoaded", () => {
    initMobileNav();
    initNavScroll();
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

function initNavScroll() {
    const nav = document.querySelector(".site-nav");
    const header = nav && nav.closest(".site-header");
    if (!nav || !header) return;

    var startGlow = document.createElement("div");
    var endGlow = document.createElement("div");
    startGlow.className = "nav-scroll-glow nav-scroll-glow--start";
    endGlow.className = "nav-scroll-glow nav-scroll-glow--end";
    startGlow.setAttribute("aria-hidden", "true");
    endGlow.setAttribute("aria-hidden", "true");
    header.appendChild(startGlow);
    header.appendChild(endGlow);

    function position() {
        var hr = header.getBoundingClientRect();
        var nr = nav.getBoundingClientRect();
        var top = nr.top - hr.top;
        startGlow.style.top = top + "px";
        startGlow.style.height = nr.height + "px";
        startGlow.style.left = (nr.left - hr.left) + "px";
        endGlow.style.top = top + "px";
        endGlow.style.height = nr.height + "px";
        endGlow.style.right = (hr.right - nr.right) + "px";
    }

    function update() {
        var overflow = getComputedStyle(nav).overflowX;
        var maxScroll = nav.scrollWidth - nav.clientWidth;
        if (overflow === "visible" || maxScroll <= 0) {
            startGlow.style.opacity = "0";
            endGlow.style.opacity = "0";
            return;
        }
        startGlow.style.opacity = nav.scrollLeft > 1 ? "1" : "0";
        endGlow.style.opacity = nav.scrollLeft < maxScroll - 1 ? "1" : "0";
    }

    nav.addEventListener("scroll", update, { passive: true });
    new ResizeObserver(function() { position(); update(); }).observe(nav);
    position();
    update();
}

function positionDropdownFixed(trigger, menu) {
    if (getComputedStyle(menu).position !== "fixed") {
        menu.style.top = "";
        menu.style.left = "";
        return;
    }
    const rect = trigger.getBoundingClientRect();
    menu.style.top = (rect.bottom + 4) + "px";
    let left = rect.left;
    const menuWidth = menu.offsetWidth || 160;
    if (left + menuWidth > window.innerWidth - 8) {
        left = window.innerWidth - menuWidth - 8;
    }
    if (left < 8) left = 8;
    menu.style.left = left + "px";
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
        if (open) {
            positionDropdownFixed(trigger, menu);
            const active = menu.querySelector(".lang-dropdown-item--active");
            if (active) active.scrollIntoView({ block: "nearest" });
        }
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            menu.classList.remove("open");
            trigger.setAttribute("aria-expanded", "false");
        }
    });

    window.addEventListener("resize", () => {
        if (menu.classList.contains("open")) positionDropdownFixed(trigger, menu);
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
        if (open) positionDropdownFixed(trigger, menu);
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

    window.addEventListener("resize", () => {
        if (menu.classList.contains("open")) positionDropdownFixed(trigger, menu);
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
