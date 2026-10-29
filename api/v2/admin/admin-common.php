<?php
/**
 * Helpers shared by the api/v2/admin/* endpoints. Not an endpoint itself:
 * opened directly over HTTP it answers 404.
 */

if (!defined("NOTEFOX_V2_BOOTSTRAP")) {
    http_response_code(404);
    exit;
}

/**
 * Describes the two log tables the admin can inspect.
 *   date-columns: preferred first; v1 clients leave the server date NULL, so
 *                 the client date is the fallback.
 *   group-column: the "context" of an error, the "action" of a telemetry event.
 *   search:       columns matched by the free-text filter.
 *   fields:       columns returned by the details endpoint (url is reduced to
 *                 its host by the caller).
 */
function admin_log_sources()
{
    global $error_logs_table, $telemetry_table;
    return array(
        "errors" => array(
            "table" => $error_logs_table,
            "date-columns" => array("inserted-date", "local-date"),
            "group-column" => "context",
            "search" => array("context", "error"),
            "fields" => array("id", "inserted-date", "local-date", "context", "error", "url", "notefox-version", "anonymous-userid"),
        ),
        "telemetry" => array(
            "table" => $telemetry_table,
            "date-columns" => array("server-datetime", "client-datetime"),
            "group-column" => "action",
            "search" => array("action", "context", "other"),
            "fields" => array("id", "server-datetime", "client-datetime", "notefox-account", "language", "action", "context", "url", "browser", "browser-version", "notefox-version", "os", "other", "anonymous-userid"),
        ),
    );
}

/**
 * Availability of a log source: array("available" => true, "date" => <SQL
 * expression>) or array("available" => false, "reason" => ..., "table" => ...).
 */
function admin_log_source_state($c, $source)
{
    $table = $source["table"];
    if (!is_string($table) || $table === "" || !db_has_table($c, $table)) {
        return array("available" => false, "reason" => "table-missing", "table" => (string)$table);
    }

    $dates = array();
    foreach ($source["date-columns"] as $column) {
        if (db_has_column($c, $table, $column)) {
            $dates[] = "`$column`";
        }
    }
    if (count($dates) === 0) {
        return array("available" => false, "reason" => "date-column-missing", "table" => $table);
    }

    $expression = count($dates) === 1 ? $dates[0] : "COALESCE(" . implode(", ", $dates) . ")";
    return array("available" => true, "date" => $expression, "table" => $table);
}

/**
 * Only the host of a URL: enough to tell where an error happens without
 * storing the full browsing address in the admin UI.
 */
function admin_url_host($url)
{
    if (!is_string($url) || $url === "") {
        return null;
    }
    $host = parse_url($url, PHP_URL_HOST);
    return is_string($host) && $host !== "" ? $host : null;
}
?>
