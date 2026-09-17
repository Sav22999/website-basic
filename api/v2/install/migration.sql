-- =====================================================================
-- Sav Account API v2 - additive migration
-- =====================================================================
--
-- Single source of truth for the SQL of `api/v2/API.md` §2.
-- MySQL / MariaDB, InnoDB. Can be pasted in phpMyAdmin or run with
--   mysql -u USER -p DATABASE < api/v2/install/migration.sql
-- after having chosen ONE of the two branches of block 2 (see below).
--
-- IMPORTANT
--   * Everything here is ADDITIVE: no v1 table (`notefox_data`, `users`,
--     `logins`, `tokens`) is renamed, emptied or altered in a way v1 can
--     notice. v1 keeps working on the very same database.
--   * There is NO data migration script, and none is needed. The data key
--     (DEK) and the promotion of the legacy row happen lazily, at the first
--     successful v2 login of each account, in `v2_get_or_create_dek()`
--     (`api/v2/include/keys.php`) which calls
--     `v2_sync_bootstrap_from_legacy()` (`api/v2/include/sync.php`).
--     The plaintext password only exists during authentication, so no batch
--     job could derive the KEK: accounts that never log in through v2 stay
--     untouched and fully functional on v1.
--   * Table names are the ones declared in `include/credentials.php`
--     (`$user_keys_table`, `$data_current_table`, `$rate_limits_table`,
--     `$services[...]["legacy-table"]`). If your installation uses
--     different names, change them here AND there.
--   * The names of the EXISTING v1 tables (`users`, `logins`, `tokens` and the
--     data table, which is `data` on notefox.eu and `notefox_data` in the old
--     documentation) are declared ONCE in the `SET @v2_*_table` block that
--     precedes block 4: check them with `SHOW TABLES;` before running the
--     file.
--
-- BLOCKS
--   1  user_keys ................................. MANDATORY
--   2a OR 2b  snapshot table ..................... MANDATORY (one branch only)
--   3  rate_limits ............................... MANDATORY
--   4  users.`otp-enabled` ....................... MANDATORY
--   5  users.`password-v2` ....................... MANDATORY
--   6  users.`otp-change-*` ...................... optional - without it
--        `POST /otp/disable` and `POST /otp/disable/verify` answer 503
--   7  expiry / attempt counters ................. optional - without it the
--        codes lose their expiry and their attempt limit
--   8  users.`password-change-*` ................. MANDATORY - without it
--        `POST /password/edit` answers 500
--   9  indexes ................................... optional - performance only
--  10  users.`history-enabled` ................... optional - without it the
--        two `POST /data/get/history*` endpoints answer 433 to EVERY account
--        (the sync history is a per-account permission, denied by default)
--
-- RE-RUNNABLE (idempotent)
--   Every statement of blocks 4 to 10 is guarded: the column (or the index) is
--   added only when it is missing, so the file can be run twice without the
--   "#1060 - Duplicate column name" / "#1061 - Duplicate key name" errors
--   that used to ABORT the script and silently leave the following blocks
--   unapplied. The guard is a prepared statement driven by
--   `information_schema`: no `CREATE ROUTINE` privilege is needed, and it
--   works on both MySQL and MariaDB (`ADD COLUMN IF NOT EXISTS` is a MariaDB
--   only syntax, so it is deliberately NOT used here). A table that does not
--   exist under the configured name is skipped too, instead of aborting with
--   "#1146 - Table ... doesn't exist".
--   The only block that is NOT re-runnable is 2b (`RENAME` + `DROP PRIMARY
--   KEY`), which must be run once and stays commented out by default.
--
-- After running this file, verify with:
--   php api/v2/tests/schema-check.php
--   GET https://notefox.eu/api/v2/status
-- See `api/v2/install/DEPLOY.md` for the full procedure and the rollback.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1) MANDATORY - Data key (DEK) wrapped by the password: changing the
--    password re-encrypts ONLY this key, never the data.
--    One single key per account, shared by every service.
--    Missing: every account is "encryption-ready: false", `GET /status`
--    reports `keys: false`.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_keys` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user-id`       VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL
                  COMMENT 'users.email = SHA-512 hex of the email',
  `key-version`   INT UNSIGNED NOT NULL DEFAULT 1,
  `wrapped-key`   TEXT NOT NULL
                  COMMENT 'nfk1:base64(salt|iv|AES-256-CBC(DEK)) encrypted with the current password',
  `key-check`     VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL
                  COMMENT 'SHA-512 fingerprint of the DEK: verifies the unwrap',
  `status`        TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = retired',
  `created-date`  DATETIME NOT NULL,
  `updated-date`  DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_key_version` (`user-id`, `key-version`),
  KEY `idx_user_status` (`user-id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- =====================================================================
