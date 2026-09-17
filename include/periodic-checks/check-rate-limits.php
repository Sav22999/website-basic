<?php
/**
 * Periodic cleanup: deletes stale rate limit counters whose window expired
 * more than 7 days ago and whose block (if any) has also expired.
 * Intended to be called by a cron job.
 */

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $rate_limits_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type: application/json");

if (!isset($rate_limits_table) || $rate_limits_table === "") {
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => 0, "note" => "rate_limits_table not configured")));
    exit;
}

$c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
if (!$c || $c->connect_errno) {
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Database connection failed"));
    exit;
}
$c->set_charset("utf8mb4");

$stmt = $c->prepare(
    "DELETE FROM `$rate_limits_table`"
    . " WHERE `window-start` < NOW() - INTERVAL 7 DAY"
    . " AND (`blocked-until` IS NULL OR `blocked-until` < NOW())"
);
if (!$stmt) {
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => 0, "note" => "table not available")));
    exit;
}

if ($stmt->execute()) {
    $deleted = $stmt->affected_rows;
    $stmt->close();
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => $deleted)));
} else {
    $error = $stmt->error;
    $stmt->close();
    $c->close();
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Delete failed - " . $error));
}
?>
