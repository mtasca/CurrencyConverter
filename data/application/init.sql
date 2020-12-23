#
# Drop database currency_converter
# ------------------------------------------------------------
DROP DATABASE IF EXISTS currency_converter;

#
# Create database currency_converter
# ------------------------------------------------------------
CREATE DATABASE currency_converter;

USE currency_converter;

#
# Dump of table currencies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `currencies`;

CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `iso_code` varchar(30) NOT NULL DEFAULT '',
  `iso_number` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iso_code` (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO currencies
VALUES (1, 'US Dollar', 'US Dollar', 'USD', 840,NOW(), NOW()),
(2, 'Euro', 'Euro', 'EUR', 978, NOW(), NOW()),
(3, 'Argentine peso', 'Argentine peso', 'ARS', 032, NOW(), NOW()),
(4, 'Brazilian real', 'Brazilian real', 'BRL', 986, NOW(), NOW()),
(5, 'Chilean peso', 'Chilean peso', 'CLP', 152, NOW(), NOW()),
(6, 'Pound sterling', 'Pound sterling', 'GBP', 826, NOW(), NOW());