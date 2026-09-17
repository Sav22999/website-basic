<?php
/**
 * POST /api/v2/error-logs/insert
 *
 * Body (same parameters as v1, batch form):
 *   { "error-logs": [ { "datetime": "...", "context": "...", "error": "...",
 *                       "url": "...", "notefox-version": "...",
 *                       "anonymous-userid": "..." }, ... ] }
 *
 * A single log may also be sent flat (without the "error-logs" wrapper).
 *
 * No authentication is required, exactly like v1.
 *
 * Differences with v1:
 *   - the IP is rate limited ("error-logs" bucket), v1 accepted an unlimited
 *     number of logs from anybody;
 *   - every field is length checked against the size of its column, instead of
 *     being sent to MySQL and silently truncated;
 *   - `datetime` is validated and normalised before touching a TIMESTAMP
 *     column;
 *   - the number of logs of a single request is bounded;
 *   - the batch is written inside a transaction and the errors of the
 *     statements are actually checked (v1 ignored the result of execute()).
 */

include_once(__DIR__ . "/../../include/bootstrap.php");

req_require_post();

define("ERROR_LOGS_MAX_ITEMS", 100);

$c = db();

v2_rate_limit($c, "error-logs", req_ip_address(), 30, 600, 600);

$body = req_body();
$items = array();

if (isset($body["error-logs"])) {
    if (!is_array($body["error-logs"]) || count($body["error-logs"]) === 0) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if (count($body["error-logs"]) > ERROR_LOGS_MAX_ITEMS) {
        api_error(ERR_PAYLOAD_TOO_LARGE);
    }
    $items = $body["error-logs"];
} else {
    // Flat form: one single log described by the body itself.
    $items = array(array(
        "datetime" => req_datetime("datetime"),
        "context" => req_string("context", 500),
        "error" => req_string("error", 65535),
        "url" => req_optional_string("url", 1000),
        "notefox-version" => req_optional_string("notefox-version", 20),
        "anonymous-userid" => req_optional_string("anonymous-userid", 50),
    ));
}

$logs = array();
foreach ($items as $item) {
    if (!is_array($item)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    $logs[] = array(
        "local-date" => error_logs_datetime($item, "datetime"),
        "context" => error_logs_field($item, "context", 500, true),
        "error" => error_logs_field($item, "error", 65535, true),
        "url" => error_logs_field($item, "url", 1000, false),
        "notefox-version" => error_logs_field($item, "notefox-version", 20, false),
        "anonymous-userid" => error_logs_field($item, "anonymous-userid", 50, false),
    );
}

global $error_logs_table;
$now = getTimestamp();

try {
    db_tx($c, function ($c) use ($logs, $now, $error_logs_table) {
        foreach ($logs as $log) {
            $affected = db_execute(
                $c,
                "INSERT INTO `$error_logs_table` (`id`, `local-date`, `inserted-date`, `context`, `error`, `url`, `notefox-version`, `anonymous-userid`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)",
                "sssssss",
                array(
                    $log["local-date"],
                    $now,
                    $log["context"],
                    $log["error"],
                    $log["url"],
                    $log["notefox-version"],
                    $log["anonymous-userid"],
                )
            );
            if ($affected < 0) {
                throw new RuntimeException("error log insert failed");
            }
        }
    });
} catch (Throwable $e) {
    error_log("[sav-account] error-logs/insert: " . $e->getMessage());
    api_error(ERR_INTERNAL);
}

api_ok(null);

/**
 * A string field of a log, bounded by the size of its column.
 */
function error_logs_field($item, $key, $max_length, $required)
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
 * The date of the log, normalised before it reaches a TIMESTAMP column.
 */
function error_logs_datetime($item, $key)
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
