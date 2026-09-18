-- Notefox sync database schema (Notefox Account v2)
-- Import this file into a new MySQL or MariaDB database.

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for `data` (legacy v1 data storage)
--

CREATE TABLE IF NOT EXISTS `data`
(
    `id`
    int
(
    11
) NOT NULL AUTO_INCREMENT,
    `user-id` varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `data` text COLLATE utf8mb4_bin NOT NULL,
    `inserted-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated-locally-date` datetime NOT NULL,
    `ip-address` varchar
(
    100
) COLLATE utf8mb4_bin NOT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `FOREIGN KEY (email)`
(
    `user-id`
),
    KEY `idx_user_updated`
(
    `user-id`
(
    191
),`updated-locally-date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin COMMENT='Notefox Data';

-- --------------------------------------------------------

--
-- Table structure for `error_logs`
--

CREATE TABLE IF NOT EXISTS `error_logs`
(
    `id`
    int
(
    11
) NOT NULL AUTO_INCREMENT,
    `local-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When it''s happened (client-datetime)',
    `inserted-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When it''s added in the database (server-datetime)',
    `context` varchar
(
    500
) COLLATE utf8mb4_bin NOT NULL,
    `error` text COLLATE utf8mb4_bin NOT NULL,
    `url` varchar
(
    1000
) COLLATE utf8mb4_bin DEFAULT NULL,
    `notefox-version` varchar
(
    20
) COLLATE utf8mb4_bin DEFAULT NULL,
    `anonymous-userid` varchar
(
    50
) COLLATE utf8mb4_bin DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `logins`
--

CREATE TABLE IF NOT EXISTS `logins`
(
    `login-id`
    varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `user-id` varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `expiry` datetime DEFAULT NULL,
    `status` tinyint
(
    1
) NOT NULL DEFAULT '0' COMMENT '{0:unverified, 1:verified, 2:forced-invalid}',
    `ip-address` varchar
(
    100
) COLLATE utf8mb4_bin NOT NULL,
    `verification-code` varchar
(
    512
) COLLATE utf8mb4_bin DEFAULT NULL,
    `verification-expiry` datetime DEFAULT NULL,
    `verified` datetime DEFAULT NULL,
    `verification-attempts` tinyint
(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY
(
    `login-id`
),
    KEY `FOREIGN KEY (email)`
(
    `user-id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `notefox_telemetry`
--

CREATE TABLE IF NOT EXISTS `notefox_telemetry`
(
    `id`
    int
(
    11
) NOT NULL AUTO_INCREMENT,
    `notefox-account` tinyint
(
    1
) DEFAULT NULL,
    `anonymous-userid` varchar
(
    50
) COLLATE utf8mb4_bin NOT NULL COMMENT 'uuid',
    `client-datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `server-datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `language` varchar
(
    20
) COLLATE utf8mb4_bin NOT NULL COMMENT '{en | it | fr | ...}',
    `action` varchar
(
    500
) COLLATE utf8mb4_bin NOT NULL,
    `context` varchar
(
    500
) COLLATE utf8mb4_bin DEFAULT NULL,
    `url` text COLLATE utf8mb4_bin,
    `browser` varchar
(
    20
) COLLATE utf8mb4_bin NOT NULL COMMENT '{firefox | chrome | edge | other}',
    `browser-version` varchar
(
    20
) COLLATE utf8mb4_bin DEFAULT NULL,
    `notefox-version` varchar
(
    20
) COLLATE utf8mb4_bin NOT NULL,
    `os` varchar
(
    20
) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '{windows | mac | linux | bsd | other}',
    `other` text COLLATE utf8mb4_bin,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `rate_limits`
--

CREATE TABLE IF NOT EXISTS `rate_limits`
(
    `id`
    bigint
(
    20
) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bucket` varchar
(
    64
) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. login, otp-verify, otp-resend',
    `subject` varchar
(
    128
) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'hash of the email or of the IP',
    `attempts` int
(
    10
) UNSIGNED NOT NULL DEFAULT '1',
    `window-start` datetime NOT NULL,
    `blocked-until` datetime DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `uq_bucket_subject`
(
    `bucket`,
    `subject`
),
    KEY `idx_window`
(
    `window-start`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci ROW_FORMAT= DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for `sav_data_current` (v2 multi-service data storage)
--

CREATE TABLE IF NOT EXISTS `sav_data_current`
(
    `user-id`
    varchar
(
    128
) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    `service` varchar
(
    32
) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'notefox',
    `revision` bigint
(
    20
) UNSIGNED NOT NULL DEFAULT '1',
    `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'encrypted with the DEK of the account',
    `key-version` int
(
    10
) UNSIGNED NOT NULL DEFAULT '1',
    `updated-locally-date` datetime DEFAULT NULL COMMENT 'informational only (client clock)',
    `updated-server-date` datetime NOT NULL,
    `ip-address` varchar
(
    100
) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `legacy-data-id` bigint
(
    20
) UNSIGNED DEFAULT NULL COMMENT 'id of the single mirror row in `data` (service notefox only)',
    PRIMARY KEY
(
    `user-id`,
    `service`
),
    KEY `idx_service`
(
    `service`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci ROW_FORMAT= DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens`
(
    `id`
    int
(
    11
) NOT NULL AUTO_INCREMENT,
    `login-id` varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `password` mediumtext COLLATE utf8mb4_bin NOT NULL,
    `expiry` datetime DEFAULT NULL,
    `ip-address` varchar
(
    100
) COLLATE utf8mb4_bin NOT NULL,
    `inserted-date` datetime NOT NULL,
    `status` int
(
    3
) NOT NULL COMMENT '{0:invalid, 1:valid}',
    PRIMARY KEY
(
    `id`
),
    KEY `FK (from logins)`
(
    `login-id`
),
    KEY `idx_login_status`
(
    `login-id`
(
    191
),`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `users`
--

CREATE TABLE IF NOT EXISTS `users`
(
    `username`
    text
    COLLATE
    utf8mb4_bin
    NOT
    NULL,
    `email`
    varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `password` varchar
(
    512
) COLLATE utf8mb4_bin NOT NULL,
    `ip-address` varchar
(
    100
) COLLATE utf8mb4_bin NOT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `verification-code` varchar
(
    512
) COLLATE utf8mb4_bin DEFAULT NULL,
    `verified` datetime DEFAULT NULL,
    `deleting-code` varchar
(
    512
) COLLATE utf8mb4_bin DEFAULT NULL,
    `deleting-expiry` datetime DEFAULT NULL,
    `status` int
(
    3
) NOT NULL DEFAULT '0' COMMENT '0: unverified, 1: verified, 2: blocked',
    `otp-enabled` tinyint
(
    1
) NOT NULL DEFAULT '1',
    `otp-change-code` varchar
(
    512
) COLLATE utf8mb4_bin DEFAULT NULL,
    `otp-change-expiry` datetime DEFAULT NULL,
    `otp-change-attempts` tinyint
(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    `password-v2` varchar
(
    255
) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'password_hash() PASSWORD_DEFAULT, kept aligned with the SHA-512 column',
    `verification-expiry` datetime DEFAULT NULL,
    `verification-attempts` tinyint
(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    `deleting-attempts` tinyint
(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    `password-change-code` varchar
(
    512
) COLLATE utf8mb4_bin DEFAULT NULL,
    `password-change-expiry` datetime DEFAULT NULL,
    `password-change-attempts` tinyint
(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    `history-enabled` tinyint
(
    1
) NOT NULL DEFAULT '0' COMMENT '1 = this account can read its sync history, 0 = denied (default)',
    `pro-features` tinyint
(
    1
) NOT NULL DEFAULT '0' COMMENT '1 = this account has access to premium features, 0 = denied (default)',
    PRIMARY KEY
(
    `email`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `user_keys` (v2 encryption key storage)
--

CREATE TABLE IF NOT EXISTS `user_keys`
(
    `id`
    int
(
    10
) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user-id` varchar
(
    128
) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'users.email = SHA-512 hex of the email',
    `key-version` int
(
    10
) UNSIGNED NOT NULL DEFAULT '1',
    `wrapped-key` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nfk1:base64(salt|iv|AES-256-CBC(DEK)) encrypted with the current password',
    `key-check` varchar
(
    128
) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'SHA-512 fingerprint of the DEK: verifies the unwrap',
    `status` tinyint
(
    1
) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = retired',
    `created-date` datetime NOT NULL,
    `updated-date` datetime DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `uq_user_key_version`
(
    `user-id`,
    `key-version`
),
    KEY `idx_user_status`
(
    `user-id`,
    `status`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci ROW_FORMAT= DYNAMIC;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
