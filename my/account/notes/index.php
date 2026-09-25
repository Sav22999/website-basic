<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
include_once($root_path . "/include/i18n.php");
$title = t('account.notes_title');
$selected_menu = "my";
include_once($root_path . "/include/header.php");
?>
<body>
<?php include_once($root_path . "/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/my/account/" class="back-link"><?php echo te('account.back_to_account'); ?></a>
        <h1 class="text-center"><?php echo te('account.notes_heading'); ?></h1>
        <p class="text-center" style="color: var(--color-text-muted);"><?php echo t('account.notes_desc'); ?></p>

        <div id="notes-message" class="form-message hidden2"></div>

        <div id="notes-loading" class="text-center" style="padding: 48px 0;">
            <span class="spinner" style="width: 28px; height: 28px; border-width: 3px;"></span>
        </div>

        <div id="notes-container" class="hidden2">
            <div class="notes-toolbar">
                <div class="notes-search-wrap" id="notes-search-wrap">
                    <svg class="notes-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="notes-search" class="notes-search-input"
                           placeholder="<?php echo te('account.notes_search'); ?>">
                </div>
                <select id="notes-sort" class="form-input notes-sort-select">
                    <option value="date-new"><?php echo te('account.notes_sort_newest'); ?></option>
                    <option value="date-old"><?php echo te('account.notes_sort_oldest'); ?></option>
                    <option value="name-az"><?php echo te('account.notes_sort_name_az'); ?></option>
                    <option value="name-za"><?php echo te('account.notes_sort_name_za'); ?></option>
                    <option value="url-az"><?php echo te('account.notes_sort_url'); ?></option>
                </select>
                <button type="button" id="notes-filter-toggle" class="btn btn--secondary notes-filter-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    <?php echo te('account.notes_filters'); ?>
                </button>
            </div>

            <div id="notes-filters" class="notes-filters hidden2">
                <div class="notes-filter-group">
                    <label><?php echo te('account.notes_filter_type'); ?></label>
                    <div class="notes-filter-chips" id="filter-type">
                        <button type="button" class="notes-chip notes-chip--active" data-value=""><?php echo te('account.notes_filter_all'); ?></button>
                        <button type="button" class="notes-chip" data-value="0"><?php echo te('account.notes_global'); ?></button>
                        <button type="button" class="notes-chip" data-value="1"><?php echo te('account.notes_domain'); ?></button>
                        <button type="button" class="notes-chip" data-value="2"><?php echo te('account.notes_page'); ?></button>
                    </div>
                </div>
                <div class="notes-filter-group">
                    <label><?php echo te('account.notes_filter_color'); ?></label>
                    <div class="notes-filter-chips" id="filter-color"></div>
                </div>
                <div class="notes-filter-group">
                    <label><?php echo te('account.notes_filter_folder'); ?></label>
                    <div class="notes-search-wrap notes-filter-chip-wrap" id="filter-folder-wrap">
                        <input type="text" id="filter-folder" class="notes-search-input" placeholder="<?php echo te('account.notes_filter_all'); ?>">
                        <div class="notes-autocomplete hidden2" id="filter-folder-suggestions"></div>
                    </div>
                </div>
                <div class="notes-filter-group">
                    <label><?php echo te('account.notes_filter_tag'); ?></label>
                    <div class="notes-search-wrap notes-filter-chip-wrap" id="filter-tag-wrap">
                        <input type="text" id="filter-tag" class="notes-search-input" placeholder="<?php echo te('account.notes_filter_all'); ?>">
                        <div class="notes-autocomplete hidden2" id="filter-tag-suggestions"></div>
                    </div>
                </div>
                <button type="button" id="notes-clear-filters" class="btn btn--secondary btn--small"><?php echo te('account.notes_clear_filters'); ?></button>
            </div>

            <p id="notes-stats" class="notes-stats"></p>
            <div id="notes-list"></div>
            <p id="notes-empty" class="text-center hidden2" style="color: var(--color-text-muted); padding: 32px 0;">
                <?php echo te('account.notes_empty'); ?>
            </p>
            <p id="notes-no-results" class="text-center hidden2" style="color: var(--color-text-muted); padding: 32px 0;">
                <?php echo te('account.notes_no_results'); ?>
            </p>
        </div>
    </div>
</main>

<div id="note-editor-overlay" class="note-editor-overlay hidden2">
    <div class="note-editor">
        <div class="note-editor-header">
            <div class="note-editor-header-left">
                <span id="editor-color-dot" class="note-editor-color-dot"></span>
                <div>
                    <h2 id="editor-heading"><?php echo te('account.notes_edit_title'); ?></h2>
                    <input type="text" id="editor-url" class="note-editor-url-input" placeholder="URL">
                </div>
            </div>
            <button type="button" id="editor-close" class="note-editor-close" aria-label="<?php echo te('common.cancel'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div id="editor-message" class="form-message hidden2"></div>

        <div class="note-editor-body">
            <div class="note-editor-field">
                <label for="editor-title"><?php echo te('account.notes_label_title'); ?></label>
                <input type="text" id="editor-title" class="form-input" placeholder="<?php echo te('account.notes_title_placeholder'); ?>">
            </div>

            <div class="note-editor-field note-editor-field--grow">
                <label><?php echo te('account.notes_label_content'); ?></label>
                <div class="note-editor-toolbar" id="editor-toolbar">
                    <button type="button" data-cmd="bold" title="Bold (Ctrl+B)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg></button>
                    <button type="button" data-cmd="italic" title="Italic (Ctrl+I)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
                    <button type="button" data-cmd="underline" title="Underline (Ctrl+U)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
                    <button type="button" data-cmd="strikeThrough" title="Strikethrough"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.3 4.9c-1.2-1.1-2.9-1.6-4.8-1.4-2.3.3-4.1 1.8-4.5 3.8"/><path d="M4 12h16"/><path d="M19 16.6c0 2.4-2.1 4.4-4.7 4.4-2.1 0-3.8-1.2-4.5-2.8"/></svg></button>
                    <span class="note-editor-toolbar-sep"></span>
                    <button type="button" data-cmd="insertUnorderedList" title="<?php echo te('account.notes_ul'); ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="18" r="1" fill="currentColor"/></svg></button>
                    <button type="button" data-cmd="insertOrderedList" title="<?php echo te('account.notes_ol'); ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><text x="1" y="8" font-size="8" fill="currentColor" stroke="none" font-family="sans-serif">1</text><text x="1" y="14" font-size="8" fill="currentColor" stroke="none" font-family="sans-serif">2</text><text x="1" y="20" font-size="8" fill="currentColor" stroke="none" font-family="sans-serif">3</text></svg></button>
                    <span class="note-editor-toolbar-sep"></span>
                    <button type="button" data-action="link" title="Link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
                    <button type="button" data-cmd="removeFormat" title="<?php echo te('account.notes_clear_format'); ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h7l-2 13"/><line x1="3" y1="20" x2="21" y2="4"/></svg></button>
                </div>
                <div id="editor-content" class="note-editor-content" contenteditable="true" spellcheck="true"></div>
            </div>

            <details class="note-editor-details" id="editor-details">
                <summary class="note-editor-summary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <?php echo te('account.notes_properties'); ?>
                </summary>
                <div class="note-editor-details-body">
                    <div class="note-editor-field">
                        <label><?php echo te('account.notes_label_color'); ?></label>
                        <div class="note-color-grid" id="editor-color-grid"></div>
                    </div>

                    <div class="note-editor-row">
                        <div class="note-editor-field note-editor-field--half">
                            <label for="editor-folder"><?php echo te('account.notes_label_folder'); ?></label>
                            <input type="text" id="editor-folder" class="form-input" placeholder="<?php echo te('account.notes_folder_placeholder'); ?>" list="editor-folder-list">
                            <datalist id="editor-folder-list"></datalist>
                        </div>
                        <div class="note-editor-field note-editor-field--half">
                            <label><?php echo te('account.notes_label_tags'); ?></label>
                            <div class="note-tags-input">
                                <div class="note-tags-add">
                                    <input type="text" id="editor-tag-input" class="form-input" placeholder="<?php echo te('account.notes_tag_placeholder'); ?>" list="editor-tag-list">
                                    <datalist id="editor-tag-list"></datalist>
                                    <button type="button" id="editor-tag-add" class="btn btn--small"><?php echo te('account.notes_add_tag'); ?></button>
                                </div>
                                <div id="editor-tags-list" class="note-tags-chips"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </div>

        <div class="note-editor-footer">
            <span id="editor-last-update" class="note-editor-meta"></span>
            <div class="note-editor-footer-actions">
                <button type="button" id="editor-cancel" class="btn btn--secondary"><?php echo te('common.cancel'); ?></button>
                <button type="button" id="editor-save" class="btn"><?php echo te('account.notes_save'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php include_once($root_path . "/include/footer.php"); ?>
<script src="/js/script.js"></script>
<script src="/js/account.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var session = requireSession();
        if (!session) return;

        var loadingEl = document.getElementById("notes-loading");
        var containerEl = document.getElementById("notes-container");
        var listEl = document.getElementById("notes-list");
        var emptyEl = document.getElementById("notes-empty");
        var noResultsEl = document.getElementById("notes-no-results");
        var statsEl = document.getElementById("notes-stats");
        var searchEl = document.getElementById("notes-search");
        var searchWrap = document.getElementById("notes-search-wrap");
        var sortEl = document.getElementById("notes-sort");
        var searchChips = [];

        var allNotes = [];
        var currentSnapshot = null;
        var currentRevision = null;

        var TAG_COLORS = {
            red: "#e74c3c", yellow: "#f1c40f", orange: "#e67e22", pink: "#e91e63",
            purple: "#9b59b6", green: "#27ae60", blue: "#3498db", gray: "#95a5a6",
            black: "#2c3e50", white: "#bdc3c7", brown: "#8d6e63", coral: "#ff7043",
            cyan: "#00bcd4", teal: "#009688", navy: "#1a237e", indigo: "#3f51b5",
            violet: "#7c4dff", lime: "#cddc39", fuchsia: "#e040fb", lavender: "#b39ddb",
            olive: "#827717", plum: "#ab47bc", salmon: "#ff8a65", aquamarine: "#69f0ae",
            turquoise: "#26c6da", darkgreen: "#2e7d32", snow: "#eceff1"
        };

        var COLOR_NAMES_SORTED = Object.keys(TAG_COLORS).sort();

        var TYPE_LABELS = {
            0: <?php echo json_encode(t('account.notes_global'), JSON_UNESCAPED_UNICODE); ?>,
            1: <?php echo json_encode(t('account.notes_domain'), JSON_UNESCAPED_UNICODE); ?>,
            2: <?php echo json_encode(t('account.notes_page'), JSON_UNESCAPED_UNICODE); ?>
        };

        var STRINGS = {
            global_notes: <?php echo json_encode(t('account.notes_global_notes'), JSON_UNESCAPED_UNICODE); ?>,
            show_more: <?php echo json_encode(t('account.notes_show_more'), JSON_UNESCAPED_UNICODE); ?>,
            show_less: <?php echo json_encode(t('account.notes_show_less'), JSON_UNESCAPED_UNICODE); ?>,
            edit: <?php echo json_encode(t('account.notes_edit'), JSON_UNESCAPED_UNICODE); ?>,
            copy: <?php echo json_encode(t('account.notes_copy'), JSON_UNESCAPED_UNICODE); ?>,
            copied: <?php echo json_encode(t('account.notes_copied'), JSON_UNESCAPED_UNICODE); ?>,
            del: <?php echo json_encode(t('account.notes_delete'), JSON_UNESCAPED_UNICODE); ?>,
            del_confirm: <?php echo json_encode(t('account.notes_delete_confirm'), JSON_UNESCAPED_UNICODE); ?>,
            del_domain_confirm: <?php echo json_encode(t('account.notes_delete_domain_confirm'), JSON_UNESCAPED_UNICODE); ?>,
            deleting: <?php echo json_encode(t('account.notes_deleting'), JSON_UNESCAPED_UNICODE); ?>,
            count: <?php echo json_encode(t('account.notes_count'), JSON_UNESCAPED_UNICODE); ?>,
            save: <?php echo json_encode(t('account.notes_save'), JSON_UNESCAPED_UNICODE); ?>,
            saving: <?php echo json_encode(t('account.notes_saving'), JSON_UNESCAPED_UNICODE); ?>,
            conflict: <?php echo json_encode(t('account.notes_conflict'), JSON_UNESCAPED_UNICODE); ?>,
            save_error: <?php echo json_encode(t('account.notes_save_error'), JSON_UNESCAPED_UNICODE); ?>
        };

        // ── Filters ──

        var activeFilters = { type: "", color: "", folders: [], tags: [] };
        var filterToggleBtn = document.getElementById("notes-filter-toggle");
        var filtersEl = document.getElementById("notes-filters");
        var filterTypeEl = document.getElementById("filter-type");
        var filterFolderEl = document.getElementById("filter-folder");
        var filterFolderWrap = document.getElementById("filter-folder-wrap");
        var filterFolderSuggestions = document.getElementById("filter-folder-suggestions");
        var filterTagEl = document.getElementById("filter-tag");
        var filterTagWrap = document.getElementById("filter-tag-wrap");
        var filterTagSuggestions = document.getElementById("filter-tag-suggestions");
        var filterColorEl = document.getElementById("filter-color");

        filterToggleBtn.addEventListener("click", function () {
            filtersEl.classList.toggle("hidden2");
            filterToggleBtn.classList.toggle("notes-filter-btn--active");
        });

        filterTypeEl.addEventListener("click", function (e) {
            var btn = e.target.closest(".notes-chip");
            if (!btn) return;
            filterTypeEl.querySelectorAll(".notes-chip").forEach(function (c) { c.classList.remove("notes-chip--active"); });
            btn.classList.add("notes-chip--active");
            activeFilters.type = btn.dataset.value;
            renderAll();
        });

        function buildColorFilterChips() {
            filterColorEl.innerHTML = "";
            var allBtn = document.createElement("button");
            allBtn.type = "button";
            allBtn.className = "notes-chip notes-chip--active";
            allBtn.dataset.value = "";
            allBtn.textContent = <?php echo json_encode(t('account.notes_filter_all'), JSON_UNESCAPED_UNICODE); ?>;
            filterColorEl.appendChild(allBtn);

            var noneBtn = document.createElement("button");
            noneBtn.type = "button";
            noneBtn.className = "notes-chip";
            noneBtn.dataset.value = "none";
            noneBtn.textContent = <?php echo json_encode(t('account.notes_filter_no_color'), JSON_UNESCAPED_UNICODE); ?>;
            filterColorEl.appendChild(noneBtn);

            COLOR_NAMES_SORTED.forEach(function (name) {
                var btn = document.createElement("button");
                btn.type = "button";
                btn.className = "notes-chip notes-chip--color";
                btn.dataset.value = name;
                btn.style.setProperty("--chip-color", TAG_COLORS[name]);
                btn.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                filterColorEl.appendChild(btn);
            });

            filterColorEl.addEventListener("click", function (e) {
                var btn = e.target.closest(".notes-chip");
                if (!btn) return;
                filterColorEl.querySelectorAll(".notes-chip").forEach(function (c) { c.classList.remove("notes-chip--active"); });
                btn.classList.add("notes-chip--active");
                activeFilters.color = btn.dataset.value;
                renderAll();
            });
        }
        buildColorFilterChips();

        function setupFilterChipInput(inputEl, wrapEl, suggestionsEl, filterKey, getOptions) {
            function renderChips() {
                var existing = wrapEl.querySelectorAll(".notes-search-chip");
                for (var i = existing.length - 1; i >= 0; i--) existing[i].parentNode.removeChild(existing[i]);
                activeFilters[filterKey].forEach(function (val, idx) {
                    var chip = document.createElement("span");
                    chip.className = "notes-search-chip";
                    chip.textContent = val === "" ? <?php echo json_encode(t('account.notes_filter_no_folder'), JSON_UNESCAPED_UNICODE); ?> : val;
                    var btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "notes-search-chip-remove";
                    btn.innerHTML = "&times;";
                    btn.addEventListener("click", function () {
                        activeFilters[filterKey].splice(idx, 1);
                        renderChips();
                        renderAll();
                    });
                    chip.appendChild(btn);
                    wrapEl.insertBefore(chip, inputEl);
                });
                inputEl.placeholder = activeFilters[filterKey].length ? "" :
                    <?php echo json_encode(t('account.notes_filter_all'), JSON_UNESCAPED_UNICODE); ?>;
            }

            function showSuggestions() {
                var text = inputEl.value.trim().toLowerCase();
                var options = getOptions();
                var filtered = options.filter(function (o) {
                    return activeFilters[filterKey].indexOf(o) === -1 &&
                        (text === "" || o.toLowerCase().indexOf(text) !== -1);
                });
                suggestionsEl.innerHTML = "";
                if (filtered.length === 0) {
                    suggestionsEl.classList.add("hidden2");
                    return;
                }
                filtered.forEach(function (opt) {
                    var item = document.createElement("div");
                    item.className = "notes-autocomplete-item";
                    item.textContent = opt === "" ? <?php echo json_encode(t('account.notes_filter_no_folder'), JSON_UNESCAPED_UNICODE); ?> : opt;
                    item.addEventListener("mousedown", function (e) {
                        e.preventDefault();
                        activeFilters[filterKey].push(opt);
                        inputEl.value = "";
                        renderChips();
                        suggestionsEl.classList.add("hidden2");
                        renderAll();
                    });
                    suggestionsEl.appendChild(item);
                });
                suggestionsEl.classList.remove("hidden2");
            }

            inputEl.addEventListener("input", showSuggestions);
            inputEl.addEventListener("focus", showSuggestions);
            inputEl.addEventListener("blur", function () {
                setTimeout(function () { suggestionsEl.classList.add("hidden2"); }, 150);
            });

            inputEl.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    var text = inputEl.value.trim();
                    if (text) {
                        var options = getOptions();
                        var match = options.find(function (o) {
                            return o.toLowerCase() === text.toLowerCase() && activeFilters[filterKey].indexOf(o) === -1;
                        });
                        if (match !== undefined) {
                            activeFilters[filterKey].push(match);
                            inputEl.value = "";
                            renderChips();
                            suggestionsEl.classList.add("hidden2");
                            renderAll();
                        }
                    }
                } else if (e.key === "Backspace" && inputEl.value === "" && activeFilters[filterKey].length > 0) {
                    activeFilters[filterKey].pop();
                    renderChips();
                    renderAll();
                } else if (e.key === "Escape") {
                    suggestionsEl.classList.add("hidden2");
                }
            });

            wrapEl.addEventListener("click", function () { inputEl.focus(); });

            return renderChips;
        }

        var allFolders = [];
        var allTags = [];

        var renderFolderChips = setupFilterChipInput(filterFolderEl, filterFolderWrap, filterFolderSuggestions, "folders", function () { return allFolders; });
        var renderTagChips = setupFilterChipInput(filterTagEl, filterTagWrap, filterTagSuggestions, "tags", function () { return allTags; });

        document.getElementById("notes-clear-filters").addEventListener("click", function () {
            activeFilters = { type: "", color: "", folders: [], tags: [] };
            filterTypeEl.querySelectorAll(".notes-chip").forEach(function (c, i) {
                c.classList.toggle("notes-chip--active", i === 0);
            });
            filterColorEl.querySelectorAll(".notes-chip").forEach(function (c, i) {
                c.classList.toggle("notes-chip--active", i === 0);
            });
            filterFolderEl.value = "";
            filterTagEl.value = "";
            renderFolderChips();
            renderTagChips();
            searchEl.value = "";
            searchChips = [];
            renderSearchChips();
            renderAll();
        });

        function populateFilterOptions() {
            var folders = {}, tags = {};
            allNotes.forEach(function (n) {
                if (n.folder) folders[n.folder] = true;
                n.tags.forEach(function (t) { tags[t] = true; });
            });

            allFolders = [""].concat(Object.keys(folders).sort());
            allTags = Object.keys(tags).sort();

            var folderList = document.getElementById("editor-folder-list");
            folderList.innerHTML = "";
            Object.keys(folders).sort().forEach(function (f) {
                var o = document.createElement("option");
                o.value = f;
                folderList.appendChild(o);
            });
            var tagList = document.getElementById("editor-tag-list");
            tagList.innerHTML = "";
            Object.keys(tags).sort().forEach(function (t) {
                var o = document.createElement("option");
                o.value = t;
                tagList.appendChild(o);
            });
        }

        // ── Helpers ──

        function hideLoading() {
            if (loadingEl) loadingEl.style.display = "none";
        }

        function sanitizeHtml(html) {
            if (!html) return "";
            var div = document.createElement("div");
            div.innerHTML = html;
            var dangerous = div.querySelectorAll("script, iframe, object, embed, form, input, textarea, button, style");
            for (var i = dangerous.length - 1; i >= 0; i--) dangerous[i].parentNode.removeChild(dangerous[i]);
            var allEls = div.querySelectorAll("*");
            for (var j = 0; j < allEls.length; j++) {
                var attrs = allEls[j].attributes;
                for (var k = attrs.length - 1; k >= 0; k--) {
                    if (attrs[k].name.indexOf("on") === 0) allEls[j].removeAttribute(attrs[k].name);
                }
            }
            return div.innerHTML;
        }

        function formatDate(value) {
            if (!value) return "";
            var d = new Date(value.replace(" ", "T"));
            return isNaN(d.getTime()) ? value : d.toLocaleString();
        }

        function nowDateString() {
            var d = new Date();
            var pad = function (n) { return n < 10 ? "0" + n : "" + n; };
            return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate()) +
                " " + pad(d.getHours()) + ":" + pad(d.getMinutes()) + ":" + pad(d.getSeconds());
        }

        function displayUrl(url) {
            if (url === "**global") return STRINGS.global_notes;
            return url.replace(/^https?:\/\//, "");
        }

        function domainOf(url) {
            if (url === "**global") return "**global";
            try {
                return url.replace(/^https?:\/\//, "").split("/")[0];
            } catch (e) {
                return url;
            }
        }

        function parseNotes(websites) {
            var notes = [];
            for (var url in websites) {
                if (!Object.prototype.hasOwnProperty.call(websites, url)) continue;
                var entry = websites[url];
                if (!entry || (!entry.notes && !entry.title)) continue;
                notes.push({
                    url: url,
                    domain: domainOf(url),
                    title: entry.title || "",
                    content: entry.notes || "",
                    lastUpdate: entry["last-update"] || "",
                    color: entry["tag-colour"] || "none",
                    tags: entry["tags-text"] || [],
                    folder: entry["tag-folder"] || "",
                    type: typeof entry.type === "number" ? entry.type : 2
                });
            }
            return notes;
        }

        // ── Filtering & Sorting ──

        function matchesSearch(note, terms) {
            if (!terms.length) return true;
            var haystack = (note.url + " " + note.title + " " + note.folder + " " +
                note.tags.join(" ") + " " + note.content.replace(/<[^>]*>/g, "") + " " +
                formatDate(note.lastUpdate)).toLowerCase();
            for (var i = 0; i < terms.length; i++) {
                if (haystack.indexOf(terms[i]) !== -1) return true;
            }
            return false;
        }

        function matchesFilters(note) {
            if (activeFilters.type !== "" && String(note.type) !== activeFilters.type) return false;
            if (activeFilters.color !== "") {
                if (activeFilters.color === "none" && note.color !== "none") return false;
                if (activeFilters.color !== "none" && note.color !== activeFilters.color) return false;
            }
            if (activeFilters.folders.length > 0) {
                var folderMatch = false;
                for (var i = 0; i < activeFilters.folders.length; i++) {
                    if (activeFilters.folders[i] === "" && note.folder === "") { folderMatch = true; break; }
                    if (activeFilters.folders[i] === note.folder) { folderMatch = true; break; }
                }
                if (!folderMatch) return false;
            }
            if (activeFilters.tags.length > 0) {
                var tagMatch = false;
                for (var i = 0; i < activeFilters.tags.length; i++) {
                    if (note.tags.indexOf(activeFilters.tags[i]) !== -1) { tagMatch = true; break; }
                }
                if (!tagMatch) return false;
            }
            return true;
        }

        function sortNotes(notes, mode) {
            var sorted = notes.slice();
            if (mode === "date-new") {
                sorted.sort(function (a, b) { return b.lastUpdate.localeCompare(a.lastUpdate); });
            } else if (mode === "date-old") {
                sorted.sort(function (a, b) { return a.lastUpdate.localeCompare(b.lastUpdate); });
            } else if (mode === "name-az") {
                sorted.sort(function (a, b) { return (a.title || a.url).localeCompare(b.title || b.url); });
            } else if (mode === "name-za") {
                sorted.sort(function (a, b) { return (b.title || b.url).localeCompare(a.title || a.url); });
            } else if (mode === "url-az") {
                sorted.sort(function (a, b) { return a.url.localeCompare(b.url); });
            }
            return sorted;
        }

        // ── Render ──

        function makeNoteUrl(url) {
            if (url === "**global" || !url) return null;
            if (url.indexOf("http") !== 0) return "https://" + url;
            return url;
        }

        function renderNote(note) {
            var card = document.createElement("div");
            card.className = "note-card";

            if (note.color !== "none" && TAG_COLORS[note.color]) {
                card.classList.add("note-card--colored");
                card.style.setProperty("--note-color", TAG_COLORS[note.color]);
            }

            var header = document.createElement("div");
            header.className = "note-card-header";

            var badge = document.createElement("span");
            badge.className = "note-type-badge";
            if (note.type === 0) badge.classList.add("note-type-badge--global");
            else if (note.type === 1) badge.classList.add("note-type-badge--domain");
            badge.textContent = TYPE_LABELS[note.type] || TYPE_LABELS[2];
            header.appendChild(badge);

            var noteUrl = makeNoteUrl(note.url);
            if (noteUrl) {
                var urlLink = document.createElement("a");
                urlLink.className = "note-card-url";
                urlLink.href = noteUrl;
                urlLink.target = "_blank";
                urlLink.rel = "noopener";
                urlLink.textContent = displayUrl(note.url);
                header.appendChild(urlLink);
            } else {
                var urlSpan = document.createElement("span");
                urlSpan.className = "note-card-url";
                urlSpan.textContent = displayUrl(note.url);
                header.appendChild(urlSpan);
            }

            var actions = document.createElement("div");
            actions.className = "note-card-actions";

            var editBtn = document.createElement("button");
            editBtn.type = "button";
            editBtn.className = "note-card-action-btn";
            editBtn.title = STRINGS.edit;
            editBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
            editBtn.addEventListener("click", function () { openEditor(note.url); });
            actions.appendChild(editBtn);

            var copyBtn = document.createElement("button");
            copyBtn.type = "button";
            copyBtn.className = "note-card-action-btn";
            copyBtn.title = STRINGS.copy;
            copyBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
            copyBtn.addEventListener("click", function () {
                var tempDiv = document.createElement("div");
                tempDiv.innerHTML = sanitizeHtml(note.content);
                var text = tempDiv.textContent || tempDiv.innerText || "";
                navigator.clipboard.writeText(text).then(function () {
                    copyBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                    setTimeout(function () {
                        copyBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
                    }, 1500);
                });
            });
            actions.appendChild(copyBtn);

            var delBtn = document.createElement("button");
            delBtn.type = "button";
            delBtn.className = "note-card-action-btn note-card-action-btn--danger";
            delBtn.title = STRINGS.del;
            delBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>';
            delBtn.addEventListener("click", function () { deleteNote(note.url); });
            actions.appendChild(delBtn);

            header.appendChild(actions);
            card.appendChild(header);

            if (note.title) {
                var titleEl = document.createElement("div");
                titleEl.className = "note-card-title";
                titleEl.textContent = note.title;
                card.appendChild(titleEl);
            }

            var contentWrapper = document.createElement("div");
            contentWrapper.className = "note-card-content";
            var contentInner = document.createElement("div");
            contentInner.className = "note-card-content-inner";
            contentInner.innerHTML = sanitizeHtml(note.content);
            contentWrapper.appendChild(contentInner);
            card.appendChild(contentWrapper);

            var meta = document.createElement("div");
            meta.className = "note-card-meta";

            if (note.lastUpdate) {
                var dateSpan = document.createElement("span");
                dateSpan.className = "note-card-date";
                dateSpan.textContent = formatDate(note.lastUpdate);
                meta.appendChild(dateSpan);
            }

            if (note.folder) {
                var folderSpan = document.createElement("span");
                folderSpan.className = "note-card-folder";
                folderSpan.textContent = note.folder;
                meta.appendChild(folderSpan);
            }

            if (note.tags.length > 0) {
                var tagsSpan = document.createElement("span");
                tagsSpan.className = "note-card-tags";
                note.tags.forEach(function (tag) {
                    var chip = document.createElement("span");
                    chip.className = "note-tag-chip";
                    chip.textContent = tag;
                    tagsSpan.appendChild(chip);
                });
                meta.appendChild(tagsSpan);
            }

            card.appendChild(meta);

            requestAnimationFrame(function () {
                if (contentInner.scrollHeight > 144) {
                    contentWrapper.classList.add("note-card-content--clamped");
                    var toggle = document.createElement("button");
                    toggle.type = "button";
                    toggle.className = "note-card-toggle";
                    toggle.textContent = STRINGS.show_more;
                    toggle.addEventListener("click", function () {
                        var expanded = contentWrapper.classList.toggle("note-card-content--expanded");
                        toggle.textContent = expanded ? STRINGS.show_less : STRINGS.show_more;
                    });
                    card.insertBefore(toggle, meta);
                }
            });

            return card;
        }

        function renderAll() {
            var typedTerms = (searchEl.value || "").trim().toLowerCase();
            var searchTerms = searchChips.slice();
            if (typedTerms) searchTerms.push(typedTerms);
            var sortMode = sortEl.value;
            var filtered = allNotes.filter(function (n) {
                return matchesSearch(n, searchTerms) && matchesFilters(n);
            });
            var sorted = sortNotes(filtered, sortMode);

            listEl.innerHTML = "";
            statsEl.textContent = sorted.length + " " + (sorted.length === 1 ? "note" : STRINGS.count);

            var hasFilters = searchTerms.length > 0 || searchChips.length > 0 || activeFilters.type || activeFilters.color || activeFilters.folders.length > 0 || activeFilters.tags.length > 0;

            if (allNotes.length === 0) {
                emptyEl.classList.remove("hidden2");
                noResultsEl.classList.add("hidden2");
            } else if (sorted.length === 0) {
                emptyEl.classList.add("hidden2");
                noResultsEl.classList.remove("hidden2");
            } else {
                emptyEl.classList.add("hidden2");
                noResultsEl.classList.add("hidden2");

                // Group by domain
                var domainMap = {};
                var domainOrder = [];
                sorted.forEach(function (note) {
                    var d = note.domain;
                    if (!domainMap[d]) {
                        domainMap[d] = [];
                        domainOrder.push(d);
                    }
                    domainMap[d].push(note);
                });

                domainOrder.forEach(function (domain) {
                    var group = document.createElement("div");
                    group.className = "notes-domain-group";

                    var domainHeader = document.createElement("div");
                    domainHeader.className = "notes-domain-header";

                    var domainTitle = document.createElement("span");
                    domainTitle.className = "notes-domain-title";
                    if (domain === "**global") {
                        domainTitle.textContent = STRINGS.global_notes;
                    } else {
                        domainTitle.textContent = domain;
                    }

                    var domainCount = document.createElement("span");
                    domainCount.className = "notes-domain-count";
                    domainCount.textContent = domainMap[domain].length;

                    var domainToggle = document.createElement("button");
                    domainToggle.type = "button";
                    domainToggle.className = "notes-domain-toggle";
                    domainToggle.setAttribute("aria-expanded", "true");
                    domainToggle.appendChild(domainTitle);
                    domainToggle.appendChild(domainCount);
                    domainHeader.appendChild(domainToggle);

                    var domainBody = document.createElement("div");
                    domainBody.className = "notes-domain-body";

                    domainToggle.addEventListener("click", function () {
                        var expanded = domainToggle.getAttribute("aria-expanded") === "true";
                        domainToggle.setAttribute("aria-expanded", String(!expanded));
                        domainBody.classList.toggle("hidden2", expanded);
                    });

                    domainMap[domain].forEach(function (note) {
                        domainBody.appendChild(renderNote(note));
                    });

                    group.appendChild(domainHeader);
                    group.appendChild(domainBody);
                    listEl.appendChild(group);
                });
            }
        }

        function renderSearchChips() {
            var existing = searchWrap.querySelectorAll(".notes-search-chip");
            for (var i = existing.length - 1; i >= 0; i--) existing[i].parentNode.removeChild(existing[i]);
            searchChips.forEach(function (term, idx) {
                var chip = document.createElement("span");
                chip.className = "notes-search-chip";
                chip.textContent = term;
                var btn = document.createElement("button");
                btn.type = "button";
                btn.className = "notes-search-chip-remove";
                btn.innerHTML = "&times;";
                btn.addEventListener("click", function () {
                    searchChips.splice(idx, 1);
                    renderSearchChips();
                    renderAll();
                });
                chip.appendChild(btn);
                searchWrap.insertBefore(chip, searchEl);
            });
            searchEl.placeholder = searchChips.length ? "" : <?php echo json_encode(t('account.notes_search'), JSON_UNESCAPED_UNICODE); ?>;
        }

        searchEl.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                var val = searchEl.value.trim();
                if (val) {
                    e.preventDefault();
                    if (searchChips.indexOf(val.toLowerCase()) === -1) {
                        searchChips.push(val.toLowerCase());
                    }
                    searchEl.value = "";
                    renderSearchChips();
                    renderAll();
                    searchEl.focus();
                }
            } else if (e.key === "Backspace" && searchEl.value === "" && searchChips.length > 0) {
                searchChips.pop();
                renderSearchChips();
                renderAll();
                searchEl.focus();
            }
        });

        searchEl.addEventListener("input", renderAll);

        searchWrap.addEventListener("click", function () { searchEl.focus(); });

        sortEl.addEventListener("change", renderAll);

        // ── Delete ──

        function deleteNote(url) {
            if (!confirm(STRINGS.del_confirm)) return;

            notefoxAuthenticatedApi("/data/get", {
                "service": NOTEFOX_DEFAULT_SERVICE
            }).then(function (freshData) {
                var snapshot;
                var revision = freshData["revision"] || null;
                try {
                    snapshot = freshData["data"] ? JSON.parse(freshData["data"]) : { websites: {} };
                } catch (e) {
                    throw { code: 0, message: "Parse error" };
                }
                if (!snapshot.websites) snapshot.websites = {};

                delete snapshot.websites[url];
                snapshot["last-update"] = nowDateString();

                var payload = {
                    "service": NOTEFOX_DEFAULT_SERVICE,
                    "updated-locally": snapshot["last-update"],
                    "data": JSON.stringify(snapshot)
                };
                if (revision != null) payload["base-revision"] = revision;

                return notefoxAuthenticatedApi("/data/insert", payload);
            }).then(function (result) {
                currentRevision = result["revision"] || currentRevision;
                lastUpdate = "";
                return fetchNotes();
            }).catch(function (error) {
                if (error && error.code === 409) {
                    showFormMessage("notes-message", STRINGS.conflict, true);
                } else {
                    showFormMessage("notes-message", STRINGS.save_error, true);
                }
            });
        }

        // ── Polling ──

        var lastUpdate = "";
        var pollTimer = null;
        var POLL_INTERVAL = 10000;

        function fetchNotes() {
            return notefoxAuthenticatedApi("/data/get", {
                "service": NOTEFOX_DEFAULT_SERVICE
            }).then(function (data) {
                hideLoading();
                containerEl.classList.remove("hidden2");

                if (!data || !data["data"]) {
                    allNotes = [];
                    currentSnapshot = null;
                    currentRevision = data ? data["revision"] : null;
                    lastUpdate = "";
                    populateFilterOptions();
                    renderAll();
                    return;
                }

                currentRevision = data["revision"] || null;

                try {
                    var snapshot = JSON.parse(data["data"]);
                    var newLastUpdate = snapshot["last-update"] || "";
                    if (newLastUpdate === lastUpdate) return;
                    lastUpdate = newLastUpdate;
                    currentSnapshot = snapshot;
                    allNotes = parseNotes(snapshot.websites || {});
                } catch (e) {
                    showFormMessage("notes-message", "Could not parse synced data.", true);
                    return;
                }

                populateFilterOptions();
                renderAll();
            });
        }

        function startPolling() {
            if (pollTimer) return;
            pollTimer = setInterval(function () {
                if (document.hidden || editorOpen) return;
                fetchNotes().catch(function () {});
            }, POLL_INTERVAL);
        }

        document.addEventListener("visibilitychange", function () {
            if (!document.hidden && lastUpdate !== "" && !editorOpen) {
                fetchNotes().catch(function () {});
            }
        });

        // ── Editor ──

        var editorOpen = false;
        var editingUrl = null;
        var editorTags = [];

        var overlayEl = document.getElementById("note-editor-overlay");
        var editorTitleEl = document.getElementById("editor-title");
        var editorContentEl = document.getElementById("editor-content");
        var editorFolderEl = document.getElementById("editor-folder");
        var editorTagInput = document.getElementById("editor-tag-input");
        var editorTagsList = document.getElementById("editor-tags-list");
        var editorColorGrid = document.getElementById("editor-color-grid");
        var editorSaveBtn = document.getElementById("editor-save");
        var editorCancelBtn = document.getElementById("editor-cancel");
        var editorCloseBtn = document.getElementById("editor-close");
        var editorColorDot = document.getElementById("editor-color-dot");
        var editorUrlEl = document.getElementById("editor-url");
        var editorLastUpdate = document.getElementById("editor-last-update");
        var editorDetailsEl = document.getElementById("editor-details");

        editorContentEl.setAttribute("data-placeholder", <?php echo json_encode(t('account.notes_content_placeholder'), JSON_UNESCAPED_UNICODE); ?>);

        var selectedColor = "none";

        function buildColorGrid() {
            var noneBtn = document.createElement("button");
            noneBtn.type = "button";
            noneBtn.className = "notes-chip notes-chip--active";
            noneBtn.dataset.color = "none";
            noneBtn.textContent = <?php echo json_encode(t('account.notes_filter_no_color'), JSON_UNESCAPED_UNICODE); ?>;
            noneBtn.addEventListener("click", function () { selectColor("none"); });
            editorColorGrid.appendChild(noneBtn);

            COLOR_NAMES_SORTED.forEach(function (name) {
                var btn = document.createElement("button");
                btn.type = "button";
                btn.className = "notes-chip notes-chip--color";
                btn.dataset.color = name;
                btn.style.setProperty("--chip-color", TAG_COLORS[name]);
                btn.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                btn.addEventListener("click", function () { selectColor(name); });
                editorColorGrid.appendChild(btn);
            });
        }
        buildColorGrid();

        function selectColor(name) {
            selectedColor = name;
            var chips = editorColorGrid.querySelectorAll(".notes-chip");
            for (var i = 0; i < chips.length; i++) {
                chips[i].classList.toggle("notes-chip--active", chips[i].dataset.color === name);
            }
            if (name !== "none" && TAG_COLORS[name]) {
                editorColorDot.style.background = TAG_COLORS[name];
                editorColorDot.style.borderColor = TAG_COLORS[name];
            } else {
                editorColorDot.style.background = "";
                editorColorDot.style.borderColor = "";
            }
        }

        function renderEditorTags() {
            editorTagsList.innerHTML = "";
            editorTags.forEach(function (tag, idx) {
                var chip = document.createElement("span");
                chip.className = "note-tag-chip note-tag-chip--removable";
                chip.textContent = tag;
                var removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.className = "note-tag-remove";
                removeBtn.innerHTML = "&times;";
                removeBtn.addEventListener("click", function () {
                    editorTags.splice(idx, 1);
                    renderEditorTags();
                });
                chip.appendChild(removeBtn);
                editorTagsList.appendChild(chip);
            });
        }

        function addEditorTag() {
            var val = editorTagInput.value.trim();
            if (val && editorTags.indexOf(val) === -1) {
                editorTags.push(val);
                renderEditorTags();
            }
            editorTagInput.value = "";
        }

        document.getElementById("editor-tag-add").addEventListener("click", addEditorTag);
        editorTagInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") { e.preventDefault(); addEditorTag(); }
        });

        document.getElementById("editor-toolbar").addEventListener("click", function (e) {
            var btn = e.target.closest("button");
            if (!btn) return;
            var cmd = btn.dataset.cmd;
            if (cmd) {
                document.execCommand(cmd, false, null);
                updateToolbarState();
                editorContentEl.focus();
                return;
            }
            if (btn.dataset.action === "link") {
                var url = prompt("URL:");
                if (url) document.execCommand("createLink", false, url);
                editorContentEl.focus();
            }
        });

        function updateToolbarState() {
            var toolbarBtns = document.querySelectorAll("#editor-toolbar button[data-cmd]");
            for (var i = 0; i < toolbarBtns.length; i++) {
                var cmd = toolbarBtns[i].dataset.cmd;
                if (cmd === "bold" || cmd === "italic" || cmd === "underline" || cmd === "strikeThrough"
                    || cmd === "insertUnorderedList" || cmd === "insertOrderedList") {
                    toolbarBtns[i].classList.toggle("active", document.queryCommandState(cmd));
                }
            }
        }

        editorContentEl.addEventListener("keyup", updateToolbarState);
        editorContentEl.addEventListener("mouseup", updateToolbarState);

        editorContentEl.addEventListener("keydown", function (e) {
            if ((e.ctrlKey || e.metaKey) && !e.shiftKey && !e.altKey) {
                var key = e.key.toLowerCase();
                if (key === "b" || key === "i" || key === "u") {
                    setTimeout(updateToolbarState, 0);
                }
            }
        });

        editorContentEl.addEventListener("paste", function (e) {
            e.preventDefault();
            var text = (e.clipboardData || window.clipboardData).getData("text/plain");
            document.execCommand("insertText", false, text);
        });

        function openEditor(url) {
            editingUrl = url;
            editorOpen = true;

            var entry = currentSnapshot && currentSnapshot.websites ? currentSnapshot.websites[url] : null;
            editorTitleEl.value = entry ? (entry.title || "") : "";
            editorContentEl.innerHTML = entry ? (entry.notes || "") : "";
            editorFolderEl.value = entry ? (entry["tag-folder"] || "") : "";
            editorTags = entry && entry["tags-text"] ? entry["tags-text"].slice() : [];
            selectColor(entry ? (entry["tag-colour"] || "none") : "none");
            renderEditorTags();

            editorUrlEl.value = url === "**global" ? "**global" : url;
            var lastUpd = entry ? (entry["last-update"] || "") : "";
            editorLastUpdate.textContent = lastUpd ? formatDate(lastUpd) : "";

            var hasMetadata = (entry && (entry["tag-colour"] && entry["tag-colour"] !== "none"))
                || (entry && entry["tag-folder"])
                || (entry && entry["tags-text"] && entry["tags-text"].length > 0);
            editorDetailsEl.open = hasMetadata;

            hideFormMessage("editor-message");
            editorSaveBtn.disabled = false;
            editorSaveBtn.textContent = STRINGS.save;
            overlayEl.classList.remove("hidden2");
            document.body.style.overflow = "hidden";
            editorTitleEl.focus();
        }

        function closeEditor() {
            editorOpen = false;
            editingUrl = null;
            overlayEl.classList.add("hidden2");
            document.body.style.overflow = "";
        }

        editorCancelBtn.addEventListener("click", closeEditor);
        editorCloseBtn.addEventListener("click", closeEditor);
        overlayEl.addEventListener("click", function (e) {
            if (e.target === overlayEl) closeEditor();
        });
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && editorOpen) closeEditor();
        });

        editorSaveBtn.addEventListener("click", function () {
            if (editorSaveBtn.disabled) return;
            editorSaveBtn.disabled = true;
            editorSaveBtn.textContent = STRINGS.saving;
            hideFormMessage("editor-message");

            notefoxAuthenticatedApi("/data/get", {
                "service": NOTEFOX_DEFAULT_SERVICE
            }).then(function (freshData) {
                var snapshot;
                var revision = freshData["revision"] || null;
                try {
                    snapshot = freshData["data"] ? JSON.parse(freshData["data"]) : {
                        "notefox": {}, "settings": {}, "websites": {},
                        "sticky-notes": {}, "storage": "", "last-update": ""
                    };
                } catch (e) {
                    throw { code: 0, message: "Parse error" };
                }

                if (!snapshot.websites) snapshot.websites = {};

                var now = nowDateString();
                var newUrl = editorUrlEl.value.trim();
                if (!newUrl) newUrl = editingUrl;
                var entry = snapshot.websites[editingUrl] || {};

                entry.title = editorTitleEl.value.trim();
                entry.notes = editorContentEl.innerHTML;
                entry["last-update"] = now;
                entry["tag-colour"] = selectedColor;
                entry["tags-text"] = editorTags.slice();
                entry["tag-folder"] = editorFolderEl.value.trim();
                if (typeof entry.type !== "number") entry.type = 2;

                if (newUrl !== editingUrl) {
                    delete snapshot.websites[editingUrl];
                    entry.domain = domainOf(newUrl);
                    if (newUrl === "**global") entry.type = 0;
                    else if (newUrl.indexOf("/", newUrl.indexOf("//") + 2) === -1 ||
                             newUrl.endsWith("/")) entry.type = 1;
                    else entry.type = 2;
                }

                if (!entry.notes && !entry.title) {
                    delete snapshot.websites[newUrl];
                } else {
                    snapshot.websites[newUrl] = entry;
                }

                snapshot["last-update"] = now;

                var payload = {
                    "service": NOTEFOX_DEFAULT_SERVICE,
                    "updated-locally": now,
                    "data": JSON.stringify(snapshot)
                };
                if (revision != null) payload["base-revision"] = revision;

                return notefoxAuthenticatedApi("/data/insert", payload);
            }).then(function (result) {
                currentRevision = result["revision"] || currentRevision;
                lastUpdate = "";
                return fetchNotes();
            }).then(function () {
                closeEditor();
            }).catch(function (error) {
                editorSaveBtn.disabled = false;
                editorSaveBtn.textContent = STRINGS.save;
                if (error && error.code === 409) {
                    showFormMessage("editor-message", STRINGS.conflict, true);
                } else {
                    showFormMessage("editor-message", STRINGS.save_error, true);
                }
            });
        });

        // ── Init ──

        notefoxAuthenticatedApi("/data/services", {}).then(function (data) {
            if (!data["pro-features"]) {
                hideLoading();
                showFormMessage("notes-message", <?php echo json_encode(t('account.notes_pro_required'), JSON_UNESCAPED_UNICODE); ?>, true);
                var msgEl = document.getElementById("notes-message");
                var helpLink = document.createElement("a");
                helpLink.href = "/help/pro-features/";
                helpLink.className = "form-message-link";
                helpLink.textContent = <?php echo json_encode(t('account.notes_pro_learn_more'), JSON_UNESCAPED_UNICODE); ?>;
                msgEl.appendChild(helpLink);
                return;
            }

            return fetchNotes().then(function () {
                startPolling();
            });
        }).catch(function (error) {
            hideLoading();
            if (error.code === 201) {
                containerEl.classList.remove("hidden2");
                emptyEl.classList.remove("hidden2");
                statsEl.textContent = "0 " + STRINGS.count;
                startPolling();
            } else {
                showFormMessage("notes-message", notefoxErrorMessage(error), true);
            }
        });
    });
</script>
</body>
</html>
