<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

//$post=$get;

$condition = isset($post["email"]) && isset($post["verification-code"]) && isset($post["password"]) && isset($post["login-id"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $logins_table, $tokens_table, $users_table;
        $password = encryptHash($post["password"]);
        $verification_code_passed = $post["verification-code"];
        $now = getTimestamp();
        $login_id = $post["login-id"];
        $ip_address = getIpAddress();

        //check the login-id in logins table (the status must be "not active" (0))
        //and the user-id in users table (the status must be "active" (1))
        //then check the verification code
        //if verification code decrypted from database is equals to the verification code passed
        //then update the status in logins table to "active" (1), the verification code to NULL and the verified field to $now
        //in addition, need to add to the tokens table a new token with the login-id, a token

        $stmt = $c->prepare("SELECT * FROM $logins_table WHERE `login-id` = ? AND `status` = 0 AND `verified` IS NULL AND (`expiry` > NOW() OR `expiry` IS NULL)");
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["user-id"];
            $verification_code = decryptTextWithPassword($row["verification-code"], $post["password"]);
            if ($verification_code == $verification_code_passed) {
                //get the username from users where password = $password and if the email is correct
                $stmt = $c->prepare("SELECT * FROM $users_table WHERE `password` = ? AND `email` = ?");
                $stmt->bind_param("ss", $password, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    //here the code after the checking of the password

                    $stmt = $c->prepare("UPDATE $logins_table SET `status` = 1, `verification-code` = NULL, `verified` = ? WHERE `login-id` = ? AND `status` = 0 AND `verified` IS NULL AND (`expiry` > NOW() OR `expiry` IS NULL)");
                    $c->query("LOCK TABLES $logins_table WRITE");
                    $stmt->bind_param("ss", $now, $login_id);
                    $c->query("UNLOCK TABLES");
                    $stmt->execute();
                    $stmt->close();

                    $token = encryptHash(getRandomString(10) . $now . $login_id . $ip_address);
                    $password_token = encryptTextWithPassword($post["password"], $token);
                    $expiry = null;

                    $stmt = $c->prepare("INSERT INTO $tokens_table (`id`, `password`, `login-id`, `expiry`, `ip-address`, `inserted-date`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, 1)");
                    $c->query("LOCK TABLES $tokens_table WRITE");
                    $stmt->bind_param("sssss", $password_token, $login_id, $expiry, $ip_address, $now);
                    $c->query("UNLOCK TABLES");
                    $stmt->execute();
                    $stmt->close();

                    $response = echo_result(array("token" => $token, "login-id" => $login_id, "expiry" => $expiry, "username" => decryptTextWithPassword($row["username"], $post["password"])));

                    //end
                } else {
                    $response = echo_error(410);
                }
            } else {
                $response = echo_error(413);
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
            $response["description"] = "Login-id not found, disabled, expired or invalid";
            break;
        case 413:
            $response["description"] = "User already verified";
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
    $response["code"] = "200";
    $response["status"] = "Successful";
    $response["data"] = $data;
    return $response;
}

?>