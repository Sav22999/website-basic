document.addEventListener("DOMContentLoaded", () => {
    initMobileNav();
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
