<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["login-id"]) && isset($post["data"]) && isset($post["updated-locally"]) && isset($post["password"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $data_table, $users_table;

        //from logins, get the user-id
        //then, insert the data in the data table
        //if login-id is wrong (disabled, not existing, expiry < current_time ...), return 402
        //if password is wrong, return 403
        //if there are issues to insert data, return 404

        $login_id = $post["login-id"];
        $password = $post["password"];
        $data = encryptTextWithPassword($post["data"], $password);

        //check login-id, status and expiry date
        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");

        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["user-id"];

            $ip_address = getIpAddress();
            $now = getTimestamp();

            $stmt = $c->prepare("SELECT * FROM $users_table WHERE `email` = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $stmt = $c->prepare("INSERT INTO $data_table (`id`, `user-id`, `data`, `updated-locally-date`, `inserted-date`, `ip-address`) VALUES (NULL, ?, ?, ?, ?, ?)");
                $c->query("LOCK TABLES $data_table WRITE");
                $stmt->bind_param("sssss", $user_id, $data, $post["updated-locally"], $now, $ip_address);
                $c->query("UNLOCK TABLES");
                $stmt->execute();
                $stmt->close();

                $response = echo_result(null);
            } else {
                //login-id looks like correct, but not linked to any user (maybe the user has been deleted)
                $response = echo_error(403);
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
        case 403:
            $response["description"] = "User not found";
            break;
        case 404:
            $response["description"] = "Error inserting data";
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