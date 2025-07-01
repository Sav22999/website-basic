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

function sendEmailSignup($username, $to_email, $code, $ip_address, $new_code = false)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another verification code." : "Thank you for signing up to Notefox.";
    $message_title = $new_code ? "New code to verify your email" : "Verify your email";

    $section_1 = $message_title;
    $section_2 = $message_code . "To confirm your login, please use the following code:";
    $section_3 = "If you didn't log in to Notefox, you should definitely change your password.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "", $message);
    $message = str_replace("{{code}}", $code, $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "", $message);
    $message = str_replace("{{ip-address}}", $ip_address, $message);

    $to = $to_email;
    $subject = "Notefox: verify your email";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailSignedup($username, $to_email, $ip_address)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $section_1 = "Account created";
    $section_2 = "You just created a Notefox account with this email.";
    $section_3 = "If you didn't sign up to Notefox, please ignore this email.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "hidden", $message);
    $message = str_replace("{{code}}", "", $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "", $message);
    $message = str_replace("{{ip-address}}", $ip_address, $message);

    $to = $to_email;
    $subject = "Notefox: account created";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailLogin($username, $to_email, $code, $ip_address, $verification_expiry, $new_code = false)
{
//send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another otp to verify the login process.<br>" : "";
    $message_title = $new_code ? "New code to log in" : "Confirm your log in";

    $section_1 = $message_title;
    $section_2 = $message_code . "To confirm your login, please use the following code:";
    $section_3 = "The code will be valid for 30 minutes (until " . $verification_expiry . ").<br>If you didn't log in to Notefox, you should definitely change your password.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "", $message);
    $message = str_replace("{{code}}", $code, $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "", $message);
    $message = str_replace("{{ip-address}}", $ip_address, $message);

    $to = $to_email;
    $subject = "Notefox: confirm your login";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailLoggedin($username, $to_email, $ip_address)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $section_1 = "Just logged in";
    $section_2 = "You just logged in to your Notefox account.";
    $section_3 = "If you haven't logged in to Notefox, please change your password immediately.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "hidden", $message);
    $message = str_replace("{{code}}", "", $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "", $message);
    $message = str_replace("{{ip-address}}", $ip_address, $message);

    $to = $to_email;
    $subject = "Notefox: just logged in";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function sendEmailDeleting($username, $to_email, $code, $ip_address, $expiry, $new_code = false)
{
    //send email from no-reply@notefox.eu to the email with the verification code (unencrypted)

    $message_code = $new_code ? "You required another otp to confirm the deleting of your Notefox Account.<br>" : "";
    $message_title = $new_code ? "New code to delete account" : "Confirm deleting account";

    $section_1 = $message_title;
    $section_2 = $message_code . "To confirm you want to delete permanently your account, please use the following deleting code:";
    $section_3 = "The code will be valid for 10 minutes (until " . $expiry . ").<br>If you didn't ask for deleting your Notefox account, please change your password immediately.<br>Once deleted the account, all data will be definitely deleted from database and you'll lose data forever.<br><br>If you asked for deleting your account, but you changed your mind, please ignore this email.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "", $message);
    $message = str_replace("{{code}}", $code, $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "", $message);
    $message = str_replace("{{ip-address}}", $ip_address, $message);

    $to = $to_email;
    $subject = "Notefox: confirm deleting account";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@notefox.eu\r\n";

    mail($to, $subject, $message, $headers);
}

function sendEmailDeleted($username, $to_email)
{
    //send email from

    $section_1 = "Account permanently deleted";
    $section_2 = "Your Notefox account is now deleted permanently, together to all your data.<br>I'm really sorry about your decision to leave Notefox.";
    $section_3 = "If you would like creating a new one, you can also reuse this email address.";

    $message = getEmailTemplate();
    $message = str_replace("{{username}}", $username, $message);
    $message = str_replace("{{section-1}}", $section_1, $message);
    $message = str_replace("{{section-2}}", $section_2, $message);
    $message = str_replace("{{hidden-code}}", "hidden", $message);
    $message = str_replace("{{code}}", "", $message);
    $message = str_replace("{{section-3}}", $section_3, $message);
    $message = str_replace("{{hidden-ip-address}}", "hidden", $message);
    $message = str_replace("{{ip-address}}", "", $message);

    $to = $to_email;
    $subject = "Notefox: account deleted";

    $headers = "From: no-reply@notefox.eu\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($to, $subject, $message, $headers);
}

function getEmailTemplate()
{
    $path = $_SERVER['DOCUMENT_ROOT'] . "";
    return file_get_contents($path . "/include/email-template.php");
}

?>