-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2016 at 01:56 PM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.4-7ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `country`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `autorizantes_busca_nombres` (IN `_query` VARCHAR(45) CHARSET utf8)  READS SQL DATA
    SQL SECURITY INVOKER
SELECT DISTINCT autorizantes.id,'' as text FROM `personas` JOIN autorizantes ON personas.id=autorizantes.id_persona
WHERE CONCAT(`apellido`, ' ',`nombre`,' ',`nombre2`) LIKE CONCAT('%', _query , '%') AND personas.estado=1 LIMIT 40$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `autorizantes_busca_nrosdoc` (IN `_query` VARCHAR(45) CHARSET utf8)  READS SQL DATA
    SQL SECURITY INVOKER
SELECT DISTINCT personas.id,'' as text FROM `personas` 
JOIN autorizantes ON personas.id=autorizantes.id_persona
WHERE nro_doc LIKE CONCAT('%', _query , '%') AND personas.estado=1  LIMIT 40$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `autorizantes_busca_uf` (IN `_query` INT)  SQL SECURITY INVOKER
SELECT DISTINCT id,'' as text FROM autorizantes
WHERE id_uf = _query LIMIT 40$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `personas_busca_nombres` (IN `_query` VARCHAR(45) CHARSET utf8)  READS SQL DATA
    SQL SECURITY INVOKER
SELECT `id`,'' as text FROM `personas` WHERE CONCAT(`apellido`, ' ',`nombre`,' ',`nombre2`) LIKE CONCAT('%', _query , '%') AND estado=1  LIMIT 40$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `personas_busca_nrosdoc` (IN `_query` VARCHAR(45) CHARSET utf8)  READS SQL DATA
    SQL SECURITY INVOKER
SELECT `id`,'' as text FROM `personas` WHERE nro_doc LIKE CONCAT('%', _query , '%') AND estado=1  LIMIT 40$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vehiculos_busca` (IN `_query` VARCHAR(45) CHARSET utf8)  READS SQL DATA
    SQL SECURITY INVOKER
SELECT `id`,'' as text FROM `vehiculos` WHERE CONCAT(`patente`, ' ',`marca`,' ',`modelo`,' ',`color`) LIKE CONCAT('%', _query , '%') AND estado=1  LIMIT 40$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accesos`
--

CREATE TABLE `accesos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_persona` int(11) NOT NULL,
  `ing_id_vehiculo` int(11) NOT NULL,
  `ing_fecha` date NOT NULL,
  `ing_hora` datetime NOT NULL,
  `ing_id_porton` smallint(6) NOT NULL,
  `ing_id_user` int(11) NOT NULL,
  `ing_id_llave` int(10) UNSIGNED DEFAULT NULL,
  `egr_id_vehiculo` int(11) DEFAULT NULL,
  `egr_fecha` date DEFAULT NULL,
  `egr_hora` datetime DEFAULT NULL,
  `egr_id_porton` smallint(6) DEFAULT NULL,
  `egr_id_user` int(11) DEFAULT NULL,
  `egr_id_llave` int(10) UNSIGNED DEFAULT NULL,
  `id_concepto` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `control` varchar(200) DEFAULT NULL,
  `cant_acomp` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accesos_autmanual`
--

CREATE TABLE `accesos_autmanual` (
  `id` int(11) NOT NULL,
  `hora_desde` datetime NOT NULL,
  `hora_hasta` datetime NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accesos_autorizantes`
--

