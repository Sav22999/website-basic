<?php
/**
 * Sav Account API v2 - database access.
 *
 * v1 writes `if ($c = new mysqli(...))`, which never detects a failure (the
 * constructor never returns a falsy value) and leaks warnings. Here the
 * connection is checked properly and every failure becomes a generic error.
 *
 * Transactions replace the v1 LOCK TABLES statements, which were placed around
 * bind_param() instead of execute() and therefore protected nothing.
 */

function db_connect()
{
    static $connection = null;
    if ($connection !== null) {
        return $connection;
    }

    global $localhost_db, $username_db, $password_db, $database_notefox;

    try {
        $c = @new mysqli($localhost_db, $username_db, $password_db, $database_notefox);
    } catch (Throwable $e) {
        error_log("[sav-account] db connect: " . $e->getMessage());
        return null;
    }

    if ($c->connect_errno) {
        error_log("[sav-account] db connect: " . $c->connect_error);
        return null;
    }

    $c->set_charset("utf8mb4");
    $connection = $c;
    return $connection;
}

/**
 * Connection or a 401/503 answer: endpoints never have to handle the failure.
 */
function db()
{
    $c = db_connect();
    if ($c === null) {
        api_error(ERR_DATABASE);
    }
    return $c;
}

/**
 * True while a db_tx() callback is running. Inside a transaction a failing
 * query must throw (so db_tx can roll back) instead of answering and exiting.
 */
function db_in_transaction($delta = 0)
{
    static $depth = 0;
    $depth += $delta;
    if ($depth < 0) {
        $depth = 0;
    }
    return $depth > 0;
}

/**
 * Runs $callback inside a transaction. The callback receives the connection
 * and may throw to trigger a rollback.
 */
function db_tx($c, $callback)
{
    $c->begin_transaction();
    db_in_transaction(1);
    try {
        $result = $callback($c);
        db_in_transaction(-1);
        $c->commit();
        return $result;
    } catch (Throwable $e) {
        db_in_transaction(-1);
        $c->rollback();
        throw $e;
    }
}

/**
 * A query failed: throw inside a transaction, answer otherwise.
 */
function db_fail($message)
{
    error_log("[sav-account] " . $message);
    if (db_in_transaction()) {
        throw new RuntimeException($message);
    }
    api_error(ERR_DATABASE);
}

/**
 * Prepared statement or null (the error only goes to the log).
 */
function db_prepare($c, $sql)
{
    $stmt = $c->prepare($sql);
    if ($stmt === false) {
        error_log("[sav-account] prepare failed: " . $c->error . " | " . $sql);
        return null;
    }
    return $stmt;
}

/**
 * Executes a prepared statement and returns the rows as an array.
 * $types/$params follow the bind_param() convention.
 */
function db_select($c, $sql, $types = "", $params = array())
{
    $stmt = db_prepare($c, $sql);
    if ($stmt === null) {
        // The statement itself, never the parameters: a failing SELECT answers
        // "Database connection error" to the client, so the log is the only
        // place that can tell WHICH table or column is missing.
        db_fail("select prepare failed | " . $sql);
        return array();
    }
    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }
    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        db_fail("select failed: " . $error . " | " . $sql);
        return array();
    }
    $result = $stmt->get_result();
    $rows = array();
    if ($result !== false) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $stmt->close();
    return $rows;
}

function db_select_one($c, $sql, $types = "", $params = array())
{
    $rows = db_select($c, $sql, $types, $params);
    return count($rows) > 0 ? $rows[0] : null;
}

/**
 * Executes an INSERT/UPDATE/DELETE. Returns the number of affected rows,
 * or -1 on failure (the caller decides what to do).
 */
function db_execute($c, $sql, $types = "", $params = array())
{
    $stmt = db_prepare($c, $sql);
    if ($stmt === null) {
        return -1;
    }
    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }
    if (!$stmt->execute()) {
        error_log("[sav-account] execute failed: " . $stmt->error . " | " . $sql);
        $stmt->close();
        return -1;
    }
    $affected = $stmt->affected_rows;
    $stmt->close();
    return $affected;
}

/**
 * Escapes the LIKE wildcards of a table/column name, so that `notefox_data`
 * cannot be matched by a different table whose name only differs where the
 * underscore is.
 */
function db_like_escape($value)
{
    return str_replace(array("\\", "%", "_"), array("\\\\", "\\%", "\\_"), (string)$value);
}

/**
 * Runs a COUNT(*) against information_schema.
 *
 * Returns true/false when the answer is trustworthy and NULL when the query
 * itself could not run: on some shared hostings information_schema is
 * restricted, and answering "the table does not exist" there would make the
 * API report a missing schema (409) on a perfectly working installation.
 */
function db_schema_count($c, $sql, $types, $params)
{
    $stmt = $c->prepare($sql);
    if ($stmt === false) {
        error_log("[sav-account] information_schema prepare failed: " . $c->error);
        return null;
    }

    $answer = null;
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result !== false ? $result->fetch_assoc() : null;
        if ($row !== null) {
            $answer = (int)$row["n"] > 0;
        }
    } else {
        error_log("[sav-account] information_schema query failed: " . $stmt->error);
    }
    $stmt->close();

    return $answer;
}

/**
 * SHOW based probe: authoritative and always allowed on the own schema.
 * Returns true/false, or NULL when the probe itself could not run.
 */
function db_show_probe($c, $sql)
{
    $result = @$c->query($sql);
    if ($result === false) {
        return null;
    }
    $exists = $result->num_rows > 0;
    $result->free();
    return $exists;
}

/**
 * True when $table has $column. Used to degrade gracefully when the additive
 * DDL of the v2 API has not been applied yet (the answer stays valid JSON).
 */
function db_has_column($c, $table, $column)
{
    static $cache = array();
    $key = $table . "." . $column;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $exists = db_schema_count(
        $c,
        "SELECT COUNT(*) AS `n` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = ? AND `COLUMN_NAME` = ?",
        "ss",
        array($table, $column)
    );

    if ($exists !== true) {
        // Either information_schema is not readable or it says no: SHOW decides.
        $probe = db_show_probe(
            $c,
            "SHOW COLUMNS FROM `" . str_replace("`", "", $table) . "` LIKE '" . $c->real_escape_string(db_like_escape($column)) . "'"
        );
        if ($probe !== null) {
            $exists = $probe;
        }
    }

    if ($exists === null) {
        // Inconclusive: never cached, so a transient failure cannot make the
        // whole request lifetime believe the schema is incomplete.
        return false;
    }

    $cache[$key] = $exists;
    return $exists;
}

function db_has_table($c, $table)
{
    static $cache = array();
    if (array_key_exists($table, $cache)) {
        return $cache[$table];
    }

    $exists = db_schema_count(
        $c,
        "SELECT COUNT(*) AS `n` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = ?",
        "s",
        array($table)
    );

    if ($exists !== true) {
        $probe = db_show_probe(
            $c,
            "SHOW TABLES LIKE '" . $c->real_escape_string(db_like_escape($table)) . "'"
        );
        if ($probe !== null) {
            $exists = $probe;
        }
    }

    if ($exists === null) {
        return false;
    }

    $cache[$table] = $exists;
    return $exists;
}

?>
