/**
 * Notefox Account ("my/*" pages) - shared client module.
 *
 * Talks exclusively to the api/v2 JSON endpoints (login-id/token auth), keeps
 * the session in sessionStorage (cleared on tab close or logout) and never
 * stores the plaintext password anywhere.
 */

var NOTEFOX_API_BASE = "/api/v2";
var NOTEFOX_SESSION_KEY = "notefox-account-session";
var NOTEFOX_LOGIN_MESSAGE_KEY = "notefox-login-message";
var NOTEFOX_DEFAULT_SERVICE = "notefox";

/**
 * Calls a v2 endpoint and resolves with `data` on success, or rejects with an
 * Error carrying `.code` and `.data` (the raw error payload) on failure.
 */
function notefoxApi(path, body) {
    // The endpoints are directories (api/v2/<endpoint>/index.php): always call
    // them with the trailing slash, otherwise Apache answers with a 301 that
    // turns the POST into a GET and drops the body.
    var url = NOTEFOX_API_BASE + path;
    if (url.charAt(url.length - 1) !== "/") {
        url += "/";
    }
    return fetch(url, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(body || {})
    }).then(function (response) {
        return response.json().catch(function () {
            return null;
        }).then(function (json) {
            if (json === null) {
                var networkError = new Error("Communication error with the server.");
                networkError.code = null;
                networkError.data = null;
                throw networkError;
            }
            if (json.status !== "Successful") {
                var apiError = new Error(json.description || "Unknown error.");
                apiError.code = json.code;
                apiError.data = json.data;
                if (json.code === 402 || json.code === 404 || json.code === 405) {
                    var message = notefoxErrorMessage(apiError);
                    setLoginMessage(message);
                    clearSession();
                    if (window.location.pathname !== "/my/login/" && window.location.pathname !== "/my/login") {
                        window.location.href = "/my/login/";
                    }
                }
                throw apiError;
            }
            return json.data;
        });
    });
}

/**
 * Same as notefoxApi(), but automatically adds the login-id/token of the
 * stored session to the body.
 */
function notefoxAuthenticatedApi(path, body) {
    var session = getSession();
    if (session === null) {
        var error = new Error("No active session.");
        error.code = null;
        error.data = null;
        return Promise.reject(error);
    }
    var payload = {};
    for (var key in body || {}) {
        if (Object.prototype.hasOwnProperty.call(body, key)) {
            payload[key] = body[key];
        }
    }
    payload["login-id"] = session["login-id"];
    payload["token"] = session["token"];
    return notefoxApi(path, payload);
}

/**
 * Persists login-id/token/username, never the password.
 */
function saveSession(session) {
    sessionStorage.setItem(NOTEFOX_SESSION_KEY, JSON.stringify({
        "login-id": session["login-id"],
        "token": session["token"],
        "username": session["username"] || null
    }));
}

function getSession() {
    var raw = sessionStorage.getItem(NOTEFOX_SESSION_KEY);
    if (!raw) {
        return null;
    }
    try {
        var session = JSON.parse(raw);
        if (!session || !session["login-id"] || !session["token"]) {
            return null;
        }
        return session;
    } catch (e) {
        return null;
    }
}

function clearSession() {
    sessionStorage.removeItem(NOTEFOX_SESSION_KEY);
}

function setLoginMessage(message) {
    if (message) {
        sessionStorage.setItem(NOTEFOX_LOGIN_MESSAGE_KEY, message);
    }
}

function consumeLoginMessage() {
    var message = sessionStorage.getItem(NOTEFOX_LOGIN_MESSAGE_KEY);
    if (message) {
        sessionStorage.removeItem(NOTEFOX_LOGIN_MESSAGE_KEY);
        return message;
    }
    return null;
}

function hasSession() {
    return getSession() !== null;
}

/**
 * Logs the current session out server-side (POST /logout) and always clears
 * the local session afterwards, even when the API call fails (an already
 * expired token must not leave the user stuck on a guarded page).
 */
function logoutSession() {
    return notefoxAuthenticatedApi("/logout", {}).catch(function () {
        // Ignored: the local session is cleared unconditionally below.
    }).then(function () {
        clearSession();
    });
}

/**
 * Guard for every page under my/account/**: redirects to my/login/ when no
 * session is present and returns null, or returns the session otherwise.
 */
function requireSession() {
    var session = getSession();
    if (session === null) {
        location.href = "/my/login/";
        return null;
    }
    return session;
}

/**
 * Generic error/success message rendering, reusing the .form-message classes.
 */
function showFormMessage(elementId, message, isError) {
    var element = document.getElementById(elementId);
    if (!element) {
        return;
    }
    element.textContent = message;
    element.classList.remove("hidden2", "form-message--error", "form-message--success");
    element.classList.add(isError ? "form-message--error" : "form-message--success");
}

function hideFormMessage(elementId) {
    var element = document.getElementById(elementId);
    if (!element) {
        return;
    }
    element.textContent = "";
    element.classList.add("hidden2");
}

/**
 * Readable message for an API error, with the most common codes translated.
 */
function notefoxErrorMessage(error) {
    if (error && (error.code === 402 || error.code === 404 || error.code === 405)) {
        clearSession();
    }
    var messages = {
        400: "Missing or invalid data.",
        401: "The server could not read your data right now, please try again later.",
        402: "Invalid session, please log in again.",
        403: "User not found.",
        404: "Session expired, please log in again.",
        405: "Invalid session, please log in again.",
        409: "This operation is not allowed right now.",
        410: "Invalid credentials.",
        411: "Account not active or not verified.",
        412: "The verification code has expired.",
        413: "Invalid verification code.",
        414: "Account already verified.",
        415: "No verification code has been requested.",
        419: "Signup not completed yet.",
        420: "Too many invalid attempts, request a new code.",
        429: "Too many requests, please try again later.",
        430: "Encryption key not available for this account.",
        431: "The two-step verification is already in the requested state.",
        432: "Sync history is not available for this service.",
        433: "The sync history is not enabled for your account.",
        452: "A code has already been sent: please check your inbox or try again later.",
        500: "Something went wrong on our side, please try again later.",
        503: "This operation is temporarily unavailable, please try again later."
    };
    if (error && error.code && messages[error.code]) {
        return messages[error.code];
    }
    return (error && error.message) ? error.message : "Unknown error.";
}

/**
 * Triggers a client-side JSON file download of a plaintext notes payload.
 */
function downloadNotesJson(filenamePrefix, plainData) {
    var content = plainData;
    try {
        content = JSON.stringify(JSON.parse(plainData), null, 2);
    } catch (e) {
        // Not valid JSON: download the raw string as-is.
    }
    var blob = new Blob([content], {type: "application/json"});
    var url = URL.createObjectURL(blob);
    var link = document.createElement("a");
    link.href = url;
    link.download = filenamePrefix + ".json";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
