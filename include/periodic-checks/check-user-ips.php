<?php
/**
 * Periodic cleanup: clears IP addresses stored at account creation
 * for accounts older than 30 days (sets the column to NULL).
 * Intended to be called by a cron job.
 */

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $users_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type: application/json");

$c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
if (!$c || $c->connect_errno) {
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Database connection failed"));
    exit;
}
$c->set_charset("utf8mb4");

$stmt = $c->prepare("SELECT COUNT(*) AS `total` FROM `$users_table` WHERE `ip-address` IS NOT NULL AND `created` < NOW() - INTERVAL 30 DAY");
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()["total"];
$stmt->close();

if ($total === 0) {
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("cleared" => 0)));
    exit;
}

$stmt = $c->prepare("UPDATE `$users_table` SET `ip-address` = NULL WHERE `ip-address` IS NOT NULL AND `created` < NOW() - INTERVAL 30 DAY");
if ($stmt->execute()) {
    $cleared = $stmt->affected_rows;
    $stmt->close();
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("cleared" => $cleared)));
} else {
    $error = $stmt->error;
    $stmt->close();
    $c->close();
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Update failed - " . $error));
}
?>
