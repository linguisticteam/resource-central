START TRANSACTION;

DROP DATABASE IF EXISTS `tutorials`;
CREATE DATABASE `tutorials`
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf_unicode_ci;

USE `tutorials`;

CREATE TABLE `creditee` (
	`id` INT AUTO_INCREMENT,
	`full_name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM
CHARACTER SET utf8
COLLATE utf_unicode_ci;

CREATE TABLE `creditee_type` (
	`id` INT AUTO_INCREMENT,
	`type` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM
CHARACTER SET utf8
COLLATE utf_unicode_ci;

COMMIT
