<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $error_logs_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
//$request = json_decode(file_get_contents('php://input'), true); //POST request
$request = $_GET; //GET request

$condition = true; //no conditions
if ($condition) {
    $found = false;
    $invalid = false;

    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        // Snippet 1: Check if there are any rows with the ip_address in the last 30 days
        $query_exists = "SELECT COUNT(*) AS `total_records` FROM `$error_logs_table` WHERE `inserted-date` < NOW() - INTERVAL 30 DAY";
        $stmt_exists = $c->prepare($query_exists);
        //$stmt_exists->bind_param();
        if ($stmt_exists->execute()) {
            //successful
        } else {
            $invalid = true;
        }
        $result_exists = $stmt_exists->get_result();
        $stmt_exists->close();

        if ($result_exists->num_rows === 1) {
            $res = $result_exists->fetch_array();

            if ($res["total_records"] > 0) {
                // Delete all rows older than 60 days
                //$query_delete = "UPDATE `$error_logs_table` SET `context` = 'deleted' WHERE `inserted-date` < NOW() - INTERVAL 30 DAY";
                $query_delete = "DELETE FROM `$error_logs_table` WHERE `inserted-date` < NOW() - INTERVAL 30 DAY";
                if ($c->query($query_delete)) {
                    $found = true;
                    $response = echo_result($res["total_records"]);
                } else {
                    $invalid = true;
                }
            }
        }
        $c->close();
    }

    if ($invalid) {
        $response = echo_invalid();
    }

    echo json_encode($response);
} else {
    echo_null();
}

function echo_null()
{
    echo json_encode(null);
}

function echo_invalid()
{
    $response["code"] = "400";
    $response["status"] = "Error";
    $response["description"] = "No error data found or invalid request";
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