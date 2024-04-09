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

        global $logins_table, $data_table, $users_table, $tokens_table;

        //get the password using the token passed as the decrypting key from tokens table
        //decrypt the password using the token passed
        //get the user-id from logins where login-id = $login_id
        //get the email from users where password = $password_hash
        //if email is correct, then get the latest data from data table where user-id = $user_id (sort by "updated-locally-date")
        //then return the data

        $login_id = $post["login-id"];

        $stmt = $c->prepare("SELECT * FROM $tokens_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL) ORDER BY `inserted-date` DESC");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $password_encrypted = $row["password"];
            $password = decryptTextWithPassword($password_encrypted, $post["token"]);
            $password_hash = encryptHash($password);

            $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");
            $stmt->bind_param("s", $login_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row["user-id"];

                $stmt = $c->prepare("SELECT * FROM $users_table WHERE `password` = ?");
                $stmt->bind_param("s", $password_hash);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $email = $row["email"];

                    if (decryptTextWithPassword($email, $password) == decryptTextWithPassword($user_id, $password)) {

                        //here the code after the checking of the password

                        $stmt = $c->prepare("SELECT * FROM $data_table WHERE `user-id` = ? ORDER BY `updated-locally-date` DESC LIMIT 1"); //get the latest data
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $response = echo_result(array("data" => decryptTextWithPassword($row["data"], $password), "updated-locally" => $row["updated-locally-date"], "updated-server" => $row["inserted-date"]));
                        } else {
                            $response = echo_error(406);
                        }

                        //end of the code after the checking of the password

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
    switch ($code) {
        case 400:
            $response["description"] = "Missing parameters";
            break;
        case 401:
            $response["description"] = "Connection error";
            break;
        case 402:
            $response["description"] = "Login-id not found or inactive"; //in tokens
            break;
        case 403:
            $response["description"] = "Login-id not found or inactive"; //in logins
            break;
        case 404:
            $response["description"] = "User not found"; //or password is incorrect
            break;
        case 405:
            $response["description"] = "User not found"; //or password is incorrect
            break;
        case 406:
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
    $response["code"] = "200";
    $response["status"] = "Successful";
    $response["data"] = $data;
    return $response;
}

?>