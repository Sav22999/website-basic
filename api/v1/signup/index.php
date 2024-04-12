<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["username"]) && isset($post["password"]) && isset($post["email"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        $username = encryptTextWithPassword($post["username"], $post["password"]);
        $email = encryptHash($post["email"]); //hashing the email (not encrypting it, because it's used for login, not for display)
        $password = encryptHash($post["password"]);
        $ip_address = getIpAddress();
        $created = getTimestamp();
        $verification_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);

        global $users_table;

        //check if email doesn't already exist
        $query_check = "SELECT * FROM $users_table WHERE `email` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows === 0) {
            //if email doesn't exist
            $query = "INSERT INTO $users_table (`username`, `email`, `password`, `ip-address`, `created`, `verification-code`, `verified`, `status`) VALUES (?, ?, ?, ?, ?, ?, NULL, 0)";
            $stmt = $c->prepare($query);
            $c->query("LOCK TABLES $users_table WRITE");
            $stmt->bind_param("ssssss", $username, $email, $password, $ip_address, $created, $verification_code);
            $c->query("UNLOCK TABLES");
            $stmt->execute();
            $stmt->close();

            sendEmailSignup(decryptTextWithPassword($username, $post["password"]), $post["email"], decryptTextWithPassword($verification_code, $post["password"]), false);

            $response = echo_result(null);
        } else {//if email already exists
            $response = echo_error(416);
        }

        $c->close();
    } else {
        $response = echo_error(401);
    }

    echo json_encode($response);
} else {
    $missing_parameters = array();
    if (!isset($post["username"])) {
        array_push($missing_parameters, "username");
    }
    if (!isset($post["password"])) {
        array_push($missing_parameters, "password");
    }
    if (!isset($post["email"])) {
        array_push($missing_parameters, "email");
    }
    $response = echo_error(400);
    //$response = echo_error(400 . " - Missing parameters: " . implode(", ", $missing_parameters));
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
        case 416:
            $response["description"] = "Email already used for another account";
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