/**
 * Notefox Account - synced notes ("my/account/notes/"): the operations on the
 * snapshot, kept apart from the page so that they can be tested.
 *
 * The snapshot is the one written by every version of the extension, so:
 *   - nothing is converted: an entry in the old format (notes at the entry
 *     level, "notes-extra") stays in that format, one with "all-notes" too;
 *   - the newer sections ("websites-archive", "deleted-notes",
 *     "labels-registry") are optional, and only added when they are needed.
 *
 * The identity of a note and the registry of the deleted ones follow exactly
 * js/note-identity.js of the extension (same ids, same records): a note deleted
 * here is then deleted on the devices too, instead of coming back from them.
 */

var NotefoxSync = (function () {
    var DELETED_NOTES_KEY = "deleted-notes";
    var DELETED_NOTES_MAX = 5000;
    var DELETED_NOTES_MAX_AGE = 365 * 24 * 60 * 60 * 1000;
    var LEGACY_NOTE_FIELDS = ["notes", "title", "last-update", "content", "tag-colour", "tags-text", "tag-folder", "sticky", "minimized", "pinned", "coords", "sizes", "opacity", "minimized-pos"];

    function nowDateString() {
        var d = new Date();
        var pad = function (n) {
            return n < 10 ? "0" + n : "" + n;
        };
        return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate()) +
            " " + pad(d.getHours()) + ":" + pad(d.getMinutes()) + ":" + pad(d.getSeconds());
    }

    function dateToTime(value) {
        var time = value ? new Date(String(value).replace(" ", "T")).getTime() : NaN;
        return isNaN(time) ? 0 : time;
    }

    /* Identity (same algorithm of the extension) */

    function getNoteFingerprint(note) {
        return JSON.stringify([note["title"] || "", note["notes"] || "", note["content"] || "", note["last-update"] || ""]);
    }

    function hashNoteText(text) {
        var h1 = 0xdeadbeef, h2 = 0x41c6ce57;
        for (var i = 0; i < text.length; i++) {
            var code = text.charCodeAt(i);
            h1 = Math.imul(h1 ^ code, 2654435761);
            h2 = Math.imul(h2 ^ code, 1597334677);
        }
        h1 = Math.imul(h1 ^ (h1 >>> 16), 2246822507) ^ Math.imul(h2 ^ (h2 >>> 13), 3266489909);
        h2 = Math.imul(h2 ^ (h2 >>> 16), 2246822507) ^ Math.imul(h1 ^ (h1 >>> 13), 3266489909);
        return ("00000000" + (h2 >>> 0).toString(16)).slice(-8) + ("00000000" + (h1 >>> 0).toString(16)).slice(-8);
    }

    function getNoteIds(url, notes) {
        var used = {};
        notes.forEach(function (note) {
            if (note && note["note-id"]) used[note["note-id"]] = true;
        });
        return notes.map(function (note) {
            if (!note || typeof note !== "object") return null;
            if (note["note-id"]) return note["note-id"];
            var base = hashNoteText(url + "\n" + getNoteFingerprint(note));
            var id = base;
            for (var i = 2; used[id]; i++) id = base + "-" + i;
            used[id] = true;
            return id;
        });
    }

    /* Entries in both formats */

    function isNewFormat(entry) {
        return !!(entry && Array.isArray(entry["all-notes"]));
    }

    /**
     * The notes as the extension sees them after converting an old entry (the
     * primary note always first): needed for the ids
     */
    function migratedNotes(entry) {
        if (!entry) return [];
        if (isNewFormat(entry)) return entry["all-notes"];
        var primary = {};
        LEGACY_NOTE_FIELDS.forEach(function (field) {
            if (entry[field] !== undefined) primary[field] = entry[field];
        });
        var list = [primary];
        if (Array.isArray(entry["notes-extra"])) {
            entry["notes-extra"].forEach(function (extra) {
                list.push(extra);
            });
        }
        return list;
    }

    /**
     * The notes shown by the page: in an old entry an empty primary note is skipped
     */
    function visibleNotes(entry) {
        if (!entry) return [];
        if (isNewFormat(entry)) return entry["all-notes"];
        var list = migratedNotes(entry);
        if (!(entry.notes || entry.title)) list = list.slice(1);
        return list.filter(function (note) {
            return !!note;
        });
    }

    /**
     * From the position shown by the page to the position among migratedNotes()
     */
    function migratedIndex(entry, noteIndex) {
        if (isNewFormat(entry)) return noteIndex;
        return (entry.notes || entry.title) ? noteIndex : noteIndex + 1;
    }

    function getNoteIdAt(url, entry, noteIndex) {
        var notes = migratedNotes(entry);
        var index = migratedIndex(entry, noteIndex);
        return getNoteIds(url, notes)[index] || null;
    }

    /**
     * Remove a note from an entry (both formats); the entry is removed when it
     * has no note left. Returns a copy of the removed note, or null.
     */
    function removeNote(store, url, noteIndex) {
        var entry = store[url];
        if (!entry) return null;
        var removed = null;
        if (isNewFormat(entry)) {
            removed = entry["all-notes"][noteIndex] ? JSON.parse(JSON.stringify(entry["all-notes"][noteIndex])) : null;
            entry["all-notes"].splice(noteIndex, 1);
            if (entry["all-notes"].length === 0) delete store[url];
            return removed;
        }

        var extras = Array.isArray(entry["notes-extra"]) ? entry["notes-extra"] : [];
        var hasPrimary = !!(entry.notes || entry.title);
        if (noteIndex === 0 && hasPrimary) {
            removed = JSON.parse(JSON.stringify(migratedNotes(entry)[0]));
            //the first extra note becomes the primary one
            var promoted = extras.shift() || {};
            LEGACY_NOTE_FIELDS.forEach(function (field) {
                if (promoted[field] !== undefined) entry[field] = promoted[field];
                else delete entry[field];
            });
        } else {
            var i = noteIndex - (hasPrimary ? 1 : 0);
            removed = extras[i] ? JSON.parse(JSON.stringify(extras[i])) : null;
            extras.splice(i, 1);
        }
        if (extras.length === 0) delete entry["notes-extra"];
        else entry["notes-extra"] = extras;
        if (!entry.notes && !entry.title && !entry["notes-extra"]) delete store[url];
        return removed;
    }

    /* Registry of the deleted notes */

    function pruneDeletedNotes(deleted) {
        var oldest = Date.now() - DELETED_NOTES_MAX_AGE;
        var keys = Object.keys(deleted).filter(function (key) {
            return deleted[key] && typeof deleted[key] === "object" && dateToTime(deleted[key]["at"]) > oldest;
        }).sort(function (a, b) {
            return dateToTime(deleted[b]["at"]) - dateToTime(deleted[a]["at"]);
        }).slice(0, DELETED_NOTES_MAX).sort();
        var result = {};
        keys.forEach(function (key) {
            result[key] = deleted[key];
        });
        return result;
    }

    function recordDeleted(snapshot, url, note, id) {
        if (!note || !id) return;
        var deleted = snapshot[DELETED_NOTES_KEY] && typeof snapshot[DELETED_NOTES_KEY] === "object" ? snapshot[DELETED_NOTES_KEY] : {};
        deleted[url + "\n" + id] = {
            "version": note["last-update"] || "",
            "moves": Number(note["move-count"]) || 0,
            "at": nowDateString()
        };
        snapshot[DELETED_NOTES_KEY] = pruneDeletedNotes(deleted);
    }

    /**
     * Delete a note, recording it so that the devices delete it too
     * @returns {object|null} - the deleted note (to delete its screenshots)
     */
    function deleteNote(snapshot, storeKey, url, noteIndex) {
        var store = snapshot[storeKey];
        if (!store || !store[url]) return null;
        var id = getNoteIdAt(url, store[url], noteIndex);
        var note = removeNote(store, url, noteIndex);
        recordDeleted(snapshot, url, note, id);
        return note;
    }

    /**
     * A whole entry is deleted (as it was before any change): all its notes
     */
    function recordEntryDeleted(snapshot, url, entry) {
        var notes = migratedNotes(entry);
        var ids = getNoteIds(url, notes);
        notes.forEach(function (note, index) {
            if (note && (note.notes || note.title)) recordDeleted(snapshot, url, note, ids[index]);
        });
    }

    function markMoved(note) {
        note["moved-at"] = nowDateString();
        note["move-count"] = (Number(note["move-count"]) || 0) + 1;
        return note;
    }

    /**
     * Move a note to the archive or back ("websites" ↔ "websites-archive"),
     * keeping its identity and recording the move, as the extension does
     */
    function moveNote(snapshot, fromKey, toKey, url, noteIndex) {
        var source = snapshot[fromKey];
        if (!source || !source[url]) return false;
        var entry = source[url];
        var id = getNoteIdAt(url, entry, noteIndex);
        var note = removeNote(source, url, noteIndex);
        if (note === null) return false;
        note["note-id"] = note["note-id"] || id;
        if (note["tags-text"] === undefined) note["tags-text"] = [];
        if (note["tag-folder"] === undefined) note["tag-folder"] = "";
        markMoved(note);

        if (!snapshot[toKey] || typeof snapshot[toKey] !== "object") snapshot[toKey] = {};
        var target = snapshot[toKey];
        if (isNewFormat(target[url])) {
            target[url]["all-notes"].push(note);
        } else if (target[url]) {
            //an old entry is given the new format only here, in the archive (read by the new versions only)
            var notes = migratedNotes(target[url]);
            var kept = {type: target[url].type, domain: target[url].domain};
            target[url] = kept;
            kept["all-notes"] = notes.concat([note]);
        } else {
            target[url] = {"all-notes": [note]};
            if (entry.type !== undefined) target[url].type = entry.type;
            if (entry.domain !== undefined) target[url].domain = entry.domain;
        }
        return true;
    }

    /**
     * The url of an entry changes: the notes are deleted from the old url for the
     * devices, and moved (a note with an id keeps it, and may come back later)
     */
    /**
     * The notes of an entry leave their url (the url has been changed): as the extension does,
     * each note keeps the id of its old fingerprint, the old url records them as deleted
     * and the notes are marked as moved
     * @param entryBefore - the entry before the changes (its fingerprints give the ids)
     * @param entryAfter - the entry saved at the new url
     */
    function recordUrlChange(snapshot, oldUrl, entryBefore, entryAfter) {
        var before = migratedNotes(entryBefore);
        var ids = getNoteIds(oldUrl, before);
        before.forEach(function (note, index) {
            if (note) recordDeleted(snapshot, oldUrl, note, ids[index]);
        });
        // the old format has no place for the ids: it is never converted here
        if (!entryAfter || !isNewFormat(entryAfter)) return;
        entryAfter["all-notes"].forEach(function (note, index) {
            if (!note || typeof note !== "object") return;
            if (!note["note-id"] && ids[index]) note["note-id"] = ids[index];
            markMoved(note);
        });
    }

    /* Labels (same registry of the extension) */

    function normalizeRegistry(registry) {
        var out = {tags: [], folders: [], changes: {tags: {}, folders: {}}};
        if (!registry || typeof registry !== "object") return out;
        ["tags", "folders"].forEach(function (kind) {
            var seen = {};
            (Array.isArray(registry[kind]) ? registry[kind] : []).forEach(function (item) {
                if (typeof item !== "string" || !item.trim()) return;
                var value = kind === "tags" ? item.trim().toLowerCase() : item.trim();
                if (!seen[value]) {
                    seen[value] = true;
                    out[kind].push(value);
                }
            });
            out[kind].sort();
            var changes = registry.changes && registry.changes[kind];
            if (changes && typeof changes === "object") {
                Object.keys(changes).forEach(function (name) {
                    if (changes[name] && typeof changes[name]["at"] === "string") out.changes[kind][name] = {at: changes[name]["at"], deleted: changes[name]["deleted"] === true};
                });
            }
        });
        return out;
    }

    /**
     * Add the folder and the tags of a note to the registry, when they are new.
     * The registry is created only if something has to be added.
     */
    function addLabels(snapshot, folder, tags) {
        var registry = normalizeRegistry(snapshot["labels-registry"]);
        var changed = false;
        var add = function (kind, name) {
            var value = kind === "tags" ? String(name || "").trim().toLowerCase() : String(name || "").trim();
            if (value === "" || registry[kind].indexOf(value) !== -1) return;
            registry[kind].push(value);
            registry[kind].sort();
            registry.changes[kind][value] = {at: nowDateString(), deleted: false};
            changed = true;
        };
        add("folders", folder);
        (tags || []).forEach(function (tag) {
            add("tags", tag);
        });
        if (changed) snapshot["labels-registry"] = registry;
        return changed;
    }

    function getRegistryLabels(snapshot) {
        return normalizeRegistry(snapshot && snapshot["labels-registry"]);
    }

    /* Screenshots */

    function getNoteAttachments(note) {
        if (!note || !Array.isArray(note["attachments"])) return [];
        return note["attachments"].filter(function (item) {
            return item && typeof item === "object" && typeof item["id"] === "string" && /^[A-Za-z0-9-]{8,64}$/.test(item["id"]);
        });
    }

    /**
     * Remove a screenshot from the note which has it (in any store and format)
     * @returns {boolean} - true if it was found
     */
    function removeAttachment(snapshot, attachmentId) {
        var found = false;
        ["websites", "websites-archive"].forEach(function (key) {
            var store = snapshot[key];
            if (!store || typeof store !== "object") return;
            Object.keys(store).forEach(function (url) {
                migratedNotes(store[url]).forEach(function (note) {
                    if (!note || !getNoteAttachments(note).some(function (item) {
                        return item.id === attachmentId;
                    })) return;
                    note["attachments"] = getNoteAttachments(note).filter(function (item) {
                        return item.id !== attachmentId;
                    });
                    note["last-update"] = nowDateString();
                    found = true;
                });
                //an old entry keeps its primary note at the entry level
                if (!isNewFormat(store[url]) && Array.isArray(store[url]["attachments"])) {
                    var before = store[url]["attachments"].length;
                    store[url]["attachments"] = getNoteAttachments(store[url]).filter(function (item) {
                        return item.id !== attachmentId;
                    });
                    if (store[url]["attachments"].length !== before) {
                        store[url]["last-update"] = nowDateString();
                        found = true;
                    }
                }
            });
        });
        return found;
    }

    return {
        nowDateString: nowDateString,
        hashNoteText: hashNoteText,
        getNoteIds: getNoteIds,
        migratedNotes: migratedNotes,
        visibleNotes: visibleNotes,
        removeNote: removeNote,
        deleteNote: deleteNote,
        moveNote: moveNote,
        recordUrlChange: recordUrlChange,
        recordEntryDeleted: recordEntryDeleted,
        addLabels: addLabels,
        getRegistryLabels: getRegistryLabels,
        getNoteAttachments: getNoteAttachments,
        removeAttachment: removeAttachment
    };
})();

if (typeof module !== "undefined") module.exports = NotefoxSync;
