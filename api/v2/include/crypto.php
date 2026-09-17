<?php
/**
 * Sav Account API v2 - cryptography.
 *
 * Envelope encryption:
 *   - the data is encrypted with a random per-user key (the DEK, "XX");
 *   - the DEK itself is encrypted (wrapped) with a key derived from the
 *     password (the KEK).
 * Changing the password therefore re-wraps ~100 bytes of key material instead
 * of re-encrypting the notes, which is what corrupts old rows in
 * api/v1/password/edit/index.php (it only re-encrypts `LIMIT 50` rows).
 *
 * All random material comes from random_bytes()/random_int(); v1 uses rand()
 * for the OTP codes.
 */

define("V2_WRAP_PREFIX", "nfk1:");
define("V2_WRAP_ITERATIONS", 210000);
define("V2_WRAP_SALT_BYTES", 16);
define("V2_DEK_BYTES", 32);
define("V2_CODE_ALPHABET", "23456789ABCDEFGHJKLMNPQRSTUVWXYZ");

/**
 * A new Data Encryption Key, as a printable string (it is used as the
 * passphrase of encryptTextWithPassword(), so it must be text).
 */
function v2_generate_dek()
{
    return rtrim(strtr(base64_encode(random_bytes(V2_DEK_BYTES)), "+/", "-_"), "=");
}

/**
 * Fingerprint of a DEK, stored in `user_keys`.`key-check`: it proves an
 * unwrap produced the right key without ever storing the key itself.
 */
function v2_key_check($dek)
{
    return hash("sha512", "notefox-dek:" . $dek);
}

/**
 * Wraps the DEK with the password. Format:
 *   "nfk1:" . base64(salt(16) | iv(16) | AES-256-CBC(dek))
 */
function v2_wrap_key($dek, $password)
{
    $salt = random_bytes(V2_WRAP_SALT_BYTES);
    $key = hash_pbkdf2("sha256", $password, $salt, V2_WRAP_ITERATIONS, 32, true);
    $iv = random_bytes(openssl_cipher_iv_length("aes-256-cbc"));
    $cipher = openssl_encrypt($dek, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
    if ($cipher === false) {
        return null;
    }
    return V2_WRAP_PREFIX . base64_encode($salt . $iv . $cipher);
}

/**
 * Unwraps the DEK. Returns null - never throws - when the password is wrong,
 * the payload is malformed or the result does not match $check.
 */
function v2_unwrap_key($wrapped, $password, $check = null)
{
    if (!is_string($wrapped) || strpos($wrapped, V2_WRAP_PREFIX) !== 0) {
        return null;
    }
    $decoded = base64_decode(substr($wrapped, strlen(V2_WRAP_PREFIX)), true);
    if ($decoded === false) {
        return null;
    }

    $iv_length = openssl_cipher_iv_length("aes-256-cbc");
    if (strlen($decoded) <= V2_WRAP_SALT_BYTES + $iv_length) {
        return null;
    }

    $salt = substr($decoded, 0, V2_WRAP_SALT_BYTES);
    $iv = substr($decoded, V2_WRAP_SALT_BYTES, $iv_length);
    $cipher = substr($decoded, V2_WRAP_SALT_BYTES + $iv_length);

    $key = hash_pbkdf2("sha256", $password, $salt, V2_WRAP_ITERATIONS, 32, true);
    $dek = openssl_decrypt($cipher, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
    if ($dek === false || $dek === "") {
        return null;
    }

    if ($check !== null && !hash_equals($check, v2_key_check($dek))) {
        return null;
    }

    return $dek;
}

/**
 * Encrypts/decrypts a payload with the DEK. It delegates to the shared v1
 * helpers so that the format stays a single one across the whole project.
 */
function v2_encrypt_with_dek($plaintext, $dek)
{
    return encryptTextWithPassword($plaintext, $dek);
}

function v2_decrypt_with_dek($ciphertext, $dek)
{
    $plain = decryptTextWithPassword($ciphertext, $dek);
    return $plain === false ? null : $plain;
}

/**
 * A cryptographically secure verification code (v1 uses rand()).
 * The alphabet has no ambiguous characters (0/O, 1/I/l).
 */
function v2_secure_code($length = 6)
{
    $alphabet = V2_CODE_ALPHABET;
    $max = strlen($alphabet) - 1;
    $code = "";
    for ($i = 0; $i < $length; $i++) {
        $code .= $alphabet[random_int(0, $max)];
    }
    return $code;
}

/**
 * An unpredictable opaque identifier (login-id, token): 64 hex characters.
 * v1 derives the login-id from sha512(email + ip + timestamp), which is
 * guessable by anyone who knows the email address.
 */
function v2_random_id($bytes = 32)
{
    return bin2hex(random_bytes($bytes));
}

/**
 * Constant-time comparison of two user supplied codes (case-insensitive).
 */
function v2_code_equals($expected, $provided)
{
    if (!is_string($expected) || !is_string($provided)) {
        return false;
    }
    return hash_equals(strtoupper(trim($expected)), strtoupper(trim($provided)));
}
?>
