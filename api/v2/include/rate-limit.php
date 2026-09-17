<?php
/**
 * Sav Account API v2 - rate limiting.
 *
 * v1 has none: passwords and the 6 characters of the OTP can be brute forced,
 * and the get-new-code endpoints can be used to bomb a mailbox.
 *
 * One counter per (bucket, subject). The subject is always hashed, so no email
 * address or IP is stored in clear in the rate limits table.
 */

define("RL_WINDOW_DEFAULT", 900);   // 15 minutes
define("RL_BLOCK_DEFAULT", 900);

function v2_rate_limit_subject($value)
{
    return substr(hash("sha512", "notefox-rl:" . strtolower(trim((string)$value))), 0, 128);
}

/**
 * Registers an attempt. Returns true when the request may proceed, false when
 * the caller is currently blocked.
 */
function v2_rate_limit_allow($c, $bucket, $subject, $max_attempts, $window_seconds = RL_WINDOW_DEFAULT, $block_seconds = RL_BLOCK_DEFAULT)
{
    global $rate_limits_table;

    if ($max_attempts < 1) {
        return false;
    }
    if (!db_has_table($c, $rate_limits_table)) {
        // Without the additive table the API still answers, it just cannot
        // count the attempts (reported by GET /status as schema: false).
        return true;
    }

    $subject_hash = v2_rate_limit_subject($subject);
    $now = time();
    $now_sql = date("Y-m-d H:i:s", $now);

    $row = db_select_one(
        $c,
        "SELECT * FROM `$rate_limits_table` WHERE `bucket` = ? AND `subject` = ? LIMIT 1",
        "ss",
        array($bucket, $subject_hash)
    );

    if ($row === null) {
        db_execute(
            $c,
            "INSERT INTO `$rate_limits_table` (`bucket`, `subject`, `attempts`, `window-start`, `blocked-until`) VALUES (?, ?, 1, ?, NULL) ON DUPLICATE KEY UPDATE `attempts` = `attempts` + 1",
            "sss",
            array($bucket, $subject_hash, $now_sql)
        );
        return true;
    }

    if ($row["blocked-until"] !== null && strtotime($row["blocked-until"]) > $now) {
        return false;
    }

    $window_start = strtotime($row["window-start"]);
    if ($window_start === false || $window_start + $window_seconds < $now) {
        db_execute(
            $c,
            "UPDATE `$rate_limits_table` SET `attempts` = 1, `window-start` = ?, `blocked-until` = NULL WHERE `id` = ?",
            "si",
            array($now_sql, (int)$row["id"])
        );
        return true;
    }

    $attempts = ((int)$row["attempts"]) + 1;
    if ($attempts > $max_attempts) {
        $blocked_until = date("Y-m-d H:i:s", $now + $block_seconds);
        db_execute(
            $c,
            "UPDATE `$rate_limits_table` SET `attempts` = ?, `blocked-until` = ? WHERE `id` = ?",
            "isi",
            array($attempts, $blocked_until, (int)$row["id"])
        );
        return false;
    }

    db_execute($c, "UPDATE `$rate_limits_table` SET `attempts` = ? WHERE `id` = ?", "ii", array($attempts, (int)$row["id"]));
    return true;
}

/**
 * Same as above but answers directly with the rate limit error.
 */
function v2_rate_limit($c, $bucket, $subject, $max_attempts, $window_seconds = RL_WINDOW_DEFAULT, $block_seconds = RL_BLOCK_DEFAULT)
{
    if (!v2_rate_limit_allow($c, $bucket, $subject, $max_attempts, $window_seconds, $block_seconds)) {
        api_error(ERR_RATE_LIMITED);
    }
}

/**
 * Clears a counter after a successful operation (e.g. a correct login).
 */
function v2_rate_limit_reset($c, $bucket, $subject)
{
    global $rate_limits_table;
    if (!db_has_table($c, $rate_limits_table)) {
        return;
    }
    db_execute(
        $c,
        "DELETE FROM `$rate_limits_table` WHERE `bucket` = ? AND `subject` = ?",
        "ss",
        array($bucket, v2_rate_limit_subject($subject))
    );
}

function v2_rate_limit_forget_subject($c, $subject)
{
    global $rate_limits_table;
    if (!db_has_table($c, $rate_limits_table)) {
        return;
    }
    db_execute($c, "DELETE FROM `$rate_limits_table` WHERE `subject` = ?", "s", array(v2_rate_limit_subject($subject)));
}

?>
