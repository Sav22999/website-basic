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

        global $users_table;

        //if email exists, check if password is correct, in case 402
        //then, update the verification code with a new one and send it to the email ONLY IF the "status" field is 0

        $password = encryptHash($post["password"]);
        $email_hash = encryptHash($post["email"]);

        $query_check = "SELECT * FROM $users_table WHERE `password` = ? AND `email` = ? AND `status` = 0";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("ss", $password, $email_hash);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $verification_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);

            $query_update = "UPDATE $users_table SET `verification-code` = ? WHERE `email` = ? AND `password` = ?";
            $stmt_update = $c->prepare($query_update);
            $c->query("LOCK TABLES $users_table WRITE");
            $stmt_update->bind_param("sss", $verification_code, $row["email"], $password);
            $c->query("UNLOCK TABLES");
            $stmt_update->execute();
            $stmt_update->close();

            $username = decryptTextWithPassword($row["username"], $post["password"]);
            $verification_code = decryptTextWithPassword($verification_code, $post["password"]);
            sendEmailSignup($username, $post["email"], $verification_code, true);

            $response = echo_result(null);
        } else {
            $response = echo_error(412);
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
            $response["description"] = "Connection error";
            break;
        case 412:
            $response["description"] = "Invalid credentials or already verified";
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