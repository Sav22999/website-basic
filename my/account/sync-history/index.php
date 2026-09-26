<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.history_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/" class="back-link"><?php echo te('account.back_to_account'); ?></a>
        <h1 class="text-center"><?php echo te('account.history_heading'); ?></h1>
        <p>
            <?php echo t('account.history_desc'); ?>
        </p>

        <div id="history-message" class="form-message hidden2"></div>

        <div id="history-loading" class="text-center" style="padding: 48px 0;">
            <span class="spinner" style="width: 28px; height: 28px; border-width: 3px;"></span>
        </div>

        <div class="text-center">
            <button type="button" id="download-current-button"
                    class="btn hidden2"><?php echo te('account.history_download_current'); ?></button>
        </div>

        <ul id="history-list" class="history-list"></ul>
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

        var listElement = document.getElementById("history-list");
        var downloadCurrentButton = document.getElementById("download-current-button");
        var loadingElement = document.getElementById("history-loading");

        function hideLoading() {
            if (loadingElement) loadingElement.style.display = "none";
        }

        function formatDate(value) {
            if (!value) {
                return "-";
            }
            var parsed = new Date(value.replace(" ", "T"));
            if (isNaN(parsed.getTime())) {
                return value;
            }
            return parsed.toLocaleString();
        }

        function downloadHistoryEntry(id, insertedDate) {
            hideFormMessage("history-message");
            notefoxAuthenticatedApi("/data/get/history/download", {
                "service": NOTEFOX_DEFAULT_SERVICE,
                "id": id
            }).then(function (data) {
                downloadNotesJson("notefox-notes-" + insertedDate.replace(/[^0-9]/g, "-"), data["data"]);
            }).catch(function (error) {
                showFormMessage("history-message", notefoxErrorMessage(error), true);
            });
        }

        function renderEntries(entries) {
            listElement.innerHTML = "";

            if (entries.length === 0) {
                var emptyItem = document.createElement("li");
                emptyItem.textContent = "No synced version found.";
                listElement.appendChild(emptyItem);
                return;
            }

            entries.forEach(function (entry, index) {
                var item = document.createElement("li");
                item.className = "history-item" + (index === 0 ? " history-item--current" : "");

                var dateSpan = document.createElement("span");
                dateSpan.className = "history-date";
                dateSpan.textContent = formatDate(entry["inserted-date"]);
                if (index === 0) {
                    var badge = document.createElement("span");
                    badge.className = "history-badge";
                    badge.textContent = "current";
                    dateSpan.appendChild(badge);
                }
                item.appendChild(dateSpan);

                var downloadButton = document.createElement("button");
                downloadButton.type = "button";
                downloadButton.className = "btn btn--secondary";
                downloadButton.textContent = "Download";
                downloadButton.addEventListener("click", function () {
                    downloadHistoryEntry(entry["id"], entry["inserted-date"]);
                });
                item.appendChild(downloadButton);

                listElement.appendChild(item);
            });
        }

        notefoxAuthenticatedApi("/data/get/history", {
            "service": NOTEFOX_DEFAULT_SERVICE
        }).then(function (data) {
            hideLoading();
            renderEntries(data["entries"] || []);
        }).catch(function (error) {
            hideLoading();
            if (error.code === 433) {
                showFormMessage("history-message", <?php echo json_encode(t('account.history_pro_required'), JSON_UNESCAPED_UNICODE); ?>, true);
                var msgEl = document.getElementById("history-message");
                var historyLink = document.createElement("a");
                historyLink.href = "/help/how-to-get-history-sync/";
                historyLink.className = "form-message-link";
                historyLink.textContent = <?php echo json_encode(t('account.history_learn_more'), JSON_UNESCAPED_UNICODE); ?>;
                msgEl.appendChild(historyLink);
                var proLink = document.createElement("a");
                proLink.href = "/help/pro-features/";
                proLink.className = "form-message-link";
                proLink.textContent = <?php echo json_encode(t('account.notes_pro_learn_more'), JSON_UNESCAPED_UNICODE); ?>;
                msgEl.appendChild(proLink);
            } else if (error.code === 432) {
                showFormMessage("history-message", "The sync history is not available for this service.", true);
            } else if (error.code === 201) {
                renderEntries([]);
            } else {
                showFormMessage("history-message", notefoxErrorMessage(error), true);
            }
        });

        notefoxAuthenticatedApi("/data/get", {
            "service": NOTEFOX_DEFAULT_SERVICE
        }).then(function (data) {
            downloadCurrentButton.classList.remove("hidden2");
            downloadCurrentButton.addEventListener("click", function () {
                downloadNotesJson("notefox-notes-current", data["data"]);
            });
        }).catch(function () {
            // No current snapshot: the download-current button simply stays hidden.
        });
    });
</script>
</body>
</html>
