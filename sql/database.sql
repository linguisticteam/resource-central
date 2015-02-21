START TRANSACTION;

DROP DATABASE IF EXISTS `content_reference_central`;
CREATE DATABASE `content_reference_central`
DEFAULT CHARACTER SET 'utf8'
DEFAULT COLLATE 'utf8_unicode_ci';

USE `content_reference_central`;

/*--------------------------------*/
/*----   TABLES AND INSERTS   ----*/
/*--------------------------------*/

CREATE TABLE `resource` (
    `id` INT AUTO_INCREMENT,
    `resource_type_id` INT REFERENCES `resource_type` (`id`),
    `description` TEXT,
PRIMARY KEY (id)
);

CREATE TABLE `element` (
    `id` INT AUTO_INCREMENT,
    `resource_id` INT REFERENCES `resource` (`id`),
    `element_type_id` INT REFERENCES `element_type` (`id`),
    `index` INT,
    `url` TEXT,
    PRIMARY KEY (id)
);

CREATE TABLE `entity` (
    `id` INT AUTO_INCREMENT,
    `resource_id` INT REFERENCES `resource` (`id`),
    `entity_type_id` INT REFERENCES `entity_type` (`id`),
PRIMARY KEY (id)
);

CREATE TABLE `entity_type` (
    `id` INT AUTO_INCREMENT,
    `name` TINYTEXT,
    PRIMARY KEY (id)
);

INSERT INTO `entity_type` (name) VALUES
    ('PERSON'),
    ('ORGANIZATION')
;

CREATE TABLE `resource_type` (
    `id` INT AUTO_INCREMENT,
    `name` TINYTEXT,
    PRIMARY KEY (id)
);

INSERT INTO `resource_type` (name) VALUES
    ('TUTORIAL'),
    ('DOCUMENTATION')
;

CREATE TABLE `element_type` (
    `id` INT AUTO_INCREMENT,
    `name` TINYTEXT,
    PRIMARY KEY (id)
);

INSERT INTO `element_type` (name) VALUES
    ('PRIMARY'),
    ('PART'),
    ('LESSON'),
    ('CHAPTER')
;

/*-------------------*/
/*----   VIEWS   ----*/
/*-------------------*/

/*-------------------------------*/
/*----   STORED PROCEDURES   ----*/
/*-------------------------------*/



COMMIT
