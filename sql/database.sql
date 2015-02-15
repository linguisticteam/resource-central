START TRANSACTION;

DROP DATABASE IF EXISTS `content_reference_central`;
CREATE DATABASE `content_reference_central`
DEFAULT CHARACTER SET 'utf8'
DEFAULT COLLATE 'utf8_unicode_ci';

USE `content_reference_central`;

/*--------------------------------*/
/*----   TABLES AND INSERTS   ----*/
/*--------------------------------*/

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

CREATE TABLE `creditee_valid_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MYISAM;

INSERT INTO `creditee_valid_type` (name) VALUES
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

/*-------------------------------*/
/*----   STORED PROCEDURES   ----*/
/*-------------------------------*/

DELIMITER $

CREATE PROCEDURE stpc_get_creditee_attribute_type_id (
	param_attribute_name TINYTEXT)
BEGIN
	RETURN (SELECT `id` FROM `creditee_attribute_type` WHERE `name` LIKE param_attribute_name);
END $

CREATE PROCEDURE stpc_insert_new_creditee_attribute (
	param_creditee_id INT,
	param_creditee_attribute_name TINYTEXT,
	param_value TINYTEXT)
BEGIN
	INSERT INTO `creditee_attribute` (
		creditee_id,
		creditee_attribute_type_id,
		value)
	VALUES (
		local_current_insert_id,
		(SELECT stpc_get_creditee_attribute_type_id(param_creditee_attribute_name)),
		param_value
		)
	;
END $

CREATE PROCEDURE stpc_insert_new_creditee (
	param_first_name TINYTEXT,
	param_last_name TINYTEXT)
BEGIN
	DECLARE local_current_insert_id INT;

	INSERT INTO `creditee` () VALUES ();

	SET local_current_insert_id = LAST_INSERT_ID();

	SELECT stpc_insert_new_creditee_attribute(
		local_current_insert_id,
		'FIRST NAME',
		param_first_name
	);

	SELECT stpc_insert_new_creditee_attribute(
		local_current_insert_id,
		'LAST NAME',
		param_last_name
	);
END $

DELIMITER ;

COMMIT
