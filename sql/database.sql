START TRANSACTION;

DROP DATABASE IF EXISTS `content_reference_central`;
CREATE DATABASE `content_reference_central`
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf_unicode_ci;

USE `content_reference_central`;

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

INSERT INTO `source_type` (name) VALUES (
	'INTERNET SITE'
);

CREATE TABLE `url` (
	`id` INT AUTO_INCREMENT,
	`at_table` INT,
	`url` TEXT,
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
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `creditee_type` (name) VALUES (
	'AUTHOR',
	'CO-AUTHOR'
);

CREATE TABLE `content` (
	`id` INT AUTO_INCREMENT,
	`content_type_ref` INT REFERENCES `content_type` (`id`),
	`content_info_ref` INT REFERENCES `content_info` (`id`),
	`content_purpose_ref` INT REFERENCES `content_purpose` (`id`),
	`content_medium_ref` INT REFERENCES `content_medium` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_purpose` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_purpose` (name) VALUES (
	'TUTORIAL',
	'DOCUMENTATION'
);

CREATE TABLE `content_medium` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_medium` (name) VALUES (
	'VIDEO STREAM',
	'VIDEO FILE',
	'AUDIO FILE',
	'PDF'
);

CREATE TABLE `content_info` (
	`id` INT AUTO_INCREMENT,
	`title` TINYTEXT,
	`description` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

COMMIT
