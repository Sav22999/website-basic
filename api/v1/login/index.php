<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["email"]) && isset($post["password"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $users_table, $logins_table;

        $email = encryptHash($post["email"]);
        $password = encryptHash($post["password"]);


        //check if email exists, in case 401
        $query_check = "SELECT * FROM $users_table WHERE `email` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        //if email exists, check if password is correct, in case 402
        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            if ($row["password"] === $password) {
                //password is correct, check if user is active, in case 405
                if ($row["status"] == 1) {
                    //user is active, generate login-id and insert it into logins table
                    $user_id = $row["email"];
                    $username = decryptTextWithPassword($row["username"], $post["password"]);
                    $ip_address = encryptTextWithPassword(getIpAddress(), $post["password"]);

                    $login_id = encryptHash($user_id . $ip_address . getTimestamp());
                    $expiry = null;

                    $query_insert = "INSERT INTO $logins_table (`login-id`, `user-id`, `expiry`, `status`, `ip-address`) VALUES (?, ?, ?, 1, ?)";
                    $stmt_insert = $c->prepare($query_insert);
                    $c->query("LOCK TABLES $logins_table WRITE");
                    $stmt_insert->bind_param("ssss", $login_id, $user_id, $expiry, $ip_address);
                    $c->query("UNLOCK TABLES");
                    $stmt_insert->execute();
                    $stmt_insert->close();

                    $response = echo_result(array("login-id" => $login_id, "expiry" => $expiry, "username" => $username));
                } else {
                    $response = echo_error(405);
                }
            } else {
                $response = echo_error(402);
            }
        } else {
            $response = echo_error(401);
        }

        $c->close();
    } else {
        $response = echo_error(403);
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
            $response["description"] = "Email doesn't exist";
            break;
        case 402:
            $response["description"] = "Password is incorrect";
            break;
        case 403:
            $response["description"] = "Connection error";
            break;
        case 404:
            $response["description"] = "Insert error";
            break;
        case 405:
            $response["description"] = "User is not active";
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