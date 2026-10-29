<?php
/**
 * POST /api/v2/admin/logs - read-only list of error logs or telemetry events
 * for administrators (permissions >= 10).
 *
 * Body:
 *   login-id, token   authentication
 *   type              "errors" | "telemetry"
 *   before-id         optional, pagination cursor (id of the last row shown)
 *   limit             optional, 1-50 (default 25)
 *   days              optional, 1 | 7 | 30 (default: no date filter)
 *   group             optional, exact context (errors) or action (telemetry)
 *   version           optional, exact Notefox version
 *   q                 optional, free text matched with LIKE
 *
 * Newest first. URLs are reduced to their host and the anonymous installation
 * id to a short prefix, enough to group events without exposing browsing data.
 */

include_once(__DIR__ . "/../../include/bootstrap.php");
include_once(__DIR__ . "/../admin-common.php");

req_require_post();

$c = db();

$session = v2_require_auth($c);
v2_require_admin($session);

v2_rate_limit($c, "admin-logs", $session["user-id"], 60, 60, 60);

$type = req_string("type", 20);
$sources = admin_log_sources();
if (!isset($sources[$type])) {
    api_error(ERR_MISSING_PARAMETERS);
}
$source = $sources[$type];

$body = req_body();
$before_id = isset($body["before-id"]) && is_numeric($body["before-id"]) ? (int)$body["before-id"] : 0;
$limit = isset($body["limit"]) && is_numeric($body["limit"]) ? (int)$body["limit"] : 25;
$limit = max(1, min(50, $limit));
$days = isset($body["days"]) && is_numeric($body["days"]) ? (int)$body["days"] : 0;
if (!in_array($days, array(0, 1, 7, 30), true)) {
    api_error(ERR_MISSING_PARAMETERS);
}
$group = req_optional_string("group", 500);
$version = req_optional_string("version", 20);
$q = req_optional_string("q", 100);

$state = admin_log_source_state($c, $source);
if (!$state["available"]) {
    api_ok(array("type" => $type, "available" => false, "reason" => $state["reason"], "table" => $state["table"], "items" => array(), "has-more" => false));
}

$table = $state["table"];
$date = $state["date"];

$columns = array();
foreach ($source["fields"] as $field) {
    if (db_has_column($c, $table, $field)) {
        $columns[] = $field;
    }
}
if (!in_array("id", $columns, true)) {
    api_ok(array("type" => $type, "available" => false, "reason" => "id-column-missing", "table" => $table, "items" => array(), "has-more" => false));
}

$where = array();
$types = "";
$params = array();

if ($before_id > 0) {
    $where[] = "`id` < ?";
    $types .= "i";
    $params[] = $before_id;
}
if ($days > 0) {
    $where[] = "$date >= DATE_SUB(NOW(), INTERVAL $days DAY)";
}
if ($group !== null && in_array($source["group-column"], $columns, true)) {
    $where[] = "`" . $source["group-column"] . "` = ?";
    $types .= "s";
    $params[] = $group;
}
if ($version !== null && in_array("notefox-version", $columns, true)) {
    $where[] = "`notefox-version` = ?";
    $types .= "s";
    $params[] = $version;
}
if ($q !== null) {
    $like = array();
    foreach ($source["search"] as $column) {
        if (in_array($column, $columns, true)) {
            $like[] = "`$column` LIKE ?";
            $types .= "s";
            $params[] = "%" . db_like_escape($q) . "%";
        }
    }
    if (count($like) > 0) {
        $where[] = "(" . implode(" OR ", $like) . ")";
    }
}

$select = implode(", ", array_map(function ($column) {
    return "`$column`";
}, $columns));
$sql = "SELECT $select, $date AS `event-date` FROM `$table`"
    . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "")
    . " ORDER BY `id` DESC LIMIT " . ($limit + 1);

$rows = db_select($c, $sql, $types, $params);

$has_more = count($rows) > $limit;
if ($has_more) {
    array_pop($rows);
}

$items = array();
foreach ($rows as $row) {
    $item = array("id" => (int)$row["id"], "date" => $row["event-date"]);
    foreach ($columns as $column) {
        if ($column === "id") {
            continue;
        }
        $value = $row[$column];
        if ($column === "url") {
            $item["url-host"] = admin_url_host($value);
            continue;
        }
        if ($column === "anonymous-userid") {
            $item["installation"] = is_string($value) && $value !== "" ? substr($value, 0, 8) : null;
            continue;
        }
        if ($column === "notefox-account") {
            $item[$column] = $value === null ? null : ((int)$value) === 1;
            continue;
        }
        if (is_string($value) && mb_strlen($value) > 8000) {
            $value = mb_substr($value, 0, 8000) . "…";
        }
        $item[$column] = $value;
    }
    $items[] = $item;
}

api_ok(array(
    "type" => $type,
    "available" => true,
    "items" => $items,
    "has-more" => $has_more,
    "next-before-id" => count($items) > 0 ? $items[count($items) - 1]["id"] : null,
));
?>
