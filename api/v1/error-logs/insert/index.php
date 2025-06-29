<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["error-logs"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $error_logs_table;

        // START of the code ====

        // $post["error-data"] is an Array with the following keys (check they exist):
        // - datetime
        // - context
        // - error
        // - url
        // it should send item of the Array as a batch request

        $data = $post["error-logs"]; // [{"datetime": ..., "context": "...", "error": "...", "url": "..."}]

        //check it's an Array and it's not empty
        if (is_array($data) && count($data) > 0) {
            //create the SQL query for the batch insert (prepared statement)
            $stmt = $c->prepare("INSERT INTO $error_logs_table (`id`, `local-date`, `inserted-date`, `context`, `error`, `url`) VALUES (NULL, ?, NULL, ?, ?, ?)");
            //$c->query("LOCK TABLES $error_logs_table WRITE");

            $any_error = false;

            $datetime_error = false;
            $context_error = false;
            $error_error = false;

            foreach ($data as $item) {
                if (isset($item["datetime"]) && isset($item["context"]) && isset($item["error"])) {
                    //nothing
                } else {
                    //if any of the keys is missing, return an error
                    $any_error = true;

                    if (!isset($item["datetime"])) {
                        $datetime_error = true;
                    }
                    if (!isset($item["context"])) {
                        $context_error = true;
                    }
                    if (!isset($item["error"])) {
                        $error_error = true;
                    }
                }
            }

            if (!$any_error) {
                foreach ($data as $item) {
                    $client_datetime = $item["datetime"];
                    $context = $item["context"];
                    $error_message = $item["error"];
                    $url = "";
                    if (isset($item["url"])) {
                        $url = $item["url"];
                    }

                    //bind the parameters
                    $stmt->bind_param("ssss", $client_datetime, $context, $error_message, $url);
                    //execute the statement
                    $stmt->execute();
                }

                $response = echo_result(null);
            } else {
                $response = echo_error(406);

                $missing_params = array();
                if ($datetime_error) {
                    $missing_params[] = "datetime";
                }
                if ($context_error) {
                    $missing_params[] = "context";
                }
                if ($error_error) {
                    $missing_params[] = "error";
                }
                $response["description"] = $response["description"] . ": " . implode(", ", $missing_params);
            }


            //$c->query("UNLOCK TABLES");
            $stmt->close();
        } else {
            $response = echo_error(420);
        }

        //END of the code ====

        $c->close();
    } else {
        $response = echo_error(401);
    }

    echo json_encode($response);
} else {
    $response = echo_error(400);
    $response["description"] = $response["description"] . ": error-logs";
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
        case 406:
            $response["description"] = "Invalid parameters for the request";
            break;
        case 420:
            $response["description"] = "It's not an Array or it's empty";
            break;
        default:
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