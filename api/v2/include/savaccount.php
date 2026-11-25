<?php
/**
 * Sav Account API v2 - "Continue with SavAccount" (OpenID Connect).
 *
 * SavAccount (https://www.savaccount.eu) only proves WHO is signing in. The
 * notes are encrypted with a key that only the password of the Notefox account
 * can open, so an account always keeps its password, and the link between the
 * two stores that password sealed:
 *
 *   `sub-hash`  SHA-256 of the SavAccount identifier (`sub`), used to find it;
 *   `sealed`    {password, email} wrapped with a key derived from the `sub`
 *               itself and from $savaccount_pepper (include/credentials.php).
 *
 * The `sub` is pairwise (256 bits known to SavAccount only, different for every
 * service) and is NEVER stored, logged or returned: a copy of this database -
 * even together with credentials.php - cannot open a single link, so it cannot
 * read a single note more than it could before. This is why the service must
 * be registered on savaccount.eu as confidential and with pairwise identifiers.
 *
 * After a password change the sealed password does not verify any more: the
 * next SavAccount sign-in asks for the new one once and seals it again.
 */

define("SAVACCOUNT_FLOW_MINUTES", 15);
define("SAVACCOUNT_TICKET_MINUTES", 15);
define("SAVACCOUNT_TICKET_ATTEMPTS", 5);
define("SAVACCOUNT_PASSWORD_MIN_LENGTH", 8);

function v2_savaccount_issuer()
{
    global $savaccount_issuer;
    return rtrim(isset($savaccount_issuer) && is_string($savaccount_issuer) && $savaccount_issuer !== "" ? $savaccount_issuer : "https://www.savaccount.eu", "/");
}

function v2_savaccount_redirect_uri()
{
    global $savaccount_redirect_uri;
    return isset($savaccount_redirect_uri) && is_string($savaccount_redirect_uri) && $savaccount_redirect_uri !== "" ? $savaccount_redirect_uri : "https://www.notefox.eu/my/savaccount/";
}

function v2_savaccount_table($which)
{
    global $savaccount_links_table, $savaccount_flows_table;
    $configured = $which === "links"
        ? (isset($savaccount_links_table) ? $savaccount_links_table : "")
        : (isset($savaccount_flows_table) ? $savaccount_flows_table : "");
    return is_string($configured) && preg_match('/^[A-Za-z0-9_\-]+$/', $configured) ? $configured : "savaccount_" . $which;
}

/**
 * The keys are in credentials.php: enough to decide whether to show the button.
 */
function v2_savaccount_configured()
{
    global $savaccount_client_id, $savaccount_client_secret, $savaccount_pepper;
    foreach (array($savaccount_client_id, $savaccount_client_secret, $savaccount_pepper) as $value) {
        if (!is_string($value) || $value === "" || $value[0] === "<") {
            return false;
        }
    }
    return strlen($savaccount_pepper) >= 32 && function_exists("curl_init");
}

function v2_savaccount_enabled($c)
{
    return v2_savaccount_configured()
        && db_has_table($c, v2_savaccount_table("links"))
        && db_has_table($c, v2_savaccount_table("flows"))
        && v2_keys_table_available($c);
}

function v2_savaccount_require($c)
{
    if (!v2_savaccount_enabled($c)) {
        api_error(ERR_SAVACCOUNT_UNAVAILABLE);
    }
}

function v2_savaccount_b64u($bytes)
{
    return rtrim(strtr(base64_encode($bytes), "+/", "-_"), "=");
}

// ---------------------------------------------------------------------
// Short lived rows: the sign-in in progress and the tickets
// ---------------------------------------------------------------------

/**
 * Seals a short lived payload with a random 256 bit secret that only the
 * client holds (no key stretching is needed for a random secret).
 */
