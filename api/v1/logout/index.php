<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["login-id"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $users_table;

        $login_id = $post["login-id"];
        $all_devices = isset($get["all-devices"]) && $get["all-devices"] == "true";

        //check if login-id (already encrypted) from logins table exists, in case 401

        $query_check = "SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("s", $login_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        //if exists, update the status to 0 (inactive) but, before,
        //check if it's passed (GET) "all-devices" -> if it's "true", then ALL login-id and not only the login-id passed
        //with the same user-id (get it from users table) will be updated to status 0 (inactive). If the query is successful, return 200, else 403

        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $user_id = $row["user-id"];

            $query_update = "UPDATE $logins_table SET `status` = 0 WHERE `user-id` = ?";
            if (!$all_devices) {
                //NOT all devices, update only the login-id passed
                $query_update .= " AND `login-id` = ?";
            }

            $stmt_update = $c->prepare($query_update);
            $c->query("LOCK TABLES $logins_table WRITE");
            if ($all_devices) {
                $stmt_update->bind_param("s", $user_id);
            } else {
                $stmt_update->bind_param("ss", $user_id, $login_id);
            }
            $c->query("UNLOCK TABLES");
            $stmt_update->execute();
            $stmt_update->close();

            $response = echo_result(null);
        } else {
            $response = echo_error(451);
        }

        $c->close();
    } else {
        $response = echo_error(401);
    }

    echo json_encode($response);
} else {
    $response = echo_error(400);
    echo json_encode($response);
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
            $response["description"] = "Missing parameters";
            break;
        case 401:
            $response["description"] = "Database connection error";
            break;
        case 451:
            $response["description"] = "Login-id already disabled or expired";
            break;
        default:
            $response["description"] = "Unknown error";
            break;
    }
    return $response;
}

function echo_result($data)
{
    $response["code"] = "200";
    $response["status"] = "Successful";
    $response["data"] = $data;
    return $response;
}

?>