-- 2) MANDATORY - snapshot table (current data + server-side revision).
--    RUN EXACTLY ONE OF THE TWO BRANCHES:
--      2a) FRESH INSTALL     - no snapshot table exists yet;
--      2b) EXISTING INSTALL  - `notefox_data_current` already exists.
--    Running 2a on an existing installation would leave the old snapshots
--    behind in `notefox_data_current`, invisible to the API.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 2a) FRESH INSTALL - current snapshot + server-side revision, already
--     multi-service (the heart of the new sync).
--     Comment this block out if you are on branch 2b.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sav_data_current` (
  `user-id`               VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `service`               VARCHAR(32)  CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'notefox',
  `revision`              BIGINT UNSIGNED NOT NULL DEFAULT 1,
  `data`                  LONGTEXT NOT NULL COMMENT 'encrypted with the DEK of the account',
  `key-version`           INT UNSIGNED NOT NULL DEFAULT 1,
  `updated-locally-date`  DATETIME NULL DEFAULT NULL COMMENT 'informational only (client clock)',
  `updated-server-date`   DATETIME NOT NULL,
  `ip-address`            VARCHAR(100) NULL DEFAULT NULL,
  `legacy-data-id`        BIGINT UNSIGNED NULL DEFAULT NULL
                          COMMENT 'id of the single mirror row in the v1 data table (service notefox only)',
  PRIMARY KEY (`user-id`, `service`),
  KEY `idx_service` (`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ---------------------------------------------------------------------
-- 2b) EXISTING INSTALL - the snapshot table already exists (it was called
--     `notefox_data_current`): rename it and add the service dimension.
--     Uncomment the three statements below and comment out block 2a.
--
--     *** ORDER MATTERS - DO NOT INVERT ***
--     The `service` column must exist, WITH its DEFAULT, BEFORE the primary
--     key is rebuilt: otherwise the existing rows would have no key and the
--     ADD PRIMARY KEY fails (or, worse, collapses them).
--
--     `DROP PRIMARY KEY` rewrites the whole table and locks it: estimate the
--     row count first and run it in a short maintenance window.
--
--     No UPDATE is needed afterwards: the `DEFAULT 'notefox'` assigns every
--     existing snapshot to the Notefox service, and nothing is re-encrypted.
--
--     Remember to set `$data_current_table = "sav_data_current";` in
--     `include/credentials.php`, otherwise every `data/*` endpoint answers
--     503 even though the table is perfectly fine.
-- ---------------------------------------------------------------------
-- RENAME TABLE `notefox_data_current` TO `sav_data_current`;
--
-- ALTER TABLE `sav_data_current`
--   ADD COLUMN `service` VARCHAR(32) CHARACTER SET ascii COLLATE ascii_bin
--       NOT NULL DEFAULT 'notefox' AFTER `user-id`;
--
-- ALTER TABLE `sav_data_current`
--   DROP PRIMARY KEY,
--   ADD PRIMARY KEY (`user-id`, `service`),
--   ADD KEY `idx_service` (`service`);


