<?php
$docs_end_point = "https://www.notefox.eu/api";

function getErrorDescription($code)
{
    switch ($code) {
        case 400:
            return "Missing parameters";
        case 401:
            return "Database connection error";
        case 402:
            return "Login-id not found, disabled, expired or invalid";
        case 403:
            return "User-id not found";
        case 404:
            return "Token not found, disabled, expired or invalid";
        case 405:
            return "Token not valid";
        case 406:
            return "Invalid parameters for the request";
        case 410:
            return "Invalid credentials";
        case 411:
            return "User is not active";
        case 412:
            return "Invalid credentials or already verified";
        case 413:
            return "Invalid verification code";
        case 414:
            return "User already verified";
        case 415:
            return "Invalid login-id or already verified";
        case 416:
            return "Email already used for another account";
        case 417:
            return "Invalid deleting code";
        case 418:
            return "Invalid credentials, account already deleted or deleting request expired";
        case 419:
            return "Email already used for another account but not verified yet";
        case 420:
            return "It's not an Array or it's empty";
        case 499:
            return "Unknown error";
        case 201:
            return "Data not found";
        case 451:
            return "Login-id already disabled or expired";
        case 452:
            return "You already requested a deleting code. Please wait for the email or ask for a new code";
    }
}

?>