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

        global $users_table, $logins_table, $tokens_table;

        $password = encryptHash($post["password"]);
        $email_hash = encryptHash(strtolower($post["email"]));


        $query_check = "SELECT * FROM $users_table WHERE `password` = ? AND `email` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("ss", $password, $email_hash);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();

            //password is correct, check if user is active
            if ($row["status"] == 1) {
                //user is active, generate login-id and insert it into logins table
                $user_id = $row["email"];
                $username = decryptTextWithPassword($row["username"], $post["password"]);
                $ip_address = getIpAddress();
                $verification_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);

                $login_id = encryptHash($user_id . $ip_address . getTimestamp());
                $expiry = null;

                $query_insert = "INSERT INTO $logins_table (`login-id`, `user-id`, `expiry`, `status`, `ip-address`, `verified`, `verification-code`) VALUES (?, ?, ?, 0, ?, NULL, ?)";
                $stmt_insert = $c->prepare($query_insert);
                $c->query("LOCK TABLES $logins_table WRITE");
                $stmt_insert->bind_param("sssss", $login_id, $user_id, $expiry, $ip_address, $verification_code);
                $c->query("UNLOCK TABLES");
                $stmt_insert->execute();
                $stmt_insert->close();

                sendEmailLogin($username, $post["email"], decryptTextWithPassword($verification_code, $post["password"]), $ip_address, false);

                $response = echo_result(array("login-id" => $login_id));
            } else {
                $response = echo_error(411);
            }
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
        case 411:
            $response["description"] = "User is not active";
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