-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: country
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1-log

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
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('administrador','8',1453657131),('consejo','7',1453657131),('intendente','9',1453657131),('sinRol','11',1453668090);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('accederUser',2,'Acceder: usuarios',NULL,NULL,1453657131,1453657131),('accederUserRol',2,'Acceder: rol de usuarios',NULL,NULL,1453657131,1453657131),('administrador',1,'Administrador',NULL,NULL,1453657130,1453657130),('arquitecto',1,'Arquitecto',NULL,NULL,1453657131,1453657131),('consejo',1,'Consejo',NULL,NULL,1453657130,1453657130),('guardia',1,'Guardia',NULL,NULL,1453657131,1453657131),('intendente',1,'Intendente',NULL,NULL,1453657130,1453657130),('opIntendencia',1,'Operador de Intendencia',NULL,NULL,1453657131,1453657131),('portero',1,'Portero',NULL,NULL,1453657131,1453657131),('propietario',1,'Propietario',NULL,NULL,1453657131,1453657131),('sinRol',1,'Sin rol asignado',NULL,NULL,1453662094,1453662094);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('administrador','accederUser'),('consejo','accederUser'),('intendente','accederUser'),('administrador','accederUserRol'),('consejo','accederUserRol'),('intendente','accederUserRol');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entradas`
--

DROP TABLE IF EXISTS `entradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entradas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idpersonas_fk` int(11) DEFAULT NULL,
  `idvehiculos_fk` int(11) DEFAULT NULL,
  `motivo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_entradas_1_idx` (`idpersonas_fk`),
  KEY `fk_entradas_2_idx` (`idvehiculos_fk`),
  CONSTRAINT `fk_entradas_1` FOREIGN KEY (`idpersonas_fk`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_entradas_2` FOREIGN KEY (`idvehiculos_fk`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas`
--

LOCK TABLES `entradas` WRITE;
/*!40000 ALTER TABLE `entradas` DISABLE KEYS */;
/*!40000 ALTER TABLE `entradas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1451753954),('m130524_201442_init',1451753965),('m140506_102106_rbac_init',1453566729);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entrada_id` int(11) DEFAULT NULL,
  `entrada_fecha` datetime DEFAULT NULL,
  `salida_id` int(11) DEFAULT NULL,
  `salida_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_movimientos_1_idx` (`entrada_id`),
  KEY `fk_movimientos_2_idx` (`salida_id`),
  CONSTRAINT `fk_movimientos_1` FOREIGN KEY (`entrada_id`) REFERENCES `entradas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimientos_2` FOREIGN KEY (`salida_id`) REFERENCES `salidas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos`
--

LOCK TABLES `movimientos` WRITE;
/*!40000 ALTER TABLE `movimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dni` int(11) DEFAULT NULL,
  `apellido` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `nombre2` varchar(45) DEFAULT NULL,
  `fecnac` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (9,23038545,'Bergonzi','Ivan','Patricio','1972-10-23',7,'2016-01-30 18:10:48',7,'2016-01-31 15:40:32'),(10,41404561,'Bergonzi','Stefano','',NULL,7,'2016-01-30 18:11:47',7,'2016-01-30 18:11:47'),(11,14012345,'Santiago Santos','Miralva','',NULL,7,'2016-01-30 18:12:29',7,'2016-01-30 18:12:29'),(22,435543535,'bjjgdbbdb','dsgdtdh','','2015-12-10',7,'2016-01-31 19:21:13',7,'2016-01-31 19:21:13'),(23,5454,'ktrjgnfvtvt','frcdctfvtr','','2016-01-29',7,'2016-01-31 19:21:35',7,'2016-01-31 19:21:35'),(24,543235433,'fjdffvjd','fjhbfvdc','',NULL,7,'2016-01-31 21:49:40',7,'2016-01-31 21:49:40'),(25,6543455,'gfgvdv','axxsgbjfb','',NULL,7,'2016-01-31 21:50:06',7,'2016-01-31 21:50:06'),(26,9764466,'llhfczvhtvy','vcrdhvbjv','',NULL,7,'2016-01-31 21:50:38',7,'2016-01-31 21:50:38'),(27,744636,'ggmx,j h ','gh dthgfvh','',NULL,7,'2016-01-31 21:52:30',7,'2016-01-31 21:52:30'),(28,47456545,'kudrdvghbu','rgvhv ktchtd','',NULL,7,'2016-01-31 21:53:03',7,'2016-01-31 21:53:03'),(29,806465455,'yjdcdc','dcrchtvyj yb','',NULL,7,'2016-01-31 21:53:40',7,'2016-01-31 21:53:40');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salidas`
--

DROP TABLE IF EXISTS `salidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salidas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idpersonas_fk` int(11) DEFAULT NULL,
  `idvehiculos_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_salidas_1_idx` (`idpersonas_fk`),
  KEY `fk_salidas_2_idx` (`idvehiculos_fk`),
  CONSTRAINT `fk_salidas_1` FOREIGN KEY (`idpersonas_fk`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_salidas_2` FOREIGN KEY (`idvehiculos_fk`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salidas`
--

LOCK TABLES `salidas` WRITE;
/*!40000 ALTER TABLE `salidas` DISABLE KEYS */;
/*!40000 ALTER TABLE `salidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (7,'consejo','vt3jVR8_7Lp28wuGUKtnwf0Yp_iW1YUH','$2y$13$hO5BpsjMsPaIqWjbw0kGye8yKPuoktIl6mY.TxCy4Pi6xv5VOqKUS',NULL,'miraflores.adm.consejo@gmail.com',10,1453571317,1453571317),(8,'administracion','_MQYFP_FDxzIlGUVgQNTpoRVfLtoqZ-B','$2y$13$5Wrt.uiqIepY0VrzKB5.qOFqDwiqPCUZ2Di3hwwT70jjXSAla0C72',NULL,'miraflores.adm.adm@gmail.com',10,1453571342,1453571342),(9,'intendencia','KtqPN2-xODBwAGRcEwnZKjkMmFH7Z-pN','$2y$13$nKB4I1JzQR3G/dloJI2UfOVECEMUSTFWE6G4OM2AxZ7NJkMdqcVja',NULL,'ibergonzi@hotmail.com',10,1453571509,1453571509),(11,'ibergonzi','d1wCybevudCGF1MzsMtebe2lSB-OLjMK','$2y$13$VdzaAs3i5wo.nSY2xjnyEObTW4AlmMIID2eAf5PlQYbxNkWM0lPQe',NULL,'cuac@hotmail.com',10,1453662742,1453662742);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `user_rol`
--

DROP TABLE IF EXISTS `user_rol`;
/*!50001 DROP VIEW IF EXISTS `user_rol`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `user_rol` AS SELECT 
 1 AS `id`,
 1 AS `username`,
 1 AS `email`,
 1 AS `item_name`,
 1 AS `description`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patente` varchar(10) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,'EFD926','FIAT','PALIO','GRIS'),(2,'CMX618','VOLSWAGEN','GOLF','ROJO');
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `user_rol`
--

/*!50001 DROP VIEW IF EXISTS `user_rol`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `user_rol` AS select `user`.`id` AS `id`,`user`.`username` AS `username`,`user`.`email` AS `email`,`auth_assignment`.`item_name` AS `item_name`,`auth_item`.`description` AS `description` from ((`user` left join `auth_assignment` on((`user`.`id` = `auth_assignment`.`user_id`))) left join `auth_item` on((`auth_assignment`.`item_name` = `auth_item`.`name`))) where (`user`.`status` = 10) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-01 17:23:06
