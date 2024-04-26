<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

//$post=$get;

$condition = isset($post["email"]) && isset($post["deleting-code"]) && isset($post["password"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $users_table, $logins_table, $tokens_table, $data_table;
        $password = encryptHash($post["password"]);
        $deleting_code = $post["deleting-code"];
        $now = getTimestamp();
        $email_hash = encryptHash(strtolower($post["email"]));

        //check if email exists, in case 401
        $query_check = "SELECT * FROM $users_table WHERE `password` = ? AND `email` = ? AND `deleting-code` IS NOT NULL AND `deleting-expiry` > ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("sss", $password, $email_hash, $now);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        $row = $result_check->fetch_assoc();

        //check there is at least one row
        if ($result_check->num_rows > 0) {
            $username = decryptTextWithPassword($row["username"], $post["password"]);

            if (decryptTextWithPassword($row["deleting-code"], $post["password"]) === $deleting_code) {
                //get all login-id in logins with the user-id=$email_hash and delete the token in tokens with the login-id
                //then, delete all login-id in logins with the user-id=$email_hash
                //then, delete all data linked to the user-id=$email_hash
                //finally, delete the user in users with the email=$email_hash and password=$password
                //if everything is ok, send an email (sendEmailDeleted)

                $query_delete_tokens = "DELETE FROM $tokens_table WHERE `login-id` IN (SELECT `login-id` FROM $logins_table WHERE `user-id` = ?)";
                $stmt_delete_tokens = $c->prepare($query_delete_tokens);
                $c->query("LOCK TABLES $tokens_table WRITE");
                $stmt_delete_tokens->bind_param("s", $email_hash);
                $c->query("UNLOCK TABLES");
                $stmt_delete_tokens->execute();
                $stmt_delete_tokens->close();

                $query_delete_logins = "DELETE FROM $logins_table WHERE `user-id` = ?";
                $stmt_delete_logins = $c->prepare($query_delete_logins);
                $c->query("LOCK TABLES $logins_table WRITE");
                $stmt_delete_logins->bind_param("s", $email_hash);
                $c->query("UNLOCK TABLES");
                $stmt_delete_logins->execute();
                $stmt_delete_logins->close();

                $query_delete_data = "DELETE FROM $data_table WHERE `user-id` = ?";
                $stmt_delete_data = $c->prepare($query_delete_data);
                $c->query("LOCK TABLES $data_table WRITE");
                $stmt_delete_data->bind_param("s", $email_hash);
                $c->query("UNLOCK TABLES");
                $stmt_delete_data->execute();
                $stmt_delete_data->close();

                $query_delete_user = "DELETE FROM $users_table WHERE `email` = ? AND `password` = ?";
                $stmt_delete_user = $c->prepare($query_delete_user);
                $c->query("LOCK TABLES $users_table WRITE");
                $stmt_delete_user->bind_param("ss", $email_hash, $password);
                $c->query("UNLOCK TABLES");
                $stmt_delete_user->execute();
                $stmt_delete_user->close();

                sendEmailDeleted($username, $post["email"]);

                $response = echo_result(null);
            } else {
                $response = echo_error(417);
            }
        } else {
            $response = echo_error(418);
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
        case 418:
            $response["description"] = "Invalid credentials, account already deleted or deleting request expired";
            break;
        case 414:
            $response["description"] = "User already verified";
            break;
        case 417:
            $response["description"] = "Invalid deleting code";
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