-- ---------------------------------------------------------------------
-- 3) MANDATORY - Rate limiting (password/OTP brute force, email bombing).
--    Missing: `GET /status` reports `rate-limits: false` and the sensitive
--    endpoints lose their brute-force protection.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bucket`        VARCHAR(64)  NOT NULL COMMENT 'e.g. login, otp-verify, otp-resend',
  `subject`       VARCHAR(128) NOT NULL COMMENT 'hash of the email or of the IP',
  `attempts`      INT UNSIGNED NOT NULL DEFAULT 1,
  `window-start`  DATETIME NOT NULL,
  `blocked-until` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_bucket_subject` (`bucket`, `subject`),
  KEY `idx_window` (`window-start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- =====================================================================
-- NAMES OF THE EXISTING (v1) TABLES - SET THEM BEFORE RUNNING BLOCKS 4 TO 10
--
--   Blocks 4 to 10 do not create anything: they add columns and indexes to the
--   tables that already exist. Those names are NOT the same on every
--   installation - they are whatever `include/credentials.php` declares in
--   `$users_table`, `$logins_table`, `$tokens_table`, `$data_table` - so they
--   are declared ONCE here and every statement below is built from them.
--
--   The Notefox data table in particular is called `data` on notefox.eu and
--   `notefox_data` in the older documentation: check with `SHOW TABLES;` and
--   use the real name. It must match `$data_table` AND the `"legacy-table"` of
--   the notefox entry of `$services`, otherwise `GET /status` reports
--   `legacy-mirror: false`.
--
--   A table that does not exist is simply SKIPPED (no `#1146 - Table ...
--   doesn't exist`), so a wrong name here silently costs you the columns of
--   that table: run the SELECT below to see what was found.
-- =====================================================================
SET @v2_users_table  = 'users';
SET @v2_logins_table = 'logins';
SET @v2_tokens_table = 'tokens';
SET @v2_data_table   = 'data';          -- v1 Notefox data table ($data_table)

SELECT 'users'  AS `variable`, @v2_users_table  AS `table`,
       IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
           WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1,
          'found', 'MISSING - fix the name above') AS `state`
UNION ALL
SELECT 'logins', @v2_logins_table,
       IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
           WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_logins_table) = 1,
          'found', 'MISSING - fix the name above')
UNION ALL
SELECT 'tokens', @v2_tokens_table,
       IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
           WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_tokens_table) = 1,
          'found', 'MISSING - fix the name above')
UNION ALL
SELECT 'data',   @v2_data_table,
       IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
           WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_data_table) = 1,
          'found', 'MISSING - fix the name above');


