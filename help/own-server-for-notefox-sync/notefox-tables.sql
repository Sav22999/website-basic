-- Notefox sync database schema
-- Import this file into a new MySQL or MariaDB database.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user-id` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `data` text COLLATE utf8mb4_bin NOT NULL,
  `inserted-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated-locally-date` datetime NOT NULL,
  `ip-address` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FOREIGN KEY (email)` (`user-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='Notefox Data';

-- --------------------------------------------------------

--
-- Table structure for `error_logs`
--

CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `local-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When it''s happened (client-datetime)',
  `inserted-date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When it''s added in the database (server-datetime)',
  `context` varchar(500) COLLATE utf8mb4_bin NOT NULL,
  `error` text COLLATE utf8mb4_bin NOT NULL,
  `url` varchar(1000) COLLATE utf8mb4_bin DEFAULT NULL,
  `notefox-version` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `anonymous-userid` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `login-id` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `user-id` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiry` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '{0:unverified, 1:verified, 2:forced-invalid}',
  `ip-address` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `verification-code` varchar(512) COLLATE utf8mb4_bin DEFAULT NULL,
  `verification-expiry` datetime DEFAULT NULL,
  `verified` datetime DEFAULT NULL,
  PRIMARY KEY (`login-id`),
  KEY `FOREIGN KEY (email)` (`user-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `notefox_telemetry`
--

CREATE TABLE IF NOT EXISTS `notefox_telemetry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notefox-account` tinyint(1) DEFAULT NULL,
  `anonymous-userid` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT 'uuid',
  `client-datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `server-datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `language` varchar(20) COLLATE utf8mb4_bin NOT NULL COMMENT '{en | it | fr | ...}',
  `action` varchar(500) COLLATE utf8mb4_bin NOT NULL,
  `context` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `url` text COLLATE utf8mb4_bin,
  `browser` varchar(20) COLLATE utf8mb4_bin NOT NULL COMMENT '{firefox | chrome | edge | other}',
  `browser-version` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `notefox-version` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `os` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '{windows | mac | linux | bsd | other}',
  `other` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login-id` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `password` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `expiry` datetime DEFAULT NULL,
  `ip-address` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `inserted-date` datetime NOT NULL,
  `status` int(3) NOT NULL COMMENT '{0:invalid, 1:valid}',
  PRIMARY KEY (`id`),
  KEY `FK (from logins)` (`login-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` text COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `ip-address` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verification-code` varchar(512) COLLATE utf8mb4_bin DEFAULT NULL,
  `verified` datetime DEFAULT NULL,
  `deleting-code` varchar(512) COLLATE utf8mb4_bin DEFAULT NULL,
  `deleting-expiry` datetime DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0' COMMENT '0: unverified, 1: verified, 2: blocked',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
