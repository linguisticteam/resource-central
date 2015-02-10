START TRANSACTION;

DROP DATABASE IF EXISTS `tutorials`;
CREATE DATABASE `tutorials`
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf_unicode_ci;

USE `tutorials`;

CREATE TABLE `creditee` (
	`id` INT AUTO_INCREMENT,
	`type` INT REFERENCES `creditee_type` (`id`),
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

INSERT INTO `creditee_type` (type) VALUES (
	'AUTHOR',
	'CO-AUTHOR'
);

CREATE TABLE `content` (
	`id` INT AUTO_INCREMENT,
	`type` INT REFERENCES `content_type` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM
CHARACTER SET utf8
COLLATE utf_unicode_ci;

CREATE TABLE `content_type` (
	`id` INT AUTO_INCREMENT,
	`type` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM
CHARACTER SET utf8
COLLATE utf_unicode_ci;

COMMIT
