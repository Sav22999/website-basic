<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $localhost_db, $username_db, $password_db, $data_table, $database_notefox;
header("Content-Type:application/json");
//$request = json_decode(file_get_contents('php://input'), true); //POST request
$request = $_GET; //GET request

$condition = true; //no conditions
if ($condition) {
    $found = false;
    $invalid = false;

    $response = null;

    $MAX_RECORDS = 200;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        // Snippet 1: Check if there are any rows with the ip_address in the last 30 days
        $query_exists = "SELECT * FROM `$data_table` AS t1 WHERE (SELECT COUNT(*) FROM `$data_table` AS t2 WHERE t2.`user-id` = t1.`user-id` AND t2.`updated-locally-date` > t1.`updated-locally-date`) >= ?";
        $stmt_exists = $c->prepare($query_exists);
        $stmt_exists->bind_param("i", $MAX_RECORDS);
        if ($stmt_exists && $stmt_exists->execute()) {
            //successful
        } else {
            $invalid = true;
            $response = echo_invalid($stmt_exists->error);
        }
        $result_exists = $stmt_exists->get_result();
        $stmt_exists->close();
        $total_records = $result_exists->num_rows;

        if ($total_records > 0) {
            // Delete all rows older than 60 days
            //$query_delete = "UPDATE `$error_logs_table` SET `context` = 'deleted' WHERE `inserted-date` < NOW() - INTERVAL 30 DAY";
            $query_delete = "DELETE FROM `$data_table` WHERE id NOT IN (SELECT id FROM (SELECT t.id FROM `$data_table` t WHERE (SELECT COUNT(*) FROM `$data_table` t2 WHERE t2.`user-id` = t.`user-id` AND t2.`updated-locally-date` > t.`updated-locally-date`) < ? ) AS RecordsToKeep)";
            $stmt_delete = $c->prepare($query_delete);
            if ($stmt_delete) {
                $stmt_delete->bind_param("i", $MAX_RECORDS);
                if ($stmt_delete->execute()) {
                    $found = true;
                    $response = echo_result($total_records);
                } else {
                    $invalid = true;
                    $response = echo_invalid($stmt_delete->error);
                }
                $stmt_delete->close();
            } else {
                $invalid = true;
                $response = echo_invalid($c->error);
            }
            /*
            if ($c->query($query_delete)) {
                $found = true;
                $response = echo_result($res["total_records"]);
            } else {
                $invalid = true;
            }
            */
        } else {
            $invalid = true;
            $response = echo_invalid();
        }
        $c->close();
    }

    if ($invalid) {
        //$response = echo_invalid();
    }

    echo json_encode($response);
} else {
    echo_null();
}

function echo_null()
{
    echo json_encode(null);
}

function echo_invalid($description = "")
{
    $response["code"] = "400";
    $response["status"] = "Error";
    $response["description"] = "No error data found or invalid request";
    if (!empty($description)) {
        $response["description"] .= " - " . $description;
    }
    return $response;
}

function echo_result($count)
{
    $response["code"] = "200";
    $response["status"] = "Successful";
    $data["deleting_number"] = $count;
    $response["data"] = $data;
    return $response;
}

?>