function v2_savaccount_box($data, $secret)
{
    $key = hash("sha256", "notefox-savaccount-box:" . $secret, true);
    $iv = random_bytes(12);
    $tag = "";
    $cipher = openssl_encrypt(json_encode($data), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
    return $cipher === false ? null : base64_encode($iv . $tag . $cipher);
}

function v2_savaccount_unbox($boxed, $secret)
{
    $raw = base64_decode((string)$boxed, true);
    if ($raw === false || strlen($raw) < 29) {
        return null;
    }
    $key = hash("sha256", "notefox-savaccount-box:" . $secret, true);
    $plain = openssl_decrypt(substr($raw, 28), "aes-256-gcm", $key, OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
    $data = $plain === false ? null : json_decode($plain, true);
    return is_array($data) ? $data : null;
}

/**
 * Stores a row of $kind ("login", "connect" or "ticket") under the hash of
 * $name; the payload is readable only with $secret.
 */
function v2_savaccount_row_create($c, $kind, $name, $secret, $payload, $user_id, $minutes)
{
    $table = v2_savaccount_table("flows");
    $now = date("Y-m-d H:i:s");
    db_execute($c, "DELETE FROM `$table` WHERE `expiry` < ?", "s", array($now));
    $boxed = v2_savaccount_box($payload, $secret);
    if ($boxed === null) {
        api_error(ERR_INTERNAL);
    }
    $inserted = db_execute(
        $c,
        "INSERT INTO `$table` (`name-hash`, `kind`, `payload`, `user-id`, `attempts`, `expiry`) VALUES (?, ?, ?, ?, 0, ?)",
        "sssss",
        array(hash("sha256", $name), $kind, $boxed, $user_id, date("Y-m-d H:i:s", time() + $minutes * 60))
    );
    if ($inserted < 1) {
        api_error(ERR_INTERNAL);
    }
}

function v2_savaccount_row($c, $kinds, $name, $secret)
{
    $table = v2_savaccount_table("flows");
    $row = db_select_one(
        $c,
        "SELECT * FROM `$table` WHERE `name-hash` = ? AND `expiry` >= ? LIMIT 1",
        "ss",
        array(hash("sha256", $name), date("Y-m-d H:i:s"))
    );
    if ($row === null || !in_array($row["kind"], $kinds, true)) {
        return null;
    }
    $payload = v2_savaccount_unbox($row["payload"], $secret);
    if ($payload === null) {
        return null;
    }
    $row["payload"] = $payload;
    return $row;
}

/**
 * Deletes a row; true only for the request that really deleted it, so that a
 * row can be consumed once even by two simultaneous requests.
 */
function v2_savaccount_row_consume($c, $row)
{
    $table = v2_savaccount_table("flows");
    return db_execute($c, "DELETE FROM `$table` WHERE `id` = ?", "i", array((int)$row["id"])) === 1;
}

/**
 * Starts a sign-in: array("url" => SavAccount page, "flow" => secret that
 * the same client must send back to /savaccount/finish).
 */
function v2_savaccount_start($c, $mode, $user_id, $email, $language)
{
    global $savaccount_client_id;

    $state = v2_savaccount_b64u(random_bytes(32));
    $flow = v2_random_id(32);
    $nonce = v2_savaccount_b64u(random_bytes(24));
    $verifier = v2_savaccount_b64u(random_bytes(48));

    v2_savaccount_row_create($c, $mode, $state, $flow, array("nonce" => $nonce, "verifier" => $verifier, "email" => $email), $user_id, SAVACCOUNT_FLOW_MINUTES);

    $query = array(
        "response_type" => "code",
        "client_id" => $savaccount_client_id,
        "redirect_uri" => v2_savaccount_redirect_uri(),
        "scope" => "openid profile email",
        "state" => $state,
        "nonce" => $nonce,
        "code_challenge" => v2_savaccount_b64u(hash("sha256", $verifier, true)),
        "code_challenge_method" => "S256",
    );
    if ($language !== null) {
        $query["ui_locales"] = $language;
    }

    return array(
        "url" => v2_savaccount_issuer() . "/authorize?" . http_build_query($query, "", "&", PHP_QUERY_RFC3986),
        "flow" => $flow,
        "state" => $state,
        "redirect-uri" => v2_savaccount_redirect_uri(),
    );
}

/**
 * Exchanges the code for the identity: array(sub, email, name), or null when
 * SavAccount does not confirm it. Nothing of the answer is ever logged.
 */
function v2_savaccount_identity($flow, $code)
{
    global $savaccount_client_id, $savaccount_client_secret;

    $curl = curl_init(v2_savaccount_issuer() . "/oauth/token");
    curl_setopt_array($curl, array(
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => array("Accept: application/json", "Content-Type: application/x-www-form-urlencoded"),
        CURLOPT_POSTFIELDS => http_build_query(array(
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => v2_savaccount_redirect_uri(),
            "code_verifier" => $flow["verifier"],
            "client_id" => $savaccount_client_id,
            "client_secret" => $savaccount_client_secret,
        ), "", "&", PHP_QUERY_RFC3986),
    ));
    $raw = (string)curl_exec($curl);
    $http = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $curl_error = curl_error($curl);
    $answer = json_decode($raw, true);

    if ($http !== 200 || !is_array($answer) || !isset($answer["id_token"]) || !is_string($answer["id_token"])) {
        $reason = is_array($answer) && isset($answer["error"]) && is_string($answer["error"]) ? $answer["error"] : $curl_error;
        error_log("[sav-account] savaccount: the token endpoint answered " . $http . " " . substr($reason, 0, 100));
        return null;
    }

    // The token comes straight from SavAccount over TLS, so checking its claims
    // is enough (OpenID Connect Core 3.1.3.7).
    $parts = explode(".", $answer["id_token"]);
    $claims = count($parts) === 3 ? json_decode((string)base64_decode(strtr($parts[1], "-_", "+/")), true) : null;
    $valid = is_array($claims)
        && isset($claims["iss"], $claims["aud"], $claims["exp"], $claims["nonce"], $claims["sub"], $claims["email"], $claims["email_verified"])
        && $claims["iss"] === v2_savaccount_issuer()
        && ($claims["aud"] === $savaccount_client_id || $claims["aud"] === array($savaccount_client_id))
        && (int)$claims["exp"] > time() - 60
        && is_string($claims["nonce"]) && hash_equals($flow["nonce"], $claims["nonce"])
        && is_string($claims["sub"]) && strlen($claims["sub"]) >= 32 && strlen($claims["sub"]) <= 255
        && $claims["email_verified"] === true
        && is_string($claims["email"]) && strlen($claims["email"]) <= 320 && filter_var($claims["email"], FILTER_VALIDATE_EMAIL);
    if (!$valid) {
        error_log("[sav-account] savaccount: the ID token is not valid (is the service registered with pairwise identifiers?)");
        return null;
    }

    $name = "";
    foreach (array("nickname", "name") as $key) {
        if ($name === "" && isset($claims[$key]) && is_string($claims[$key])) {
            $name = trim($claims[$key]);
        }
    }

    return array("sub" => $claims["sub"], "email" => strtolower($claims["email"]), "name" => mb_substr($name, 0, 60, "UTF-8"));
}

// ---------------------------------------------------------------------
// Links
// ---------------------------------------------------------------------

function v2_savaccount_sub_hash($sub)
{
    return hash("sha256", "notefox-savaccount:" . $sub);
}

function v2_savaccount_key($sub)
{
    global $savaccount_pepper;
    return hash_hmac("sha256", "notefox-savaccount-key:" . $sub, $savaccount_pepper);
}

function v2_savaccount_link_by_sub($c, $sub)
{
    $table = v2_savaccount_table("links");
    return db_select_one($c, "SELECT * FROM `$table` WHERE `sub-hash` = ? LIMIT 1", "s", array(v2_savaccount_sub_hash($sub)));
}

function v2_savaccount_link_by_user($c, $user_id)
{
    $table = v2_savaccount_table("links");
    return db_select_one($c, "SELECT * FROM `$table` WHERE `user-id` = ? LIMIT 1", "s", array($user_id));
}

/**
 * Creates the link of an account, or seals the new password in the existing
 * one. $email is the address of the NOTEFOX account (it may differ from the
 * SavAccount one), or null when it is not known. False when it cannot.
 */
function v2_savaccount_link_save($c, $user_id, $sub, $password, $email)
{
    $table = v2_savaccount_table("links");
    $sealed = v2_wrap_key(json_encode(array("password" => $password, "email" => $email)), v2_savaccount_key($sub));
    if ($sealed === null) {
        return false;
    }

    // One SavAccount per account and one account per SavAccount: what is being
    // replaced has just been re-proved by the caller.
    try {
        return db_tx($c, function ($c) use ($table, $user_id, $sub, $sealed) {
            db_execute($c, "DELETE FROM `$table` WHERE `user-id` = ?", "s", array($user_id));
            $inserted = db_execute(
                $c,
                "INSERT INTO `$table` (`user-id`, `sub-hash`, `sealed`, `created-date`) VALUES (?, ?, ?, ?)",
                "ssss",
                array($user_id, v2_savaccount_sub_hash($sub), $sealed, getTimestamp())
            );
            if ($inserted !== 1) {
                throw new RuntimeException("link insert failed");
            }
            return true;
        });
    } catch (Throwable $e) {
        error_log("[sav-account] savaccount: " . $e->getMessage());
        return false;
    }
}

/**
 * array(password, email, valid) of a link, or null when it cannot be opened.
 * `valid` is false when the password of the account has changed since.
 */
function v2_savaccount_link_open($link, $user, $sub)
{
    $plain = v2_unwrap_key($link["sealed"], v2_savaccount_key($sub));
    $data = $plain === null ? null : json_decode($plain, true);
    if (!is_array($data) || !isset($data["password"]) || !is_string($data["password"])) {
        return null;
    }
    return array(
        "password" => $data["password"],
        "email" => isset($data["email"]) && is_string($data["email"]) ? $data["email"] : null,
        "valid" => v2_verify_password($user, $data["password"]),
    );
}

/**
 * Called by every account deletion (v1 and v2) through v2_delete_user_keys():
 * it must never be able to fail the deletion.
 */
function v2_savaccount_forget_user($c, $user_id)
{
    foreach (array("links", "flows") as $which) {
        $table = v2_savaccount_table($which);
        if (db_has_table($c, $table)) {
            db_execute($c, "DELETE FROM `$table` WHERE `user-id` = ?", "s", array($user_id));
        }
    }
}

// ---------------------------------------------------------------------
// Session and tickets
// ---------------------------------------------------------------------

/**
 * Opens a session with a password that has already been verified, and answers.
 *
 * The login code by email is not asked: SavAccount has just authenticated the
 * person, and the link was created by someone who knew the password.
 */
function v2_savaccount_answer_session($c, $user, $password, $email, $source, $extra = array())
{
    $user_id = $user["email"];
    $ip_address = req_ip_address();

    try {
        $session = db_tx($c, function ($c) use ($user_id, $password, $ip_address) {
            $created = v2_create_session($c, $user_id, $password, $ip_address, null);
            if ($created === null) {
                throw new RuntimeException("session creation failed");
            }
            return $created;
        });
    } catch (Throwable $e) {
        error_log("[sav-account] savaccount: " . $e->getMessage());
        api_error(ERR_INTERNAL);
    }

    v2_refresh_password_hash($c, $user_id, $password);
    $dek = v2_get_or_create_dek($c, $user_id, $password);
    $username = decryptTextWithPassword($user["username"], $password);
    $username = $username === false ? null : $username;

    if ($email !== null && empty($extra["created"])) {
        v2_email_logged_in($email, $username === null ? "" : $username, $ip_address, $source);
    }

    api_ok(array_merge(array(
        "result" => "session",
        "login-id" => $session["login-id"],
        "token" => $session["token"],
        "expiry" => $session["expiry"],
        "username" => $username,
        "email" => $email,
        "encryption-ready" => $dek !== null,
        "permissions" => v2_permissions($c, $user),
    ), $extra));
}

/**
 * The identity is proved but a password is still needed: the client gets a
 * ticket for the next step, and the identity waits sealed with that ticket.
 * $account_email is the address of the Notefox account (null when unknown).
 */
function v2_savaccount_answer_ticket($c, $result, $who, $user_id, $account_email, $data = array())
{
    $ticket = v2_random_id(32);
    v2_savaccount_row_create(
        $c,
        "ticket",
        "name:" . $ticket,
        $ticket,
        array("for" => $result === "signup-required" ? "signup" : "link", "sub" => $who["sub"], "email" => $who["email"], "account-email" => $account_email),
        $user_id,
        SAVACCOUNT_TICKET_MINUTES
    );
    api_ok(array_merge(array("result" => $result, "ticket" => $ticket, "email" => $account_email), $data));
}

/**
 * The ticket of the request, with the attempt already counted.
 */
function v2_savaccount_require_ticket($c, $for)
{
    $ticket = req_string("ticket", 128);
    $row = v2_savaccount_row($c, array("ticket"), "name:" . $ticket, $ticket);
    if ($row === null || $row["payload"]["for"] !== $for) {
        api_error(ERR_SAVACCOUNT_EXPIRED);
    }

    // Counted before the password is looked at, and atomically: parallel
    // requests cannot get more attempts than the allowed ones.
    $table = v2_savaccount_table("flows");
    $counted = db_execute(
        $c,
        "UPDATE `$table` SET `attempts` = `attempts` + 1 WHERE `id` = ? AND `attempts` < ?",
        "ii",
        array((int)$row["id"], SAVACCOUNT_TICKET_ATTEMPTS)
    );
    if ($counted !== 1) {
        v2_savaccount_row_consume($c, $row);
        api_error(ERR_SAVACCOUNT_EXPIRED);
    }
    return $row;
}

/**
 * "addon", "web" or null.
 */
function v2_savaccount_source()
{
    $source = req_optional_string("source", 16);
    return in_array($source, array("addon", "web"), true) ? $source : null;
}

?>
