<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["password"]) && isset($post["new-password"]) && isset($post["login-id"]) && isset($post["token"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $users_table, $data_table, $tokens_table;

        $login_id = $post["login-id"];
        $password = $post["password"];
        $password_hash = encryptHash($password);
        $new_password = $post["new-password"];
        $new_password_hash = encryptHash($new_password);
        $token = $post["token"];

        //from logins, get the user-id in users
        //then, check the password is correct
        //then, update the password in users --> need to decrypt with the old password and encrypt with the new password
        //then, update all data in data table with the new password (same: decrypt with the old password and encrypt with the new password)
        //then, update the password in logins (decrypt with the old password and encrypt with the new password)
        //BEFORE to update all data, in all tables, LOCK the tables and then UNLOCK them at the end

        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["user-id"];

            //get the username from users, where email = $user_id and password = $password_hash

            $stmt = $c->prepare("SELECT * FROM $users_table WHERE `email` = ? AND `password` = ?");
            $stmt->bind_param("ss", $user_id, $password_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $username = $row["username"];
            }

            $new_username = encryptTextWithPassword(decryptTextWithPassword($username, $password), $new_password);

            $stmt = $c->prepare("SELECT * FROM $users_table WHERE `email` = ? AND `password` = ?");
            $stmt->bind_param("ss", $user_id, $password_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $stmt = $c->prepare("UPDATE $users_table SET `password` = ?, `username` = ? WHERE `email` = ? AND `password` = ?");
                $c->query("LOCK TABLES $users_table WRITE");
                $stmt->bind_param("ssss", $new_password_hash, $new_username, $user_id, $password_hash);
                $c->query("UNLOCK TABLES");
                $stmt->execute();
                $stmt->close();

                $stmt = $c->prepare("SELECT * FROM $data_table WHERE `user-id` = ? LIMIT 50"); //update only the latest 50 data
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                //iterate over all data and update them
                while ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                    $new_data = encryptTextWithPassword(decryptTextWithPassword($row["data"], $password), $new_password);
                    $old_data = $row["data"];
                    $id = $row["id"];

                    $stmt = $c->prepare("UPDATE $data_table SET `data` = ? WHERE `user-id` = ? AND `data` = ? AND `id` = ?");
                    $c->query("LOCK TABLES $data_table WRITE");
                    $stmt->bind_param("sssi", $new_data, $user_id, $old_data, $id);
                    $c->query("UNLOCK TABLES");
                    $stmt->execute();
                    $stmt->close();
                }

                $new_password_token = encryptTextWithPassword($new_password, $token);
                //get the token row from tokens where login-id = $login_id and then check the password
                //then, update the token row linked to the login-id with status = 1 and the password is updated
                $stmt = $c->prepare("SELECT * FROM $tokens_table WHERE `login-id` = ? AND `status` = 1 AND (`expiry` > NOW() OR `expiry` IS NULL)");
                $stmt->bind_param("s", $login_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                    $password_token = $row["password"];
                    $password_decrypted = decryptTextWithPassword($password_token, $token);

                    if ($password_decrypted === $password) {
                        $stmt = $c->prepare("UPDATE $tokens_table SET `password` = ? WHERE `login-id` = ? AND `password` = ?");
                        $c->query("LOCK TABLES $tokens_table WRITE");
                        $stmt->bind_param("sss", $new_password_token, $login_id, $password_token);
                        $c->query("UNLOCK TABLES");
                        $stmt->execute();
                        $stmt->close();

                        //get all unique login-id from logins where user-id = $user_id
                        //then, update all tokens with the old password (so linked to the userid) to status = 0
                        $stmt = $c->prepare("SELECT DISTINCT `login-id` FROM $logins_table WHERE `user-id` = ?");
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();

                        while ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                            $login_id = $row["login-id"];
                            $stmt = $c->prepare("UPDATE $tokens_table SET `status` = 0 WHERE `login-id` = ? AND NOT `password` = ?");
                            $c->query("LOCK TABLES $tokens_table WRITE");
                            $stmt->bind_param("ss", $login_id, $new_password_token);
                            $c->query("UNLOCK TABLES");
                            $stmt->execute();
                            $stmt->close();
                        }

                        $response = echo_result(null);
                    } else {
                        $response = echo_error(405);
                    }
                }
            } else {
                $response = echo_error(410);
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
        case 405:
            $response["description"] = "Token not valid";
            break;
        case 410:
            $response["description"] = "Invalid credentials";
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