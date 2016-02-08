-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
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
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assig_usr` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('administrador',8,1454792330),('consejo',7,1454792330),('guardia',12,1454960074),('intendente',9,1454792330),('sinRol',11,1454957230);
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
INSERT INTO `auth_item` VALUES ('accederEntradas',2,'Acceso: Entradas',NULL,NULL,1454792330,1454792330),('accederPorton',2,'Acceso: elección de portón',NULL,NULL,1454792330,1454792330),('accederUser',2,'Acceso: usuarios',NULL,NULL,1454792330,1454792330),('accederUserRol',2,'Acceso: rol de usuarios',NULL,NULL,1454792330,1454792330),('administrador',1,'Rol: Administrador',NULL,NULL,1454792330,1454792330),('arquitecto',1,'Rol: Arquitecto',NULL,NULL,1454792330,1454792330),('consejo',1,'Rol: Consejo',NULL,NULL,1454792330,1454792330),('guardia',1,'Rol: Guardia',NULL,NULL,1454792330,1454792330),('intendente',1,'Rol: Intendente',NULL,NULL,1454792330,1454792330),('opIntendencia',1,'Rol: Operador de Intendencia',NULL,NULL,1454792330,1454792330),('portero',1,'Rol: Portero',NULL,NULL,1454792330,1454792330),('propietario',1,'Rol: Propietario',NULL,NULL,1454792330,1454792330),('sinRol',1,'Rol: Sin rol asignado',NULL,NULL,1454792330,1454792330);
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
INSERT INTO `auth_item_child` VALUES ('intendente','accederEntradas'),('intendente','accederPorton'),('administrador','accederUser'),('consejo','accederUser'),('intendente','accederUser'),('administrador','accederUserRol'),('consejo','accederUserRol'),('intendente','accederUserRol');
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
  `idporton` tinyint(4) NOT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `idvehiculo` int(11) DEFAULT NULL,
  `motivo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_entradas_1_idx` (`idpersona`),
  KEY `fk_entradas_2_idx` (`idvehiculo`),
  KEY `fk_entradas_3` (`idporton`),
  CONSTRAINT `fk_entradas_1` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_entradas_2` FOREIGN KEY (`idvehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_entradas_3` FOREIGN KEY (`idporton`) REFERENCES `portones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas`
--

LOCK TABLES `entradas` WRITE;
/*!40000 ALTER TABLE `entradas` DISABLE KEYS */;
INSERT INTO `entradas` VALUES (3,1,9,NULL,'');
/*!40000 ALTER TABLE `entradas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libro`
--

DROP TABLE IF EXISTS `libro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto` varchar(500) NOT NULL,
  `idporton` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro`
--

LOCK TABLES `libro` WRITE;
/*!40000 ALTER TABLE `libro` DISABLE KEYS */;
INSERT INTO `libro` VALUES (1,'In this tutorial i will explain how to use jui auto complete in yii2. The Autocomplete widget enables users to quickly find and select from a pre-populated list of values as they type, leveraging searching and filtering. ',1,9,'2016-02-07 17:27:34',9,'2016-02-08 17:27:34'),(2,'  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod vehicula eros pellentesque lacinia. Nulla suscipit metus ut enim iaculis, vitae sollicitudin velit vehicula. Nunc et elit sagittis, venenatis arcu vitae, pretium purus. Praesent venenatis libero vel lobortis consectetur. Nulla ut feugiat tortor. Quisque vestibulum, nisi sit amet luctus lobortis, diam diam dignissim tellus, a dignissim nibh massa ac magna. Nullam in nisi risus.  Donec finibus condimentum arcu. Nunc eu risus ph',2,9,'2016-02-08 18:44:28',9,'2016-02-08 18:44:28'),(3,'Fusce imperdiet at sapien vel tempor. Integer mattis, ipsum ac commodo aliquam, justo ligula dapibus justo, ut rhoncus purus arcu et nisi. Nunc vulputate et nibh sit amet tincidunt. Integer dictum, elit eu malesuada egestas, massa eros sodales nisi, a suscipit magna ex finibus diam. Duis maximus massa nec scelerisque ullamcorper. Praesent risus metus, maximus eget turpis ac, ultricies pretium tortor. Nulla cursus ante diam. Mauris posuere nisi feugiat tortor. ',2,9,'2016-02-08 19:21:14',9,'2016-02-08 19:21:14');
/*!40000 ALTER TABLE `libro` ENABLE KEYS */;
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
  `apellido` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `nombre2` varchar(45) DEFAULT NULL,
  `fecnac` date DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (9,23038545,'Bergonzi','Ivan','Patricio','1972-10-23','',7,'2016-01-30 18:10:48',0,'2016-02-03 12:20:27'),(10,41404561,'Bergonzi','Stefano','',NULL,'',7,'2016-01-30 18:11:47',7,'2016-01-30 18:11:47'),(11,14012345,'Santiago Santos','Miralva','','2016-02-02','',7,'2016-01-30 18:12:29',0,'2016-02-02 10:09:54'),(30,454545,'Bergonzi','Martina','',NULL,'',9,'2016-02-05 12:55:34',9,'2016-02-05 12:55:34'),(77,464646,'Perez','Juan','',NULL,'',9,'2016-02-06 10:55:53',9,'2016-02-06 10:55:53'),(84,343423,'Bergonzi','Yuyu','',NULL,'84.jpg',9,'2016-02-06 14:12:25',9,'2016-02-06 15:22:18');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portones`
--

DROP TABLE IF EXISTS `portones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portones` (
  `id` tinyint(4) NOT NULL,
  `descripcion` varchar(25) NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portones`
--

LOCK TABLES `portones` WRITE;
/*!40000 ALTER TABLE `portones` DISABLE KEYS */;
INSERT INTO `portones` VALUES (1,'Portón Entrada (1)',1),(2,'Portón Proveedores (2)',1),(3,'Golf',0);
/*!40000 ALTER TABLE `portones` ENABLE KEYS */;
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
  `foto` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (7,'consejo','vt3jVR8_7Lp28wuGUKtnwf0Yp_iW1YUH','$2y$13$hO5BpsjMsPaIqWjbw0kGye8yKPuoktIl6mY.TxCy4Pi6xv5VOqKUS',NULL,'miraflores.adm.consejo@gmail.com',10,NULL,1453571317,1453571317),(8,'administracion','_MQYFP_FDxzIlGUVgQNTpoRVfLtoqZ-B','$2y$13$5Wrt.uiqIepY0VrzKB5.qOFqDwiqPCUZ2Di3hwwT70jjXSAla0C72',NULL,'miraflores.adm.adm@gmail.com',10,NULL,1453571342,1453571342),(9,'intendencia','KtqPN2-xODBwAGRcEwnZKjkMmFH7Z-pN','$2y$13$nKB4I1JzQR3G/dloJI2UfOVECEMUSTFWE6G4OM2AxZ7NJkMdqcVja',NULL,'ibergonzi@hotmail.com',10,NULL,1453571509,1453571509),(11,'ibergonzi','d1wCybevudCGF1MzsMtebe2lSB-OLjMK','$2y$13$VdzaAs3i5wo.nSY2xjnyEObTW4AlmMIID2eAf5PlQYbxNkWM0lPQe',NULL,'cuac@hotmail.com',10,NULL,1453662742,1454957670),(12,'petu','YbaD3j46eRWHI1Zhu4xvIhGBihYy2dxV','$2y$13$ENwLvej.4LH.8mVq0QzT4uX08cjTZ2OF9vW91FbYhFofiv7Qmu5Ou',NULL,'petu@com.ar',10,'12.jpg',1454958741,1454960075);
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

-- Dump completed on 2016-02-08 19:27:07
