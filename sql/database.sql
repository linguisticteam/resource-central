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

CREATE TABLE `creditee_role` (
	`id` INT AUTO_INCREMENT,
	`creditee_id` INT REFERENCES `creditee` (`id`),
	`role_type_id` INT REFERENCES `role_type` (`id`),
	`content_id` INT REFERENCES `content` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `role_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `role_type` (name) VALUES
	('Author'),
	('Co-Author'),
	('Editor'),
	('Producer'),
	('Director')
;

CREATE TABLE `creditee` (
	`id` INT AUTO_INCREMENT,
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
	('FIRST NAME'),
	('MIDDLE NAME'),
	('LAST NAME')
;

CREATE TABLE `content` (
	`id` INT AUTO_INCREMENT,
	`parent_id` INT REFERENCES `content` (`id`),
	`child_index` INT,
	`content_base_type_id` INT REFERENCES `content_base_type` (`id`),
	`content_info_id` INT REFERENCES `content_info` (`id`),
	`content_purpose_id` INT REFERENCES `content_purpose` (`id`),
	`content_medium_id` INT REFERENCES `content_medium` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `content_base_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `content_base_type` (name) VALUES
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

/*-------------------*/
/*----   VIEWS   ----*/
/*-------------------*/

CREATE VIEW view_all_creditees AS
	SELECT
		`creditee`.`id` AS 'Creditee ID',
		(SELECT `value`
			FROM `creditee_attribute`
			WHERE `creditee_attribute_type_id` LIKE
				(SELECT `id` FROM `creditee_attribute_type` WHERE `name` LIKE 'FIRST NAME')
			AND `creditee_id` LIKE `creditee`.`id`) AS 'First Name',
		(SELECT `value`
			FROM `creditee_attribute`
			WHERE `creditee_attribute_type_id` LIKE
				(SELECT `id` FROM `creditee_attribute_type` WHERE `name` LIKE 'LAST NAME')
			AND `creditee_id` LIKE `creditee`.`id`) AS 'Last Name'
	FROM
		`creditee`;

/*-------------------------------*/
/*----   STORED PROCEDURES   ----*/
/*-------------------------------*/

DELIMITER $

CREATE FUNCTION stfc_get_creditee_attribute_type_id (
	param_attribute_name TINYTEXT)
RETURNS INT
BEGIN
	RETURN (SELECT `id` FROM `creditee_attribute_type` WHERE `name` LIKE param_attribute_name);
END $

CREATE PROCEDURE stpc_insert_new_creditee_attribute (
	IN param_creditee_id INT,
	IN param_creditee_attribute_name TINYTEXT,
	IN param_value TINYTEXT)
BEGIN
	INSERT INTO `creditee_attribute` (
		creditee_id,
		creditee_attribute_type_id,
		value)
	VALUES (
		param_creditee_id,
		stfc_get_creditee_attribute_type_id(param_creditee_attribute_name),
		param_value
	);
END $

CREATE PROCEDURE stpc_insert_new_creditee (
	IN param_first_name TINYTEXT,
	IN param_last_name TINYTEXT)
BEGIN
	DECLARE local_current_insert_id INT;

	INSERT INTO `creditee` () VALUES ();

	SET local_current_insert_id = LAST_INSERT_ID();

	CALL stpc_insert_new_creditee_attribute(
		local_current_insert_id,
		'FIRST NAME',
		param_first_name
	);

	CALL stpc_insert_new_creditee_attribute(
		local_current_insert_id,
		'LAST NAME',
		param_last_name
	);
END $

DELIMITER ;

COMMIT
