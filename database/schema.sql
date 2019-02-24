-- utf8
SET NAMES utf8;

-- DataBase staff
DROP DATABASE IF EXISTS `staff`;
CREATE DATABASE IF NOT EXISTS `staff`;
USE `staff`;

-- Table staff.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_uniq` (`title`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_lithuanian_ci
COMMENT='Table to store departments data';

-- Table staff.persons
DROP TABLE IF EXISTS `persons`;
CREATE TABLE IF NOT EXISTS `persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `lastname` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `department_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_lastname_uniq` (`name`,`lastname`),
  KEY `FK_persons_departments` (`department_id`),
  INDEX `person_name_idx` (`name`),
  INDEX `person_lastname_idx` (`lastname`),
  CONSTRAINT `FK_persons_departments`
  FOREIGN KEY (`department_id`)
  REFERENCES `departments` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_lithuanian_ci
COMMENT='Table to store staff data';

-- Triggers to maintain persons of persons_projects table
-- after delete
DROP TRIGGER IF EXISTS `delete_persons_projects`;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `delete_persons_projects`
AFTER DELETE ON `persons`
FOR EACH ROW
BEGIN
  DELETE FROM `persons_projects` WHERE persons_projects.person_id = OLD.id;
END; //

DELIMITER ;

-- after update
DROP TRIGGER IF EXISTS `update_persons_projects`;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `update_persons_projects`
AFTER UPDATE ON `persons`
FOR EACH ROW
BEGIN
  UPDATE `persons_projects` SET persons_projects.person_id = NEW.id WHERE persons_projects.person_id = OLD.id;
END; //

DELIMITER ;

-- Table staff.projects
DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `budget` float(10, 4) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_budget_uniq` (`title`,`budget`),
  INDEX `description_idx` (`description`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_lithuanian_ci
COMMENT='Table to store projects data';

-- Triggers to maintain projects on persons_projects table
-- after delete
DROP TRIGGER IF EXISTS `delete_project_persons`;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS`delete_project_persons`
AFTER DELETE ON `projects`
FOR EACH ROW
BEGIN
  DELETE FROM `persons_projects` WHERE persons_projects.project_id = OLD.id;
END; //

DELIMITER ;

-- after update
DROP TRIGGER IF EXISTS `update_projects_persons`;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS`update_projects_persons`
AFTER UPDATE ON `projects`
FOR EACH ROW
BEGIN
  UPDATE `persons_projects` SET persons_projects.project_id = NEW.id WHERE persons_projects.project_id = OLD.id;
END; //

DELIMITER ;


-- Table staff.persons_projects
DROP TABLE IF EXISTS `persons_projects`;
CREATE TABLE IF NOT EXISTS `persons_projects` (
  `person_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`person_id`,`project_id`),
  INDEX `person_id_idx` (`person_id`),
  INDEX `project_id_idx` (`project_id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_lithuanian_ci
COMMENT='Helper table to support many to many relationship on persons and projects';
