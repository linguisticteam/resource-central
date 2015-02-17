START TRANSACTION;

DROP DATABASE IF EXISTS `content_reference_central`;
CREATE DATABASE `content_reference_central`
DEFAULT CHARACTER SET 'utf8'
DEFAULT COLLATE 'utf8_unicode_ci';

USE `content_reference_central`;

/*--------------------------------*/
/*----   TABLES AND INSERTS   ----*/
/*--------------------------------*/

CREATE TABLE `page_context` (
	`id` INT AUTO_INCREMENT,
)
ENGINE=MyISAM;

CREATE TABLE `parse_relation` (
	`id` INT AUTO_INCREMENT,
	`action` ENUM (
		'print'
	);
	`first_entity_type_name` TINYTEXT,
	`relation_type_name` TINYTEXT,
	`second_entity_type_name` TINYTEXT,
	`description` MEDIUMTEXT,
	`code` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `parse_relation` (
	action,
	first_entity_type_name,
	relation_type_name,
	second_entity_type_name,
	description,
	code)
	(	'print',
		'PERSON',
		'MEMBER OF',
		'ORGANIZATION',
		'Print membership.',
		'Is member of %s')
;

CREATE TABLE `parse_attribute` (
	`id` INT AUTO_INCREMENT,
	`action` ENUM (
		'print'
	);
	`entity_type_name` TINYTEXT,
	`attribute_type_name` TINYTEXT,
	`description` MEDIUMTEXT,
	`code` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `parse_attribute` (
	action,
	entity_type_name,
	attribute_type_name,
	description,
	code)
	(	'print',
		'PERSON',
		'FIRST NAME',
		'Print the first name of a person.',
		'First name: %s')
;

CREATE TABLE `entity` (
	`id` INT AUTO_INCREMENT,
	`entity_type_id` INT REFERENCES `entity_type` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `entity_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `entity_type` (name) VALUES
	('PERSON'),
	('ORGANIZATION'),
	('TEAM'),
	('CONTENT'),
	('INTERNET SITE')
;

CREATE TABLE `relation` (
	`id` INT AUTO_INCREMENT,
	`relation_type_id` INT REFERENCES `relation_type` (`id`),
	`from_entity_id` INT REFERENCES `entity` (`id`),
	`to_entity_id` INT REFERENCES `entity` (`id`),
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `relation_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `relation_type` (name) VALUES
	('AUTHOR OF'),
	('CO-AUTHOR OF'),
	('OWNER OF'),
	('COORDINATOR OF'),
	('PRODUCER OF'),
	('MEMBER OF')
;

CREATE TABLE `attribute` (
	`id` INT AUTO_INCREMENT,
	`entity_id` INT REFERENCES `entity` (`id`),
	`attribute_type_id` INT REFERENCES `attribute_type` (`id`),
	`value` MEDIUMTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

CREATE TABLE `attribute_type` (
	`id` INT AUTO_INCREMENT,
	`name` TINYTEXT,
	PRIMARY KEY (id)
)
ENGINE=MyISAM;

INSERT INTO `attribute_type` (name) VALUES
	('FIRST NAME'),
	('LAST NAME'),
	('MIDDLE NAME'),
	('URL'),
	('E-MAIL')
;

/*-------------------*/
/*----   VIEWS   ----*/
/*-------------------*/

/*-------------------------------*/
/*----   STORED PROCEDURES   ----*/
/*-------------------------------*/

COMMIT
