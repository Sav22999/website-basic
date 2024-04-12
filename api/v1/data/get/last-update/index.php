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

        global $logins_table, $data_table;

        //from logins, get the user-id and then get the latest data from the data table
        $login_id = $post["login-id"];
        //check login-id, status and expiry date
        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1  AND (`expiry` > NOW() OR `expiry` IS NULL)");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["user-id"];

            $stmt = $c->prepare("SELECT * FROM $data_table WHERE `user-id` = ? ORDER BY `updated-locally-date` DESC LIMIT 1");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response = echo_result(array("updated-locally" => $row["updated-locally-date"], "updated-server" => $row["inserted-date"]));
            } else {
                $response = echo_error(450);
            }
        } else {
            $response = echo_error(402);
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
        case 402:
            $response["description"] = "Login-id not found, disabled or expired";
            break;
        case 450:
            $response["description"] = "Data not found";
            break;
        default:
            $response["description"] = "Unknown error";
            break;
    }
    return $response;
}

function echo_result($data)
{
    $response["code"] = 200;
    $response["status"] = "Successful";
    $response["data"] = $data;
    return $response;
}

?>