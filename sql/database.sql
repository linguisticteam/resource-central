START TRANSACTION;

DROP DATABASE IF EXISTS `content_reference_central`;
CREATE DATABASE `content_reference_central`
DEFAULT CHARACTER SET 'utf8'
DEFAULT COLLATE 'utf8_unicode_ci';

USE `content_reference_central`;

CREATE TABLE `source` (
	`id` INT AUTO_INCREMENT,
	`content_id` INT REFERENCES `content` (`id`),
	`source_type_id` INT REFERENCES `source_type` (`id`),
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
	`ref_id` INT,
	`in_table` ENUM(
		'source',
		'creditee',
		'content'),
	`url` TEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee_to_content` (
	`id` INT AUTO_INCREMENT,
	`creditee_id` INT REFERENCES `creditee` (`id`),
	`content_id` INT REFERENCES `content` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee` (
	`id` INT AUTO_INCREMENT,
	`temp_id` INT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee_attribute` (
	`id` INT AUTO_INCREMENT,
	`creditee_id` INT REFERENCES `creditee` (`id`),
	`creditee_attribute_type_id` INT REFERENCES `creditee_attribute_type` (`id`),
	`value` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `creditee_attribute_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `creditee_attribute_type` (name) VALUES
	('CREDITEE TYPE'),
	('FIRST NAME'),
	('MIDDLE NAME'),
	('LAST NAME')
;

CREATE TABLE `valid_creditee_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MYISAM;

INSERT INTO `valid_creditee_type` (name) VALUES
	('AUTHOR'),
	('CO-AUTHOR')
;

CREATE TABLE `content` (
	`id` INT AUTO_INCREMENT,
	`parent_id` INT REFERENCES `content` (`id`),
	`child_index` INT,
	`content_type_id` INT REFERENCES `content_type` (`id`),
	`content_info_id` INT REFERENCES `content_info` (`id`),
	`content_purpose_id` INT REFERENCES `content_purpose` (`id`),
	`content_medium_id` INT REFERENCES `content_medium` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_type` (name) VALUES
	('AUDIO'),
	('VIDEO'),
	('TEXT')
;

CREATE TABLE `content_info` (
	`id` INT AUTO_INCREMENT,
	`title` TINYTEXT,
	`description` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_purpose` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_purpose` (name) VALUES
	('TUTORIAL'),
	('DOCUMENTATION')
;

CREATE TABLE `content_medium` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	`content_type_match` INT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_medium` (name,content_type_match) VALUES
	('VIDEO STREAM',
		(SELECT `id` FROM `content_type` WHERE `name` LIKE 'VIDEO')),
	('VIDEO FILE',
		(SELECT `id` FROM `content_type` WHERE `name` LIKE 'VIDEO')),
	('AUDIO STREAM',
		(SELECT `id` FROM `content_type` WHERE `name` LIKE 'AUDIO')),
	('AUDIO FILE',
		(SELECT `id` FROM `content_type` WHERE `name` LIKE 'AUDIO')),
	('PDF',
		(SELECT `id` FROM `content_type` WHERE `name` LIKE 'TEXT'))
;

COMMIT
