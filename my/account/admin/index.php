<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.admin_dashboard_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/" class="back-link"><?php echo te('account.back_to_account'); ?></a>
        <h1 class="text-center"><?php echo te('account.admin_dashboard'); ?></h1>
        <p class="text-center" style="color: var(--color-text-muted);">
            <?php echo te('account.admin_dashboard_intro'); ?>
        </p>

        <div class="admin-toolbar">
            <span id="admin-updated" class="admin-updated"></span>
            <button type="button" id="admin-refresh" class="btn btn--small admin-refresh" disabled>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="23 4 23 10 17 10"/>
                    <polyline points="1 20 1 14 7 14"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                </svg>
                Refresh
            </button>
        </div>

        <div id="admin-message" class="form-message hidden2"></div>

        <div id="admin-skeleton" class="admin-skeleton" aria-hidden="true">
            <div class="admin-stat-grid admin-stat-grid--hero">
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
            </div>
            <div class="admin-stat-grid">
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
                <div class="admin-stat admin-stat--skeleton skeleton"></div>
            </div>
        </div>

        <div id="admin-stats" aria-live="polite"></div>
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

        var ICONS = {
            users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            activity: '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
            sync: '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
            alert: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
            monitor: '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
            chart: '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>'
        };

        var container = document.getElementById("admin-stats");
        var skeleton = document.getElementById("admin-skeleton");
        var refreshButton = document.getElementById("admin-refresh");
        var updatedLabel = document.getElementById("admin-updated");

        function isNumber(value) {
            return typeof value === "number" && !isNaN(value);
        }

        function formatNumber(value) {
            return isNumber(value) ? value.toLocaleString() : "—";
        }

        function ratio(part, total) {
            if (!isNumber(part) || !isNumber(total) || total <= 0) return null;
            return Math.min(100, Math.round((part / total) * 100));
        }

        function el(tag, className, text) {
            var node = document.createElement(tag);
            if (className) node.className = className;
            if (text !== undefined && text !== null) node.textContent = text;
            return node;
        }

        function icon(name, size) {
            var wrap = el("span", "admin-icon");
            wrap.innerHTML = '<svg width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + ICONS[name] + '</svg>';
            return wrap;
        }

        function statTile(tile, hero) {
            var card = el("div", "admin-stat" + (hero ? " admin-stat--hero" : ""));
            if (hero && tile.icon) card.appendChild(icon(tile.icon, 22));

            if (tile.text !== undefined) {
                card.appendChild(el("span", "admin-stat-value admin-stat-value--text", tile.text));
                card.appendChild(el("span", "admin-stat-label", tile.label));
                return card;
            }

            var available = isNumber(tile.value);
            card.appendChild(el("span", "admin-stat-value" + (available ? "" : " admin-stat-value--na"), formatNumber(tile.value)));
            card.appendChild(el("span", "admin-stat-label", tile.label));

            if (!available) {
                card.appendChild(el("p", "admin-stat-desc", "Not available on this server."));
                return card;
            }

            if (tile.of) {
                var pct = ratio(tile.value, tile.of.value);
                if (pct !== null) {
                    var box = el("div", "admin-stat-ratio");
                    var bar = el("div", "admin-stat-ratio-bar");
                    var fill = el("div", "admin-stat-ratio-fill");
                    fill.style.width = pct + "%";
                    bar.appendChild(fill);
                    box.appendChild(bar);
                    box.appendChild(el("span", "admin-stat-ratio-text", pct + "% of " + tile.of.label));
                    card.appendChild(box);
                }
            }

            if (tile.desc) card.appendChild(el("p", "admin-stat-desc", tile.desc));
            return card;
        }

        function topList(list) {
            var box = el("div", "admin-top");
            box.appendChild(el("h3", "admin-top-title", list.title));

            if (!list.items || list.items.length === 0) {
                box.appendChild(el("p", "admin-top-empty", "No data in this period."));
                return box;
            }

            var max = 0, sum = 0;
            list.items.forEach(function (item) {
                max = Math.max(max, item.count);
                sum += item.count;
            });

            list.items.forEach(function (item) {
                var linked = !!list.link && item.label !== "unknown";
                var row = el(linked ? "a" : "div", "admin-top-row" + (linked ? " admin-top-row--link" : ""));
                if (linked) {
                    row.href = list.link(item);
                    row.title = "Show these entries";
                }
                var head = el("div", "admin-top-head");
                var label = el("span", "admin-top-label", item.label);
                label.title = item.label;
                head.appendChild(label);
                var count = el("span", "admin-top-count", formatNumber(item.count));
                count.appendChild(el("span", "admin-top-share", " · " + Math.round((item.count / sum) * 100) + "%"));
                head.appendChild(count);
                var bar = el("div", "admin-top-bar");
                var fill = el("div", "admin-top-fill");
                fill.style.width = Math.max(2, Math.round((item.count / (max || 1)) * 100)) + "%";
                bar.appendChild(fill);
                row.appendChild(head);
                row.appendChild(bar);
                box.appendChild(row);
            });
            return box;
        }

        function addSection(options) {
            var section = el("section", "admin-section");
            var header = el("div", "admin-section-header");
            header.appendChild(icon(options.icon, 18));
            var titles = el("div", "admin-section-titles");
            titles.appendChild(el("h2", "admin-section-title", options.title));
            if (options.desc) titles.appendChild(el("p", "admin-section-desc", options.desc));
            header.appendChild(titles);
            if (options.action) {
                var action = el("a", "btn btn--small admin-section-action", options.action.label);
                action.href = options.action.href;
                header.appendChild(action);
            }
            section.appendChild(header);

            if (options.notice) {
                section.appendChild(el("p", "admin-section-notice", options.notice));
                container.appendChild(section);
                return;
            }

            var grid = el("div", "admin-stat-grid");
            options.tiles.forEach(function (tile) {
                grid.appendChild(statTile(tile, false));
            });
            section.appendChild(grid);

            var lists = (options.lists || []).filter(function (list) { return list.items !== null && list.items !== undefined; });
            if (lists.length > 0) {
                var listGrid = el("div", "admin-top-grid");
                lists.forEach(function (list) { listGrid.appendChild(topList(list)); });
                section.appendChild(listGrid);
            }

            container.appendChild(section);
        }

        function logsUrl(type, filters) {
            var query = new URLSearchParams({type: type});
            Object.keys(filters || {}).forEach(function (key) {
                if (filters[key] !== "unknown") query.set(key, filters[key]);
            });
            return "/my/account/admin/logs/?" + query.toString();
        }

        function unavailableText(section) {
            var reasons = {
                "table-missing": "The configured table does not exist in the database",
                "date-column-missing": "The configured table has no date column"
            };
            return (reasons[section["reason"]] || "Not available") + (section["table"] ? " (table: " + section["table"] + ")." : ".");
        }

        function render(data) {
            container.innerHTML = "";

            var a = data["accounts"] || {};
            var s = data["sessions"] || {};
            var y = data["sync"];
            var t = data["telemetry"];
            var e = data["error-logs"];

            var registered = {value: a["total"], label: "registered"};
            var verified = {value: a["verified"], label: "verified"};

            var hero = el("div", "admin-stat-grid admin-stat-grid--hero");
            [
                {icon: "users", label: "Registered accounts", value: a["total"]},
                {icon: "activity", label: "Active in the last 30 days", value: s["users-logged-in-30d"], of: verified},
                {icon: "sync", label: "Synced in the last 7 days", value: y ? y["synced-7d"] : null, of: verified},
                {icon: "alert", label: "Errors in the last 7 days", value: e && e["available"] ? e["errors-7d"] : null}
            ].forEach(function (tile) { hero.appendChild(statTile(tile, true)); });
            container.appendChild(hero);

            addSection({
                icon: "users",
                title: "Accounts",
                desc: "Every account in the users table, verified or not.",
                tiles: [
                    {label: "Verified", value: a["verified"], of: registered},
                    {label: "Not verified", value: a["unverified"], of: registered, desc: "Signed up but never confirmed the email code."},
                    {label: "New in the last 7 days", value: a["new-7d"]},
                    {label: "New in the last 30 days", value: a["new-30d"]},
                    {label: "Two-step verification on", value: a["otp-enabled"], of: verified},
                    {label: "Sync history enabled", value: a["history-enabled"]},
                    {label: "Pro features enabled", value: a["pro-features"]},
                    {label: "Administrators", value: a["admins"]}
                ]
            });

            addSection({
                icon: "monitor",
                title: "Sessions",
                desc: "Logins that completed verification and have not expired.",
                tiles: [
                    {label: "Active sessions", value: s["active"]},
                    {label: "Accounts with an active session", value: s["users-with-active-session"], of: verified},
                    {label: "Accounts logged in (30 days)", value: s["users-logged-in-30d"], of: verified}
                ]
            });

            if (y) {
                var withData = {value: y["accounts-with-data"], label: "accounts with data"};
                addSection({
                    icon: "sync",
                    title: "Sync",
                    desc: "From server timestamps and revision counters only, never from the synced content.",
                    tiles: [
                        {label: "Accounts with synced data", value: y["accounts-with-data"], of: verified},
                        {label: "Synced in the last 24 hours", value: y["synced-24h"], of: withData},
                        {label: "Synced in the last 7 days", value: y["synced-7d"], of: withData},
                        {label: "Synced in the last 30 days", value: y["synced-30d"], of: withData},
                        {label: "Average syncs per account", value: y["average-revisions"], desc: "Mean revision number of the current snapshots."},
                        {label: "Verified without v2 data", value: y["verified-without-v2-data"], of: verified, desc: "Never synced through API v2 (v1 only, or no data yet)."}
                    ]
                });
            }

            if (t) {
                addSection({
                    icon: "chart",
                    title: "Telemetry",
                    desc: "Only from installations that turned on anonymous telemetry. Lists cover the last 30 days.",
                    action: t["available"] ? {label: "View events", href: logsUrl("telemetry")} : null,
                    notice: t["available"] ? null : unavailableText(t),
                    tiles: [
                        {label: "Events (30 days)", value: t["events-30d"], of: {value: t["events-total"], label: "all time"}},
                        {label: "Events (all time)", value: t["events-total"]},
                        {label: "Installations (30 days)", value: t["installations-30d"], of: {value: t["installations-total"], label: "all time"}},
                        {label: "Installations (all time)", value: t["installations-total"]},
                        {label: "Last event", text: t["last-event"] ? t["last-event"] : "Never"}
                    ],
                    lists: [
                        {title: "Actions", items: t["actions"], link: function (item) { return logsUrl("telemetry", {group: item.label, days: 30}); }},
                        {title: "Notefox versions", items: t["versions"], link: function (item) { return logsUrl("telemetry", {version: item.label, days: 30}); }},
                        {title: "Browsers", items: t["browsers"]},
                        {title: "Operating systems", items: t["os"]},
                        {title: "Languages", items: t["languages"]}
                    ]
                });
            }

            if (e) {
                addSection({
                    icon: "alert",
                    title: "Error logs",
                    desc: "Error reports sent automatically by the add-on. Lists cover the last 7 days.",
                    action: e["available"] ? {label: "View error logs", href: logsUrl("errors")} : null,
                    notice: e["available"] ? null : unavailableText(e),
                    tiles: [
                        {label: "Errors in the last 7 days", value: e["errors-7d"]},
                        {label: "Errors in the last 30 days", value: e["errors-30d"]},
                        {label: "Errors (all time)", value: e["errors-total"]}
                    ],
                    lists: [
                        {title: "Top contexts (7 days)", items: e["contexts"], link: function (item) { return logsUrl("errors", {group: item.label, days: 7}); }},
                        {title: "Notefox versions (7 days)", items: e["versions"], link: function (item) { return logsUrl("errors", {version: item.label, days: 7}); }}
                    ]
                });
            }

            updatedLabel.textContent = "Updated at " + new Date().toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"});
        }

        function load() {
            refreshButton.disabled = true;
            refreshButton.classList.add("is-loading");
            document.getElementById("admin-message").classList.add("hidden2");
            if (!container.hasChildNodes()) skeleton.classList.remove("hidden2");

            notefoxAuthenticatedApi("/admin/stats", {}).then(function (data) {
                render(data);
            }).catch(function (error) {
                showFormMessage("admin-message", notefoxErrorMessage(error), true);
            }).then(function () {
                skeleton.classList.add("hidden2");
                refreshButton.disabled = false;
                refreshButton.classList.remove("is-loading");
            });
        }

        refreshButton.addEventListener("click", load);
        load();
    });
</script>
</body>
</html>
