START TRANSACTION

DROP DATABASE IF EXISTS `video_tutorials`
CREATE DATABASE `video_tutorials`

DROP TABLE IF EXISTS `video`
CREATE TABLE `video` (
  `id` INT AUTO_INCREMENT,
  `author` VARCHAR(64),
  `title` VARCHAR(128),
  `parts` INT UNSIGNED,
  `description` MEDIUMTEXT,
  PRIMARY KEY (id)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `video_url`
CREATE TABLE `video_url` (
  `id` INT AUTO_INCREMENT,
  `video_id` INT REFERENCES `video` (`id`),
  `part` INT UNSIGNED,
  `part_title` VARCHAR(128),
  `url` VARCHAR(1024),
  PRIMARY KEY (id)
) ENGINE=MyISAM;

COMMIT