-- ---------------------------------------------------------------------
-- 4) MANDATORY - Optional 2FA, enabled by default (v1 ignores the column).
--    Missing: `GET /status` reports `otp: false` and the `otp/*` endpoints
--    cannot read the setting.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'otp-enabled') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `otp-enabled` TINYINT(1) NOT NULL DEFAULT 1 COMMENT ''1 = OTP by email at login (default), 0 = disabled'''),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 5) MANDATORY - Modern password hash, only used by v2 (SHA-512 stays for
--    v1). Missing: v2 cannot keep the modern hash aligned at login.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'password-v2') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `password-v2` VARCHAR(255) NULL DEFAULT NULL COMMENT ''password_hash() PASSWORD_DEFAULT, kept aligned with the SHA-512 column'''),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 6) OPTIONAL - Code used to disable the OTP.
--    Missing: `POST /otp/disable` and `POST /otp/disable/verify` answer 503
--    (no code is emailed and the 2FA stays enabled). The rest of the API is
--    unaffected. `GET /status` reports `otp-change-code: false`.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'otp-change-code') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `otp-change-code` VARCHAR(512) NULL DEFAULT NULL'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'otp-change-expiry') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `otp-change-expiry` DATETIME NULL DEFAULT NULL'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'otp-change-attempts') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `otp-change-attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 7) OPTIONAL (recommended) - Expiry and attempt limits for the other codes
--    (v1 has no expiry on the signup code and no attempt counter anywhere).
--    Missing: the signup/login/deletion codes keep working but lose their
--    expiry and their attempt limit.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'verification-expiry') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `verification-expiry` DATETIME NULL DEFAULT NULL'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'verification-attempts') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `verification-attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'deleting-attempts') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `deleting-attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_logins_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_logins_table
                        AND `COLUMN_NAME` = 'verification-attempts') = 0,
  CONCAT('ALTER TABLE `', @v2_logins_table, '` ADD COLUMN `verification-attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 8) MANDATORY - Code used to confirm a password change (always required,
--    see API.md 7.6).
--    Missing: `POST /password/edit` answers 500 - the confirmation code
--    cannot be stored, and the password is never changed without its second
--    factor. `GET /status` reports `password-change-code: false`.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'password-change-code') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `password-change-code` VARCHAR(512) NULL DEFAULT NULL'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'password-change-expiry') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `password-change-expiry` DATETIME NULL DEFAULT NULL'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'password-change-attempts') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `password-change-attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 9) OPTIONAL - Indexes: they help both v1 and v2, they change no column.
--    Guarded like the blocks above: an index that is already there is simply
--    left alone, and so is a table that does not exist under the name set at
--    the top of the v1 section.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_data_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`STATISTICS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_data_table
                        AND `INDEX_NAME` = 'idx_user_updated') = 0,
  CONCAT('ALTER TABLE `', @v2_data_table, '` ADD INDEX `idx_user_updated` (`user-id`(191), `updated-locally-date`)'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;

SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_tokens_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`STATISTICS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_tokens_table
                        AND `INDEX_NAME` = 'idx_login_status') = 0,
  CONCAT('ALTER TABLE `', @v2_tokens_table, '` ADD INDEX `idx_login_status` (`login-id`(191), `status`)'),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;


-- ---------------------------------------------------------------------
-- 10) OPTIONAL - Sync history permission, DISABLED by default (v1 ignores
--     the column, exactly like `otp-enabled`).
--
--     Unlike every other flag of the account, this one defaults to 0: the
--     sync history (`POST /data/get/history` and
--     `POST /data/get/history/download`) is reserved to the accounts it is
--     granted to, one by one. An installation that never runs this block
--     denies the history to EVERY account (433), it does not open it: the
--     core reads a missing column as "not granted" on purpose.
--
--     Granting it is a manual, per-account operation - there is no endpoint
--     for it, so that no user can grant it to themselves. `users`.`email`
--     holds the SHA-512 hex of the address, never the address itself:
--
--       UPDATE `users` SET `history-enabled` = 1
--        WHERE `email` = SHA2(LOWER('someone@example.com'), 512);
--
--     and to revoke it, the same statement with `= 0`. To list the accounts
--     that currently have it:
--
--       SELECT `email` FROM `users` WHERE `history-enabled` = 1;
--
--     `GET /status` reports `history-permission: false` while the column is
--     missing, and `php api/v2/tests/schema-check.php` prints a warning.
-- ---------------------------------------------------------------------
SET @v2_sql = IF((SELECT COUNT(*) FROM `information_schema`.`TABLES`
                  WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table) = 1
                 AND (SELECT COUNT(*) FROM `information_schema`.`COLUMNS`
                      WHERE `TABLE_SCHEMA` = DATABASE() AND `TABLE_NAME` = @v2_users_table
                        AND `COLUMN_NAME` = 'history-enabled') = 0,
  CONCAT('ALTER TABLE `', @v2_users_table, '` ADD COLUMN `history-enabled` TINYINT(1) NOT NULL DEFAULT 0 COMMENT ''1 = this account can read its sync history, 0 = denied (default)'''),
  'SELECT 1');
PREPARE v2_stmt FROM @v2_sql; EXECUTE v2_stmt; DEALLOCATE PREPARE v2_stmt;
