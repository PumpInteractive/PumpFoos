-- MySQL dump 10.13  Distrib 5.5.42, for osx10.6 (i386)
--
-- Host: localhost    Database: pumpfoos
-- ------------------------------------------------------
-- Server version	5.5.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `game_types`
--

DROP TABLE IF EXISTS `game_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `number_of_players` enum('2','4') NOT NULL DEFAULT '4',
  `score_to_win` tinyint(4) NOT NULL DEFAULT '5',
  `description` text,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_types`
--

LOCK TABLES `game_types` WRITE;
/*!40000 ALTER TABLE `game_types` DISABLE KEYS */;
INSERT INTO `game_types` VALUES (1,'Doubles','4',5,'2 v 2 play',1),(2,'Singles','2',3,'1 v 1 play',0);
/*!40000 ALTER TABLE `game_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_type_id` int(10) unsigned NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `duration` int(10) unsigned DEFAULT NULL,
  `team_1_final_score` tinyint(3) unsigned DEFAULT NULL,
  `team_2_final_score` tinyint(3) unsigned DEFAULT NULL,
  `winning_team` enum('1','2') DEFAULT NULL,
  `losing_team` enum('1','2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `games_game_type_id_idx` (`game_type_id`),
  CONSTRAINT `games_game_type_id` FOREIGN KEY (`game_type_id`) REFERENCES `game_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,1,'2016-04-01 12:58:28',NULL,NULL,NULL,NULL,NULL,NULL),(2,1,'2016-04-01 13:01:58','2016-04-01 13:08:31',393,5,1,'1','2'),(3,1,'2016-04-01 13:09:03','2016-04-01 13:13:04',241,5,3,'1','2'),(4,1,'2016-04-01 13:13:31','2016-04-01 13:23:08',576,3,5,'2','1'),(5,1,'2016-04-03 11:26:20',NULL,NULL,NULL,NULL,NULL,NULL),(6,1,'2016-04-04 15:23:19','2016-04-04 15:28:48',328,2,5,'2','1'),(7,1,'2016-04-04 15:30:03','2016-04-04 15:36:34',391,5,1,'1','2'),(8,1,'2016-04-04 15:37:57','2016-04-04 15:43:08',311,5,4,'1','2'),(9,1,'2016-04-04 15:53:22','2016-04-04 15:59:20',358,5,4,'1','2'),(10,1,'2016-04-05 11:06:27','2016-04-05 11:12:41',375,1,5,'2','1'),(11,1,'2016-04-05 11:13:49','2016-04-05 11:18:13',264,5,3,'1','2'),(12,1,'2016-04-05 11:23:13','2016-04-05 11:27:06',233,2,5,'2','1'),(13,1,'2016-04-05 16:15:39','2016-04-05 16:20:00',258,5,4,'1','2'),(14,1,'2016-04-05 16:20:43','2016-04-05 16:23:54',187,3,5,'2','1'),(15,1,'2016-04-05 16:24:37','2016-04-05 16:29:14',272,5,1,'1','2'),(16,1,'2016-04-05 16:30:37','2016-04-05 16:36:52',371,4,5,'2','1'),(17,1,'2016-04-06 12:53:19',NULL,NULL,NULL,NULL,NULL,NULL),(18,1,'2016-04-06 12:58:40','2016-04-06 13:01:16',152,0,5,'2','1'),(19,1,'2016-04-06 13:01:53','2016-04-06 13:05:23',206,2,5,'2','1'),(20,1,'2016-04-06 13:06:08','2016-04-06 13:10:15',242,5,0,'1','2'),(21,1,'2016-04-06 21:04:16',NULL,NULL,NULL,NULL,NULL,NULL),(22,1,'2016-04-07 13:31:36','2016-04-07 13:36:49',309,5,2,'1','2'),(23,1,'2016-04-07 13:37:35','2016-04-07 13:43:54',374,4,5,'2','1'),(24,1,'2016-04-07 13:44:36','2016-04-07 13:50:03',323,5,4,'1','2'),(25,1,'2016-04-07 16:47:18','2016-04-07 16:56:01',518,5,4,'1','2'),(26,1,'2016-04-07 16:57:21','2016-04-07 17:00:49',203,5,4,'1','2'),(27,1,'2016-04-07 17:01:39','2016-04-07 17:05:42',239,5,0,'1','2'),(28,1,'2016-04-07 17:06:40','2016-04-07 17:13:59',435,2,5,'2','1'),(29,1,'2016-04-07 17:14:44','2016-04-07 17:23:31',523,5,3,'1','2');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games_players`
--

DROP TABLE IF EXISTS `games_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games_players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `team` enum('1','2') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `games_players_game_id_idx` (`game_id`),
  KEY `games_players_player_id_idx` (`player_id`),
  CONSTRAINT `games_players_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  CONSTRAINT `games_players_player_id` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games_players`
--

LOCK TABLES `games_players` WRITE;
/*!40000 ALTER TABLE `games_players` DISABLE KEYS */;
INSERT INTO `games_players` VALUES (1,1,4,'1'),(2,1,5,'1'),(3,1,3,'2'),(4,1,2,'2'),(5,2,1,'1'),(6,2,2,'1'),(7,2,5,'2'),(8,2,3,'2'),(9,3,5,'1'),(10,3,2,'1'),(11,3,3,'2'),(12,3,1,'2'),(13,4,5,'1'),(14,4,1,'1'),(15,4,2,'2'),(16,4,3,'2'),(17,5,5,'1'),(18,5,4,'1'),(19,5,9,'2'),(20,5,10,'2'),(21,6,6,'1'),(22,6,1,'1'),(23,6,4,'2'),(24,6,5,'2'),(25,7,4,'1'),(26,7,6,'1'),(27,7,1,'2'),(28,7,5,'2'),(29,8,2,'1'),(30,8,6,'2'),(31,8,3,'2'),(32,8,4,'1'),(33,9,3,'2'),(34,9,2,'2'),(35,9,4,'1'),(36,9,6,'1'),(37,10,6,'2'),(38,10,4,'2'),(39,10,5,'1'),(40,10,1,'1'),(41,11,6,'1'),(42,11,4,'1'),(43,11,5,'2'),(44,11,2,'2'),(45,12,4,'2'),(46,12,5,'2'),(47,12,1,'1'),(48,12,2,'1'),(49,13,6,'1'),(50,13,1,'1'),(51,13,3,'2'),(52,13,2,'2'),(53,14,6,'2'),(54,14,4,'2'),(55,14,5,'1'),(56,14,1,'1'),(57,15,4,'1'),(58,15,2,'1'),(59,15,6,'2'),(60,15,3,'2'),(61,16,10,'1'),(62,16,3,'2'),(63,16,5,'1'),(64,16,4,'2'),(65,17,4,'1'),(66,17,5,'1'),(67,17,2,'2'),(68,17,3,'2'),(69,18,3,'1'),(70,18,4,'1'),(71,18,5,'2'),(72,18,2,'2'),(73,19,5,'1'),(74,19,3,'1'),(75,19,4,'2'),(76,19,2,'2'),(77,20,9,'1'),(78,20,10,'2'),(79,20,2,'2'),(80,20,5,'1'),(81,21,3,'1'),(82,21,5,'1'),(83,21,4,'2'),(84,21,6,'2'),(85,22,1,'1'),(86,22,2,'1'),(87,22,4,'2'),(88,22,5,'2'),(89,23,4,'1'),(90,23,1,'1'),(91,23,5,'2'),(92,23,2,'2'),(93,24,5,'1'),(94,24,1,'1'),(95,24,4,'2'),(96,24,2,'2'),(97,25,2,'2'),(98,25,5,'2'),(99,25,6,'1'),(100,25,4,'1'),(101,26,6,'1'),(102,26,4,'2'),(103,26,5,'1'),(104,26,10,'2'),(105,27,5,'1'),(106,27,2,'1'),(107,27,4,'2'),(108,27,6,'2'),(109,28,1,'2'),(110,28,2,'1'),(111,28,10,'1'),(112,28,6,'2'),(113,29,4,'1'),(114,29,5,'1'),(115,29,1,'2'),(116,29,2,'2');
/*!40000 ALTER TABLE `games_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `scoring_player_id` int(10) unsigned NOT NULL,
  `scoring_man_id` int(10) unsigned NOT NULL,
  `defending_player_id` int(10) unsigned NOT NULL,
  `bar` enum('3-bar-goalie','2-bar','5-bar','3-bar-attack') NOT NULL,
  `position` enum('near','near-middle','middle','far-middle','far') NOT NULL,
  `player_position` enum('attack','defence') NOT NULL,
  `team` enum('1','2') NOT NULL,
  `time_of_goal` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goals_game_id_idx` (`game_id`),
  KEY `goals_scoring_player_id_idx` (`scoring_player_id`),
  KEY `goals_scoring_man_id_idx` (`scoring_man_id`),
  KEY `goals_defending_player_id_idx` (`defending_player_id`),
  CONSTRAINT `goals_defending_player_id` FOREIGN KEY (`defending_player_id`) REFERENCES `players` (`id`),
  CONSTRAINT `goals_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  CONSTRAINT `goals_scoring_man_id` FOREIGN KEY (`scoring_man_id`) REFERENCES `men` (`id`),
  CONSTRAINT `goals_scoring_player_id` FOREIGN KEY (`scoring_player_id`) REFERENCES `players` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goals`
--

LOCK TABLES `goals` WRITE;
/*!40000 ALTER TABLE `goals` DISABLE KEYS */;
INSERT INTO `goals` VALUES (1,2,1,11,3,'3-bar-attack','near','attack','1',40,'2016-04-01 13:02:39'),(2,2,3,17,2,'2-bar','near','defence','2',80,'2016-04-01 13:03:18'),(3,2,1,12,3,'3-bar-attack','middle','attack','1',178,'2016-04-01 13:04:56'),(4,2,1,13,3,'3-bar-attack','far','attack','1',268,'2016-04-01 13:06:26'),(5,2,2,1,3,'3-bar-goalie','near','defence','1',362,'2016-04-01 13:08:00'),(6,2,2,4,3,'2-bar','near','defence','1',393,'2016-04-01 13:08:31'),(7,3,1,17,5,'2-bar','near','defence','2',23,'2016-04-01 13:09:26'),(8,3,2,12,1,'3-bar-attack','middle','attack','1',43,'2016-04-01 13:09:46'),(9,3,2,13,1,'3-bar-attack','far','attack','1',75,'2016-04-01 13:10:18'),(10,3,2,13,1,'3-bar-attack','far','attack','1',92,'2016-04-01 13:10:34'),(11,3,1,17,5,'2-bar','near','defence','2',174,'2016-04-01 13:11:57'),(12,3,3,26,5,'3-bar-attack','far','attack','2',191,'2016-04-01 13:12:14'),(13,3,2,12,1,'3-bar-attack','middle','attack','1',224,'2016-04-01 13:12:47'),(14,3,2,11,1,'3-bar-attack','near','attack','1',241,'2016-04-01 13:13:04'),(15,4,2,25,1,'3-bar-attack','middle','attack','2',77,'2016-04-01 13:14:48'),(16,4,2,19,1,'5-bar','near','attack','2',90,'2016-04-01 13:15:02'),(17,4,5,11,3,'3-bar-attack','near','attack','1',214,'2016-04-01 13:17:06'),(18,4,2,22,1,'5-bar','far-middle','attack','2',249,'2016-04-01 13:17:41'),(19,4,5,12,3,'3-bar-attack','middle','attack','1',316,'2016-04-01 13:18:48'),(20,4,1,5,3,'2-bar','far','defence','1',337,'2016-04-01 13:19:09'),(21,4,3,16,1,'3-bar-goalie','far','defence','2',468,'2016-04-01 13:21:20'),(22,4,3,15,1,'3-bar-goalie','middle','defence','2',576,'2016-04-01 13:23:08'),(23,5,9,25,5,'3-bar-attack','middle','attack','2',8,'2016-04-03 11:26:29'),(24,5,9,25,5,'3-bar-attack','middle','attack','2',11,'2016-04-03 11:26:32'),(25,5,5,1,10,'3-bar-goalie','near','defence','1',15,'2016-04-03 11:26:35'),(26,6,5,16,6,'3-bar-goalie','far','defence','2',71,'2016-04-04 15:24:30'),(27,6,6,4,5,'2-bar','near','defence','1',129,'2016-04-04 15:25:28'),(28,6,1,13,5,'3-bar-attack','far','attack','1',184,'2016-04-04 15:26:24'),(29,6,4,25,6,'3-bar-attack','middle','attack','2',239,'2016-04-04 15:27:18'),(30,6,4,25,6,'3-bar-attack','middle','attack','2',278,'2016-04-04 15:27:57'),(31,6,4,25,6,'3-bar-attack','middle','attack','2',304,'2016-04-04 15:28:24'),(32,6,4,20,6,'5-bar','near-middle','attack','2',328,'2016-04-04 15:28:48'),(33,7,6,10,1,'5-bar','far','attack','1',46,'2016-04-04 15:30:49'),(34,7,4,5,1,'2-bar','far','defence','1',76,'2016-04-04 15:31:19'),(35,7,1,16,4,'3-bar-goalie','far','defence','2',174,'2016-04-04 15:32:57'),(36,7,4,2,1,'3-bar-goalie','middle','defence','1',193,'2016-04-04 15:33:16'),(37,7,6,11,1,'3-bar-attack','near','attack','1',300,'2016-04-04 15:35:04'),(38,7,6,6,1,'5-bar','near','attack','1',390,'2016-04-04 15:36:34'),(39,8,3,25,4,'3-bar-attack','middle','attack','2',27,'2016-04-04 15:38:24'),(40,8,2,12,6,'3-bar-attack','middle','attack','1',40,'2016-04-04 15:38:37'),(41,8,3,25,4,'3-bar-attack','middle','attack','2',68,'2016-04-04 15:39:06'),(42,8,4,4,6,'2-bar','near','defence','1',120,'2016-04-04 15:39:58'),(43,8,2,8,6,'5-bar','middle','attack','1',183,'2016-04-04 15:41:00'),(44,8,3,20,4,'5-bar','near-middle','attack','2',212,'2016-04-04 15:41:29'),(45,8,4,4,6,'2-bar','near','defence','1',231,'2016-04-04 15:41:48'),(46,8,6,17,4,'2-bar','near','defence','2',255,'2016-04-04 15:42:12'),(47,8,2,13,6,'3-bar-attack','far','attack','1',311,'2016-04-04 15:43:08'),(48,9,4,11,2,'3-bar-attack','near','attack','1',29,'2016-04-04 15:53:51'),(49,9,4,12,2,'3-bar-attack','middle','attack','1',50,'2016-04-04 15:54:11'),(50,9,4,12,2,'3-bar-attack','middle','attack','1',84,'2016-04-04 15:54:45'),(51,9,3,21,6,'5-bar','middle','attack','2',122,'2016-04-04 15:55:24'),(52,9,3,24,6,'3-bar-attack','near','attack','2',207,'2016-04-04 15:56:48'),(53,9,3,19,6,'5-bar','near','attack','2',236,'2016-04-04 15:57:17'),(54,9,6,3,2,'3-bar-goalie','far','defence','1',320,'2016-04-04 15:58:41'),(55,9,3,19,6,'5-bar','near','attack','2',334,'2016-04-04 15:58:56'),(56,9,4,6,2,'5-bar','near','attack','1',358,'2016-04-04 15:59:20'),(57,10,6,26,1,'3-bar-attack','far','attack','2',28,'2016-04-05 11:06:55'),(58,10,1,5,4,'2-bar','far','defence','1',88,'2016-04-05 11:07:55'),(59,10,6,24,1,'3-bar-attack','near','attack','2',111,'2016-04-05 11:08:18'),(60,10,6,19,1,'5-bar','near','attack','2',218,'2016-04-05 11:10:05'),(61,10,6,26,1,'3-bar-attack','far','attack','2',334,'2016-04-05 11:12:01'),(62,10,4,16,1,'3-bar-goalie','far','defence','2',374,'2016-04-05 11:12:41'),(63,11,2,26,6,'3-bar-attack','far','attack','2',17,'2016-04-05 11:14:06'),(64,11,4,11,5,'3-bar-attack','near','attack','1',35,'2016-04-05 11:14:24'),(65,11,4,13,5,'3-bar-attack','far','attack','1',70,'2016-04-05 11:14:59'),(66,11,4,13,5,'3-bar-attack','far','attack','1',93,'2016-04-05 11:15:22'),(67,11,2,24,6,'3-bar-attack','near','attack','2',117,'2016-04-05 11:15:46'),(68,11,4,12,5,'3-bar-attack','middle','attack','1',193,'2016-04-05 11:17:02'),(69,11,2,22,6,'5-bar','far-middle','attack','2',206,'2016-04-05 11:17:15'),(70,11,4,12,5,'3-bar-attack','middle','attack','1',264,'2016-04-05 11:18:13'),(71,12,4,16,2,'3-bar-goalie','far','defence','2',18,'2016-04-05 11:23:32'),(72,12,1,12,4,'3-bar-attack','middle','attack','1',54,'2016-04-05 11:24:08'),(73,12,5,21,2,'5-bar','middle','attack','2',68,'2016-04-05 11:24:22'),(74,12,2,3,4,'3-bar-goalie','far','defence','1',99,'2016-04-05 11:24:52'),(75,12,5,21,2,'5-bar','middle','attack','2',170,'2016-04-05 11:26:04'),(76,12,5,19,2,'5-bar','near','attack','2',189,'2016-04-05 11:26:22'),(77,12,5,25,2,'3-bar-attack','middle','attack','2',233,'2016-04-05 11:27:06'),(78,13,3,18,1,'2-bar','far','defence','2',34,'2016-04-05 16:16:17'),(79,13,1,2,3,'3-bar-goalie','middle','defence','1',64,'2016-04-05 16:16:47'),(80,13,2,25,1,'3-bar-attack','middle','attack','2',80,'2016-04-05 16:17:03'),(81,13,6,11,3,'3-bar-attack','near','attack','1',113,'2016-04-05 16:17:36'),(82,13,2,20,1,'5-bar','near-middle','attack','2',135,'2016-04-05 16:17:58'),(83,13,1,4,3,'2-bar','near','defence','1',164,'2016-04-05 16:18:27'),(84,13,6,11,3,'3-bar-attack','near','attack','1',186,'2016-04-05 16:18:49'),(85,13,2,24,1,'3-bar-attack','near','attack','2',214,'2016-04-05 16:19:17'),(86,13,6,11,3,'3-bar-attack','near','attack','1',257,'2016-04-05 16:20:00'),(87,14,1,4,6,'2-bar','near','defence','1',20,'2016-04-05 16:21:07'),(88,14,1,4,6,'2-bar','near','defence','1',47,'2016-04-05 16:21:34'),(89,14,5,6,6,'5-bar','near','attack','1',67,'2016-04-05 16:21:54'),(90,14,6,18,1,'2-bar','far','defence','2',91,'2016-04-05 16:22:18'),(91,14,4,24,1,'3-bar-attack','near','attack','2',112,'2016-04-05 16:22:39'),(92,14,4,25,1,'3-bar-attack','middle','attack','2',131,'2016-04-05 16:22:58'),(93,14,4,22,1,'5-bar','far-middle','attack','2',144,'2016-04-05 16:23:11'),(94,14,4,26,1,'3-bar-attack','far','attack','2',187,'2016-04-05 16:23:54'),(95,15,6,22,4,'5-bar','far-middle','attack','2',29,'2016-04-05 16:25:11'),(96,15,2,11,3,'3-bar-attack','near','attack','1',64,'2016-04-05 16:25:46'),(97,15,2,11,3,'3-bar-attack','near','attack','1',129,'2016-04-05 16:26:51'),(98,15,4,5,3,'2-bar','far','defence','1',214,'2016-04-05 16:28:16'),(99,15,4,3,3,'3-bar-goalie','far','defence','1',241,'2016-04-05 16:28:43'),(100,15,4,4,3,'2-bar','near','defence','1',272,'2016-04-05 16:29:14'),(101,16,4,18,5,'2-bar','far','defence','2',30,'2016-04-05 16:31:11'),(102,16,4,14,5,'3-bar-goalie','near','defence','2',73,'2016-04-05 16:31:54'),(103,16,3,26,5,'3-bar-attack','far','attack','2',90,'2016-04-05 16:32:11'),(104,16,5,3,4,'3-bar-goalie','far','defence','1',150,'2016-04-05 16:33:11'),(105,16,10,12,4,'3-bar-attack','middle','attack','1',170,'2016-04-05 16:33:31'),(106,16,10,7,4,'5-bar','near-middle','attack','1',207,'2016-04-05 16:34:08'),(107,16,5,3,4,'3-bar-goalie','far','defence','1',300,'2016-04-05 16:35:41'),(108,16,3,21,5,'5-bar','middle','attack','2',318,'2016-04-05 16:35:59'),(109,16,3,21,5,'5-bar','middle','attack','2',370,'2016-04-05 16:36:51'),(110,17,2,14,4,'3-bar-goalie','near','defence','2',28,'2016-04-06 12:53:51'),(111,17,5,7,2,'5-bar','near-middle','attack','1',37,'2016-04-06 12:54:01'),(112,17,3,25,4,'3-bar-attack','middle','attack','2',55,'2016-04-06 12:54:19'),(113,17,5,9,2,'5-bar','far-middle','attack','1',88,'2016-04-06 12:54:52'),(114,17,3,21,4,'5-bar','middle','attack','2',99,'2016-04-06 12:55:03'),(115,18,2,25,3,'3-bar-attack','middle','attack','2',31,'2016-04-06 12:59:15'),(116,18,2,24,3,'3-bar-attack','near','attack','2',78,'2016-04-06 13:00:03'),(117,18,5,16,3,'3-bar-goalie','far','defence','2',98,'2016-04-06 13:00:22'),(118,18,2,25,3,'3-bar-attack','middle','attack','2',115,'2016-04-06 13:00:40'),(119,18,2,25,3,'3-bar-attack','middle','attack','2',151,'2016-04-06 13:01:16'),(120,19,4,16,5,'3-bar-goalie','far','defence','2',20,'2016-04-06 13:02:18'),(121,19,3,11,4,'3-bar-attack','near','attack','1',93,'2016-04-06 13:03:31'),(122,19,2,25,5,'3-bar-attack','middle','attack','2',114,'2016-04-06 13:03:52'),(123,19,2,24,5,'3-bar-attack','near','attack','2',141,'2016-04-06 13:04:19'),(124,19,4,14,5,'3-bar-goalie','near','defence','2',162,'2016-04-06 13:04:40'),(125,19,3,6,4,'5-bar','near','attack','1',186,'2016-04-06 13:05:04'),(126,19,2,21,5,'5-bar','middle','attack','2',206,'2016-04-06 13:05:23'),(127,20,9,1,2,'3-bar-goalie','near','defence','1',11,'2016-04-06 13:06:24'),(128,20,5,7,2,'5-bar','near-middle','attack','1',26,'2016-04-06 13:06:39'),(129,20,5,11,2,'3-bar-attack','near','attack','1',76,'2016-04-06 13:07:29'),(130,20,5,11,2,'3-bar-attack','near','attack','1',162,'2016-04-06 13:08:54'),(131,20,5,12,2,'3-bar-attack','middle','attack','1',242,'2016-04-06 13:10:15'),(132,22,2,12,5,'3-bar-attack','middle','attack','1',49,'2016-04-07 13:32:29'),(133,22,2,12,5,'3-bar-attack','middle','attack','1',79,'2016-04-07 13:32:58'),(134,22,2,12,5,'3-bar-attack','middle','attack','1',112,'2016-04-07 13:33:32'),(135,22,4,26,1,'3-bar-attack','far','attack','2',137,'2016-04-07 13:33:57'),(136,22,2,6,5,'5-bar','near','attack','1',201,'2016-04-07 13:35:01'),(137,22,4,24,1,'3-bar-attack','near','attack','2',278,'2016-04-07 13:36:18'),(138,22,2,11,5,'3-bar-attack','near','attack','1',309,'2016-04-07 13:36:49'),(139,23,4,3,2,'3-bar-goalie','far','defence','1',47,'2016-04-07 13:38:27'),(140,23,5,24,4,'3-bar-attack','near','attack','2',70,'2016-04-07 13:38:50'),(141,23,5,20,4,'5-bar','near-middle','attack','2',109,'2016-04-07 13:39:29'),(142,23,5,24,4,'3-bar-attack','near','attack','2',145,'2016-04-07 13:40:05'),(143,23,5,25,4,'3-bar-attack','middle','attack','2',253,'2016-04-07 13:41:52'),(144,23,4,1,2,'3-bar-goalie','near','defence','1',284,'2016-04-07 13:42:24'),(145,23,1,12,2,'3-bar-attack','middle','attack','1',300,'2016-04-07 13:42:40'),(146,23,4,3,2,'3-bar-goalie','far','defence','1',343,'2016-04-07 13:43:23'),(147,23,5,20,4,'5-bar','near-middle','attack','2',374,'2016-04-07 13:43:54'),(148,24,4,25,5,'3-bar-attack','middle','attack','2',29,'2016-04-07 13:45:09'),(149,24,1,7,2,'5-bar','near-middle','attack','1',89,'2016-04-07 13:46:09'),(150,24,1,12,2,'3-bar-attack','middle','attack','1',127,'2016-04-07 13:46:47'),(151,24,4,24,5,'3-bar-attack','near','attack','2',156,'2016-04-07 13:47:16'),(152,24,4,26,5,'3-bar-attack','far','attack','2',195,'2016-04-07 13:47:55'),(153,24,1,11,2,'3-bar-attack','near','attack','1',228,'2016-04-07 13:48:28'),(154,24,4,25,5,'3-bar-attack','middle','attack','2',249,'2016-04-07 13:48:49'),(155,24,1,12,2,'3-bar-attack','middle','attack','1',269,'2016-04-07 13:49:09'),(156,24,1,12,2,'3-bar-attack','middle','attack','1',323,'2016-04-07 13:50:02'),(157,25,5,22,6,'5-bar','far-middle','attack','2',25,'2016-04-07 16:47:47'),(158,25,5,24,6,'3-bar-attack','near','attack','2',77,'2016-04-07 16:48:39'),(159,25,2,18,6,'2-bar','far','defence','2',230,'2016-04-07 16:51:13'),(160,25,4,11,2,'3-bar-attack','near','attack','1',263,'2016-04-07 16:51:46'),(161,25,5,21,6,'5-bar','middle','attack','2',281,'2016-04-07 16:52:04'),(162,25,6,4,2,'2-bar','near','defence','1',350,'2016-04-07 16:53:13'),(163,25,4,12,2,'3-bar-attack','middle','attack','1',365,'2016-04-07 16:53:28'),(164,25,6,2,2,'3-bar-goalie','middle','defence','1',407,'2016-04-07 16:54:09'),(165,25,4,12,2,'3-bar-attack','middle','attack','1',518,'2016-04-07 16:56:01'),(166,26,6,8,4,'5-bar','middle','attack','1',10,'2016-04-07 16:57:36'),(167,26,6,8,4,'5-bar','middle','attack','1',25,'2016-04-07 16:57:50'),(168,26,4,16,5,'3-bar-goalie','far','defence','2',54,'2016-04-07 16:58:20'),(169,26,6,12,4,'3-bar-attack','middle','attack','1',77,'2016-04-07 16:58:42'),(170,26,6,8,4,'5-bar','middle','attack','1',117,'2016-04-07 16:59:23'),(171,26,4,17,5,'2-bar','near','defence','2',141,'2016-04-07 16:59:47'),(172,26,4,16,5,'3-bar-goalie','far','defence','2',167,'2016-04-07 17:00:13'),(173,26,4,16,5,'3-bar-goalie','far','defence','2',190,'2016-04-07 17:00:36'),(174,26,6,8,4,'5-bar','middle','attack','1',203,'2016-04-07 17:00:49'),(175,27,5,1,4,'3-bar-goalie','near','defence','1',71,'2016-04-07 17:02:54'),(176,27,2,11,4,'3-bar-attack','near','attack','1',92,'2016-04-07 17:03:15'),(177,27,2,11,4,'3-bar-attack','near','attack','1',165,'2016-04-07 17:04:29'),(178,27,2,12,4,'3-bar-attack','middle','attack','1',198,'2016-04-07 17:05:01'),(179,27,2,13,4,'3-bar-attack','far','attack','1',238,'2016-04-07 17:05:42'),(180,28,6,25,2,'3-bar-attack','middle','attack','2',32,'2016-04-07 17:07:17'),(181,28,2,4,1,'2-bar','near','defence','1',72,'2016-04-07 17:07:56'),(182,28,6,24,2,'3-bar-attack','near','attack','2',133,'2016-04-07 17:08:57'),(183,28,6,26,2,'3-bar-attack','far','attack','2',161,'2016-04-07 17:09:26'),(184,28,2,4,1,'2-bar','near','defence','1',293,'2016-04-07 17:11:37'),(185,28,6,25,2,'3-bar-attack','middle','attack','2',411,'2016-04-07 17:13:36'),(186,28,6,26,2,'3-bar-attack','far','attack','2',435,'2016-04-07 17:13:59'),(187,29,1,25,4,'3-bar-attack','middle','attack','2',36,'2016-04-07 17:15:24'),(188,29,5,8,2,'5-bar','middle','attack','1',89,'2016-04-07 17:16:18'),(189,29,2,17,4,'2-bar','near','defence','2',131,'2016-04-07 17:16:59'),(190,29,4,3,2,'3-bar-goalie','far','defence','1',186,'2016-04-07 17:17:54'),(191,29,4,5,2,'2-bar','far','defence','1',216,'2016-04-07 17:18:25'),(192,29,1,25,4,'3-bar-attack','middle','attack','2',245,'2016-04-07 17:18:54'),(193,29,4,3,2,'3-bar-goalie','far','defence','1',360,'2016-04-07 17:20:49'),(194,29,5,11,2,'3-bar-attack','near','attack','1',523,'2016-04-07 17:23:31');
/*!40000 ALTER TABLE `goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `men`
--

DROP TABLE IF EXISTS `men`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `men` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team` enum('1','2') NOT NULL,
  `bar` enum('3-bar-goalie','2-bar','5-bar','3-bar-attack') NOT NULL,
  `position` enum('near','near-middle','middle','far-middle','far') NOT NULL,
  `player_position` enum('attack','defence') NOT NULL,
  `scoring_shortcut_key` char(1) NOT NULL,
  `scoring_key_code` tinyint(4) NOT NULL,
  `display_order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `men`
--

LOCK TABLES `men` WRITE;
/*!40000 ALTER TABLE `men` DISABLE KEYS */;
INSERT INTO `men` VALUES (1,'1','3-bar-goalie','near','defence','q',81,1),(2,'1','3-bar-goalie','middle','defence','w',87,2),(3,'1','3-bar-goalie','far','defence','e',69,3),(4,'1','2-bar','near','defence','r',82,1),(5,'1','2-bar','far','defence','t',84,2),(6,'1','5-bar','near','attack','y',89,1),(7,'1','5-bar','near-middle','attack','u',85,2),(8,'1','5-bar','middle','attack','i',73,3),(9,'1','5-bar','far-middle','attack','o',79,4),(10,'1','5-bar','far','attack','p',80,5),(11,'1','3-bar-attack','near','attack','a',65,1),(12,'1','3-bar-attack','middle','attack','s',83,2),(13,'1','3-bar-attack','far','attack','d',68,3),(14,'2','3-bar-goalie','near','defence','f',70,3),(15,'2','3-bar-goalie','middle','defence','g',71,2),(16,'2','3-bar-goalie','far','defence','h',72,1),(17,'2','2-bar','near','defence','j',74,2),(18,'2','2-bar','far','defence','k',75,1),(19,'2','5-bar','near','attack','l',76,5),(20,'2','5-bar','near-middle','attack','z',90,4),(21,'2','5-bar','middle','attack','x',88,3),(22,'2','5-bar','far-middle','attack','c',67,2),(23,'2','5-bar','far','attack','v',86,1),(24,'2','3-bar-attack','near','attack','b',66,3),(25,'2','3-bar-attack','middle','attack','n',78,2),(26,'2','3-bar-attack','far','attack','m',77,1);
/*!40000 ALTER TABLE `men` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slack_user_id` varchar(255) NOT NULL,
  `slack_user_name` varchar(255) NOT NULL,
  `slack_profile_pic_url` varchar(255) NOT NULL,
  `games_played` int(10) unsigned NOT NULL,
  `wins` int(10) unsigned NOT NULL,
  `losses` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `players_slack_user_id_idx` (`slack_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (1,'U052ZM7H9','sean','https://secure.gravatar.com/avatar/c89360d2945a74bf81d5903a5db98fd9.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0012-192.png',45,21,24),(2,'U052BJ7S2','troy','https://secure.gravatar.com/avatar/3fcc295c42638dbd75cbe8a2d4340b5c.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0020-192.png',64,39,25),(3,'U052Z95B5','scott','https://secure.gravatar.com/avatar/2e7cd69e5868bb22437c70a44ea7f509.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0015-192.png',52,25,27),(4,'U08JRNPR7','brendan','https://avatars.slack-edge.com/2016-03-01/23831393376_cb6c805992a5b4b1cd2e_192.jpg',70,37,33),(5,'U0532LGKV','jake','https://avatars.slack-edge.com/2016-01-22/19221767252_6eaf8a24d34a528f00d8_192.jpg',64,36,28),(6,'U052BH68S','andrew','https://avatars.slack-edge.com/2016-01-28/19717468007_b908effbad42b5402784_192.jpg',0,0,0),(9,'U0531AGST','liz','https://avatars.slack-edge.com/2016-02-03/20281212976_0367c55ca2550342c378_192.jpg',0,0,0),(10,'U0KAVFR2T','melissa','https://secure.gravatar.com/avatar/6c6967e1b38f2cad08cacda0059da1d3.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0009-192.png',0,0,0);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-07 17:26:18
