<?php
/**
 * Sav Account API v2 - single response layer.
 *
 * Replaces the echo_error()/echo_result() pair that v1 duplicates in 19 files
 * with a single catalogue: one code always means the same thing, and every
 * answer carries a real HTTP status.
 *
 * Response shape (unchanged from v1, so clients keep the same parsing):
 *   { "status": "Successful", "code": 200, "data": ... }
 *   { "status": "Error", "code": 402, "description": "...", "data": ... }
 */

define("ERR_OK", 200);
define("ERR_NO_DATA", 201);
define("ERR_MISSING_PARAMETERS", 400);
define("ERR_DATABASE", 401);
define("ERR_LOGIN_ID", 402);
define("ERR_USER_NOT_FOUND", 403);
define("ERR_TOKEN_NOT_FOUND", 404);
define("ERR_TOKEN_INVALID", 405);
define("ERR_METHOD_NOT_ALLOWED", 406);
define("ERR_PAYLOAD_TOO_LARGE", 407);
define("ERR_REVISION_CONFLICT", 409);
define("ERR_INVALID_CREDENTIALS", 410);
define("ERR_USER_NOT_ACTIVE", 411);
define("ERR_CODE_EXPIRED", 412);
define("ERR_CODE_INVALID", 413);
define("ERR_ALREADY_VERIFIED", 414);
define("ERR_CODE_NOT_REQUESTED", 415);
define("ERR_SIGNUP_PENDING", 419);
define("ERR_TOO_MANY_ATTEMPTS", 420);
define("ERR_RATE_LIMITED", 429);
define("ERR_KEY_UNAVAILABLE", 430);
define("ERR_OTP_ALREADY_SET", 431);
define("ERR_HISTORY_UNAVAILABLE", 432);
define("ERR_HISTORY_FORBIDDEN", 433);
define("ERR_DELETING_PENDING", 452);
define("ERR_INTERNAL", 500);
define("ERR_UNAVAILABLE", 503);

/**
 * Description + real HTTP status for every application code.
 */
function api_catalogue()
{
    return array(
        ERR_OK => array("OK", 200),
        ERR_NO_DATA => array("Data not found", 200),
        ERR_MISSING_PARAMETERS => array("Missing or invalid parameters", 400),
        ERR_DATABASE => array("Database connection error", 503),
        ERR_LOGIN_ID => array("Login-id not found, disabled, expired or invalid", 401),
        ERR_USER_NOT_FOUND => array("User-id not found", 401),
        ERR_TOKEN_NOT_FOUND => array("Token not found, disabled, expired or invalid", 401),
        ERR_TOKEN_INVALID => array("Token not valid", 401),
        ERR_METHOD_NOT_ALLOWED => array("Method not allowed", 405),
        ERR_PAYLOAD_TOO_LARGE => array("Payload too large", 413),
        ERR_REVISION_CONFLICT => array("Revision conflict", 409),
        ERR_INVALID_CREDENTIALS => array("Invalid credentials", 401),
        ERR_USER_NOT_ACTIVE => array("User is not active", 403),
        ERR_CODE_EXPIRED => array("Verification code expired", 400),
        ERR_CODE_INVALID => array("Invalid verification code", 400),
        ERR_ALREADY_VERIFIED => array("User already verified", 409),
        ERR_CODE_NOT_REQUESTED => array("No verification code has been requested", 400),
        ERR_SIGNUP_PENDING => array("Signup not completed yet", 409),
        ERR_TOO_MANY_ATTEMPTS => array("Too many invalid attempts, request a new code", 429),
        ERR_RATE_LIMITED => array("Too many requests, please try again later", 429),
        ERR_KEY_UNAVAILABLE => array("Encryption key not available for this account", 409),
        ERR_OTP_ALREADY_SET => array("The two-factor authentication is already in the requested state", 409),
        ERR_HISTORY_UNAVAILABLE => array("Sync history is not available for this service", 409),
        ERR_HISTORY_FORBIDDEN => array("Sync history is not enabled for this account", 403),
        ERR_DELETING_PENDING => array("You already requested a deleting code. Please wait for the email or try again later.", 429),
        ERR_INTERNAL => array("Internal error", 500),
        ERR_UNAVAILABLE => array("Service temporarily unavailable", 503),
    );
}

function api_response_sent($set = false)
{
    static $sent = false;
    if ($set) {
        $sent = true;
    }
    return $sent;
}

function api_send($payload, $http_status)
{
    if (api_response_sent()) {
        return;
    }
    api_response_sent(true);

    if (PHP_SAPI !== "cli") {
        if (!headers_sent()) {
            http_response_code($http_status);
            header("Content-Type: application/json; charset=utf-8");
        }
    }
    echo json_encode($payload);
}

/**
 * Successful answer. $data is always present (never a silent null response
 * like some v1 paths produce).
 */
function api_ok($data = null, $http_status = 200)
{
    api_send(array(
        "status" => "Successful",
        "code" => ERR_OK,
        "data" => $data,
    ), $http_status);
    api_stop();
}

/**
 * Ends the request. Under CLI it throws instead of exiting, so that a test
 * harness can catch it: the important part is that nothing after an api_ok()
 * or an api_error() is ever executed.
 */
function api_stop()
{
    if (PHP_SAPI !== "cli") {
        exit;
    }
    throw new RuntimeException("notefox-v2: response already sent");
}

/**
 * Error answer. Never leaks internal details: $extra is only what the endpoint
 * explicitly decided to expose (e.g. the current revision on a conflict).
 */
function api_error($code, $extra = null, $description = null)
{
    $catalogue = api_catalogue();
    $entry = isset($catalogue[$code]) ? $catalogue[$code] : array("Unknown error", 400);

    api_send(array(
        "status" => "Error",
        "code" => $code,
        "description" => $description !== null ? $description : $entry[0],
        "data" => $extra,
    ), $entry[1]);
    api_stop();
}
?>
