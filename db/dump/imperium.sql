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
INSERT INTO `migrations` VALUES (20190627173303,'Users','2019-08-13 14:14:32','2019-08-13 14:14:32',0);
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
INSERT INTO `users` VALUES (2,'Cayla','Dooley','jacobs.price@mueller.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(3,'Pinkie','Erdman','deon19@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(4,'Kaley','Pollich','zdaugherty@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(5,'Elena','Flatley','stracke.kara@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(6,'Kenton','Cassin','brown91@kovacek.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(7,'Anita','Reynolds','ccollier@medhurst.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(8,'Roberta','Kihn','cschaden@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(9,'Cortney','Botsford','trantow.marco@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(10,'Nella','Blanda','ward.gaston@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(11,'Javonte','Wolf','rutherford.alden@johnson.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(12,'Karianne','Kuphal','tania.stamm@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(13,'Alena','Romaguera','reinger.doyle@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(14,'Logan','Vandervort','brannon.gerlach@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(15,'Alivia','Pacocha','ecummings@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(16,'Julius','Wintheiser','pierce45@feil.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(17,'Makenzie','Hickle','andreanne79@rice.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(18,'Dena','Pouros','isobel63@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(19,'Otto','Johns','lelia.hayes@nolan.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(21,'Myriam','Wuckert','olen72@hauck.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(22,'Doyle','Rosenbaum','merritt94@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(23,'Charlene','Gulgowski','ryan.newell@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(24,'Jaida','Upton','bruen.nicklaus@paucek.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(25,'Lionel','Bernhard','carlee84@cremin.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(26,'Shyanne','Murray','considine.candace@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(27,'Bernard','Anderson','gayle44@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(28,'Virgil','Goldner','olueilwitz@runte.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(29,'Mya','Rohan','zcarter@powlowski.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(30,'Tatyana','Greenfelder','vdeckow@fisher.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(31,'Jerald','Kling','cummings.alejandrin@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(32,'Elda','Schinner','jakubowski.gordon@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(33,'Maria','Shields','ugleichner@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(34,'August','Rutherford','cecil.jacobs@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(35,'Kasandra','Brekke','bzulauf@ziemann.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(36,'Rickie','Orn','zjacobs@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(37,'Audreanne','Walsh','quigley.clark@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(38,'Susanna','Hettinger','schowalter.savanna@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(39,'Maye','Nikolaus','erdman.saige@morar.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(40,'Lori','Moore','elisabeth09@mccullough.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(41,'Bulah','Durgan','joel.beahan@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(42,'Misty','Hauck','bahringer.madie@kshlerin.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(43,'Nedra','McClure','mose80@hilpert.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(44,'Carleton','Walsh','qschneider@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(45,'Candido','Johnson','dicki.retha@bechtelar.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(46,'Mohammed','Reinger','stacey.hamill@kuhn.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(47,'Tamara','Kozey','zschamberger@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(48,'Selena','Beatty','hauck.shyanne@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(49,'Lonny','Abernathy','eve92@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(50,'Nettie','Kutch','dickinson.donny@hessel.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(51,'Gilberto','Herzog','wnienow@bosco.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(52,'Anjali','Mayer','ignacio80@brakus.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(53,'Rosamond','Berge','murazik.melisa@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(54,'Taurean','Cummings','mohr.marjolaine@torp.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(55,'Brooklyn','Sanford','angie.pollich@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(56,'Laurence','Powlowski','devante37@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(57,'Asia','Pfannerstill','ziemann.selena@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(58,'Julius','Beer','dubuque.haskell@borer.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(59,'Josefina','Nitzsche','dedric57@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(60,'Rashad','Beier','qkihn@ritchie.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(61,'Cleo','Orn','burley.gleichner@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(62,'Edmund','Koss','zechariah.pagac@boyer.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(63,'Christine','Gusikowski','abernathy.cathy@rosenbaum.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(64,'Kian','Eichmann','schimmel.haylie@treutel.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(65,'Philip','Bosco','arely00@block.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(66,'Mateo','Mertz','sheldon.homenick@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(67,'Dasia','Runte','batz.jeffery@hamill.net','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(68,'Ismael','Boehm','halvorson.shanny@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(69,'Alycia','Nader','itzel04@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(70,'Petra','Bernier','talon.zboncak@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(71,'Jody','Pacocha','rigoberto78@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(72,'Jose','Upton','candace.rau@price.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(73,'Luna','Gusikowski','jacobson.micaela@crooks.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(74,'Kira','Fritsch','chaim.pollich@kulas.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(75,'Celestine','Paucek','kub.carlie@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(76,'Yolanda','Brekke','uwintheiser@larkin.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(77,'Khalil','Wehner','rosalinda.ondricka@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(78,'Alba','Doyle','powlowski.barney@towne.info','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(79,'Art','Wyman','augustus.homenick@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(80,'Mercedes','Haley','efeest@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(81,'Melisa','Heller','xwehner@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(82,'Skyla','McLaughlin','demetris.franecki@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(83,'Judson','Watsica','juliana.lindgren@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(84,'Carrie','Stark','leonie.weissnat@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(85,'Cleora','Aufderhar','ihomenick@gmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(86,'Liliane','Hagenes','ckonopelski@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(87,'Kendall','Paucek','mcclure.johnathan@harber.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(88,'Dell','Fay','amari27@yahoo.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(89,'Adelia','Beer','lamont.weimann@dibbert.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(90,'Kristopher','Greenholt','deanna85@bogisich.org','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(91,'Athena','Koelpin','cordell.klocko@kuhn.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(92,'Nyasia','Bayer','eula16@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(93,'Manley','Ryan','minnie25@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(94,'Joaquin','Anderson','troy.parisian@lowe.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(95,'Vincenza','Feest','isteuber@dicki.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(96,'Wyman','Considine','ahoppe@wunsch.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(97,'Emmie','Dicki','simonis.rose@little.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(98,'Laney','Cassin','kleffler@gleason.biz','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(99,'Palma','Runolfsson','mills.violette@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a'),(100,'Isaias','Wisozk','osvaldo.reinger@hotmail.com','fc95e7ccb2d517047966363ef64d412444748fb406c7420ba35571a0ebd50a6a');
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

-- Dump completed on 2019-08-13 16:14:34
