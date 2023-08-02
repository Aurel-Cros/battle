-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour php-battle
CREATE DATABASE IF NOT EXISTS `php-battle` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `php-battle`;

-- Listage de la structure de table php-battle. fighters
CREATE TABLE IF NOT EXISTS `fighters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `health` int unsigned NOT NULL,
  `attack` int unsigned NOT NULL,
  `mana` int unsigned NOT NULL,
  `healRatio` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table php-battle.fighters : ~4 rows (environ)

-- Listage de la structure de table php-battle. fights
CREATE TABLE IF NOT EXISTS `fights` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fighter_id_1` bigint unsigned NOT NULL,
  `fighter_id_2` bigint unsigned NOT NULL,
  `winner` bigint unsigned DEFAULT NULL,
  `logs` text,
  PRIMARY KEY (`id`),
  KEY `FK_fights_fighters` (`fighter_id_1`),
  KEY `FK_fights_fighters_2` (`fighter_id_2`),
  KEY `FK_fights_fighters_3` (`winner`),
  CONSTRAINT `FK_fights_fighters` FOREIGN KEY (`fighter_id_1`) REFERENCES `fighters` (`id`),
  CONSTRAINT `FK_fights_fighters_2` FOREIGN KEY (`fighter_id_2`) REFERENCES `fighters` (`id`),
  CONSTRAINT `FK_fights_fighters_3` FOREIGN KEY (`winner`) REFERENCES `fighters` (`id`),
  CONSTRAINT `Stop hitting yourself` CHECK ((`fighter_id_1` <> `fighter_id_2`)),
  CONSTRAINT `Winner is real` CHECK ((`winner` in (`fighter_id_1`,`fighter_id_2`)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table php-battle.fights : ~0 rows (environ)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
