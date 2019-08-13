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
INSERT INTO `migrations` VALUES (20190627173303,'Users','2019-08-13 15:23:07','2019-08-13 15:23:07',0);
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
INSERT INTO `users` VALUES (2,'Shayna','Kreiger','carey.roob@ondricka.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(3,'Talon','Fadel','kub.macy@watsica.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(4,'Mitchel','Walsh','eldred71@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(5,'Lola','Marks','briana78@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(6,'Lela','Brakus','lila60@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(7,'Nettie','Bayer','howell.norma@vandervort.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(8,'Noe','Kunze','bwiza@cronin.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(9,'Phoebe','Huels','ypacocha@kuphal.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(10,'Renee','Will','leuschke.edyth@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(11,'Francisca','Bogan','valentina.medhurst@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(12,'Hunter','Jakubowski','gerardo94@nolan.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(13,'Leonor','Dooley','walsh.jaydon@goldner.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(14,'Jedidiah','Hermiston','finn.schmitt@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(15,'Trevion','Rosenbaum','owillms@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(16,'Natalie','Morar','padberg.greyson@toy.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(17,'Imelda','Borer','larkin.anne@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(18,'Linda','Turner','yrohan@hoppe.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(19,'Lupe','Ullrich','dariana78@marks.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(21,'Rick','Kilback','wiley08@wintheiser.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(22,'Mona','Heaney','lucienne75@west.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(23,'Alexanne','Kuhic','lucious.renner@bruen.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(24,'Lucinda','Connelly','axel59@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(25,'Adolph','Fritsch','bsipes@mccullough.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(26,'Ansel','Bernhard','trevion.rosenbaum@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(27,'Price','Becker','marianna.schoen@damore.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(28,'Buford','Witting','delilah.dietrich@powlowski.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(29,'Jamar','Fisher','trystan77@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(30,'Garfield','White','nicolas.monique@schoen.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(31,'Aaliyah','Kessler','hschmitt@waters.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(32,'Darien','White','loyal.roob@gorczany.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(33,'Florence','Jacobi','boyle.immanuel@zemlak.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(34,'Marjorie','Larkin','kendall63@thompson.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(35,'Marcelina','Bayer','bmurphy@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(36,'Adaline','Durgan','wunsch.dorthy@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(37,'Vernice','Bartell','dane46@fadel.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(38,'Hadley','Cummerata','brekke.cordelia@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(39,'Shakira','Brekke','kayden.willms@gulgowski.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(40,'Blanche','Bechtelar','layla15@will.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(41,'Theodore','Heidenreich','jewell.turcotte@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(42,'Vergie','Streich','sipes.gia@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(43,'Janelle','Braun','leo69@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(44,'Sabina','Bernhard','etromp@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(45,'Erich','Eichmann','greenfelder.marcelo@altenwerth.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(46,'Watson','Von','wweimann@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(47,'Mallory','Schuster','lucy96@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(48,'Glenna','Spinka','marjory32@howe.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(49,'Aisha','Lehner','jerrold.ullrich@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(50,'Jarvis','Streich','barrett77@schinner.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(51,'Isidro','Mueller','pschumm@hauck.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(52,'Ervin','Sanford','mbeer@friesen.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(53,'Ashly','Hauck','marielle49@quigley.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(54,'Verda','Ledner','margaretta85@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(55,'Rylan','Gorczany','deondre.wiza@lind.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(56,'Imelda','Klein','hblock@mraz.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(57,'Muriel','Gislason','xkiehn@zboncak.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(58,'Darion','Bahringer','vida.mitchell@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(59,'Russ','Erdman','aletha.williamson@adams.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(60,'Guadalupe','Stracke','rfeeney@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(61,'Roxanne','Shanahan','cristal68@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(62,'Jon','Bogisich','udibbert@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(63,'Moshe','Bogisich','matilda.wisoky@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(64,'Agnes','Luettgen','zhintz@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(65,'Melvina','Hoeger','vaughn.cummerata@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(66,'Meggie','Rosenbaum','dillan91@champlin.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(67,'Bennett','Kihn','rogahn.pamela@hahn.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(68,'Lemuel','Raynor','block.iva@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(69,'Cordell','Schulist','legros.alexandrea@crona.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(70,'Cathy','Gleason','tillman.nigel@fahey.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(71,'Corene','Dach','roma06@boehm.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(72,'Edgardo','Huels','wuckert.shanel@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(73,'Lora','King','jamaal.vandervort@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(74,'Megane','Lesch','ykrajcik@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(75,'Connor','Shanahan','crona.aletha@reynolds.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(76,'Brady','Kihn','ccummings@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(77,'Vernie','Goodwin','dominique18@baumbach.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(78,'Dannie','Hahn','flowe@fisher.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(79,'Kaitlyn','Murphy','herbert28@rolfson.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(80,'Eugene','Feeney','fbraun@spinka.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(81,'Declan','Tillman','morissette.cleveland@vandervort.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(82,'Zita','Marks','boehm.stevie@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(83,'Keara','DuBuque','jason25@rowe.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(84,'Valentine','Sauer','addie99@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(85,'Abdul','Rogahn','keanu13@boehm.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(86,'Morton','Lesch','gutkowski.syble@green.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(87,'Jermaine','Cummings','nabbott@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(88,'Ashtyn','Gaylord','bode.benton@tromp.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(89,'Jolie','Bins','bartell.reynold@raynor.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(90,'Valentina','Ryan','erdman.vince@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(91,'Cecil','Kuvalis','ryley.howell@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(92,'Sunny','Fadel','kkutch@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(93,'Ruben','Nikolaus','zlang@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(94,'Betsy','Russel','zemlak.marcia@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(95,'Mario','Bruen','prohaska.madelynn@johns.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(96,'Antonia','Casper','cronin.kaylie@okeefe.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(97,'Bruce','Aufderhar','sven28@kub.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(98,'Melvina','Bosco','fahey.anastasia@runolfsson.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(99,'Adah','Collier','schroeder.gideon@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(100,'Erwin','Ward','alvera21@ferry.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a');
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

-- Dump completed on 2019-08-13 17:23:08
