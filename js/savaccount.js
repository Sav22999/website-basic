/**
 * Notefox Account ("my/*" pages) - "Continue with SavAccount".
 *
 * Needs js/account.js. The texts come from the page (include/savaccount.php).
 *
 * The sign-in belongs to the tab that started it: the secret of the flow stays
 * in its sessionStorage, so a callback URL opened anywhere else is worthless.
 */

var NOTEFOX_SAVACCOUNT_KEY = "notefox-savaccount";
var NOTEFOX_SAVACCOUNT_BUTTON = "https://www.savaccount.eu/button/v1/savaccount.js";

function savaccountText(key, values) {
    var text = window.NOTEFOX_SAVACCOUNT_TEXT[key] || key;
    for (var name in values || {}) {
        text = text.split("{" + name + "}").join(values[name]);
    }
    return text;
}

function savaccountErrorMessage(error) {
    var keys = {440: "unavailable", 441: "failed", 442: "expired", 443: "in_use"};
    if (error && keys[error.code]) {
        return savaccountText(keys[error.code]);
    }
    if (error && error.code === 410 && error.data && typeof error.data["attempts-left"] === "number") {
        return savaccountText("wrong_password", {n: error.data["attempts-left"]});
    }
    return notefoxErrorMessage(error);
}

/**
 * Starts a sign-in and leaves the page. $extra: the fields of a "connect".
 */
function savaccountStart(mode, extra) {
    var body = {"mode": mode, "lang": document.documentElement.lang};
    for (var key in extra || {}) {
        body[key] = extra[key];
    }
    var call = mode === "connect" ? notefoxAuthenticatedApi : notefoxApi;
    return call("/savaccount/start", body).then(function (data) {
        sessionStorage.setItem(NOTEFOX_SAVACCOUNT_KEY, JSON.stringify({
            "flow": data["flow"],
            "state": data["state"],
            "mode": mode
        }));
        location.href = data["url"];
    });
}

function savaccountIsDark() {
    var theme = document.documentElement.getAttribute("data-theme");
    if (theme === "light" || theme === "dark") {
        return theme === "dark";
    }
    return !(window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches);
}

/**
 * Fills a `.savaccount-box` with the official button, only when this
 * installation has the sign-in configured. A plain button is inside the
 * element, so it works even when the script of savaccount.eu does not load.
 */
function savaccountShowButton(box, onError) {
    notefoxApi("/savaccount/status", {}).then(function (data) {
        if (!data["enabled"]) {
            return;
        }

        var element = document.createElement("savaccount-button");
        var plain = document.createElement("button");
        plain.type = "button";
        plain.className = "btn btn--secondary btn--block";
        plain.textContent = savaccountText("button");
        element.setAttribute("block", "");
        element.setAttribute("shape", "rounded");
        element.setAttribute("lang", document.documentElement.lang);
        element.setAttribute("theme", savaccountIsDark() ? "dark" : "light");
        element.appendChild(plain);

        function go(event) {
            event.preventDefault();
            element.setAttribute("disabled", "");
            plain.disabled = true;
            savaccountStart("login").catch(function (error) {
                element.removeAttribute("disabled");
                plain.disabled = false;
                onError(savaccountErrorMessage(error));
            });
        }

        element.addEventListener("savaccount-start", go);
        plain.addEventListener("click", go);

        // The theme can be switched from the menu while the page is open, and
        // a page restored by the "back" button must not stay disabled.
        new MutationObserver(function () {
            element.setAttribute("theme", savaccountIsDark() ? "dark" : "light");
        }).observe(document.documentElement, {attributes: true, attributeFilter: ["data-theme"]});
        window.addEventListener("pageshow", function () {
            element.removeAttribute("disabled");
            plain.disabled = false;
        });

        box.querySelector(".savaccount-slot").appendChild(element);
        box.classList.remove("hidden2");

        if (window.customElements && !customElements.get("savaccount-button")) {
            var script = document.createElement("script");
            script.src = NOTEFOX_SAVACCOUNT_BUTTON;
            script.async = true;
            script.referrerPolicy = "strict-origin";
            document.head.appendChild(script);
        }
    }).catch(function () {
        // Without an answer there is simply no button: email and password work.
    });
}

/**
 * The page SavAccount comes back to (/my/savaccount/).
 */
