-- MySQL dump 10.13  Distrib 5.7.21, for Win64 (x86_64)
--
-- Host: localhost    Database: agnes
-- ------------------------------------------------------
-- Server version	5.7.21

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Villages'),(2,'Plages');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_picture` int(11) NOT NULL,
  `content` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_category` (`id_picture`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`id_picture`) REFERENCES `picture` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,1,1,'dsqdsqdfefz','2018-05-20 00:31:04'),(2,1,1,'sqeazb tgerq hd gq gq g','2018-05-20 00:49:56'),(3,1,1,'<script type=\"text/javascript\">alert(\'test\');</script>','2018-05-20 00:59:32'),(4,1,1,'dsqdsqdsqdsqdsq','2018-05-20 01:50:06'),(5,1,1,'dsqdsqdqsdsd','2018-05-20 01:50:06'),(6,1,1,'dsqdsqddsqdsq','2018-05-20 01:50:11'),(9,1,1,'fdsfsdf','2018-05-20 03:24:56'),(11,1,1,'fdsdsd','2018-05-20 03:24:56'),(12,1,1,'fdsazr','2018-05-20 03:28:32'),(13,1,1,'gn h ezhrthr','2018-05-20 03:28:32'),(14,1,1,'dsq','2018-05-20 03:34:58'),(15,1,1,'greztahq','2018-05-20 03:35:03'),(16,1,1,'yhjrzrss','2018-05-20 03:35:10'),(17,1,1,'dsqdsqdsq','2018-05-20 23:49:24'),(18,1,1,'je viens d\'etre créé','2018-05-20 23:49:33'),(19,1,1,'dsqdsqdsqd à l\'instant','2018-05-20 23:51:54'),(20,1,1,'dsqdsqdsqd à l\'instant','2018-05-20 23:52:51'),(21,1,1,'fdsfsd','2018-05-20 23:53:44'),(22,1,1,'<script type=\"text/javascript\">alert(\'test\');</script>','2018-05-20 23:54:52'),(23,1,1,'ugiuuv','2018-05-20 23:55:34'),(24,1,1,'azezaeaze','2018-05-20 23:56:39'),(25,1,1,'fdsfdearear ag gr ','2018-05-20 23:57:17'),(26,1,1,'fdsfdearear ag gr ','2018-05-20 23:57:56'),(27,1,1,'fdsfdearear ag gr ','2018-05-20 23:58:04'),(28,1,1,'ezaeazb on test putain','2018-05-20 23:58:55'),(29,1,1,'bonon test le dernier','2018-05-20 23:59:56'),(30,1,1,'hrfeg regea q','2018-05-23 21:26:39'),(31,1,2,'BLABLABLA','2018-05-23 21:27:39'),(32,1,2,'dzadza','2018-05-23 21:29:14'),(33,1,1,'bkabka','2018-06-07 00:29:14'),(34,1,1,'cqsdqs','2018-06-07 00:29:32'),(35,1,1,'hgdfgfdg','2018-06-07 00:42:33');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (2,1,'Test numéro 2','blabla','2018-08-04 06:00:00','2018-08-04 23:00:00',0),(3,1,'Bonjour je test','fdsfdfds','1970-01-01 01:00:08','1970-01-01 01:00:08',0),(4,1,'Bonjour je test','dsqdqsdqs','1970-01-01 01:00:08','1970-01-01 01:00:08',0),(5,1,'Bonjour je test','salut','2018-08-12 00:00:00','2018-08-12 23:00:00',0),(6,1,'Bonjour je test','fezfezfeefffe','2018-08-11 00:00:00','2018-08-11 23:00:00',0),(7,1,'Bonjour je test','dsqdsdsqdsqdsq','2018-08-09 00:00:00','2018-08-09 23:00:00',0),(8,1,'dsqdsqdsqdsqf','dqsdsqqdq','2018-08-09 00:00:00','2018-08-09 23:00:00',0),(9,1,'fdfsdsfdfsdfs','fdsfssfs','2018-08-11 00:00:00','2018-08-11 23:00:00',0),(10,1,'Excursions','Bonjour je voudrais réserver pour le 10 Août de 8h à 16h','2018-08-10 00:00:00','2018-08-10 23:00:00',0);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture`
--

DROP TABLE IF EXISTS `picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `extension` varchar(64) NOT NULL,
  `description` text,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_category` (`id_category`),
  CONSTRAINT `picture_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture`
--

LOCK TABLES `picture` WRITE;
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` VALUES (1,1,'transport-2','.jpg',NULL,540,960),(2,1,'transport-3','.jpg',NULL,540,960),(3,1,'transport1','.jpg',NULL,540,960),(4,1,'transports','.jpg',NULL,540,960),(5,1,'vautours','.jpg',NULL,1224,689),(6,1,'villa-en-location','.jpg',NULL,960,720),(7,2,'st-louis','.jpg',NULL,2048,1152),(8,2,'stlouis-6','.jpg',NULL,540,960),(9,2,'sur-ile-de-goree','.jpg',NULL,960,960),(10,2,'tout-vert-2','.jpg',NULL,1080,731),(11,2,'tout-vert','.jpg',NULL,540,960);
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'stevens','$2y$10$L9BGrW6ixtaT1dVc3JHuJuJ42xtPpnwJF0qC6IhHrNFd3Ap.tolBy','stevensc92@gmail.com',1,'ROLE_ADMIN');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-05  8:37:53
