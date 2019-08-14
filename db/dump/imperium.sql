-- MariaDB dump 10.17  Distrib 10.4.7-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: imperium
-- ------------------------------------------------------
-- Server version	10.4.7-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (20190627173303,'Users','2019-08-14 08:17:08','2019-08-14 08:17:08',0);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Harmon','Effertz','carissa.mclaughlin@treutel.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(3,'Alize','Simonis','dsimonis@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(4,'Nona','Corkery','lubowitz.luna@white.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(5,'Demarco','Jenkins','antonietta.hansen@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(6,'Edison','Veum','xschowalter@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(7,'Laurianne','Connelly','myriam.corkery@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(8,'Marietta','Torphy','lillie.wisozk@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(9,'Meghan','Glover','bradford97@hirthe.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(10,'Bennie','Wehner','parisian.foster@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(11,'Marco','Aufderhar','ramona.koelpin@murray.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(12,'Keshawn','Durgan','rubye.stehr@king.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(13,'Aleen','Bernier','sipes.emelie@wolf.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(14,'Georgiana','Ernser','misael05@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(15,'Willard','Bernhard','ward13@waters.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(16,'Hazel','Kiehn','wcummings@rippin.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(17,'Bella','Wisoky','sonia93@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(18,'Mauricio','Klein','jules.koepp@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(19,'Alysson','Krajcik','orrin26@wunsch.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(21,'Sandy','Lowe','harley68@kessler.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(22,'Sylvan','Bode','madonna13@marks.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(23,'Barbara','Gulgowski','russel.sonya@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(24,'Casimer','Lesch','lebsack.margret@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(25,'Eveline','Koelpin','swolf@schroeder.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(26,'Giles','Marvin','wilhelmine99@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(27,'Tatyana','Thiel','eliezer06@kuhlman.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(28,'Leonel','Rosenbaum','ldickinson@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(29,'Oral','Bins','jleffler@prosacco.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(30,'Name','Casper','leannon.rosalinda@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(31,'Pattie','Satterfield','dcorwin@borer.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(32,'Kolby','Herman','russel.leann@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(33,'Nyah','Thiel','stella35@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(34,'Will','Goodwin','ubailey@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(35,'Kavon','Windler','green.adan@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(36,'Jedediah','Smith','howe.georgianna@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(37,'Herbert','Predovic','sven.schaefer@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(38,'Gina','Sawayn','bergnaum.edgardo@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(39,'Asa','Braun','fmcglynn@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(40,'Laurianne','Brown','carlos.west@anderson.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(41,'Filiberto','Rempel','dharvey@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(42,'Elizabeth','Kessler','jacky.dickinson@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(43,'Carlie','Mueller','icronin@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(44,'Ransom','Halvorson','ayden.konopelski@kris.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(45,'Louie','Murray','fmoen@tillman.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(46,'Daphne','Hoeger','frami.toni@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(47,'Dora','Block','kitty.swaniawski@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(48,'Cassidy','Wisozk','londricka@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(49,'Brooks','Runolfsdottir','hyundt@crona.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(50,'Esther','Langworth','alta63@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(51,'Hollis','Denesik','utoy@baumbach.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(52,'Fausto','McLaughlin','hoppe.cortney@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(53,'Jasper','Gerhold','runte.hilda@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(54,'Annabelle','Legros','velva85@halvorson.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(55,'Jean','White','orodriguez@bailey.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(56,'Cynthia','Prosacco','jessika39@hartmann.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(57,'Xander','Pagac','rosalyn59@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(58,'Johnpaul','Robel','xpaucek@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(59,'Randal','Cummings','elody.sipes@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(60,'Jerod','Rippin','woodrow57@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(61,'Norma','Lowe','rutherford.kurt@schultz.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(62,'Jo','Ortiz','christiansen.felipe@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(63,'Vergie','Tremblay','deffertz@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(64,'Pete','Walter','alvah.batz@conroy.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(65,'Carroll','Rogahn','preichel@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(66,'Elissa','Dibbert','stokes.durward@buckridge.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(67,'Abel','Heathcote','diamond32@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(68,'Charlene','Bruen','kuhic.gaetano@hermiston.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(69,'Ava','Sawayn','jupton@rohan.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(70,'Robbie','Stamm','alfredo33@lockman.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(71,'Therese','Collins','carlotta79@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(72,'Keagan','Terry','fherman@rowe.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(73,'Cory','Luettgen','fwhite@cormier.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(74,'Precious','Fay','dare.kellen@reichert.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(75,'Gail','Volkman','block.rosalia@ritchie.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(76,'Dayne','Huels','voconnell@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(77,'Tanner','Ruecker','annabelle.graham@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(78,'Torey','D\'Amore','zetta.ryan@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(79,'Franco','Mante','crystel30@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(80,'Arlo','Purdy','roger.hilpert@mcdermott.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(81,'Newell','Parisian','omoen@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(82,'Yasmin','Langworth','viva78@okeefe.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(83,'Margaret','Kilback','weissnat.kennedi@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(84,'Bettye','Senger','beryl55@crist.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(85,'Vincenzo','Herman','kuhic.phyllis@cole.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(86,'Rita','Stark','nichole.reilly@wisozk.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(87,'Nettie','Kohler','griffin51@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(88,'Shemar','Douglas','aylin.buckridge@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(89,'Diana','Raynor','jovani.brekke@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(90,'Natalie','Bauch','mante.fay@brekke.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(91,'Gust','Murray','adolf16@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(92,'Serenity','Schowalter','rhianna.jakubowski@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(93,'Rebeca','Bahringer','wehner.omer@dietrich.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(94,'Lew','Will','effertz.karlie@kessler.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(95,'Kasandra','Flatley','santiago.reynolds@wiegand.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(96,'Jo','Gutkowski','runolfsdottir.trever@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(97,'Grace','Cole','ehowell@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(98,'Kelsi','Feest','xcummerata@luettgen.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(99,'Eugenia','Marvin','christa03@abbott.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(100,'Aracely','Mayer','trey.harris@mcdermott.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-14 10:17:09
