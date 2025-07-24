<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/credentials.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/api-functions.php");
global $redirect_table, $opened_table, $localhost_db, $username_db, $password_db, $database_notefox;
header("Content-Type:application/json");
$post = json_decode(file_get_contents('php://input'), true); //POST request
$get = $_GET; //GET request

$condition = isset($post["telemetry"]);
if ($condition) {
    $response = null;

    //Using prepared statements -> it's the safest way for MySQL queries
    if ($c = new mysqli($localhost_db, $username_db, $password_db, $database_notefox)) {
        $c->set_charset("utf8mb4");

        global $telemetry_table;

        // START of the code ====

        $data = $post["telemetry"]; // Array of telemetry data

        //check it's an Array and it's not empty
        if (is_array($data) && count($data) > 0) {
            //create the SQL query for the batch insert (prepared statement)
            $stmt = $c->prepare("INSERT INTO $telemetry_table (`id`, `notefox-account`, `anonymous-userid`, `client-datetime`, `server-datetime`, `language`, `action`, `context`, `url`, `browser`, `browser-version`, `notefox-version`, `os`, `other`) VALUES (NULL, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            //$c->query("LOCK TABLES $error_logs_table WRITE");

            $any_error = false;

            $anonymous_userid_error = false;
            $client_datetime_error = false;
            $language_error = false;
            $action_error = false;
            $browser_error = false;
            $notefox_version_error = false;
            $browser_version_error = false;

            foreach ($data as $item) {
                if (isset($item["anonymous-userid"]) && isset($item["client-datetime"]) && isset($item["language"]) && isset($item["action"]) && isset($item["browser"]) && isset($item["notefox-version"])) {
                    //nothing
                } else {
                    //if any of the keys is missing, return an error
                    $any_error = true;

                    if (!isset($item["anonymous-userid"])) {
                        $anonymous_userid_error = true;
                    }
                    if (!isset($item["client-datetime"])) {
                        $client_datetime_error = true;
                    }
                    if (!isset($item["language"])) {
                        $language_error = true;
                    }
                    if (!isset($item["action"])) {
                        $action_error = true;
                    }
                    if (!isset($item["browser"])) {
                        $browser_error = true;
                    }
                    if (!isset($item["browser-version"])) {
                        $browser_version_error = true;
                    }
                    if (!isset($item["notefox-version"])) {
                        $notefox_version_error = true;
                    }
                }
            }

            if (!$any_error) {
                foreach ($data as $item) {
                    //notefox-account (true|false)
                    //anonymous-userid [required]
                    //client-datetime [required]
                    //language [required]
                    //action [required]
                    //context
                    //url
                    //browser [required]
                    //browser-version
                    //notefox-version [required]
                    //os
                    //other-note

                    $notefox_account = null;
                    if (isset($item["notefox-account"])) {
                        $notefox_account = $item["notefox-account"];
                    }
                    $anonymous_userid = $item["anonymous-userid"];
                    $client_datetime = $item["client-datetime"];
                    $language = $item["language"];
                    $action = $item["action"];
                    $context = null;
                    if (isset($item["context"])) {
                        $context = $item["context"];
                    }
                    $url = null;
                    if (isset($item["url"])) {
                        $url = $item["url"];
                    }
                    $browser = $item["browser"];
                    $browser_version = null;
                    if (isset($item["browser-version"])) {
                        $browser_version = $item["browser-version"];
                    }
                    $notefox_version = $item["notefox-version"];
                    $os = null;
                    if (isset($item["os"])) {
                        $os = $item["os"];
                    }
                    $other = null;
                    if (isset($item["other"])) {
                        $other = $item["other"];
                    }

                    //bind the parameters
                    $stmt->bind_param("isssssssssss",
                        $notefox_account, //notefox-account
                        $anonymous_userid, //anonymous-userid
                        $client_datetime, //client-datetime
                        $language, //language
                        $action, //action
                        $context, //context
                        $url, //url
                        $browser, //browser
                        $browser_version, //browser-version
                        $notefox_version, //notefox-version
                        $os, //os
                        $other //other-note
                    );
                    //execute the statement
                    $stmt->execute();
                }

                /*$response = echo_result(
                    array(
                        "error" => $stmt->error,
                        "code" => $stmt->errno,
                        "inserted" => $stmt->affected_rows,
                    )
                );*/
                $response = echo_result(null);
            } else {
                $response = echo_error(406);

                $missing_params = array();
                if ($anonymous_userid_error) {
                    $missing_params[] = "anonymous-userid";
                }
                if ($client_datetime_error) {
                    $missing_params[] = "client-datetime";
                }
                if ($language_error) {
                    $missing_params[] = "language";
                }
                if ($action_error) {
                    $missing_params[] = "action";
                }
                if ($browser_error) {
                    $missing_params[] = "browser";
                }
                if ($browser_version_error) {
                    $missing_params[] = "browser-version";
                }
                if ($notefox_version_error) {
                    $missing_params[] = "notefox-version";
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
    $response["description"] = $response["description"] . ": telemetry";
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