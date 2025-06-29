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

            $now = getTimestamp();

            if ($row["deleting-expiry"] < $now) {
                //already expired ||OR|| not yet request a deleting code


                //update user (in users) as "in deleting", so set a deleting-code, a deleting-expiry (10 minutes)

                $deleting_code = encryptTextWithPassword(getNewValidationCode(6), $post["password"]);
                $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

                $query_update = "UPDATE $users_table SET `deleting-code` = ?, `deleting-expiry` = ? WHERE `email` = ? AND `password` = ? AND `status` = '1'";
                $stmt_update = $c->prepare($query_update);
                $c->query("LOCK TABLES $users_table WRITE");
                $stmt_update->bind_param("ssss", $deleting_code, $expiry, $email_hash, $password);
                $c->query("UNLOCK TABLES");
                $stmt_update->execute();
                $stmt_update->close();

                $username = decryptTextWithPassword($row["username"], $post["password"]);
                $ip_address = getIpAddress();

                sendEmailDeleting($username, $post["email"], decryptTextWithPassword($deleting_code, $post["password"]), $ip_address, $expiry, false);

                $response = echo_result(null);
            } else {
                //already requested a deleting code
                $response = echo_error(452);
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

    //http_response_code($code);

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
        case 452:
            $response["description"] = "You already requested a deleting code. Please wait for the email or try again later.";
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