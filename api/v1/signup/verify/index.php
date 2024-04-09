<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

//$post=$get;

$condition = isset($post["email"]) && isset($post["verification-code"]) && isset($post["password"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $users_table;
        $password = encryptHash($post["password"]);
        $verification_code = $post["verification-code"];
        $now = getTimestamp();

        //check if email exists, in case 401
        $query_check = "SELECT * FROM $users_table WHERE `password` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("s", $password);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        $row = $result_check->fetch_assoc();
        //check there is at least one row
        if ($result_check->num_rows > 0) {
            if (decryptTextWithPassword($row["email"], $post["password"]) === $post["email"]) {
                if ($row["verified"] === null) {
                    if (decryptTextWithPassword($row["verification-code"], $post["password"]) === $verification_code) {
                        //update the status to 1 (verified), verified with the current datetime (getTimestamp()) and verification code to NULL
                        //where email is the email passed and the verification code is the verification code passed
                        $query_update = "UPDATE $users_table SET `status` = 1, `verification-code` = NULL, `verified` = ? WHERE `email` = ?";
                        $stmt_update = $c->prepare($query_update);
                        $stmt_update->bind_param("ss", $now, $row["email"]);
                        $c->query("LOCK TABLES $users_table WRITE");
                        $stmt_update->execute();
                        $c->query("UNLOCK TABLES");
                        $stmt_update->close();

                        $response = echo_result(null);
                    } else {
                        $response = echo_error(404);
                    }
                } else {
                    $response = echo_error(403);
                }
            } else {
                $response = echo_error(402);
            }
        } else {
            $response = echo_error(405);
        }

        $stmt_check->close();

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
            $response["description"] = "Invalid password";
            break;
        case 403:
            $response["description"] = "Email already verified";
            break;
        case 404:
            $response["description"] = "Invalid verification code";
            break;
        case 405:
            $response["description"] = "Email not found";
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