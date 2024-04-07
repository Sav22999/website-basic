<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["username"]) && isset($post["password"]) && isset($post["email"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        $username = encryptTextWithPassword($post["username"], $post["password"]);
        $email = encryptTextWithPassword($post["email"], $post["password"]);
        $password = encryptHash($post["password"]);
        $ip_address = getIpAddress();
        $created = getTimestamp();
        $verification_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);

        $link_email_verify = "https://notefox.eu/account/verify/?";

        global $users_table;

        //check if email doesn't already exist
        $query_check = "SELECT * FROM $users_table WHERE `email` = ?";
        $stmt_check = $c->prepare($query_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows > 0) {
            //if email already exists
            $response = echo_error(401);
        } else {
            //if email doesn't exist
            $query = "INSERT INTO $users_table (`username`, `email`, `password`, `ip-address`, `created`, `verification-code`, `verified`, `status`) VALUES (?, ?, ?, ?, ?, ?, NULL, 0)";
            if ($stmt = $c->prepare($query)) {
                $c->query("LOCK TABLES $users_table WRITE");
                $stmt->bind_param("ssssss", $username, $email, $password, $ip_address, $created, $verification_code);
                $c->query("UNLOCK TABLES");
                $stmt->execute();
                $stmt->close();

                //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)
                $to = $post["email"];
                $subject = "Notefox account: verify your email";
                $message = "Hello " . decryptTextWithPassword($username, $post["password"]) . ",<br>";
                $message .= "Thank you for signing up to Notefox. To verify your email, please use the following code: <b><code>" . decryptTextWithPassword($verification_code, $post["password"]) . "</code></b> or <a href='" . $link_email_verify . "code=" . decryptTextWithPassword($verification_code, $post["email"]) . "&email=" . $post["email"] . "'>click here</a> to verify automatically.<br><br>";
                $message .= "<small>If you didn't sign up to Notefox, please ignore this email.</small><br><br>";
                $message .= "Best regards,<br>Sav, the developer of Notefox";
                $headers = "From: no-reply@notefox.eu\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                mail($to, $subject, $message, $headers);

                $response = echo_result(null);
            } else {
                $response = echo_error(402);
            }
        }

        $c->close();
    } else {
        $response = echo_error(403);
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
            $response["description"] = "Email already exists. Please use another one";
            break;
        case 402:
            $response["description"] = "Error while creating the account";
            break;
        case 403:
            $response["description"] = "Error while connecting to the database";
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