CREATE TABLE `accesos_autorizantes` (
  `id` int(11) NOT NULL,
  `id_acceso` int(11) UNSIGNED NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_uf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accesos_conceptos`
--

CREATE TABLE `accesos_conceptos` (
  `id` int(11) NOT NULL,
  `concepto` varchar(50) NOT NULL,
  `req_tarjeta` tinyint(4) NOT NULL,
  `req_seguro` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `accesos_ufs`
--
CREATE TABLE `accesos_ufs` (
`accesos_id` int(11) unsigned
,`id_uf` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `accesos_vista`
--
CREATE TABLE `accesos_vista` (
`id` int(10) unsigned
,`id_persona` int(11)
,`ing_id_vehiculo` int(11)
,`ing_fecha` date
,`ing_hora` datetime
,`ing_id_porton` smallint(6)
,`ing_id_user` int(11)
,`ing_id_llave` int(10) unsigned
,`egr_id_vehiculo` int(11)
,`egr_fecha` date
,`egr_hora` datetime
,`egr_id_porton` smallint(6)
,`egr_id_user` int(11)
,`egr_id_llave` int(10) unsigned
,`id_concepto` int(11)
,`motivo` varchar(50)
,`control` varchar(200)
,`cant_acomp` tinyint(4)
,`created_by` int(11)
,`created_at` datetime
,`updated_by` int(11)
,`updated_at` datetime
,`estado` tinyint(4)
,`motivo_baja` varchar(50)
,`r_ing_usuario` varchar(255)
,`r_egr_usuario` varchar(255)
,`r_apellido` varchar(45)
,`r_nombre` varchar(45)
,`r_nombre2` varchar(45)
,`r_nro_doc` varchar(15)
,`r_ing_patente` varchar(10)
,`r_ing_marca` varchar(20)
,`r_ing_modelo` varchar(30)
,`r_ing_color` varchar(10)
,`r_egr_patente` varchar(10)
,`r_egr_marca` varchar(20)
,`r_egr_modelo` varchar(30)
,`r_egr_color` varchar(10)
,`desc_concepto` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `accesos_vista_f`
--
CREATE TABLE `accesos_vista_f` (
`id` binary(0)
,`id_acceso` int(10) unsigned
,`id_persona` int(11)
,`ing_id_vehiculo` int(11)
,`ing_fecha` date
,`ing_hora` datetime
,`ing_id_porton` smallint(6)
,`ing_id_user` int(11)
,`ing_id_llave` int(10) unsigned
,`egr_id_vehiculo` int(11)
,`egr_fecha` date
,`egr_hora` datetime
,`egr_id_porton` smallint(6)
,`egr_id_user` int(11)
,`egr_id_llave` int(10) unsigned
,`id_concepto` int(11)
,`motivo` varchar(50)
,`control` varchar(200)
,`cant_acomp` tinyint(4)
,`created_by` int(11)
,`created_at` datetime
,`updated_by` int(11)
,`updated_at` datetime
,`estado` tinyint(4)
,`motivo_baja` varchar(50)
,`id_autorizante` int(11)
,`id_uf` int(11)
,`r_ing_usuario` varchar(255)
,`r_egr_usuario` varchar(255)
,`r_apellido` varchar(45)
,`r_nombre` varchar(45)
,`r_nombre2` varchar(45)
,`r_nro_doc` varchar(15)
,`r_aut_apellido` varchar(45)
,`r_aut_nombre` varchar(45)
,`r_aut_nombre2` varchar(45)
,`r_aut_nro_doc` varchar(15)
,`r_ing_patente` varchar(10)
,`r_ing_marca` varchar(20)
,`r_ing_modelo` varchar(30)
,`r_ing_color` varchar(10)
,`r_egr_patente` varchar(10)
,`r_egr_marca` varchar(20)
,`r_egr_modelo` varchar(30)
,`r_egr_color` varchar(10)
,`desc_concepto` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `numero` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `localidad` varchar(50) DEFAULT NULL,
  `cod_pos` varchar(10) DEFAULT NULL,
  `provincia` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `telefono1` varchar(50) DEFAULT NULL,
  `telefono2` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `telex` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `palabra` varchar(50) DEFAULT NULL,
  `actividad` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autorizantes`
--

CREATE TABLE `autorizantes` (
  `id` int(11) NOT NULL,
  `id_uf` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `comentario` varchar(500) NOT NULL,
  `model` varchar(50) NOT NULL,
  `model_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cortes_energia`
--

CREATE TABLE `cortes_energia` (
  `id` int(11) NOT NULL,
  `hora_desde` datetime NOT NULL,
  `hora_hasta` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cortes_energia_gen`
--

CREATE TABLE `cortes_energia_gen` (
  `id` int(11) NOT NULL,
  `id_cortes_energia` int(11) NOT NULL,
  `id_generador` tinyint(4) NOT NULL,
  `hora_desde` datetime NOT NULL,
  `hora_hasta` datetime NOT NULL,
  `observaciones` varchar(60) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `generadores`
--

CREATE TABLE `generadores` (
  `id` tinyint(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infracciones`
--

CREATE TABLE `infracciones` (
  `id` int(11) NOT NULL,
  `id_uf` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` datetime NOT NULL,
  `nro_acta` varchar(10) DEFAULT NULL,
  `lugar` varchar(50) NOT NULL,
  `id_concepto` smallint(6) NOT NULL,
  `id_informante` int(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `notificado` tinyint(4) NOT NULL,
  `fecha_verif` date DEFAULT NULL,
  `verificado` tinyint(4) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `multa_unidad` tinyint(4) DEFAULT NULL,
  `multa_fec_reinc` date NOT NULL,
  `multa_monto` double NOT NULL,
  `multa_pers_cant` tinyint(4) NOT NULL,
  `multa_pers_monto` double NOT NULL,
  `multa_pers_total` double NOT NULL,
  `multa_total` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infrac_conceptos`
--

CREATE TABLE `infrac_conceptos` (
  `id` smallint(6) NOT NULL,
  `concepto` varchar(75) NOT NULL,
  `es_multa` tinyint(1) NOT NULL,
  `dias_verif` tinyint(4) NOT NULL,
  `multa_unidad` tinyint(4) DEFAULT NULL,
  `multa_precio` double NOT NULL,
  `multa_reincidencia` tinyint(1) NOT NULL,
  `multa_reinc_porc` double NOT NULL,
  `multa_reinc_dias` smallint(6) NOT NULL,
  `multa_personas` tinyint(1) NOT NULL,
  `multa_personas_precio` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infrac_unidades`
--

CREATE TABLE `infrac_unidades` (
  `id` tinyint(4) NOT NULL,
  `unidad` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `libro`
--

CREATE TABLE `libro` (
  `id` int(11) NOT NULL,
  `texto` varchar(500) NOT NULL,
  `idporton` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `llaves`
--

CREATE TABLE `llaves` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `panico` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `id_autorizante` int(11) DEFAULT NULL,
  `id_vehiculo` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `avisar_a` varchar(50) NOT NULL,
  `mensaje` varchar(500) NOT NULL,
  `model` varchar(50) NOT NULL,
  `model_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `movim_uf`
--

CREATE TABLE `movim_uf` (
  `id` smallint(6) NOT NULL,
  `desc_movim_uf` varchar(30) NOT NULL,
  `cesion` tinyint(4) NOT NULL DEFAULT '0',
  `migracion` tinyint(6) NOT NULL DEFAULT '0',
  `fec_vto` tinyint(4) NOT NULL DEFAULT '0',
  `manual` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `nombre2` varchar(45) DEFAULT NULL,
  `id_tipo_doc` smallint(6) NOT NULL,
  `nro_doc` varchar(15) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `vto_seguro` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portones`
--

CREATE TABLE `portones` (
  `id` tinyint(4) NOT NULL,
  `descripcion` varchar(25) NOT NULL,
  `habilitado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tiposdoc`
--

CREATE TABLE `tiposdoc` (
  `id` smallint(6) NOT NULL,
  `desc_tipo_doc` varchar(50) NOT NULL,
  `desc_tipo_doc_abr` varchar(4) NOT NULL,
  `persona_fisica` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `titularidad_vista`
--
CREATE TABLE `titularidad_vista` (
`id` binary(0)
,`id_titularidad` int(11)
,`id_uf` int(11)
,`desc_movim_uf` varchar(30)
,`fec_desde` date
,`fec_hasta` date
,`exp_telefono` varchar(30)
,`exp_direccion` varchar(60)
,`exp_localidad` varchar(60)
,`exp_email` varchar(255)
,`tipo` char(1)
,`id_persona` int(11)
,`apellido` varchar(45)
,`nombre` varchar(45)
,`nombre2` varchar(45)
,`desc_tipo_doc_abr` varchar(4)
,`nro_doc` varchar(15)
,`superficie` float
,`observaciones` varchar(60)
,`ultima` tinyint(4)
,`unidad_estado` tinyint(4)
);

-- --------------------------------------------------------

--
-- Table structure for table `uf`
--

CREATE TABLE `uf` (
  `id` int(11) NOT NULL,
  `loteo` smallint(6) NOT NULL,
  `manzana` smallint(6) NOT NULL,
  `superficie` float NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uf_titularidad`
--

CREATE TABLE `uf_titularidad` (
  `id` int(11) NOT NULL,
  `id_uf` int(11) NOT NULL,
  `tipo_movim` smallint(6) NOT NULL,
  `fec_desde` date NOT NULL,
  `fec_hasta` date DEFAULT NULL,
  `exp_telefono` varchar(30) DEFAULT NULL,
  `exp_direccion` varchar(60) DEFAULT NULL,
  `exp_localidad` varchar(60) DEFAULT NULL,
  `exp_email` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL,
  `ultima` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uf_titularidad_personas`
--

CREATE TABLE `uf_titularidad_personas` (
  `id` int(11) NOT NULL,
  `uf_titularidad_id` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `tipo` char(1) NOT NULL,
  `observaciones` varchar(60) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acceso_externo` tinyint(4) NOT NULL DEFAULT '1',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `foto` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `patente` varchar(10) NOT NULL,
  `marca` varchar(20) NOT NULL,
  `modelo` varchar(30) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `motivo_baja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `accesos_ufs`
--
DROP TABLE IF EXISTS `accesos_ufs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accesos_ufs`  AS  select `accesos_autorizantes`.`id_acceso` AS `accesos_id`,group_concat(`accesos_autorizantes`.`id_uf` separator ',') AS `id_uf` from `accesos_autorizantes` group by `accesos_id` ;

-- --------------------------------------------------------

--
-- Structure for view `accesos_vista`
--
DROP TABLE IF EXISTS `accesos_vista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accesos_vista`  AS  select `accesos`.`id` AS `id`,`accesos`.`id_persona` AS `id_persona`,`accesos`.`ing_id_vehiculo` AS `ing_id_vehiculo`,`accesos`.`ing_fecha` AS `ing_fecha`,`accesos`.`ing_hora` AS `ing_hora`,`accesos`.`ing_id_porton` AS `ing_id_porton`,`accesos`.`ing_id_user` AS `ing_id_user`,`accesos`.`ing_id_llave` AS `ing_id_llave`,`accesos`.`egr_id_vehiculo` AS `egr_id_vehiculo`,`accesos`.`egr_fecha` AS `egr_fecha`,`accesos`.`egr_hora` AS `egr_hora`,`accesos`.`egr_id_porton` AS `egr_id_porton`,`accesos`.`egr_id_user` AS `egr_id_user`,`accesos`.`egr_id_llave` AS `egr_id_llave`,`accesos`.`id_concepto` AS `id_concepto`,`accesos`.`motivo` AS `motivo`,`accesos`.`control` AS `control`,`accesos`.`cant_acomp` AS `cant_acomp`,`accesos`.`created_by` AS `created_by`,`accesos`.`created_at` AS `created_at`,`accesos`.`updated_by` AS `updated_by`,`accesos`.`updated_at` AS `updated_at`,`accesos`.`estado` AS `estado`,`accesos`.`motivo_baja` AS `motivo_baja`,`uing`.`username` AS `r_ing_usuario`,`uegr`.`username` AS `r_egr_usuario`,`personas`.`apellido` AS `r_apellido`,`personas`.`nombre` AS `r_nombre`,`personas`.`nombre2` AS `r_nombre2`,`personas`.`nro_doc` AS `r_nro_doc`,`ving`.`patente` AS `r_ing_patente`,`ving`.`marca` AS `r_ing_marca`,`ving`.`modelo` AS `r_ing_modelo`,`ving`.`color` AS `r_ing_color`,`vegr`.`patente` AS `r_egr_patente`,`vegr`.`marca` AS `r_egr_marca`,`vegr`.`modelo` AS `r_egr_modelo`,`vegr`.`color` AS `r_egr_color`,`accesos_conceptos`.`concepto` AS `desc_concepto` from ((((((`accesos` left join `user` `uing` on((`accesos`.`ing_id_user` = `uing`.`id`))) left join `user` `uegr` on((`accesos`.`egr_id_user` = `uegr`.`id`))) left join `personas` on((`accesos`.`id_persona` = `personas`.`id`))) left join `vehiculos` `ving` on((`accesos`.`ing_id_vehiculo` = `ving`.`id`))) left join `vehiculos` `vegr` on((`accesos`.`egr_id_vehiculo` = `vegr`.`id`))) left join `accesos_conceptos` on((`accesos`.`id_concepto` = `accesos_conceptos`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `accesos_vista_f`
--
DROP TABLE IF EXISTS `accesos_vista_f`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accesos_vista_f`  AS  select NULL AS `id`,`accesos`.`id` AS `id_acceso`,`accesos`.`id_persona` AS `id_persona`,`accesos`.`ing_id_vehiculo` AS `ing_id_vehiculo`,`accesos`.`ing_fecha` AS `ing_fecha`,`accesos`.`ing_hora` AS `ing_hora`,`accesos`.`ing_id_porton` AS `ing_id_porton`,`accesos`.`ing_id_user` AS `ing_id_user`,`accesos`.`ing_id_llave` AS `ing_id_llave`,`accesos`.`egr_id_vehiculo` AS `egr_id_vehiculo`,`accesos`.`egr_fecha` AS `egr_fecha`,`accesos`.`egr_hora` AS `egr_hora`,`accesos`.`egr_id_porton` AS `egr_id_porton`,`accesos`.`egr_id_user` AS `egr_id_user`,`accesos`.`egr_id_llave` AS `egr_id_llave`,`accesos`.`id_concepto` AS `id_concepto`,`accesos`.`motivo` AS `motivo`,`accesos`.`control` AS `control`,`accesos`.`cant_acomp` AS `cant_acomp`,`accesos`.`created_by` AS `created_by`,`accesos`.`created_at` AS `created_at`,`accesos`.`updated_by` AS `updated_by`,`accesos`.`updated_at` AS `updated_at`,`accesos`.`estado` AS `estado`,`accesos`.`motivo_baja` AS `motivo_baja`,`aa`.`id_persona` AS `id_autorizante`,`aa`.`id_uf` AS `id_uf`,`uing`.`username` AS `r_ing_usuario`,`uegr`.`username` AS `r_egr_usuario`,`personas`.`apellido` AS `r_apellido`,`personas`.`nombre` AS `r_nombre`,`personas`.`nombre2` AS `r_nombre2`,`personas`.`nro_doc` AS `r_nro_doc`,`pautoriz`.`apellido` AS `r_aut_apellido`,`pautoriz`.`nombre` AS `r_aut_nombre`,`pautoriz`.`nombre2` AS `r_aut_nombre2`,`pautoriz`.`nro_doc` AS `r_aut_nro_doc`,`ving`.`patente` AS `r_ing_patente`,`ving`.`marca` AS `r_ing_marca`,`ving`.`modelo` AS `r_ing_modelo`,`ving`.`color` AS `r_ing_color`,`vegr`.`patente` AS `r_egr_patente`,`vegr`.`marca` AS `r_egr_marca`,`vegr`.`modelo` AS `r_egr_modelo`,`vegr`.`color` AS `r_egr_color`,`accesos_conceptos`.`concepto` AS `desc_concepto` from ((((((((`accesos` left join `accesos_autorizantes` `aa` on((`accesos`.`id` = `aa`.`id_acceso`))) left join `user` `uing` on((`accesos`.`ing_id_user` = `uing`.`id`))) left join `user` `uegr` on((`accesos`.`egr_id_user` = `uegr`.`id`))) left join `personas` on((`accesos`.`id_persona` = `personas`.`id`))) left join `vehiculos` `ving` on((`accesos`.`ing_id_vehiculo` = `ving`.`id`))) left join `vehiculos` `vegr` on((`accesos`.`egr_id_vehiculo` = `vegr`.`id`))) left join `personas` `pautoriz` on((`aa`.`id_persona` = `pautoriz`.`id`))) left join `accesos_conceptos` on((`accesos`.`id_concepto` = `accesos_conceptos`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `titularidad_vista`
--
DROP TABLE IF EXISTS `titularidad_vista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `titularidad_vista`  AS  select NULL AS `id`,`uf_titularidad`.`id` AS `id_titularidad`,`uf_titularidad`.`id_uf` AS `id_uf`,`movim_uf`.`desc_movim_uf` AS `desc_movim_uf`,`uf_titularidad`.`fec_desde` AS `fec_desde`,`uf_titularidad`.`fec_hasta` AS `fec_hasta`,`uf_titularidad`.`exp_telefono` AS `exp_telefono`,`uf_titularidad`.`exp_direccion` AS `exp_direccion`,`uf_titularidad`.`exp_localidad` AS `exp_localidad`,`uf_titularidad`.`exp_email` AS `exp_email`,`uf_titularidad_personas`.`tipo` AS `tipo`,`personas`.`id` AS `id_persona`,`personas`.`apellido` AS `apellido`,`personas`.`nombre` AS `nombre`,`personas`.`nombre2` AS `nombre2`,`tiposdoc`.`desc_tipo_doc_abr` AS `desc_tipo_doc_abr`,`personas`.`nro_doc` AS `nro_doc`,`uf`.`superficie` AS `superficie`,`uf_titularidad_personas`.`observaciones` AS `observaciones`,`uf_titularidad`.`ultima` AS `ultima`,`uf`.`estado` AS `unidad_estado` from (((((`uf_titularidad` join `movim_uf` on((`movim_uf`.`id` = `uf_titularidad`.`tipo_movim`))) join `uf_titularidad_personas` on((`uf_titularidad_personas`.`uf_titularidad_id` = `uf_titularidad`.`id`))) join `personas` on((`uf_titularidad_personas`.`id_persona` = `personas`.`id`))) join `uf` on((`uf`.`id` = `uf_titularidad`.`id_uf`))) join `tiposdoc` on((`tiposdoc`.`id` = `personas`.`id_tipo_doc`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_persona` (`id_persona`) USING BTREE,
  ADD KEY `idx_ing_id_vehiculo` (`ing_id_vehiculo`) USING BTREE,
  ADD KEY `idx_egr_id_vehiculo` (`egr_id_vehiculo`) USING BTREE,
  ADD KEY `idx_id_concepto` (`id_concepto`) USING BTREE,
  ADD KEY `idx_ing_fecha` (`ing_fecha`) USING BTREE,
  ADD KEY `control` (`control`),
  ADD KEY `ing_id_llave` (`ing_id_llave`),
  ADD KEY `egr_id_llave` (`egr_id_llave`),
  ADD KEY `egr_fecha` (`egr_fecha`);

--
-- Indexes for table `accesos_autmanual`
--
ALTER TABLE `accesos_autmanual`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accesos_autorizantes`
--
ALTER TABLE `accesos_autorizantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_acceso` (`id_acceso`) USING BTREE,
  ADD KEY `id_uf` (`id_uf`),
  ADD KEY `id_persona` (`id_persona`) USING BTREE;

--
-- Indexes for table `accesos_conceptos`
--
ALTER TABLE `accesos_conceptos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `nombre` (`nombre`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assig_usr` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `autorizantes`
--
ALTER TABLE `autorizantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_persona_2` (`id_persona`,`id_uf`),
  ADD KEY `id_uf` (`id_uf`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indexes for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `cortes_energia`
--
ALTER TABLE `cortes_energia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cortes_energia_gen`
--
ALTER TABLE `cortes_energia_gen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cortes_energia` (`id_cortes_energia`),
  ADD KEY `id_generador` (`id_generador`);

--
-- Indexes for table `generadores`
--
ALTER TABLE `generadores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `infracciones`
--
ALTER TABLE `infracciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uf` (`id_uf`),
  ADD KEY `id_vehiculo` (`id_vehiculo`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_informante` (`id_informante`),
  ADD KEY `multa_unidad` (`multa_unidad`),
  ADD KEY `id_concepto` (`id_concepto`);

--
-- Indexes for table `infrac_conceptos`
--
ALTER TABLE `infrac_conceptos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `multa_unidad` (`multa_unidad`);

--
-- Indexes for table `infrac_unidades`
--
ALTER TABLE `infrac_unidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `llaves`
--
ALTER TABLE `llaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_autorizante` (`id_autorizante`),
  ADD KEY `id_vehiculo` (`id_vehiculo`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `movim_uf`
--
ALTER TABLE `movim_uf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nro_doc` (`nro_doc`,`id_tipo_doc`,`estado`),
  ADD KEY `id_tipo_doc` (`id_tipo_doc`),
  ADD KEY `nombrecompleto` (`apellido`,`nombre`,`nombre2`,`estado`) USING BTREE;

--
-- Indexes for table `portones`
--
ALTER TABLE `portones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiposdoc`
--
ALTER TABLE `tiposdoc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uf`
--
ALTER TABLE `uf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uf_titularidad`
--
ALTER TABLE `uf_titularidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ud_uf` (`id_uf`) USING BTREE,
  ADD KEY `tipo_movim` (`tipo_movim`);

--
-- Indexes for table `uf_titularidad_personas`
--
ALTER TABLE `uf_titularidad_personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_uftp` (`id_persona`,`uf_titularidad_id`) USING BTREE,
  ADD KEY `idx_uftp_uf` (`uf_titularidad_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patente` (`patente`),
  ADD KEY `id_marca` (`marca`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `accesos_autmanual`
--
ALTER TABLE `accesos_autmanual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `accesos_autorizantes`
--
ALTER TABLE `accesos_autorizantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `accesos_conceptos`
--
ALTER TABLE `accesos_conceptos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1047;
--
-- AUTO_INCREMENT for table `autorizantes`
--
ALTER TABLE `autorizantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `cortes_energia`
--
ALTER TABLE `cortes_energia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `cortes_energia_gen`
--
ALTER TABLE `cortes_energia_gen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `infracciones`
--
ALTER TABLE `infracciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `infrac_conceptos`
--
ALTER TABLE `infrac_conceptos`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `infrac_unidades`
--
ALTER TABLE `infrac_unidades`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `libro`
--
ALTER TABLE `libro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7312;
--
-- AUTO_INCREMENT for table `tiposdoc`
--
ALTER TABLE `tiposdoc`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `uf_titularidad`
--
ALTER TABLE `uf_titularidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `uf_titularidad_personas`
--
ALTER TABLE `uf_titularidad_personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`egr_id_llave`) REFERENCES `llaves` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_concepto` FOREIGN KEY (`id_concepto`) REFERENCES `accesos_conceptos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_egr_vehiculo` FOREIGN KEY (`egr_id_vehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ing_vehiculo` FOREIGN KEY (`ing_id_vehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `accesos_autorizantes`
--
ALTER TABLE `accesos_autorizantes`
  ADD CONSTRAINT `accesos_autorizantes_ibfk_1` FOREIGN KEY (`id_uf`) REFERENCES `uf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_accesos_aut` FOREIGN KEY (`id_acceso`) REFERENCES `accesos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personas_aut` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `autorizantes`
--
ALTER TABLE `autorizantes`
  ADD CONSTRAINT `autorizantes_ibfk_1` FOREIGN KEY (`id_uf`) REFERENCES `uf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `autorizantes_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cortes_energia_gen`
--
ALTER TABLE `cortes_energia_gen`
  ADD CONSTRAINT `cortes_energia_gen_ibfk_1` FOREIGN KEY (`id_cortes_energia`) REFERENCES `cortes_energia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cortes_energia_gen_ibfk_2` FOREIGN KEY (`id_generador`) REFERENCES `generadores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `infracciones`
--
ALTER TABLE `infracciones`
  ADD CONSTRAINT `infracciones_ibfk_1` FOREIGN KEY (`id_uf`) REFERENCES `uf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `infracciones_ibfk_2` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `infracciones_ibfk_3` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `infracciones_ibfk_4` FOREIGN KEY (`id_informante`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `infracciones_ibfk_5` FOREIGN KEY (`multa_unidad`) REFERENCES `infrac_unidades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `infracciones_ibfk_6` FOREIGN KEY (`id_concepto`) REFERENCES `infrac_conceptos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `infrac_conceptos`
--
ALTER TABLE `infrac_conceptos`
  ADD CONSTRAINT `infrac_conceptos_ibfk_1` FOREIGN KEY (`multa_unidad`) REFERENCES `infrac_unidades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `llaves`
--
ALTER TABLE `llaves`
  ADD CONSTRAINT `llaves_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `llaves_ibfk_2` FOREIGN KEY (`id_autorizante`) REFERENCES `autorizantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `llaves_ibfk_3` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `fk_tiposdoc` FOREIGN KEY (`id_tipo_doc`) REFERENCES `tiposdoc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `uf_titularidad`
--
ALTER TABLE `uf_titularidad`
  ADD CONSTRAINT `uf_titularidad_ibfk_1` FOREIGN KEY (`id_uf`) REFERENCES `uf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `uf_titularidad_ibfk_2` FOREIGN KEY (`tipo_movim`) REFERENCES `movim_uf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `uf_titularidad_personas`
--
ALTER TABLE `uf_titularidad_personas`
  ADD CONSTRAINT `uf_titularidad_personas_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `uf_titularidad_personas_ibfk_2` FOREIGN KEY (`uf_titularidad_id`) REFERENCES `uf_titularidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
