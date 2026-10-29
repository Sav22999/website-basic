<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = "Logs – " . t('account.admin_dashboard_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/admin/" class="back-link"><?php echo te('account.admin_dashboard'); ?></a>
        <h1 class="text-center" id="logs-heading">Logs</h1>

        <div class="admin-tabs" role="tablist">
            <button type="button" class="admin-tab" role="tab" data-type="errors">Error logs</button>
            <button type="button" class="admin-tab" role="tab" data-type="telemetry">Telemetry</button>
        </div>

        <div class="admin-filters">
            <div class="search-box admin-filters-search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" class="search-input" id="logs-search" autocomplete="off" maxlength="100">
            </div>
            <select id="logs-days" class="form-input admin-filters-days" aria-label="Period">
                <option value="0">All time</option>
                <option value="1">Last 24 hours</option>
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
            </select>
        </div>

        <div id="logs-active-filters" class="admin-active-filters hidden2"></div>

        <div id="logs-message" class="form-message hidden2"></div>

        <div id="logs-skeleton" class="admin-log-list" aria-hidden="true">
            <div class="skeleton skeleton--row"></div>
            <div class="skeleton skeleton--row"></div>
            <div class="skeleton skeleton--row"></div>
            <div class="skeleton skeleton--row"></div>
            <div class="skeleton skeleton--row"></div>
        </div>

        <div id="logs-list" class="admin-log-list" aria-live="polite"></div>

        <p id="logs-empty" class="admin-log-empty hidden2"></p>

        <div class="text-center">
            <button type="button" id="logs-more" class="btn btn--secondary hidden2">Load more</button>
        </div>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var session = requireSession();
        if (!session) return;

        if ((session["permissions"] || 0) < 10) {
            location.href = "/my/account/";
            return;
        }

        var TYPES = {
            errors: {heading: "Error logs", group: "Context", search: "Search in context and error text…", empty: "No error logs match these filters."},
            telemetry: {heading: "Telemetry", group: "Action", search: "Search in action, context and details…", empty: "No telemetry events match these filters."}
        };
        var REASONS = {
            "table-missing": "The table configured for this log does not exist in the database",
            "date-column-missing": "The table has no date column",
            "id-column-missing": "The table has no id column"
        };

        var params = new URLSearchParams(location.search);
        var state = {
            type: TYPES[params.get("type")] ? params.get("type") : "errors",
            q: params.get("q") || "",
            days: ["0", "1", "7", "30"].indexOf(params.get("days")) !== -1 ? params.get("days") : "0",
            group: params.get("group") || "",
            version: params.get("version") || "",
            beforeId: null,
            requestId: 0
        };

        var list = document.getElementById("logs-list");
        var skeleton = document.getElementById("logs-skeleton");
        var empty = document.getElementById("logs-empty");
        var moreButton = document.getElementById("logs-more");
        var searchInput = document.getElementById("logs-search");
        var daysSelect = document.getElementById("logs-days");
        var activeFilters = document.getElementById("logs-active-filters");

        function el(tag, className, text) {
            var node = document.createElement(tag);
            if (className) node.className = className;
            if (text !== undefined && text !== null) node.textContent = text;
            return node;
        }

        function syncUrl() {
            var next = new URLSearchParams();
            next.set("type", state.type);
            if (state.q) next.set("q", state.q);
            if (state.days !== "0") next.set("days", state.days);
            if (state.group) next.set("group", state.group);
            if (state.version) next.set("version", state.version);
            history.replaceState(null, "", location.pathname + "?" + next.toString());
        }

        function renderControls() {
            var config = TYPES[state.type];
            document.getElementById("logs-heading").textContent = config.heading;
            document.title = config.heading + " – Notefox";
            searchInput.placeholder = config.search;
            searchInput.value = state.q;
            daysSelect.value = state.days;
            document.querySelectorAll(".admin-tab").forEach(function (tab) {
                var active = tab.getAttribute("data-type") === state.type;
                tab.classList.toggle("is-active", active);
                tab.setAttribute("aria-selected", active ? "true" : "false");
            });

            activeFilters.innerHTML = "";
            [
                {key: "group", label: config.group},
                {key: "version", label: "Version"}
            ].forEach(function (filter) {
                if (!state[filter.key]) return;
                var chip = el("button", "admin-filter-chip");
                chip.type = "button";
                chip.title = "Remove filter";
                chip.appendChild(el("span", "admin-filter-chip-key", filter.label + ":"));
                chip.appendChild(el("span", "admin-filter-chip-value", state[filter.key]));
                chip.insertAdjacentHTML("beforeend", '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>');
                chip.addEventListener("click", function () {
                    state[filter.key] = "";
                    reload();
                });
                activeFilters.appendChild(chip);
            });
            activeFilters.classList.toggle("hidden2", !activeFilters.hasChildNodes());
        }

        function filterChip(label, value, key) {
            var chip = el("button", "admin-meta-chip admin-meta-chip--link", label);
            chip.type = "button";
            chip.title = "Show only " + value;
            chip.addEventListener("click", function (event) {
                event.stopPropagation();
                state[key] = value;
                reload();
            });
            return chip;
        }

        function metaChip(text, title) {
            var chip = el("span", "admin-meta-chip", text);
            if (title) chip.title = title;
            return chip;
        }

        function detailRow(container, label, value) {
            if (value === null || value === undefined || value === "") return;
            var row = el("div", "admin-log-detail");
            row.appendChild(el("span", "admin-log-detail-key", label));
            row.appendChild(el("span", "admin-log-detail-value", String(value)));
            container.appendChild(row);
        }

        function codeBlock(container, label, text) {
            if (!text) return;
            var wrap = el("div", "admin-log-code");
            var head = el("div", "admin-log-code-head");
            head.appendChild(el("span", "admin-log-detail-key", label));
            var copy = el("button", "admin-log-copy", "Copy");
            copy.type = "button";
            copy.addEventListener("click", function (event) {
                event.stopPropagation();
                navigator.clipboard.writeText(text).then(function () {
                    copy.textContent = "Copied";
                    setTimeout(function () { copy.textContent = "Copy"; }, 1500);
                });
            });
            head.appendChild(copy);
            wrap.appendChild(head);
            wrap.appendChild(el("pre", "admin-log-pre", text));
            container.appendChild(wrap);
        }

        function renderItem(item) {
            var isErrors = state.type === "errors";
            var groupValue = isErrors ? item["context"] : item["action"];

            var card = el("article", "admin-log");
            var head = el("button", "admin-log-head");
            head.type = "button";
            head.setAttribute("aria-expanded", "false");

            var top = el("div", "admin-log-top");
            top.appendChild(el("span", "admin-log-title", groupValue || "(no " + TYPES[state.type].group.toLowerCase() + ")"));
            top.appendChild(el("span", "admin-log-date", item["date"] || ""));
            head.appendChild(top);

            var summary = isErrors ? item["error"] : (item["context"] || item["other"]);
            if (summary) head.appendChild(el("p", "admin-log-summary", summary.split("\n")[0]));

            var meta = el("div", "admin-log-meta");
            if (groupValue) meta.appendChild(filterChip(groupValue, groupValue, "group"));
            if (item["notefox-version"]) meta.appendChild(filterChip("v" + item["notefox-version"], item["notefox-version"], "version"));
            if (!isErrors) {
                if (item["browser"]) meta.appendChild(metaChip(item["browser"] + (item["browser-version"] ? " " + item["browser-version"] : "")));
                if (item["os"]) meta.appendChild(metaChip(item["os"]));
                if (item["language"]) meta.appendChild(metaChip(item["language"]));
            }
            if (item["url-host"]) meta.appendChild(metaChip(item["url-host"], "Page host"));
            if (item["installation"]) meta.appendChild(metaChip("#" + item["installation"], "Anonymous installation"));
            head.appendChild(meta);

            var chevron = el("span", "admin-log-chevron");
            chevron.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>';
            head.appendChild(chevron);
            card.appendChild(head);

            var body = el("div", "admin-log-body hidden2");
            var details = el("div", "admin-log-details");
            detailRow(details, "ID", item["id"]);
            if (isErrors) {
                detailRow(details, "Received", item["inserted-date"]);
                detailRow(details, "Happened (client clock)", item["local-date"]);
            } else {
                detailRow(details, "Received", item["server-datetime"]);
                detailRow(details, "Client time", item["client-datetime"]);
                detailRow(details, "Notefox account", item["notefox-account"] === null || item["notefox-account"] === undefined ? null : (item["notefox-account"] ? "Yes" : "No"));
            }
            detailRow(details, "Page host", item["url-host"]);
            detailRow(details, "Installation", item["installation"]);
            body.appendChild(details);
            if (isErrors) {
                codeBlock(body, "Error", item["error"]);
            } else {
                codeBlock(body, "Context", item["context"]);
                codeBlock(body, "Details", item["other"]);
            }
            card.appendChild(body);

            head.addEventListener("click", function () {
                var open = body.classList.toggle("hidden2") === false;
                card.classList.toggle("is-open", open);
                head.setAttribute("aria-expanded", open ? "true" : "false");
            });

            return card;
        }

        function load(append) {
            var requestId = ++state.requestId;
            document.getElementById("logs-message").classList.add("hidden2");
            empty.classList.add("hidden2");
            moreButton.disabled = true;
            if (!append) {
                list.innerHTML = "";
                skeleton.classList.remove("hidden2");
                moreButton.classList.add("hidden2");
            }

            var body = {type: state.type, limit: 25, days: parseInt(state.days, 10)};
            if (append && state.beforeId) body["before-id"] = state.beforeId;
            if (state.q) body.q = state.q;
            if (state.group) body.group = state.group;
            if (state.version) body.version = state.version;

            notefoxAuthenticatedApi("/admin/logs", body).then(function (data) {
                if (requestId !== state.requestId) return;
                skeleton.classList.add("hidden2");

                if (!data["available"]) {
                    empty.textContent = (REASONS[data["reason"]] || "This log is not available") + (data["table"] ? " (table: " + data["table"] + ")." : ".");
                    empty.classList.remove("hidden2");
                    return;
                }

                (data["items"] || []).forEach(function (item) {
                    list.appendChild(renderItem(item));
                });
                state.beforeId = data["next-before-id"];

                if (!list.hasChildNodes()) {
                    empty.textContent = TYPES[state.type].empty;
                    empty.classList.remove("hidden2");
                }
                moreButton.classList.toggle("hidden2", !data["has-more"]);
                moreButton.disabled = false;
            }).catch(function (error) {
                if (requestId !== state.requestId) return;
                skeleton.classList.add("hidden2");
                moreButton.disabled = false;
                showFormMessage("logs-message", notefoxErrorMessage(error), true);
            });
        }

        function reload() {
            state.beforeId = null;
            syncUrl();
            renderControls();
            load(false);
        }

        document.querySelectorAll(".admin-tab").forEach(function (tab) {
            tab.addEventListener("click", function () {
                var type = tab.getAttribute("data-type");
                if (type === state.type) return;
                state.type = type;
                state.group = "";
                reload();
            });
        });

        var searchTimer = null;
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                var value = searchInput.value.trim();
                if (value === state.q) return;
                state.q = value;
                reload();
            }, 350);
        });

        daysSelect.addEventListener("change", function () {
            state.days = daysSelect.value;
            reload();
        });

        moreButton.addEventListener("click", function () {
            load(true);
        });

        reload();
    });
</script>
</body>
</html>
