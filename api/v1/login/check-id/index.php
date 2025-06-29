<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["login-id"]) && isset($post["token"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $users_table, $tokens_table;
        $login_id = $post["login-id"];
        $token = $post["token"];

        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["user-id"];

            $stmt = $c->prepare("SELECT * FROM $users_table WHERE `email` = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $password_from_users_table = $row["password"];

                $stmt = $c->prepare("SELECT * FROM $tokens_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");
                $stmt->bind_param("s", $login_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();


                if ($result->num_rows > 0) {
                    $found = false;
                    $password_decrypted = null;
                    $password_encrypted = null;

                    while (($row = $result->fetch_assoc()) && !$found) {
                        $password_temp_decrypted = decryptTextWithPassword($row["password"], $token);

                        if (encryptHash($password_temp_decrypted) == $password_from_users_table) {
                            $found = true;
                            $password_decrypted = $password_temp_decrypted;
                            $password_encrypted = $row["password"];
                        }
                    }
                    $row = $result->fetch_assoc();

                    if ($found) {
                        $password_hash = encryptHash($password_decrypted);

                        //now it's found the password using token, login-id
                        // START of the code ====

                        $response = echo_result(null);

                        //END of the code ====
                    } else {
                        $response = echo_error(405);
                    }
                } else {
                    $response = echo_error(404);
                }
            } else {
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

    //http_response_code($code);

    switch ($code) {
        case 400:
            $response["description"] = "Missing parameters";
            break;
        case 401:
            $response["description"] = "Database connection error";
            break;
        case 402:
            $response["description"] = "Login-id not found, disabled, expired or invalid";
            break;
        case 403:
            $response["description"] = "User-id not found";
            break;
        case 404:
            $response["description"] = "Token not found, disabled, expired or invalid";
            break;
        case 405:
            $response["description"] = "Token not valid";
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