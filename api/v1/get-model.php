<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
//$request = json_decode(file_get_contents('php://input'), true); //POST request
$request = $_GET; //GET request

$condition = isset($request[""]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");


        $c->close();
    }

    echo json_encode($response);
} else {
    echo_null();
}

function echo_null()
{
    echo json_encode(null);
}

function echo_error($code)
{
    $response["code"] = $code;
    $response["status"] = "Error";
    switch ($code) {
        case 400:
            $response["description"] = "";
            break;
        case 401:
            $response["description"] = "";
            break;
        case 404:
            $response["description"] = "Page doesn't exist or has expired";
            break;
    }
    return $response;
}

function echo_result($count, $openings, $date, $inserted_timestamp)
{
    $response["code"] = "200";
    $response["status"] = "Successful";
    $data[""] = "";
    $response["data"] = $data;
    return $response;
}

?>