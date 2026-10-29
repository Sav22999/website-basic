<?php
/**
 * Periodic cleanup: deletes error log entries older than 30 days.
 * Intended to be called by a cron job.
 */

require __DIR__ . "/cron-guard.php";
global $error_logs_table, $localhost_db, $username_db, $password_db, $database_notefox;

$c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
if (!$c || $c->connect_errno) {
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Database connection failed"));
    exit;
}
$c->set_charset("utf8mb4");

$stmt = $c->prepare("SELECT COUNT(*) AS `total` FROM `$error_logs_table` WHERE `inserted-date` < NOW() - INTERVAL 30 DAY");
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()["total"];
$stmt->close();

if ($total === 0) {
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => 0)));
    exit;
}

$stmt = $c->prepare("DELETE FROM `$error_logs_table` WHERE `inserted-date` < NOW() - INTERVAL 30 DAY");
if ($stmt->execute()) {
    $deleted = $stmt->affected_rows;
    $stmt->close();
    $c->close();
    echo json_encode(array("code" => "200", "status" => "Successful", "data" => array("deleted" => $deleted)));
} else {
    $error = $stmt->error;
    $stmt->close();
    $c->close();
    error_log("[periodic-checks] check-error-logs.php: " . $error);
    echo json_encode(array("code" => "500", "status" => "Error", "description" => "Delete failed"));
}
?>
