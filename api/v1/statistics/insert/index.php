<?php
header("Content-Type: application/json");

$action = "";
if (isset($_GET["action"])) {
    $action = $_GET["action"];
}

$url = "https://saveriomorelli.com/api/savpdfviewer/v1/statistics/insert/index.php?action=" . $action;
// User data to send using HTTP POST method in curl

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);

?>