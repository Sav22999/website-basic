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
    $subject = "Notefox: verify your email";
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
    $subject = "Notefox: confirm your login";
    $message = "Hello " . $username . ",<br>";
    $message .= $message_code;
    $message .= "To confirm your login, please use the following code: <b><code>" . $code . "</code></b><br><br>";
    $message .= "<small>If you didn't log in to Notefox, you should definitely change your password.</small><br><br>";
    $message .= "Best regards,<br>Sav, the developer of Notefox";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailDeleting($username, $to_email, $code, $ip_address, $expiry, $new_code = false)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another otp to confirm the deleting of your Notefox Account.<br>" : "";

    $to = $to_email;
    $subject = "Notefox: confirm deleting account";
    $message = "Hello " . $username . ",<br>";
    $message .= $message_code;
    $message .= "To confirm you want to delete your account, please use the following deleting code: <b><code>" . $code . "</code></b><br><small>The code will expire in 10 minutes ($expiry).</small><br><br>";
    $message .= "<small>If you didn't ask for deleting your Notefox account, please change your password immediately.<br>Once deleted the account, all data will be definitely deleted from database and you'll lose data forever.</small><br><br>";
    $message .= "<small>If you asked for deleting your account, but you changed your mind, please ignore this email.</small><br><br>";
    $message .= "Best regards,<br>Sav, the developer of Notefox";
    $message .= "<br><br><small>Request received from: " . $ip_address . "</small>";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailDeleted($username, $to_email)
{
    //send email from

    $to = $to_email;
    $subject = "Notefox: account deleted";
    $message = "Hello " . $username . ",<br>";
    $message .= "Your Notefox account is now deleted permanently, together to all your data.<br>I'm really sorry about your decision to leave Notefox.<br><br>";
    $message .= "<small>If you would like creating a new one, you can reuse the same email.</small><br><br>";
    $message .= "Best regards,<br>Sav, the developer of Notefox";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

?>