<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["password"]) && isset($post["new-password"]) && isset($post["login-id"]);
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

        //from logins, get the user-id in users
        //then, check the password is correct
        //then, update the password in users --> need to decrypt with the old password and encrypt with the new password
        //then, update all data in data table with the new password (same: decrypt with the old password and encrypt with the new password)
        //then, update the password in logins (decrypt with the old password and encrypt with the new password)
        //BEFORE to update all data, in all tables, LOCK the tables and then UNLOCK them at the end

        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ?");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_username = encryptTextWithPassword(decryptTextWithPassword($row["username"], $password), $new_password);
            $new_user_id = encryptTextWithPassword(decryptTextWithPassword($row["email"], $password), $new_password);

            $user_id = $row["user-id"];

            $stmt = $c->prepare("SELECT * FROM $users_table WHERE `email` = ? AND `password` = ?");
            $stmt->bind_param("ss", $user_id, $password_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $stmt = $c->prepare("UPDATE $users_table SET `password` = ?, `username` = ?, `email` = ? WHERE `email` = ? AND `password` = ?");
                $c->query("LOCK TABLES $users_table WRITE");
                $stmt->bind_param("sssss", $new_password_hash, $new_username, $new_user_id, $user_id, $password_hash);
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

                    $stmt = $c->prepare("UPDATE $data_table SET `data` = ?, `user-id` = ? WHERE `user-id` = ? AND `data` = ? AND `id` = ?");
                    $c->query("LOCK TABLES $data_table WRITE");
                    $stmt->bind_param("ssssi", $new_data, $new_user_id, $user_id, $old_data, $id);
                    $c->query("UNLOCK TABLES");
                    $stmt->execute();
                    $stmt->close();
                }

                //all tokens with the old password (so linked to the userid) became invalid (status = 0)
                //get all unique login-id from logins where user-id = $user_id
                //then, update all tokens with the old password (so linked to the userid) to status = 0
                $stmt = $c->prepare("SELECT DISTINCT `login-id` FROM $logins_table WHERE `user-id` = ?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                while ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                    $login_id = $row["login-id"];
                    $stmt = $c->prepare("UPDATE $tokens_table SET `status` = 0 WHERE `login-id` = ?");
                    $c->query("LOCK TABLES $tokens_table WRITE");
                    $stmt->bind_param("s", $login_id);
                    $c->query("UNLOCK TABLES");
                    $stmt->execute();
                    $stmt->close();
                }


                $stmt = $c->prepare("UPDATE $logins_table SET `user-id` = ? WHERE `user-id` = ?");
                $c->query("LOCK TABLES $logins_table WRITE");
                $stmt->bind_param("ss", $new_user_id, $user_id);
                $c->query("UNLOCK TABLES");
                $stmt->execute();
                $stmt->close();

                $response = echo_result("old email ${row["email"]} - new email $new_user_id - old username ${row["username"]} - new username $new_username - old password $password - new password $new_password");
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
            $response["description"] = "Database connection error";
            break;
        case 402:
            $response["description"] = "Login ID not found";
            break;
        case 403:
            $response["description"] = "Wrong password";
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