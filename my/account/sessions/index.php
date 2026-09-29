<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.sessions_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/" class="back-link"><?php echo te('account.back_to_account'); ?></a>
        <h1 class="text-center"><?php echo te('account.sessions_heading'); ?></h1>
        <p>
            <?php echo t('account.sessions_desc'); ?>
        </p>

        <div id="sessions-message" class="form-message hidden2"></div>

        <div id="sessions-loading" class="text-center" style="padding: 48px 0;">
            <span class="spinner" style="width: 28px; height: 28px; border-width: 3px;"></span>
        </div>

        <ul id="sessions-list" class="history-list"></ul>
    </div>
</main>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var session = requireSession();
        if (!session) {
            return;
        }

        var listElement = document.getElementById("sessions-list");
        var loadingElement = document.getElementById("sessions-loading");

        function hideLoading() {
            if (loadingElement) loadingElement.style.display = "none";
        }

        function formatDate(value) {
            if (!value) {
                return "—";
            }
            var parsed = new Date(value.replace(" ", "T"));
            if (isNaN(parsed.getTime())) {
                return value;
            }
            return parsed.toLocaleString();
        }

        function revokeSession(loginId) {
            if (!confirm(<?php echo json_encode(t('account.sessions_revoke_confirm'), JSON_UNESCAPED_UNICODE); ?>)) {
                return;
            }
            hideFormMessage("sessions-message");
            notefoxAuthenticatedApi("/sessions/revoke", {
                "target": loginId
            }).then(function () {
                loadSessions();
            }).catch(function (error) {
                showFormMessage("sessions-message", notefoxErrorMessage(error), true);
            });
        }

        function renderSessions(sessions) {
            listElement.innerHTML = "";

            if (sessions.length === 0) {
                var emptyItem = document.createElement("li");
                emptyItem.textContent = <?php echo json_encode(t('account.sessions_none'), JSON_UNESCAPED_UNICODE); ?>;
                listElement.appendChild(emptyItem);
                return;
            }

            sessions.forEach(function (entry) {
                var item = document.createElement("li");
                item.className = "history-item" + (entry["current"] ? " history-item--current" : "");

                var infoDiv = document.createElement("div");
                infoDiv.className = "session-info";

                var idLine = document.createElement("div");
                idLine.className = "session-id";
                var idCode = document.createElement("code");
                idCode.textContent = entry["login-id-short"];
                idLine.appendChild(idCode);
                if (entry["current"]) {
                    var badge = document.createElement("span");
                    badge.className = "history-badge";
                    badge.textContent = <?php echo json_encode(t('account.sessions_current'), JSON_UNESCAPED_UNICODE); ?>;
                    idLine.appendChild(badge);
                }
                infoDiv.appendChild(idLine);

                var detailsLine = document.createElement("div");
                detailsLine.className = "session-details";

                var ipSpan = document.createElement("span");
                ipSpan.textContent = entry["ip-address"] || "—";
                detailsLine.appendChild(ipSpan);

                var sep1 = document.createElement("span");
                sep1.className = "session-sep";
                sep1.textContent = "·";
                detailsLine.appendChild(sep1);

                var dateSpan = document.createElement("span");
                dateSpan.textContent = formatDate(entry["verified"]);
                detailsLine.appendChild(dateSpan);

                if (entry["expiry"]) {
                    var sep2 = document.createElement("span");
                    sep2.className = "session-sep";
                    sep2.textContent = "·";
                    detailsLine.appendChild(sep2);

                    var expirySpan = document.createElement("span");
                    expirySpan.textContent = <?php echo json_encode(t('account.sessions_expires'), JSON_UNESCAPED_UNICODE); ?> +" " + formatDate(entry["expiry"]);
                    detailsLine.appendChild(expirySpan);
                }

                infoDiv.appendChild(detailsLine);
                item.appendChild(infoDiv);

                if (!entry["current"]) {
                    var revokeButton = document.createElement("button");
                    revokeButton.type = "button";
                    revokeButton.className = "btn btn--danger btn--small";
                    revokeButton.textContent = <?php echo json_encode(t('account.sessions_revoke'), JSON_UNESCAPED_UNICODE); ?>;
                    revokeButton.addEventListener("click", function () {
                        revokeSession(entry["login-id"]);
                    });
                    item.appendChild(revokeButton);
                }

                listElement.appendChild(item);
            });
        }

        function loadSessions() {
            notefoxAuthenticatedApi("/sessions", {}).then(function (data) {
                hideLoading();
                renderSessions(data["sessions"] || []);
            }).catch(function (error) {
                hideLoading();
                showFormMessage("sessions-message", notefoxErrorMessage(error), true);
            });
        }

        loadSessions();
    });
</script>
</body>
</html>
