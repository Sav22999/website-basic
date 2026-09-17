<?php
/**
 * Sav Account API v2 - service registry.
 *
 * Starting from v2 the account (signup, login, tokens, keys, OTP, rate limits,
 * emails) is generic and shared: Notefox is only the first service using it.
 * The synchronised data, instead, is partitioned per service, so two products
 * on the same account never overwrite each other and each one owns its own
 * `revision`.
 *
 * The registry lives in include/credentials.php ($services). When that
 * variable is missing - an installation that has not been updated yet - the
 * registry falls back to the single "notefox" service, so nothing breaks.
 */

define("SAV_SERVICE_DEFAULT", "notefox");
define("SAV_SERVICE_PATTERN", "/^[a-z0-9][a-z0-9_-]{0,31}$/");

/**
 * The declared services, normalised:
 *   array("notefox" => array("name" => "Notefox", "legacy-table" => "notefox_data"))
 */
function sav_services()
{
    static $registry = null;
    if ($registry !== null) {
        return $registry;
    }

    global $services, $data_table;

    $registry = array();
    if (isset($services) && is_array($services)) {
        foreach ($services as $key => $entry) {
            if (!is_string($key)) {
                continue;
            }
            $name = strtolower(trim($key));
            if (!preg_match(SAV_SERVICE_PATTERN, $name)) {
                continue;
            }
            if (!is_array($entry)) {
                $entry = array();
            }
            $registry[$name] = array(
                "name" => isset($entry["name"]) && is_string($entry["name"]) ? $entry["name"] : $name,
                "legacy-table" => isset($entry["legacy-table"]) && is_string($entry["legacy-table"]) && $entry["legacy-table"] !== ""
                    ? $entry["legacy-table"]
                    : null,
            );
        }
    }

    if (empty($registry)) {
        // Fallback for installations whose credentials.php predates the registry.
        $registry[SAV_SERVICE_DEFAULT] = array(
            "name" => "Notefox",
            "legacy-table" => isset($data_table) && is_string($data_table) && $data_table !== "" ? $data_table : null,
        );
    }

    return $registry;
}

/**
 * The service assumed when the client does not send one: it keeps every
 * already deployed v2 client working without a single change.
 */
function sav_service_default()
{
    return SAV_SERVICE_DEFAULT;
}

/**
 * The names of the configured services, in the order they were declared.
 */
function sav_service_names()
{
    return array_keys(sav_services());
}

/**
 * Lower-cases and trims a service name, returning null when it does not match
 * the allowed format. It does NOT check the registry: see sav_service_valid().
 */
function sav_service_normalise($name)
{
    if (!is_string($name)) {
        return null;
    }
    $name = strtolower(trim($name));
    if ($name === "" || !preg_match(SAV_SERVICE_PATTERN, $name)) {
        return null;
    }
    return $name;
}

/**
 * True when the name has a valid format AND is declared in the registry.
 */
function sav_service_valid($name)
{
    $normalised = sav_service_normalise($name);
    if ($normalised === null) {
        return false;
    }
    $registry = sav_services();
    return isset($registry[$normalised]);
}

/**
 * The v1 mirror table of a service, or null when the service has none.
 * Only Notefox declares one: the legacy `notefox_data` table read by the old
 * extensions. This is the only Notefox specific bridge left in the core.
 */
function sav_service_legacy_table($name)
{
    $normalised = sav_service_normalise($name);
    if ($normalised === null) {
        return null;
    }
    $registry = sav_services();
    if (!isset($registry[$normalised])) {
        return null;
    }
    return $registry[$normalised]["legacy-table"];
}

/**
 * The human readable label of a service (used in emails and documentation).
 */
function sav_service_label($name)
{
    $normalised = sav_service_normalise($name);
    $registry = sav_services();
    if ($normalised === null || !isset($registry[$normalised])) {
        return null;
    }
    return $registry[$normalised]["name"];
}
?>
