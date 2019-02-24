-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.37-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table staff.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_uniq` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci COMMENT='Table to store departments data';

-- Dumping data for table staff.departments: ~4 rows (approximately)
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` (`id`, `title`) VALUES
	(1, 'Administracija'),
	(2, 'Finansų tarnyba'),
	(3, 'Pardavimų skyrius'),
	(4, 'Vadovybė');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;

-- Dumping structure for table staff.persons
DROP TABLE IF EXISTS `persons`;
CREATE TABLE IF NOT EXISTS `persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `lastname` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `department_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_lastname_uniq` (`name`,`lastname`),
  KEY `FK_persons_departments` (`department_id`),
  CONSTRAINT `FK_persons_departments` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci COMMENT='Table to store staff data';

-- Dumping data for table staff.persons: ~7 rows (approximately)
/*!40000 ALTER TABLE `persons` DISABLE KEYS */;
INSERT INTO `persons` (`id`, `name`, `lastname`, `department_id`) VALUES
	(1, 'Karolis', 'Didysis', 4),
	(2, 'Hermiona', 'Įkyrėlė', 2),
	(3, 'Haris', 'Poteris', 3),
	(4, 'Albas', 'Dumbldoras', 1),
	(5, 'Drakas', 'Smirdžius', 3),
	(6, 'Laisvasis', 'Šaulys', NULL);
/*!40000 ALTER TABLE `persons` ENABLE KEYS */;

-- Dumping structure for table staff.persons_projects
DROP TABLE IF EXISTS `persons_projects`;
CREATE TABLE IF NOT EXISTS `persons_projects` (
  `person_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`person_id`,`project_id`),
  KEY `person_id_idx` (`person_id`),
  KEY `project_id_idx` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci COMMENT='Helper table to support many to many relationship on persons and projects';

-- Dumping data for table staff.persons_projects: ~9 rows (approximately)
/*!40000 ALTER TABLE `persons_projects` DISABLE KEYS */;
INSERT INTO `persons_projects` (`person_id`, `project_id`) VALUES
	(1, 1),
	(2, 1),
	(2, 2),
	(2, 3),
	(2, 4),
	(3, 1),
	(3, 2),
	(4, 4),
	(6, 5);
/*!40000 ALTER TABLE `persons_projects` ENABLE KEYS */;

-- Dumping structure for table staff.projects
DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  `budget` float(10,4) NOT NULL DEFAULT '0.0000',
  `description` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '''''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_budget_uniq` (`title`,`budget`),
  KEY `description_idx` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci COMMENT='Table to store projects data';

-- Dumping data for table staff.projects: ~5 rows (approximately)
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` (`id`, `title`, `budget`, `description`) VALUES
	(1, 'ES projektas "Vaistai"', 10000.0000, 'Skirtas vaistams'),
	(2, 'ES projektas "Maistas"', 12000.0000, 'Skirtas maistui'),
	(3, 'ES projektas "Vanduo"', 14000.0000, 'Skirtas vandeniui'),
	(4, 'ES projektas "Oras"', 11320.0000, 'Skirtas orui'),
	(5, 'Projektas "Laisvamanis"', 1312.0000, 'Skirtas geriems žmonėms remti');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;

-- Dumping structure for trigger staff.delete_persons_projects
DROP TRIGGER IF EXISTS `delete_persons_projects`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `delete_persons_projects` AFTER DELETE ON `persons` FOR EACH ROW BEGIN
  DELETE FROM `persons_projects` WHERE persons_projects.person_id = OLD.id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger staff.delete_project_persons
DROP TRIGGER IF EXISTS `delete_project_persons`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `delete_project_persons` AFTER DELETE ON `projects` FOR EACH ROW BEGIN
  DELETE FROM `persons_projects` WHERE persons_projects.project_id = OLD.id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger staff.update_persons_projects
DROP TRIGGER IF EXISTS `update_persons_projects`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_persons_projects` AFTER UPDATE ON `persons` FOR EACH ROW BEGIN
  UPDATE `persons_projects` SET persons_projects.person_id = NEW.id WHERE persons_projects.person_id = OLD.id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger staff.update_projects_persons
DROP TRIGGER IF EXISTS `update_projects_persons`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_projects_persons` AFTER UPDATE ON `projects` FOR EACH ROW BEGIN
  UPDATE `persons_projects` SET persons_projects.project_id = NEW.id WHERE persons_projects.project_id = OLD.id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;



Collapse 

Message Input
