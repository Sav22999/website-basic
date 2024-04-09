<?php
function encryptHash($text)
{
    return hash("sha512", $text);
}

function deriveKeyFromPassword($password, $salt, $keyLength = 32, $iterations = 10000, $algorithm = 'sha256')
{
    return hash_pbkdf2($algorithm, $password, $salt, $iterations, $keyLength, true);
}

function encryptTextWithPassword($text, $password)
{
    $salt = openssl_random_pseudo_bytes(16); // Generate a random salt
    $key = deriveKeyFromPassword($password, $salt);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encryptedText = openssl_encrypt($text, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($salt . $iv . $encryptedText);
}

function decryptTextWithPassword($encryptedText, $password)
{
    $decoded = base64_decode($encryptedText);
    $salt = substr($decoded, 0, 16);
    $iv = substr($decoded, 16, openssl_cipher_iv_length('aes-256-cbc'));
    $encryptedText = substr($decoded, 16 + openssl_cipher_iv_length('aes-256-cbc'));
    $key = deriveKeyFromPassword($password, $salt);
    return openssl_decrypt($encryptedText, 'aes-256-cbc', $key, 0, $iv);
}

function getIpAddress()
{
    $ip_address = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR']);
    $ip_address = $ip_address ?: "Unknown";
    return $ip_address;
}

function getTimestamp()
{
    return date('Y-m-d H:i:s');
}

function getNewValidationCode($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }
    return $randomString;
}

function getCorrectedDateTimestamp($date)
{
    return date('Y-m-d H:i:s', strtotime($date));
}

function getRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }
    return $randomString;
}

function sendEmailSignup($username, $to_email, $code, $new_code = false)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another verification code." : "Thank you for signing up to Notefox.";

    $to = $to_email;
    $subject = "Notefox account: verify your email";
    $message = "Hello " . $username . ",<br>";
    $message .= $message_code;
    $message .= "<br>To verify your email, please use the following code: <b><code>" . $code . "</code></b><br><br>";
    $message .= "<small>If you didn't sign up to Notefox, please ignore this email.</small><br><br>";
    $message .= "Best regards,<br>Sav, the developer of Notefox";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailLogin($username, $to_email, $code, $new_code = false)
{
//send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another otp to verify the login process.<br>" : "";

    $to = $to_email;
    $subject = "Notefox account: confirm your login";
    $message = "Hello " . $username . ",<br>";
    $message .= $message_code;
    $message .= "To confirm your login, please use the following code: <b><code>" . $code . "</code></b><br><br>";
    $message .= "<small>If you didn't log in to Notefox, you should definitely change your password.</small><br><br>";
    $message .= "Best regards,<br>Sav, the developer of Notefox";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

?>