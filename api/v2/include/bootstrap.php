<?php
/**
 * Sav Account API v2 - bootstrap.
 *
 * Loaded by every v2 endpoint as the very first instruction:
 *   include_once(__DIR__ . "/../include/bootstrap.php");
 *
 * It loads the credentials, the (untouched) v1 shared functions and the whole
 * v2 core. It never prints anything by itself.
 *
 * NOTE: nothing under api/v1/, docs/v1/, documentation/v1/ is used or modified
 * by the v2 API. include/api-functions.php is only read (never changed): the v2
 * API reuses its hashing/encryption helpers so that data written by v2 stays
 * readable by v1 clients.
 */

if (defined("NOTEFOX_V2_BOOTSTRAP")) {
    return;
}
define("NOTEFOX_V2_BOOTSTRAP", true);

define("NOTEFOX_V2_API_VERSION", "2.0");

/**
 * Project root: DOCUMENT_ROOT when it looks correct (web requests), the folder
 * three levels above this file otherwise (CLI, tests, unusual hosting setups).
 */
if (!defined("NOTEFOX_V2_ROOT")) {
    $notefox_v2_root = isset($_SERVER["DOCUMENT_ROOT"]) ? rtrim($_SERVER["DOCUMENT_ROOT"], "/") : "";
    if ($notefox_v2_root === "" || !file_exists($notefox_v2_root . "/include/credentials.php")) {
        $notefox_v2_root = dirname(dirname(dirname(__DIR__)));
    }
    define("NOTEFOX_V2_ROOT", $notefox_v2_root);
    unset($notefox_v2_root);
}

// Errors are logged, never printed: an API must always answer valid JSON.
@ini_set("display_errors", "0");
@ini_set("log_errors", "1");
error_reporting(E_ALL);

// mysqli must not throw on its own: the v2 core checks every return value.
if (function_exists("mysqli_report")) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

include_once(NOTEFOX_V2_ROOT . "/include/credentials.php");
include_once(NOTEFOX_V2_ROOT . "/include/api-functions.php");

include_once(__DIR__ . "/response.php");
include_once(__DIR__ . "/services.php");
include_once(__DIR__ . "/request.php");
include_once(__DIR__ . "/db.php");
include_once(__DIR__ . "/crypto.php");
include_once(__DIR__ . "/codes.php");
include_once(__DIR__ . "/keys.php");
include_once(__DIR__ . "/auth.php");
include_once(__DIR__ . "/rate-limit.php");
include_once(__DIR__ . "/mailer.php");
include_once(__DIR__ . "/emails.php");
include_once(__DIR__ . "/sync.php");

if (PHP_SAPI !== "cli") {
    header("Content-Type: application/json; charset=utf-8");
    header("Cache-Control: no-store");
    header("X-Content-Type-Options: nosniff");

    set_exception_handler(function ($e) {
        error_log("[sav-account] uncaught: " . $e->getMessage());
        api_error(ERR_INTERNAL);
    });

    set_error_handler(function ($severity, $message, $file = "", $line = 0) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error !== null && in_array($error["type"], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR), true)) {
            error_log("[sav-account] fatal: " . $error["message"]);
            if (!api_response_sent()) {
                api_error(ERR_INTERNAL);
            }
        }
    });
}
?>
