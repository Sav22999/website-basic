<?php
/**
 * Sav Account API v2 - one-time codes (signup, login OTP, account deletion,
 * password change, disabling the OTP).
 *
 * v1 generates them with rand(), never limits the attempts, and in some flows
 * never consumes them. Here every code is:
 *   - generated with random_int();
 *   - stored encrypted with the password (v1 column format, so the schema does
 *     not change);
 *   - given an expiry and a maximum number of attempts;
 *   - compared in constant time and consumed on success.
 *
 * The expiry/attempts columns are additive: when they do not exist yet the
 * code still works, just without that extra protection.
 */

define("CODE_RESULT_OK", "ok");
define("CODE_RESULT_MISSING", "missing");
define("CODE_RESULT_EXPIRED", "expired");
define("CODE_RESULT_INVALID", "invalid");
define("CODE_RESULT_BLOCKED", "blocked");

define("CODE_MAX_ATTEMPTS", 5);

/**
 * Column layout of a code. Only "code" is mandatory.
 */
function v2_code_columns($code, $expiry = null, $attempts = null)
{
    return array("code" => $code, "expiry" => $expiry, "attempts" => $attempts);
}

function v2_code_column_available($c, $table, $columns, $name)
{
    return isset($columns[$name]) && $columns[$name] !== null && db_has_column($c, $table, $columns[$name]);
}

/**
 * Issues a new code and stores it. Returns array("code" => ..., "expiry" => ...)
 * or null when the write failed.
 */
function v2_code_issue($c, $table, $key_column, $key_value, $password, $columns, $ttl_minutes = 30, $length = 6)
{
    $code = v2_secure_code($length);
    $encrypted = encryptTextWithPassword($code, $password);
    $expiry = date("Y-m-d H:i:s", strtotime("+" . ((int)$ttl_minutes) . " minutes"));

    $sets = array("`" . $columns["code"] . "` = ?");
    $types = "s";
    $params = array($encrypted);

    if (v2_code_column_available($c, $table, $columns, "expiry")) {
        $sets[] = "`" . $columns["expiry"] . "` = ?";
        $types .= "s";
        $params[] = $expiry;
    }
    if (v2_code_column_available($c, $table, $columns, "attempts")) {
        $sets[] = "`" . $columns["attempts"] . "` = 0";
    }

    $types .= "s";
    $params[] = $key_value;

    $affected = db_execute(
        $c,
        "UPDATE `$table` SET " . implode(", ", $sets) . " WHERE `$key_column` = ?",
        $types,
        $params
    );
    // 0 rows means no such row: the code has NOT been stored, so it must not
    // be emailed either.
    if ($affected <= 0) {
        return null;
    }

    return array("code" => $code, "expiry" => $expiry);
}

/**
 * Verifies a code against a row already read from the database.
 * Returns one of the CODE_RESULT_* constants; on a wrong code the attempt
 * counter is incremented.
 */
function v2_code_verify($c, $table, $key_column, $key_value, $row, $password, $columns, $provided, $max_attempts = CODE_MAX_ATTEMPTS)
{
    if (!is_array($row) || !isset($row[$columns["code"]]) || $row[$columns["code"]] === null || $row[$columns["code"]] === "") {
        return CODE_RESULT_MISSING;
    }

    $has_attempts = v2_code_column_available($c, $table, $columns, "attempts");
    if ($has_attempts && isset($row[$columns["attempts"]]) && ((int)$row[$columns["attempts"]]) >= $max_attempts) {
        return CODE_RESULT_BLOCKED;
    }

    if (v2_code_column_available($c, $table, $columns, "expiry")) {
        $expiry = isset($row[$columns["expiry"]]) ? $row[$columns["expiry"]] : null;
        if ($expiry !== null && strtotime($expiry) < time()) {
            return CODE_RESULT_EXPIRED;
        }
    }

    $expected = decryptTextWithPassword($row[$columns["code"]], $password);
    if ($expected === false || $expected === null || !v2_code_equals($expected, $provided)) {
        if ($has_attempts) {
            db_execute(
                $c,
                "UPDATE `$table` SET `" . $columns["attempts"] . "` = `" . $columns["attempts"] . "` + 1 WHERE `$key_column` = ?",
                "s",
                array($key_value)
            );
        }
        return CODE_RESULT_INVALID;
    }

    return CODE_RESULT_OK;
}

/**
 * Consumes/clears a code (always called right after a successful check, so a
 * code can never be replayed).
 */
function v2_code_clear($c, $table, $key_column, $key_value, $columns, $extra_sets = "")
{
    $sets = array("`" . $columns["code"] . "` = NULL");
    if (v2_code_column_available($c, $table, $columns, "expiry")) {
        $sets[] = "`" . $columns["expiry"] . "` = NULL";
    }
    if (v2_code_column_available($c, $table, $columns, "attempts")) {
        $sets[] = "`" . $columns["attempts"] . "` = 0";
    }
    if ($extra_sets !== "") {
        $sets[] = $extra_sets;
    }

    return db_execute(
            $c,
            "UPDATE `$table` SET " . implode(", ", $sets) . " WHERE `$key_column` = ?",
            "s",
            array($key_value)
        ) >= 0;
}

/**
 * Maps a CODE_RESULT_* to the API error. Never called with CODE_RESULT_OK.
 */
function v2_code_error($result)
{
    switch ($result) {
        case CODE_RESULT_EXPIRED:
            return ERR_CODE_EXPIRED;
        case CODE_RESULT_MISSING:
            return ERR_CODE_NOT_REQUESTED;
        case CODE_RESULT_BLOCKED:
            return ERR_TOO_MANY_ATTEMPTS;
        default:
            return ERR_CODE_INVALID;
    }
}

/**
 * Column layouts used by the v2 endpoints.
 */
function v2_codes_signup()
{
    return v2_code_columns("verification-code", "verification-expiry", "verification-attempts");
}

function v2_codes_login()
{
    return v2_code_columns("verification-code", "verification-expiry", "verification-attempts");
}

function v2_codes_delete()
{
    return v2_code_columns("deleting-code", "deleting-expiry", "deleting-attempts");
}

function v2_codes_otp_change()
{
    return v2_code_columns("otp-change-code", "otp-change-expiry", "otp-change-attempts");
}

/**
 * Password change: the second factor is always required here, exactly like for
 * the account deletion, so a stolen token (or a password typed on a borrowed
 * device) can never be used to take an account over.
 */
function v2_codes_password()
{
    return v2_code_columns("password-change-code", "password-change-expiry", "password-change-attempts");
}

?>
