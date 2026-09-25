<html>
<head>
    <?php
    $title = "Sync history – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php");
    ?>
    <script src="/js/account.js"></script>
</head>
<body>
<?php
$selected_menu = "my";
include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Sync history</h1>
            <p>
                Here you can find the list of the synced versions of your notes, sorted from the most recent one. You
                can download the current version or any previous version in JSON format.
            </p>

            <div id="history-message" class="form-message hidden2"></div>

            <div class="horizontal-center">
                <button type="button" id="download-current-button" class="button hidden2">Download the current version
                </button>
            </div>

            <ul id="history-list" class="history-list"></ul>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var session = requireSession();
        if (!session) {
            return;
        }

        var listElement = document.getElementById("history-list");
        var downloadCurrentButton = document.getElementById("download-current-button");

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
                dateSpan.className = "history-item-date";
                dateSpan.textContent = formatDate(entry["inserted-date"]);
                if (index === 0) {
                    var badge = document.createElement("span");
                    badge.className = "history-item-badge";
                    badge.textContent = "current";
                    dateSpan.appendChild(badge);
                }
                item.appendChild(dateSpan);

                var downloadButton = document.createElement("button");
                downloadButton.type = "button";
                downloadButton.className = "button button-secondary";
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
            renderEntries(data["entries"] || []);
        }).catch(function (error) {
            if (error.code === 433) {
                // Per-account permission (`users`.`history-enabled`): the page
                // is reachable by URL, so it has to say so by itself. The
                // download of the CURRENT version stays available: it comes
                // from POST /data/get, which this permission never touches.
                showFormMessage("history-message", "The sync history is not enabled for your account.", true);
                // The help page is linked inside the same message, as a
                // secondary button aligned to the right: showFormMessage()
                // writes plain text, so the link is appended afterwards.
                var messageElement = document.getElementById("history-message");
                var helpLink = document.createElement("a");
                helpLink.href = "/help/how-to-get-history-sync/";
                helpLink.className = "button button-secondary form-message-action";
                helpLink.textContent = "How to get the Sync history";
                messageElement.appendChild(helpLink);

                var loginIdBlock = document.createElement("div");
                loginIdBlock.style.marginTop = "12px";
                loginIdBlock.style.paddingTop = "10px";
                loginIdBlock.style.borderTop = "1px solid rgba(255, 255, 255, 0.15)";

                var loginIdText = document.createElement("p");
                loginIdText.className = "font-small";
                loginIdText.style.margin = "0 0 4px 0";
                loginIdText.innerHTML = "Your account identifier (login-id) to communicate after donating: <code>" + (session["login-id"] || "") + "</code>";

                var loginIdNote = document.createElement("p");
                loginIdNote.className = "font-very-small";
                loginIdNote.style.margin = "0";
                loginIdNote.style.opacity = "0.85";
                loginIdNote.textContent = "The Login ID is not sensitive data and can be safely communicated to the developer.";

                loginIdBlock.appendChild(loginIdText);
                loginIdBlock.appendChild(loginIdNote);
                messageElement.appendChild(loginIdBlock);
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

<?php
?>
