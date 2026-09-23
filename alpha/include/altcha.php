<?php

function altcha_create_challenge($hmac_key)
{
    $salt = bin2hex(random_bytes(12));
    $number = random_int(10000, 100000);
    $challenge = hash('sha256', $salt . $number);
    $signature = hash_hmac('sha256', $challenge, $hmac_key);

    return json_encode(array(
        'algorithm' => 'SHA-256',
        'challenge' => $challenge,
        'salt' => $salt,
        'signature' => $signature,
        'maxnumber' => 100000,
    ));
}

function altcha_verify($payload_b64, $hmac_key)
{
    $json = base64_decode($payload_b64);
    if ($json === false) return false;

    $data = json_decode($json, true);
    if (!$data || !isset($data['algorithm'], $data['challenge'], $data['number'], $data['salt'], $data['signature'])) {
        return false;
    }

    if ($data['algorithm'] !== 'SHA-256') return false;

    $expected_challenge = hash('sha256', $data['salt'] . $data['number']);
    $expected_signature = hash_hmac('sha256', $expected_challenge, $hmac_key);

    return hash_equals($data['challenge'], $expected_challenge)
        && hash_equals($data['signature'], $expected_signature);
}
