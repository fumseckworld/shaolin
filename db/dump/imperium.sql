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
INSERT INTO `migrations` VALUES (20190627173303,'Users','2019-08-13 18:46:52','2019-08-13 18:46:52',0);
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
INSERT INTO `users` VALUES (2,'Dave','Schuppe','tressie57@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(3,'Verda','Schamberger','marcel.schamberger@daniel.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(4,'Gaylord','Denesik','boyer.dillon@considine.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(5,'Natasha','Jaskolski','davonte.olson@fisher.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(6,'Conor','Mante','jules21@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(7,'Van','Boyle','oscar.stiedemann@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(8,'Verona','Deckow','friesen.mckenzie@marquardt.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(9,'Roberto','Steuber','danielle48@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(10,'Celestino','Waters','lori43@spinka.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(11,'Gerda','Hartmann','nernser@smith.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(12,'Aimee','Hahn','igibson@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(13,'Aurelio','Satterfield','odach@oconner.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(14,'Kristy','O\'Reilly','heber52@hodkiewicz.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(15,'Althea','Gerhold','esauer@rippin.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(16,'Tessie','Kris','hferry@parker.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(17,'Reggie','Bernhard','kuhic.ezekiel@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(18,'Desmond','Mraz','jessika49@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(19,'Yvette','Quitzon','shania.treutel@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(21,'Agustina','Schulist','jack27@jaskolski.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(22,'Talia','Bartell','veronica73@koch.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(23,'Wallace','Murphy','lavinia.oconner@monahan.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(24,'Janae','Legros','penelope52@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(25,'Vallie','Wilkinson','rice.thora@mcclure.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(26,'Ollie','Jones','kuvalis.pearline@sanford.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(27,'Maxie','Anderson','wilmer.hagenes@ondricka.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(28,'Lessie','Wintheiser','feeney.lexie@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(29,'Elvera','Schmidt','pheathcote@doyle.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(30,'Myrl','Kuphal','hdietrich@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(31,'Kaleigh','Walter','rosa.hilpert@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(32,'Miguel','Ebert','wkreiger@crist.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(33,'Birdie','Monahan','keaton.gusikowski@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(34,'Kaylin','Harris','hkris@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(35,'Rick','Jerde','giovanny.oconner@hagenes.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(36,'Jarrett','Okuneva','jeremie.altenwerth@kertzmann.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(37,'Ida','Tromp','kaitlyn.west@wiza.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(38,'Herbert','Medhurst','osinski.kristofer@schiller.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(39,'Rusty','Hayes','qparisian@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(40,'Carmine','Rolfson','eriberto43@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(41,'Miracle','DuBuque','moore.elmo@frami.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(42,'Cesar','Veum','brain.homenick@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(43,'Melyssa','Rodriguez','huels.ross@walsh.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(44,'Emerson','Yost','tyrell.nolan@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(45,'Dawn','Romaguera','ariel.cronin@brekke.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(46,'Bradford','Luettgen','hermann.camryn@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(47,'Matilde','Beahan','violet.howell@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(48,'Helmer','Ferry','hreichert@haley.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(49,'Hudson','Hudson','bonita.oreilly@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(50,'Presley','Gusikowski','fritz.lebsack@paucek.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(51,'Gloria','Corwin','gerald.wyman@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(52,'Antwan','Schimmel','yundt.delfina@schamberger.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(53,'Baron','McKenzie','lourdes.hirthe@gleichner.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(54,'Justina','Kreiger','citlalli62@gutkowski.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(55,'Richmond','Weber','heathcote.gilda@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(56,'Niko','Wisoky','xbarrows@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(57,'Erick','Kuhic','waters.daphnee@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(58,'Tobin','Weimann','aglae.waelchi@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(59,'Camila','Lowe','winnifred43@langosh.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(60,'Alexandrea','Jacobs','sasha.wiegand@larson.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(61,'Jovan','Hickle','obauch@williamson.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(62,'Hermina','Monahan','harris.alex@shanahan.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(63,'Baron','Volkman','timmy.schiller@cruickshank.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(64,'Marjorie','Feest','virgie.kertzmann@boehm.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(65,'Erika','Dickens','sherwood.heidenreich@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(66,'Theresa','Maggio','lauryn.anderson@kiehn.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(67,'Adrien','O\'Hara','ucole@little.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(68,'Kathryn','Rodriguez','alejandra.bartoletti@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(69,'Serenity','Kub','bettye51@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(70,'Emelie','Rosenbaum','rmcdermott@hand.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(71,'Narciso','Gaylord','orie.dietrich@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(72,'Johann','Morar','mraz.alexa@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(73,'Tristin','Weimann','aida.ritchie@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(74,'Quentin','Nienow','gerardo.kilback@wisozk.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(75,'Laurianne','Kozey','ilockman@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(76,'Prince','Rippin','quinn.kuhn@bayer.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(77,'Merritt','Auer','troy.walsh@kohler.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(78,'Ellie','Weber','ricardo.altenwerth@mitchell.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(79,'Scotty','Reichel','jess22@douglas.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(80,'Wilhelm','Lakin','padberg.onie@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(81,'Edyth','Kunde','mallory41@stark.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(82,'Kamren','O\'Keefe','evans.macejkovic@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(83,'Alfonzo','Braun','franecki.delphia@barrows.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(84,'Tabitha','Langworth','witting.esta@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(85,'Kendra','Mosciski','gunner67@lind.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(86,'Rose','Hackett','katrina72@rolfson.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(87,'Bryon','Terry','shanahan.cordell@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(88,'Arvel','Rosenbaum','claire.muller@crooks.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(89,'Marcelina','Dietrich','rice.lupe@armstrong.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(90,'Elmo','Collier','vgreenholt@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(91,'Ryleigh','Ratke','mhermann@robel.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(92,'Kyleigh','Torp','zbrekke@hills.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(93,'Alberta','Padberg','bradford.blick@blanda.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(94,'Robbie','Lang','ronny01@bartoletti.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(95,'Verlie','Pfannerstill','aidan.oconnell@stamm.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(96,'Caesar','Rohan','fbruen@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(97,'Mary','Murazik','eli72@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(98,'Melyna','Abshire','griffin49@hauck.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(99,'Bette','Zulauf','bechtelar.justine@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(100,'Javonte','Christiansen','avery42@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a');
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

-- Dump completed on 2019-08-13 20:46:53
