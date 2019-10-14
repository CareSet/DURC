-- MySQL dump 10.16  Distrib 10.2.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aaaDurctest
-- ------------------------------------------------------
-- Server version	10.2.11-MariaDB-10.2.11+maria~xenial-log

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
-- Table structure for table `Should be Ignored`
--

DROP TABLE IF EXISTS `Should be Ignored`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Should be Ignored` (
  `firstname` varchar(11) DEFAULT NULL,
  `lastname` varchar(12) DEFAULT NULL,
  `NULL` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Should be Ignored`
--

LOCK TABLES `Should be Ignored` WRITE;
/*!40000 ALTER TABLE `Should be Ignored` DISABLE KEYS */;
/*!40000 ALTER TABLE `Should be Ignored` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Trotter','Frederick','2017-12-05 00:00:00','2017-12-05 00:00:00'),(2,'Uhlman','David','2017-12-05 00:00:00','2017-12-05 00:00:00'),(3,'Steinbeck','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(4,'Perkins','Maxwell','2017-12-05 00:00:00','2017-12-05 00:00:00'),(5,'Fitzgerald','F Scott','2017-12-05 00:00:00','2017-12-05 00:00:00'),(6,'BECKETT','SAMUEL','2017-12-05 00:00:00','2017-12-05 00:00:00'),(7,'Clancy','Tom','2017-12-05 00:00:00','2017-12-05 00:00:00'),(8,'Shakespere','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(9,'Rand','Ayn','2017-12-05 00:00:00','2017-12-05 00:00:00'),(10,'HEMINGWAY','ERNEST','2017-12-05 00:00:00','2017-12-05 00:00:00'),(11,'DIDION','JOAN','2017-12-05 00:00:00','2017-12-05 00:00:00'),(12,'BRADBURY','RAY','2017-12-05 00:00:00','2017-12-05 00:00:00'),(13,'MARTIN','GEORGE','2017-12-05 00:00:00','2017-12-05 00:00:00'),(14,'FLYNN','GILLIAN','2017-12-05 00:00:00','2017-12-05 00:00:00'),(15,'NABOKOV','VLADIMIR','2017-12-05 00:00:00','2017-12-05 00:00:00'),(16,'AUSTEN','JANE','2017-12-05 00:00:00','2017-12-05 00:00:00'),(17,'TWAIN','MARK','2017-12-05 00:00:00','2017-12-05 00:00:00'),(18,'WOLITZER','MEG','2017-12-05 00:00:00','2017-12-05 00:00:00'),(19,'LARSON','ERIK','2017-12-05 00:00:00','2017-12-05 00:00:00'),(20,'DANTICAT','EDWIDGE','2017-12-05 00:00:00','2017-12-05 00:00:00'),(21,'Christie','Agatha','2017-12-05 00:00:00','2017-12-05 00:00:00'),(22,'Moore','Alan','2017-12-05 00:00:00','2017-12-05 00:00:00'),(23,'Camus','Albert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(24,'Huxley','Aldous','2017-12-05 00:00:00','2017-12-05 00:00:00'),(25,'Smith','Alexander','2017-12-05 00:00:00','2017-12-05 00:00:00'),(26,'Ginsberg','Allen','2017-12-05 00:00:00','2017-12-05 00:00:00'),(27,'Nin','Anaïs','2017-12-05 00:00:00','2017-12-05 00:00:00'),(28,'Frank','Anne','2017-12-05 00:00:00','2017-12-05 00:00:00'),(29,'Rice','Anne','2017-12-05 00:00:00','2017-12-05 00:00:00'),(30,'De','Antoine','2017-12-05 00:00:00','2017-12-05 00:00:00'),(31,'Chekhov','Anton','2017-12-05 00:00:00','2017-12-05 00:00:00'),(32,'Clarke','Arthur','2017-12-05 00:00:00','2017-12-05 00:00:00'),(33,'Miller','Arthur','2017-12-05 00:00:00','2017-12-05 00:00:00'),(34,'Roy','Arundhati','2017-12-05 00:00:00','2017-12-05 00:00:00'),(35,'Lindgren','Astrid','2017-12-05 00:00:00','2017-12-05 00:00:00'),(36,'Rand','Ayn','2017-12-05 00:00:00','2017-12-05 00:00:00'),(37,'Cartland','Barbara','2017-12-05 00:00:00','2017-12-05 00:00:00'),(38,'Potter','Beatrix','2017-12-05 00:00:00','2017-12-05 00:00:00'),(39,'Cleary','Beverly','2017-12-05 00:00:00','2017-12-05 00:00:00'),(40,'Jacques','Brian','2017-12-05 00:00:00','2017-12-05 00:00:00'),(41,'Duffy','Carol','2017-12-05 00:00:00','2017-12-05 00:00:00'),(42,'Dickens','Charles','2017-12-05 00:00:00','2017-12-05 00:00:00'),(43,'Achebe','Chinua','2017-12-05 00:00:00','2017-12-05 00:00:00'),(44,'Hitchens','Christopher','2017-12-05 00:00:00','2017-12-05 00:00:00'),(45,'Marlowe','Christopher','2017-12-05 00:00:00','2017-12-05 00:00:00'),(46,'Paolini','Christopher','2017-12-05 00:00:00','2017-12-05 00:00:00'),(47,'Palahniuk','Chuck','2017-12-05 00:00:00','2017-12-05 00:00:00'),(48,'Cussler','Clive','2017-12-05 00:00:00','2017-12-05 00:00:00'),(49,'McCarthy','Cormac','2017-12-05 00:00:00','2017-12-05 00:00:00'),(50,'Lewis','CS','2017-12-05 00:00:00','2017-12-05 00:00:00'),(51,'Mi?osz','Czes?aw','2017-12-05 00:00:00','2017-12-05 00:00:00'),(52,'Carnegie','Dale','2017-12-05 00:00:00','2017-12-05 00:00:00'),(53,'Brown','Dan','2017-12-05 00:00:00','2017-12-05 00:00:00'),(54,'Steel','Danielle','2017-12-05 00:00:00','2017-12-05 00:00:00'),(55,'Alighieri','Dante','2017-12-05 00:00:00','2017-12-05 00:00:00'),(56,'du-Maurier','Daphne','2017-12-05 00:00:00','2017-12-05 00:00:00'),(57,'Hammett','Dashiell','2017-12-05 00:00:00','2017-12-05 00:00:00'),(58,'Pilkey','Dav','2017-12-05 00:00:00','2017-12-05 00:00:00'),(59,'Baldacci','David','2017-12-05 00:00:00','2017-12-05 00:00:00'),(60,'Sedaris','David','2017-12-05 00:00:00','2017-12-05 00:00:00'),(61,'Wallace','David','2017-12-05 00:00:00','2017-12-05 00:00:00'),(62,'Koontz','Dean','2017-12-05 00:00:00','2017-12-05 00:00:00'),(63,'Macomber','Debbie','2017-12-05 00:00:00','2017-12-05 00:00:00'),(64,'Diderot','Denis','2017-12-05 00:00:00','2017-12-05 00:00:00'),(65,'Lawrence','DH','2017-12-05 00:00:00','2017-12-05 00:00:00'),(66,'DeLillo','Don','2017-12-05 00:00:00','2017-12-05 00:00:00'),(67,'Lessing','Doris','2017-12-05 00:00:00','2017-12-05 00:00:00'),(68,'Adams','Douglas','2017-12-05 00:00:00','2017-12-05 00:00:00'),(69,'Seuss','Dr','2017-12-05 00:00:00','2017-12-05 00:00:00'),(70,'Poe','Edgar','2017-12-05 00:00:00','2017-12-05 00:00:00'),(71,'Wharton','Edith','2017-12-05 00:00:00','2017-12-05 00:00:00'),(72,'Hubbard','Elbert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(73,'Wiesel','Elie','2017-12-05 00:00:00','2017-12-05 00:00:00'),(74,'White','Ellen','2017-12-05 00:00:00','2017-12-05 00:00:00'),(75,'Forster','EM','2017-12-05 00:00:00','2017-12-05 00:00:00'),(76,'Zola','Émile','2017-12-05 00:00:00','2017-12-05 00:00:00'),(77,'Dickinson','Emily','2017-12-05 00:00:00','2017-12-05 00:00:00'),(78,'Blyton','Enid','2017-12-05 00:00:00','2017-12-05 00:00:00'),(79,'Colfer','Eoin','2017-12-05 00:00:00','2017-12-05 00:00:00'),(80,'Carle','Eric','2017-12-05 00:00:00','2017-12-05 00:00:00'),(81,'Jong','Erica','2017-12-05 00:00:00','2017-12-05 00:00:00'),(82,'Hemingway','Ernest','2017-12-05 00:00:00','2017-12-05 00:00:00'),(83,'Euripides','Euripides','2017-12-05 00:00:00','2017-12-05 00:00:00'),(84,'Fitzgerald','F.Scott','2017-12-05 00:00:00','2017-12-05 00:00:00'),(85,'Pessoa','Fernando','2017-12-05 00:00:00','2017-12-05 00:00:00'),(86,'O’Connor','Flannery','2017-12-05 00:00:00','2017-12-05 00:00:00'),(87,'McCourt','Frank','2017-12-05 00:00:00','2017-12-05 00:00:00'),(88,'Kafka','Franz','2017-12-05 00:00:00','2017-12-05 00:00:00'),(89,'Márquez','Gabriel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(90,'Chaucer','Geoffrey','2017-12-05 00:00:00','2017-12-05 00:00:00'),(91,'Eliot','George','2017-12-05 00:00:00','2017-12-05 00:00:00'),(92,'Orwell','George','2017-12-05 00:00:00','2017-12-05 00:00:00'),(93,'Shaw','George','2017-12-05 00:00:00','2017-12-05 00:00:00'),(94,'Martin','George-RR','2017-12-05 00:00:00','2017-12-05 00:00:00'),(95,'Greer','Germaine','2017-12-05 00:00:00','2017-12-05 00:00:00'),(96,'Stein','Gertrude','2017-12-05 00:00:00','2017-12-05 00:00:00'),(97,'Leopardi','Giacomo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(98,'Boccaccio','Giovanni','2017-12-05 00:00:00','2017-12-05 00:00:00'),(99,'Grass','Günter','2017-12-05 00:00:00','2017-12-05 00:00:00'),(100,'Flaubert','Gustave','2017-12-05 00:00:00','2017-12-05 00:00:00'),(101,'Homer','H','2017-12-05 00:00:00','2017-12-05 00:00:00'),(102,'Andersen','Hans','2017-12-05 00:00:00','2017-12-05 00:00:00'),(103,'Lee','Harper','2017-12-05 00:00:00','2017-12-05 00:00:00'),(104,'Murakami','Haruki','2017-12-05 00:00:00','2017-12-05 00:00:00'),(105,'Mankell','Henning','2017-12-05 00:00:00','2017-12-05 00:00:00'),(106,'Ibsen','Henrik','2017-12-05 00:00:00','2017-12-05 00:00:00'),(107,'James','Henry','2017-12-05 00:00:00','2017-12-05 00:00:00'),(108,'Miller','Henry','2017-12-05 00:00:00','2017-12-05 00:00:00'),(109,'Thoreau','Henry','2017-12-05 00:00:00','2017-12-05 00:00:00'),(110,'Melville','Herman','2017-12-05 00:00:00','2017-12-05 00:00:00'),(111,'Hesse','Hermann','2017-12-05 00:00:00','2017-12-05 00:00:00'),(112,'Wells','HG','2017-12-05 00:00:00','2017-12-05 00:00:00'),(113,'Balzac','Honoré','2017-12-05 00:00:00','2017-12-05 00:00:00'),(114,'Thompson','Hunter','2017-12-05 00:00:00','2017-12-05 00:00:00'),(115,'Fleming','Ian','2017-12-05 00:00:00','2017-12-05 00:00:00'),(116,'McEwan','Ian','2017-12-05 00:00:00','2017-12-05 00:00:00'),(117,'Asimov','Isaac','2017-12-05 00:00:00','2017-12-05 00:00:00'),(118,'Allende','Isabel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(119,'Calvino','Italo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(120,'Svevo','Italo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(121,'Kerouac','Jack','2017-12-05 00:00:00','2017-12-05 00:00:00'),(122,'London','Jack','2017-12-05 00:00:00','2017-12-05 00:00:00'),(123,'Collins','Jackie','2017-12-05 00:00:00','2017-12-05 00:00:00'),(124,'Wilson','Jacqueline','2017-12-05 00:00:00','2017-12-05 00:00:00'),(125,'Frey','James','2017-12-05 00:00:00','2017-12-05 00:00:00'),(126,'Joyce','James','2017-12-05 00:00:00','2017-12-05 00:00:00'),(127,'Patterson','James','2017-12-05 00:00:00','2017-12-05 00:00:00'),(128,'Austen','Jane','2017-12-05 00:00:00','2017-12-05 00:00:00'),(129,'Salinger','JD.','2017-12-05 00:00:00','2017-12-05 00:00:00'),(130,'Auel','Jean','2017-12-05 00:00:00','2017-12-05 00:00:00'),(131,'Kinney','Jeff','2017-12-05 00:00:00','2017-12-05 00:00:00'),(132,'Archer','Jeffrey','2017-12-05 00:00:00','2017-12-05 00:00:00'),(133,'Ballard','JG.','2017-12-05 00:00:00','2017-12-05 00:00:00'),(134,'Rowling','JK.','2017-12-05 00:00:00','2017-12-05 00:00:00'),(135,'Nesbø','Jo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(136,'Goethe','Johann','2017-12-05 00:00:00','2017-12-05 00:00:00'),(137,'Gray','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(138,'Grisham','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(139,'Irving','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(140,'Keats','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(141,'Milton','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(142,'Steinbeck','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(143,'Updike','John','2017-12-05 00:00:00','2017-12-05 00:00:00'),(144,'Foer','Jonathan','2017-12-05 00:00:00','2017-12-05 00:00:00'),(145,'Swift','Jonathan','2017-12-05 00:00:00','2017-12-05 00:00:00'),(146,'Borges','Jorge','2017-12-05 00:00:00','2017-12-05 00:00:00'),(147,'Saramago','José','2017-12-05 00:00:00','2017-12-05 00:00:00'),(148,'Conrad','Joseph','2017-12-05 00:00:00','2017-12-05 00:00:00'),(149,'Heller','Joseph','2017-12-05 00:00:00','2017-12-05 00:00:00'),(150,'Gaarder','Jostein','2017-12-05 00:00:00','2017-12-05 00:00:00'),(151,'Oates','Joyce','2017-12-05 00:00:00','2017-12-05 00:00:00'),(152,'R.','JR.','2017-12-05 00:00:00','2017-12-05 00:00:00'),(153,'Rulfo','Juan','2017-12-05 00:00:00','2017-12-05 00:00:00'),(154,'Verne','Jules','2017-12-05 00:00:00','2017-12-05 00:00:00'),(155,'Barnes','Julian','2017-12-05 00:00:00','2017-12-05 00:00:00'),(156,'May','Karl','2017-12-05 00:00:00','2017-12-05 00:00:00'),(157,'Ishiguro','Kazuo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(158,'Follett','Ken','2017-12-05 00:00:00','2017-12-05 00:00:00'),(159,'Hosseini','Khaled','2017-12-05 00:00:00','2017-12-05 00:00:00'),(160,'Gibran','Khalil','2017-12-05 00:00:00','2017-12-05 00:00:00'),(161,'Hamsun','Knut','2017-12-05 00:00:00','2017-12-05 00:00:00'),(162,'Vonnegut','Kurt','2017-12-05 00:00:00','2017-12-05 00:00:00'),(163,'Wilder','Laura','2017-12-05 00:00:00','2017-12-05 00:00:00'),(164,'Child','Lee','2017-12-05 00:00:00','2017-12-05 00:00:00'),(165,'Tolstoy','Leo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(166,'Carroll','Lewis','2017-12-05 00:00:00','2017-12-05 00:00:00'),(167,'Byron','Lord','2017-12-05 00:00:00','2017-12-05 00:00:00'),(168,'Hay','Louise','2017-12-05 00:00:00','2017-12-05 00:00:00'),(169,'Gladwell','Malcolm','2017-12-05 00:00:00','2017-12-05 00:00:00'),(170,'Proust','Marcel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(171,'Mitchell','Margaret','2017-12-05 00:00:00','2017-12-05 00:00:00'),(172,'Peterson','Margaret','2017-12-05 00:00:00','2017-12-05 00:00:00'),(173,'Yourcenar','Marguerite','2017-12-05 00:00:00','2017-12-05 00:00:00'),(174,'Llosa','Mario','2017-12-05 00:00:00','2017-12-05 00:00:00'),(175,'Puzo','Mario','2017-12-05 00:00:00','2017-12-05 00:00:00'),(176,'Twain','Mark','2017-12-05 00:00:00','2017-12-05 00:00:00'),(177,'Amis','Martin','2017-12-05 00:00:00','2017-12-05 00:00:00'),(178,'Higgins','Mary','2017-12-05 00:00:00','2017-12-05 00:00:00'),(179,'Shelley','Mary','2017-12-05 00:00:00','2017-12-05 00:00:00'),(180,'Sendak','Maurice','2017-12-05 00:00:00','2017-12-05 00:00:00'),(181,'Angelou','Maya','2017-12-05 00:00:00','2017-12-05 00:00:00'),(182,'Crichton','Michael','2017-12-05 00:00:00','2017-12-05 00:00:00'),(183,'De-Montaigne','Michel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(184,'Houellebecq','Michel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(185,'De-Cervantes','Miguel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(186,'Albom','Mitch','2017-12-05 00:00:00','2017-12-05 00:00:00'),(187,'Hill','Napoleon','2017-12-05 00:00:00','2017-12-05 00:00:00'),(188,'Gaiman','Neil','2017-12-05 00:00:00','2017-12-05 00:00:00'),(189,'Ferguson','Niall','2017-12-05 00:00:00','2017-12-05 00:00:00'),(190,'Sparks','Nicholas','2017-12-05 00:00:00','2017-12-05 00:00:00'),(191,'Hornby','Nick','2017-12-05 00:00:00','2017-12-05 00:00:00'),(192,'Krauss','Nicole','2017-12-05 00:00:00','2017-12-05 00:00:00'),(193,'Roberts','Nora','2017-12-05 00:00:00','2017-12-05 00:00:00'),(194,'Mailer','Norman','2017-12-05 00:00:00','2017-12-05 00:00:00'),(195,'Wilde','Oscar','2017-12-05 00:00:00','2017-12-05 00:00:00'),(196,'Ovid','Ovid','2017-12-05 00:00:00','2017-12-05 00:00:00'),(197,'Cornwell','Patricia','2017-12-05 00:00:00','2017-12-05 00:00:00'),(198,'Auster','Paul','2017-12-05 00:00:00','2017-12-05 00:00:00'),(199,'Celan','Paul','2017-12-05 00:00:00','2017-12-05 00:00:00'),(200,'Valéry','Paul','2017-12-05 00:00:00','2017-12-05 00:00:00'),(201,'Coelho','Paulo','2017-12-05 00:00:00','2017-12-05 00:00:00'),(202,'James','PD','2017-12-05 00:00:00','2017-12-05 00:00:00'),(203,'Hitchens','Peter','2017-12-05 00:00:00','2017-12-05 00:00:00'),(204,'Pullman','Philip','2017-12-05 00:00:00','2017-12-05 00:00:00'),(205,'Dukan','Pierre','2017-12-05 00:00:00','2017-12-05 00:00:00'),(206,'Ellison','Ralph','2017-12-05 00:00:00','2017-12-05 00:00:00'),(207,'Carver','Raymond','2017-12-05 00:00:00','2017-12-05 00:00:00'),(208,'Chandler','Raymond','2017-12-05 00:00:00','2017-12-05 00:00:00'),(209,'Feist','Raymond','2017-12-05 00:00:00','2017-12-05 00:00:00'),(210,'Byrne','Rhonda','2017-12-05 00:00:00','2017-12-05 00:00:00'),(211,'Bach','Richard','2017-12-05 00:00:00','2017-12-05 00:00:00'),(212,'Scarry','Richard','2017-12-05 00:00:00','2017-12-05 00:00:00'),(213,'Wright','Richard','2017-12-05 00:00:00','2017-12-05 00:00:00'),(214,'Riordan','Rick','2017-12-05 00:00:00','2017-12-05 00:00:00'),(215,'Warren','Rick','2017-12-05 00:00:00','2017-12-05 00:00:00'),(216,'Stine','RL','2017-12-05 00:00:00','2017-12-05 00:00:00'),(217,'Dahl','Roald','2017-12-05 00:00:00','2017-12-05 00:00:00'),(218,'Jordan','Robert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(219,'Ludlum','Robert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(220,'Munsch','Robert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(221,'Stevenson','Robert','2017-12-05 00:00:00','2017-12-05 00:00:00'),(222,'Cook','Robin','2017-12-05 00:00:00','2017-12-05 00:00:00'),(223,'Hargreaves','Roger','2017-12-05 00:00:00','2017-12-05 00:00:00'),(224,'Kipling','Rudyard','2017-12-05 00:00:00','2017-12-05 00:00:00'),(225,'Rumi','Rumi','2017-12-05 00:00:00','2017-12-05 00:00:00'),(226,'Rushdie','Salman','2017-12-05 00:00:00','2017-12-05 00:00:00'),(227,'Beckett','Samuel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(228,'Johnson','Samuel','2017-12-05 00:00:00','2017-12-05 00:00:00'),(229,'Hinton','SE','2017-12-05 00:00:00','2017-12-05 00:00:00'),(230,'Heaney','Seamus','2017-12-05 00:00:00','2017-12-05 00:00:00'),(231,'Tan','Shaun','2017-12-05 00:00:00','2017-12-05 00:00:00'),(232,'Sheldon','Sidney','2017-12-05 00:00:00','2017-12-05 00:00:00'),(233,'de-Beauvoir','Simone','2017-12-05 00:00:00','2017-12-05 00:00:00'),(234,'Sophocles','Sophocles','2017-12-05 00:00:00','2017-12-05 00:00:00'),(235,'Stendhal','Stendhal','2017-12-05 00:00:00','2017-12-05 00:00:00'),(236,'Hawking','Stephen','2017-12-05 00:00:00','2017-12-05 00:00:00'),(237,'King','Stephen','2017-12-05 00:00:00','2017-12-05 00:00:00'),(238,'Meyer','Stephenie','2017-12-05 00:00:00','2017-12-05 00:00:00'),(239,'Larsson','Stieg','2017-12-05 00:00:00','2017-12-05 00:00:00'),(240,'Collins','Suzanne','2017-12-05 00:00:00','2017-12-05 00:00:00'),(241,'Plath','Sylvia','2017-12-05 00:00:00','2017-12-05 00:00:00'),(242,'Hughes','Ted','2017-12-05 00:00:00','2017-12-05 00:00:00'),(243,'Williams','Tennessee','2017-12-05 00:00:00','2017-12-05 00:00:00'),(244,'Brooks','Terry','2017-12-05 00:00:00','2017-12-05 00:00:00'),(245,'Pratchett','Terry','2017-12-05 00:00:00','2017-12-05 00:00:00'),(246,'Mann','Thomas','2017-12-05 00:00:00','2017-12-05 00:00:00'),(247,'Pynchon','Thomas','2017-12-05 00:00:00','2017-12-05 00:00:00'),(248,'Clancy','Tom','2017-12-05 00:00:00','2017-12-05 00:00:00'),(249,'Robbins','Tom','2017-12-05 00:00:00','2017-12-05 00:00:00'),(250,'Morrison','Toni','2017-12-05 00:00:00','2017-12-05 00:00:00'),(251,'Capote','Truman','2017-12-05 00:00:00','2017-12-05 00:00:00'),(252,'Eco','Umberto','2017-12-05 00:00:00','2017-12-05 00:00:00'),(253,'Virgil','V','2017-12-05 00:00:00','2017-12-05 00:00:00'),(254,'Frankl','Viktor','2017-12-05 00:00:00','2017-12-05 00:00:00'),(255,'Woolf','Virginia','2017-12-05 00:00:00','2017-12-05 00:00:00'),(256,'Nabokov','Vladimir','2017-12-05 00:00:00','2017-12-05 00:00:00'),(257,'Whitman','Walt','2017-12-05 00:00:00','2017-12-05 00:00:00'),(258,'Smith','Wilbur','2017-12-05 00:00:00','2017-12-05 00:00:00'),(259,'Blake','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(260,'Burroughs','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(261,'Faulkner','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(262,'Gibson','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(263,'Golding','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(264,'Shakespeare','William','2017-12-05 00:00:00','2017-12-05 00:00:00'),(265,'Hurston','Zora','2017-12-05 00:00:00','2017-12-05 00:00:00');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author_book`
--

DROP TABLE IF EXISTS `author_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `authortype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author_book`
--

LOCK TABLES `author_book` WRITE;
/*!40000 ALTER TABLE `author_book` DISABLE KEYS */;
INSERT INTO `author_book` VALUES (1,1,1,1),(2,2,1,1),(3,3,3,1),(4,4,3,2),(5,5,3,1);
/*!40000 ALTER TABLE `author_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authortype`
--

DROP TABLE IF EXISTS `authortype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authortype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authortypedesc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authortype`
--

LOCK TABLES `authortype` WRITE;
/*!40000 ALTER TABLE `authortype` DISABLE KEYS */;
INSERT INTO `authortype` VALUES (1,'Main Author','2017-12-05 00:00:00','2017-12-05 00:00:00'),(2,'Editor','2017-12-05 00:00:00','2017-12-05 00:00:00');
/*!40000 ALTER TABLE `authortype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `release_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES (1,'Hacking Healthcare - A guide to Meaningful Use','2009-09-08 00:00:00','2017-12-05 00:00:00','2017-12-05 00:00:00'),(2,'Of Mice and Men','1937-09-08 00:00:00','2017-12-05 00:00:00','2017-12-05 00:00:00'),(3,'This Side of Paradise','1920-12-05 00:00:00','2017-12-05 00:00:00','2017-12-05 00:00:00');
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `bookextended`
--

CREATE TABLE `bookextended` (
  `book_id` int(11) NOT NULL,
  `ISBN` varchar(255) NOT NULL,
  `local_isle` varchar(255) NOT NULL,
  `local_shelf` int(255) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bookextended`
--
LOCK TABLES `bookextended` WRITE;
INSERT INTO `bookextended` (`book_id`, `ISBN`, `local_isle`, `local_shelf`) VALUES
(1, '12345', 'L', 112),
(2, '66666', 'R', 32);
UNLOCK TABLES;

--
-- Table structure for table `booleantest`
--
DROP TABLE IF EXISTS `test_boolean`;

CREATE TABLE `test_boolean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `is_something` varchar(255) NOT NULL,
  `has_something` varchar(255) NOT NULL,
  `is_something2` tinyint(4) DEFAULT NULL,
  `has_something2` tinyint(4) DEFAULT NULL,
  `has_something3` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booleantest`
--

LOCK TABLES `test_boolean` WRITE;
INSERT INTO `test_boolean` (`id`, `label`, `is_something`, `has_something`, `is_something2`, `has_something2`, `has_something3`) VALUES
(1, 'Test checkbox', 'yes', '1', 1, 0, 1),
(2, 'Test checkbox', '2', 'no', 0, 2, 0);
UNLOCK TABLES;

--
-- Table structure for table `test_created_only`
--
DROP TABLE IF EXISTS `test_created_only`;

CREATE TABLE `test_created_only` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `test_created_only`
--

LOCK TABLES `test_created_only` WRITE;
INSERT INTO `test_created_only` (`id`, `name`, `created_at`) VALUES
(1, 'Test 1', '2018-02-22 00:00:00'),
(2, 'Test 2', '2018-02-21 00:00:00');
UNLOCK TABLES;




--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_text` varchar(1000) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,'That is a thoughtful post',1,'2017-12-06 00:00:00','2017-12-06 00:00:00'),(2,'The first commentor is stupid',1,'2017-12-07 00:00:00','2017-12-07 00:00:00'),(3,'More comments',1,'2017-12-06 00:00:00','2017-12-12 00:00:00'),(4,'Still further',1,'2017-12-06 00:00:00','2017-12-06 00:00:00'),(5,'more comment goodness',2,'2017-12-06 00:00:00','2017-12-06 00:00:00'),(6,'moar',2,'2017-12-06 00:00:00','2017-12-06 00:00:00');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation`
--

DROP TABLE IF EXISTS `donation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `nonprofitcorp_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation`
--

LOCK TABLES `donation` WRITE;
/*!40000 ALTER TABLE `donation` DISABLE KEYS */;
INSERT INTO `donation` VALUES (1,11,741152597,'2018-01-10 00:12:00','2018-01-11 00:01:00',NULL);
/*!40000 ALTER TABLE `donation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foreignkeytestgizmo`
--

DROP TABLE IF EXISTS `foreignkeytestgizmo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `foreignkeytestgizmo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gizmoname` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foreignkeytestgizmo`
--

LOCK TABLES `foreignkeytestgizmo` WRITE;
/*!40000 ALTER TABLE `foreignkeytestgizmo` DISABLE KEYS */;
INSERT INTO `foreignkeytestgizmo` VALUES (1,'Red Gizmo','2017-12-01 00:00:00','2017-12-08 00:00:00','2017-12-08 00:00:00'),(2,'Blue Gizmo','2017-12-01 00:00:00','2017-12-08 00:00:00','2017-12-08 00:00:00'),(3,'Yellow Gizmo','2017-12-01 00:00:00','2017-12-08 00:00:00','2017-12-08 00:00:00'),(4,'Green Gizmo','2017-12-01 00:00:00','2017-12-08 00:00:00','2017-12-08 00:00:00');
/*!40000 ALTER TABLE `foreignkeytestgizmo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foreignkeytestthingy`
--

DROP TABLE IF EXISTS `foreignkeytestthingy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `foreignkeytestthingy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thingyname` varchar(100) NOT NULL,
  `gizmopickupaskey` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gizmopickupaskey` (`gizmopickupaskey`),
  CONSTRAINT `forgizmo` FOREIGN KEY (`gizmopickupaskey`) REFERENCES `foreignkeytestgizmo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foreignkeytestthingy`
--

LOCK TABLES `foreignkeytestthingy` WRITE;
/*!40000 ALTER TABLE `foreignkeytestthingy` DISABLE KEYS */;
INSERT INTO `foreignkeytestthingy` VALUES (1,'Has one',1,'2017-12-13 00:00:00','2017-12-12 00:00:00','2017-12-08 00:00:00'),(2,'Has two',2,'2017-12-13 00:00:00','2017-12-12 00:00:00','2017-12-08 00:00:00'),(3,'Has three',3,'2017-12-13 00:00:00','2017-12-12 00:00:00','2017-12-08 00:00:00'),(4,'Has four',4,'2017-12-13 00:00:00','2017-12-12 00:00:00','2017-12-08 00:00:00'),(5,'Also two',2,'2017-12-13 00:00:00','2017-12-12 00:00:00','2017-12-08 00:00:00');
/*!40000 ALTER TABLE `foreignkeytestthingy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funnything`
--

DROP TABLE IF EXISTS `funnything`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funnything` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thisint` int(11) DEFAULT NULL,
  `thisfloat` float DEFAULT NULL,
  `thisdecimal` decimal(5,5) DEFAULT NULL,
  `thisvarchar` varchar(100) DEFAULT NULL,
  `thisdate` date DEFAULT NULL,
  `thisdatetime` datetime DEFAULT NULL,
  `thistimestamp` timestamp NULL DEFAULT NULL,
  `thischar` char(1) NOT NULL,
  `thistext` text NOT NULL,
  `thisblob` blob DEFAULT NULL,
  `thistinyint` tinyint(11) NOT NULL,
  `thistinytext` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funnything`
--

LOCK TABLES `funnything` WRITE;
/*!40000 ALTER TABLE `funnything` DISABLE KEYS */;
INSERT INTO `funnything` VALUES (1,1,1.1,0.00000,'vc','2017-12-05','2017-12-19 00:00:00','2017-12-21 00:00:00','c','t',NULL,1,'tt');
/*!40000 ALTER TABLE `funnything` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'blog title','This is blog content','2017-12-05 00:00:00','2017-12-13 00:00:00'),(2,'This is also a blog','This is some more ocntent','2017-12-05 00:00:00','2017-12-14 00:00:00');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sibling`
--

DROP TABLE IF EXISTS `sibling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sibling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siblingname` varchar(255) NOT NULL,
  `step_sibling_id` int(11) DEFAULT NULL,
  `sibling_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sibling`
--

LOCK TABLES `sibling` WRITE;
/*!40000 ALTER TABLE `sibling` DISABLE KEYS */;
INSERT INTO `sibling` VALUES (1,'Maria',1,2,'2017-12-05 00:00:00','2017-12-05 00:00:00'),(2,'Joan',1,4,'2017-12-05 00:00:00','2017-12-05 00:00:00'),(4,'Tim',NULL,2,'2017-12-05 00:00:00','2017-12-05 00:00:00');
/*!40000 ALTER TABLE `sibling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `votenum` varchar(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
INSERT INTO `vote` VALUES (1,1,'1','2017-12-01 00:00:00','2017-12-01 00:00:00'),(2,1,'-1','2017-12-01 00:00:00','2017-12-01 00:00:00'),(3,1,'1','2017-12-01 00:00:00','2017-12-01 00:00:00'),(4,2,'1','2017-12-06 00:00:00','2017-12-01 00:00:00'),(5,2,'1','2017-12-01 00:00:00','2017-12-08 00:00:00');
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-01 15:14:50
