<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$link_email_verify = "https://notefox.eu/verify-email?";

$condition = isset($post["email"]) && isset($post["password"]) && isset($post["login-id"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $users_table, $logins_table;

        //if email exists, check if password is correct, in case 402
        //then, update the verification code with a new one and send it to the email ONLY IF the "status" field is 0

        $password = encryptHash($post["password"]);
        $email_hash = encryptHash(strtolower($post["email"]));

        //if email exists, check if password is correct
        //then, check if login-id is linked to the email (which is the user-id of logins)
        //if it's correct, update the verification code with a new one and send it to the email

        $query_check = "SELECT * FROM $users_table WHERE `password` = ? AND `email` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("ss", $password, $email_hash);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();

            $user_id = $row["email"];

            //here the code after the checking of the password

            $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `user-id` = ? AND `status` = 0");
            $stmt->bind_param("ss", $post["login-id"], $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $verification_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);
                $stmt = $c->prepare("UPDATE $logins_table SET `verification-code` = ? WHERE `login-id` = ? AND `user-id` = ? AND `status` = 0");
                $c->query("LOCK TABLES $logins_table WRITE");
                $stmt->bind_param("sss", $verification_code, $post["login-id"], $user_id);
                $c->query("UNLOCK TABLES");
                $stmt->execute();
                $stmt->close();

                $ip_address = getIpAddress();
                sendEmailLogin(decryptTextWithPassword($row["username"], $post["password"]), $post["email"], decryptTextWithPassword($verification_code, $post["password"]), $ip_address, true);

                $response = echo_result(null);
            } else {
                $response = echo_error(415);
            }

            //end of the code after the checking of the password

        } else {
            $response = echo_error(410);
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
        case 410:
            $response["description"] = "Invalid credentials";
            break;
        case 415:
            $response["description"] = "Invalid login-id or already verified";
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