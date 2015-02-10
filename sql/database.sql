START TRANSACTION;

DROP DATABASE IF EXISTS `tutorials`;
CREATE DATABASE `tutorials`
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf_unicode_ci;

USE `tutorials`;

CREATE TABLE `source` (
	`id` INT AUTO_INCREMENT,
	`content_ref` INT REFERENCES `content` (`id`),
	`source_type_ref` INT REFERENCES `source_type` (`id`),
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `source_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee` (
	`id` INT AUTO_INCREMENT,
	`creditee_type_ref` INT REFERENCES `creditee_type` (`id`),
	`full_name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee_type` (
	`id` INT AUTO_INCREMENT,
	`type` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `creditee_type` (type) VALUES (
	'AUTHOR',
	'CO-AUTHOR'
);

CREATE TABLE `content` (
	`id` INT AUTO_INCREMENT,
	`content_type_ref` INT REFERENCES `content_type` (`id`),
	`content_info_ref` INT REFERENCES `content_info` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_type` (
	`id` INT AUTO_INCREMENT,
	`type` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_info` (
	`id` INT AUTO_INCREMENT,
	`title` TINYTEXT,
	`description` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

COMMIT
