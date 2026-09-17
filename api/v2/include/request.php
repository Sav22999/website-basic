<?php
/**
 * Sav Account API v2 - request parsing and validation.
 *
 * v1 never checks the HTTP method, never limits the body size and never
 * validates the values it stores (e.g. `updated-locally` goes straight into a
 * DATETIME column). Everything an endpoint reads from the client goes through
 * this file instead.
 */

define("REQ_MAX_BODY_BYTES", 2 * 1024 * 1024);   // whole JSON body
define("REQ_MAX_DATA_BYTES", 1536 * 1024);       // the "data" payload of the sync
define("REQ_MAX_FIELD_LENGTH", 512);
define("REQ_MAX_CLOCK_SKEW_SECONDS", 24 * 60 * 60);

function req_method()
{
    return isset($_SERVER["REQUEST_METHOD"]) ? strtoupper($_SERVER["REQUEST_METHOD"]) : "GET";
}

/**
 * Every endpoint but /status is POST only.
 */
function req_require_post()
{
    $method = req_method();
    if ($method === "OPTIONS") {
        api_ok(null);
    }
    if ($method !== "POST") {
        api_error(ERR_METHOD_NOT_ALLOWED);
    }
}

function req_require_get()
{
    $method = req_method();
    if ($method === "OPTIONS") {
        api_ok(null);
    }
    if ($method !== "GET" && $method !== "HEAD" && $method !== "POST") {
        api_error(ERR_METHOD_NOT_ALLOWED);
    }
}

/**
 * Decoded JSON body, with a hard size limit and real error checking.
 */
function req_body()
{
    static $body = null;
    if ($body !== null) {
        return $body;
    }

    $length = isset($_SERVER["CONTENT_LENGTH"]) ? (int)$_SERVER["CONTENT_LENGTH"] : 0;
    if ($length > REQ_MAX_BODY_BYTES) {
        api_error(ERR_PAYLOAD_TOO_LARGE);
    }

    $raw = file_get_contents("php://input", false, null, 0, REQ_MAX_BODY_BYTES + 1);
    if ($raw === false) {
        $raw = "";
    }
    if (strlen($raw) > REQ_MAX_BODY_BYTES) {
        api_error(ERR_PAYLOAD_TOO_LARGE);
    }
    if (trim($raw) === "") {
        $body = array();
        return $body;
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        api_error(ERR_MISSING_PARAMETERS);
    }

    $body = $decoded;
    return $body;
}

/**
 * Required string field. Empty strings and non-scalars are refused.
 */
function req_string($key, $max_length = REQ_MAX_FIELD_LENGTH)
{
    $body = req_body();
    if (!isset($body[$key]) || !is_string($body[$key])) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    $value = trim($body[$key]);
    if ($value === "" || strlen($value) > $max_length) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $value;
}

/**
 * Optional string field, $default when absent.
 */
function req_optional_string($key, $max_length = REQ_MAX_FIELD_LENGTH, $default = null)
{
    $body = req_body();
    if (!isset($body[$key]) || !is_string($body[$key])) {
        return $default;
    }
    $value = trim($body[$key]);
    if ($value === "") {
        return $default;
    }
    if (strlen($value) > $max_length) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $value;
}

function req_bool($key, $default = false)
{
    $body = req_body();
    if (!isset($body[$key])) {
        if (isset($_GET[$key])) {
            return $_GET[$key] === "true" || $_GET[$key] === "1";
        }
        return $default;
    }
    $value = $body[$key];
    if (is_bool($value)) {
        return $value;
    }
    if (is_int($value)) {
        return $value === 1;
    }
    if (is_string($value)) {
        return strtolower($value) === "true" || $value === "1";
    }
    return $default;
}

/**
 * Password field: kept as-is (not trimmed), only length-bounded.
 */
function req_password($key = "password")
{
    $body = req_body();
    if (!isset($body[$key]) || !is_string($body[$key]) || $body[$key] === "") {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if (strlen($body[$key]) > 1024) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $body[$key];
}

function req_email($key = "email")
{
    $value = strtolower(req_string($key, 320));
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $value;
}

/**
 * The data payload of the sync: a string, size-limited, never validated in v1.
 */
function req_data_payload($key = "data")
{
    $body = req_body();
    if (!isset($body[$key]) || !is_string($body[$key])) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if (strlen($body[$key]) > REQ_MAX_DATA_BYTES) {
        api_error(ERR_PAYLOAD_TOO_LARGE);
    }
    return $body[$key];
}

/**
 * Validates a client supplied datetime and normalises it to 'Y-m-d H:i:s'.
 * Returns null when the value cannot be parsed or is too far in the future.
 */
function req_parse_datetime($value, $allow_future_seconds = REQ_MAX_CLOCK_SKEW_SECONDS)
{
    if (!is_string($value)) {
        return null;
    }
    $value = trim($value);
    if ($value === "") {
        return null;
    }
    $timestamp = strtotime($value);
    if ($timestamp === false || $timestamp <= 0) {
        return null;
    }
    if ($timestamp > time() + $allow_future_seconds) {
        return null;
    }
    if ($timestamp < strtotime("2000-01-01 00:00:00")) {
        return null;
    }
    return date("Y-m-d H:i:s", $timestamp);
}

function req_datetime($key, $required = true)
{
    $body = req_body();
    if (!isset($body[$key])) {
        if ($required) {
            api_error(ERR_MISSING_PARAMETERS);
        }
        return null;
    }
    $parsed = req_parse_datetime($body[$key]);
    if ($parsed === null) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $parsed;
}

/**
 * An expiry may legitimately be in the future (or explicitly null to remove it).
 */
function req_expiry($key = "expiry")
{
    $body = req_body();
    if (!array_key_exists($key, $body)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if ($body[$key] === null || $body[$key] === "" || $body[$key] === "null") {
        return null;
    }
    if (!is_string($body[$key])) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    $timestamp = strtotime(trim($body[$key]));
    if ($timestamp === false || $timestamp <= 0) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return date("Y-m-d H:i:s", $timestamp);
}

function req_revision($key = "base-revision", $required = false)
{
    $body = req_body();
    if (!isset($body[$key])) {
        if ($required) {
            api_error(ERR_MISSING_PARAMETERS);
        }
        return null;
    }
    $value = $body[$key];
    if (is_string($value) && ctype_digit($value)) {
        $value = (int)$value;
    }
    if (!is_int($value) || $value < 0) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $value;
}

/**
 * A verification code as typed by the user: trimmed and upper-cased so that
 * "a1b2c3" and "A1B2C3" are the same code.
 */
function req_code($key)
{
    $value = req_string($key, 64);
    return strtoupper($value);
}

function req_ip_address()
{
    return getIpAddress();
}

/**
 * The service the data belongs to: "notefox" when absent, so that every
 * already deployed v2 client keeps working unchanged. A name outside the
 * allowed format or missing from the registry is refused before any write.
 */
function req_service($key = "service")
{
    $body = req_body();
    if (!isset($body[$key])) {
        return sav_service_default();
    }
    if (!is_string($body[$key])) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    if (trim($body[$key]) === "") {
        return sav_service_default();
    }
    $value = sav_service_normalise($body[$key]);
    if ($value === null || !sav_service_valid($value)) {
        api_error(ERR_MISSING_PARAMETERS);
    }
    return $value;
}
?>
