<?php
/**
 * POST /api/v2/telemetry/insert
 *
 * Body (same parameters as v1, batch form):
 *   { "telemetry": [ { "notefox-account": true, "anonymous-userid": "...",
 *                      "client-datetime": "...", "language": "...",
 *                      "action": "...", "context": "...", "url": "...",
 *                      "browser": "...", "browser-version": "...",
 *                      "notefox-version": "...", "os": "...",
 *                      "other": "..." }, ... ] }
 *
 * A single event may also be sent flat (without the "telemetry" wrapper).
 *
 * No authentication is required, exactly like v1: the events are anonymous.
 *
 * Differences with v1:
 *   - the IP is rate limited ("telemetry" bucket);
 *   - every field is length checked against the size of its column;
 *   - `client-datetime` is validated and normalised before touching a
 *     TIMESTAMP column, and `server-datetime` is written by the server;
 *   - the number of events of a single request is bounded;
 *   - the batch is written inside a transaction and the errors of the
 *     statements are actually checked (v1 ignored the result of execute()).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

define("TELEMETRY_MAX_ITEMS", 100);

$c = db();

v2_rate_limit($c, "telemetry", req_ip_address(), 30, 600, 600);

$body = req_body();
$items = array();

if (isset($body["telemetry"])) {
    if (!is_array($body["telemetry"]) || count($body["telemetry"]) === 0) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if (count($body["telemetry"]) > TELEMETRY_MAX_ITEMS) {
        api_error(ERR_PAYLOAD_TOO_LARGE);
    }
    $items = $body["telemetry"];
} else {
    // Flat form: one single event described by the body itself.
    $items = array(array(
        "notefox-account" => req_bool("notefox-account"),
        "anonymous-userid" => req_string("anonymous-userid", 50),
        "client-datetime" => req_datetime("client-datetime"),
        "language" => req_string("language", 20),
        "action" => req_string("action", 500),
        "context" => req_optional_string("context", 500),
        "url" => req_optional_string("url", 65535),
        "browser" => req_string("browser", 20),
        "browser-version" => req_optional_string("browser-version", 20),
        "notefox-version" => req_string("notefox-version", 20),
        "os" => req_optional_string("os", 20),
        "other" => req_optional_string("other", 65535),
    ));
}

$events = array();
foreach ($items as $item) {
    if (!is_array($item)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    $events[] = array(
        "notefox-account" => telemetry_flag($item, "notefox-account"),
        "anonymous-userid" => telemetry_field($item, "anonymous-userid", 50, true),
        "client-datetime" => telemetry_datetime($item, "client-datetime"),
        "language" => telemetry_field($item, "language", 20, true),
        "action" => telemetry_field($item, "action", 500, true),
        "context" => telemetry_field($item, "context", 500, false),
        "url" => telemetry_field($item, "url", 65535, false),
        "browser" => telemetry_field($item, "browser", 20, true),
        "browser-version" => telemetry_field($item, "browser-version", 20, false),
        "notefox-version" => telemetry_field($item, "notefox-version", 20, true),
        "os" => telemetry_field($item, "os", 20, false),
        "other" => telemetry_field($item, "other", 65535, false),
    );
}

global $telemetry_table;
$now = getTimestamp();

try {
    db_tx($c, function ($c) use ($events, $now, $telemetry_table) {
        foreach ($events as $event) {
            $affected = db_execute(
                $c,
                "INSERT INTO `$telemetry_table` (`id`, `notefox-account`, `anonymous-userid`, `client-datetime`, `server-datetime`, `language`, `action`, `context`, `url`, `browser`, `browser-version`, `notefox-version`, `os`, `other`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                "issssssssssss",
                array(
                    $event["notefox-account"],
                    $event["anonymous-userid"],
                    $event["client-datetime"],
                    $now,
                    $event["language"],
                    $event["action"],
                    $event["context"],
                    $event["url"],
                    $event["browser"],
                    $event["browser-version"],
                    $event["notefox-version"],
                    $event["os"],
                    $event["other"],
                )
            );
            if ($affected < 0) {
                throw new RuntimeException("telemetry insert failed");
            }
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] telemetry/insert: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

api_ok(null);

/**
 * A string field of an event, bounded by the size of its column.
 */
function telemetry_field($item, $key, $max_length, $required)
{
    if (!isset($item[$key]) || (!is_string($item[$key]) && !is_numeric($item[$key]))) {
        if ($required) {
            api_error(ERR_MISSING_PARAMETERS);
        }
        return null;
    }

    $value = trim((string)$item[$key]);
    if ($value === "") {
        if ($required) {
            api_error(ERR_MISSING_PARAMETERS);
        }
        return null;
    }
    if (strlen($value) > $max_length) {
        api_error(ERR_MISSING_PARAMETERS);
    }

    return $value;
}

/**
 * The optional `notefox-account` flag of an event (a tinyint column).
 */
function telemetry_flag($item, $key)
{
    if (!isset($item[$key])) {
        return null;
    }

    $value = $item[$key];
    if (is_bool($value)) {
        return $value ? 1 : 0;
    }
    if (is_int($value)) {
        return $value !== 0 ? 1 : 0;
    }
    if (is_string($value)) {
        return (strtolower(trim($value)) === "true" || trim($value) === "1") ? 1 : 0;
    }

    return null;
}

/**
 * The date of the event, normalised before it reaches a TIMESTAMP column.
 */
function telemetry_datetime($item, $key)
{
    if (!isset($item[$key])) {
        api_error(ERR_MISSING_PARAMETERS);
    }

    $value = req_parse_datetime($item[$key]);
    if ($value === null) {
        api_error(ERR_MISSING_PARAMETERS);
    }

    return $value;
}
?>
