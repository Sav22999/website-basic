<?php
/**
 * Periodic cleanup: keeps at most $MAX_RECORDS rows per user in the legacy
 * data table, deleting the oldest by `updated-locally-date`. Intended to be
 * called by a cron job.
 */

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $localhost_db, $username_db, $password_db, $data_table, $database_notefox;
header("Content-Type: application/json");

$MAX_RECORDS = 200;

$c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
if (!$c || $c->connect_errno) {
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Database connection failed"));
    exit;
}
$c->set_charset("utf8mb4");

$stmt = $c->prepare(
    "SELECT COUNT(*) AS `total` FROM `$data_table` AS t1"
    . " WHERE (SELECT COUNT(*) FROM `$data_table` AS t2"
    . " WHERE t2.`user-id` = t1.`user-id` AND t2.`updated-locally-date` > t1.`updated-locally-date`) >= ?"
);
$stmt->bind_param("i", $MAX_RECORDS);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()["total"];
$stmt->close();

if ($total === 0) {
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => 0)));
    exit;
}

$stmt = $c->prepare(
    "DELETE FROM `$data_table` WHERE `id` NOT IN ("
    . "SELECT `id` FROM (SELECT t.`id` FROM `$data_table` t"
    . " WHERE (SELECT COUNT(*) FROM `$data_table` t2"
    . " WHERE t2.`user-id` = t.`user-id` AND t2.`updated-locally-date` > t.`updated-locally-date`) < ?"
    . ") AS keep)"
);
$stmt->bind_param("i", $MAX_RECORDS);
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
