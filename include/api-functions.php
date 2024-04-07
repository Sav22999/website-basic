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

?>