function savaccountFinish() {
    var query = new URLSearchParams(location.search);
    var pending = null;
    try {
        pending = JSON.parse(sessionStorage.getItem(NOTEFOX_SAVACCOUNT_KEY));
    } catch (e) {
        pending = null;
    }

    function show(name) {
        ["working", "elsewhere", "problem", "link", "signup"].forEach(function (step) {
            document.getElementById("savaccount-" + step).classList.toggle("hidden2", step !== name);
        });
    }

    function problem(message) {
        var account = pending && pending["mode"] === "connect" && hasSession();
        document.getElementById("savaccount-retry").href = account ? "/my/account/" : "/my/login/";
        showFormMessage("savaccount-problem-message", message, true);
        show("problem");
    }

    function signedIn(data) {
        saveSession({
            "login-id": data["login-id"],
            "token": data["token"],
            "username": data["username"],
            "permissions": data["permissions"] || 0
        });
        location.replace("/my/account/");
    }

    // Started by the extension (or in another tab): the code is not touched,
    // who started the sign-in completes it.
    if (!pending || !query.get("state") || query.get("state") !== pending["state"]) {
        show("elsewhere");
        return;
    }
    sessionStorage.removeItem(NOTEFOX_SAVACCOUNT_KEY);
    history.replaceState(null, "", location.pathname);

    if (query.get("error") || !query.get("code")) {
        problem(savaccountText(query.get("error") === "access_denied" ? "cancelled" : "failed"));
        return;
    }

    var body = {
        "flow": pending["flow"],
        "state": pending["state"],
        "code": query.get("code"),
        "source": "web"
    };
    if (query.get("iss")) {
        body["iss"] = query.get("iss");
    }

    var call = pending["mode"] === "connect" ? notefoxAuthenticatedApi : notefoxApi;
    call("/savaccount/finish", body).then(function (data) {
        if (data["result"] === "session") {
            signedIn(data);
        } else if (data["result"] === "connected") {
            sessionStorage.setItem(NOTEFOX_SAVACCOUNT_KEY + "-done", "1");
            location.replace("/my/account/");
        } else if (data["result"] === "signup-required") {
            signup(data);
        } else {
            link(data);
        }
    }).catch(function (error) {
        problem(savaccountErrorMessage(error));
    });

    function submit(formId, messageId, read) {
        var form = document.getElementById(formId);
        var button = form.querySelector("button[type=submit]");
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            hideFormMessage(messageId);
            var request = read();
            if (request === null) {
                return;
            }
            button.disabled = true;
            notefoxApi(request.path, request.body).then(signedIn).catch(function (error) {
                button.disabled = false;
                if (error.code === 442 || error.code === 443 || error.code === 414) {
                    problem(savaccountErrorMessage(error));
                    return;
                }
                showFormMessage(messageId, savaccountErrorMessage(error), true);
            });
        });
    }

    function link(data) {
        var changed = data["result"] === "password-required";
        document.getElementById("savaccount-link-heading").textContent = savaccountText(changed ? "changed_heading" : "link_heading");
        document.getElementById("savaccount-link-desc").textContent = changed ? savaccountText("changed_desc") : savaccountText("link_desc", {email: data["email"]});
        document.getElementById("savaccount-link-submit").textContent = changed ? window.NOTEFOX_SAVACCOUNT_TEXT["log_in"] : savaccountText("link_submit");
        document.getElementById("savaccount-link-email").value = data["email"] || "";
        submit("savaccount-link-form", "savaccount-link-message", function () {
            var password = document.getElementById("savaccount-link-password").value;
            return password === "" ? null : {
                path: "/savaccount/link",
                body: {"ticket": data["ticket"], "password": password, "source": "web"}
            };
        });
        show("link");
        document.getElementById("savaccount-link-password").focus();
    }

    function signup(data) {
        document.getElementById("savaccount-signup-desc").textContent = savaccountText("signup_desc", {email: data["email"]});
        document.getElementById("savaccount-signup-email").value = data["email"];
        document.getElementById("savaccount-signup-username").value = data["name"] || "";
        submit("savaccount-signup-form", "savaccount-signup-message", function () {
            var username = document.getElementById("savaccount-signup-username").value.trim();
            var password = document.getElementById("savaccount-signup-password").value;
            var message = null;
            if (username === "" || password === "") {
                message = "fill_all_fields";
            } else if (password.length < 8) {
                message = "password_too_short";
            } else if (password !== document.getElementById("savaccount-signup-confirm").value) {
                message = "passwords_dont_match";
            }
            if (message !== null) {
                showFormMessage("savaccount-signup-message", savaccountText(message), true);
                return null;
            }
            return {
                path: "/savaccount/signup",
                body: {"ticket": data["ticket"], "username": username, "password": password, "source": "web"}
            };
        });
        show("signup");
        document.getElementById("savaccount-signup-username").focus();
    }
}

/**
 * The SavAccount card of /my/account/.
 */
function savaccountCard() {
    var card = document.getElementById("savaccount-card");
    var status = document.getElementById("savaccount-status");
    var form = document.getElementById("savaccount-connect-form");
    var email = document.getElementById("savaccount-connect-email");
    var password = document.getElementById("savaccount-connect-password");
    var unlink = document.getElementById("savaccount-unlink");

    function draw(linked) {
        status.textContent = savaccountText(linked ? "linked" : "not_linked");
        status.classList.toggle("account-status--off", !linked);
        form.classList.toggle("hidden2", linked);
        unlink.classList.toggle("hidden2", !linked);
    }

    notefoxAuthenticatedApi("/savaccount/status", {}).then(function (data) {
        if (!data["enabled"] && !data["linked"]) {
            return;
        }
        draw(data["linked"]);
        card.classList.remove("hidden2");
        if (sessionStorage.getItem(NOTEFOX_SAVACCOUNT_KEY + "-done")) {
            sessionStorage.removeItem(NOTEFOX_SAVACCOUNT_KEY + "-done");
            showFormMessage("account-message", savaccountText("done_linked"), false);
        }
    }).catch(function () {
        // The card just stays hidden.
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        hideFormMessage("savaccount-card-message");
        if (email.value.trim() === "" || password.value === "") {
            return;
        }
        var button = form.querySelector("button");
        button.disabled = true;
        savaccountStart("connect", {"email": email.value.trim(), "password": password.value}).catch(function (error) {
            button.disabled = false;
            showFormMessage("savaccount-card-message", savaccountErrorMessage(error), true);
        });
        password.value = "";
    });

    unlink.addEventListener("click", function () {
        if (!window.confirm(savaccountText("unlink_confirm"))) {
            return;
        }
        unlink.disabled = true;
        hideFormMessage("savaccount-card-message");
        notefoxAuthenticatedApi("/savaccount/unlink", {}).then(function () {
            unlink.disabled = false;
            draw(false);
        }).catch(function (error) {
            unlink.disabled = false;
            showFormMessage("savaccount-card-message", savaccountErrorMessage(error), true);
        });
    });
}
