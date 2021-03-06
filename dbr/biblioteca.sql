-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: localhost    Database: bibliofin
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1

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
-- Table structure for table `actividades`
--

DROP TABLE IF EXISTS `actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividades` (
  `idactividad` varchar(22) NOT NULL,
  `nombre` varchar(65) DEFAULT NULL,
  `descripcion` mediumtext,
  `imagen` varchar(45) DEFAULT NULL,
  `fechapublicacion` datetime DEFAULT NULL,
  `cuentaempleado` varchar(10) DEFAULT NULL,
  `dirigidoa` varchar(2) DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `horainicio` varchar(10) DEFAULT NULL,
  `horafin` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idactividad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `actividadesdia`
--

DROP TABLE IF EXISTS `actividadesdia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividadesdia` (
  `actividad_id` varchar(30) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `asistencia` int(11) DEFAULT NULL,
  `idactividadesdia` varchar(30) NOT NULL,
  `registrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idactividadesdia`),
  KEY `fk_actividad` (`actividad_id`),
  CONSTRAINT `fk_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`idactividad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `autor`
--

DROP TABLE IF EXISTS `autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autor` (
  `idautor` int(11) NOT NULL AUTO_INCREMENT,
  `nameautor` varchar(255) NOT NULL,
  PRIMARY KEY (`idautor`),
  FULLTEXT KEY `ftname` (`nameautor`)
) ENGINE=InnoDB AUTO_INCREMENT=10581 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `autorescribio`
--

DROP TABLE IF EXISTS `autorescribio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autorescribio` (
  `idescribio` varchar(14) NOT NULL,
  `libro_id` int(11) DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idescribio`),
  KEY `libro_fk_idx` (`libro_id`),
  KEY `autor_id_idx` (`autor_id`),
  CONSTRAINT `autor_id` FOREIGN KEY (`autor_id`) REFERENCES `autor` (`idautor`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `libro_fk` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`idlibro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clasificacion`
--

DROP TABLE IF EXISTS `clasificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clasificacion` (
  `idclasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `clasificacion` varchar(100) NOT NULL,
  PRIMARY KEY (`idclasificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=20353 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `datosbiblio`
--

DROP TABLE IF EXISTS `datosbiblio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datosbiblio` (
  `idbiblio` int(11) NOT NULL,
  `namebiblio` varchar(100) CHARACTER SET latin1 NOT NULL,
  `localidad` varchar(100) CHARACTER SET latin1 NOT NULL,
  `municipio` varchar(100) CHARACTER SET latin1 NOT NULL,
  `estado` varchar(100) CHARACTER SET latin1 NOT NULL,
  `encargado` varchar(100) CHARACTER SET latin1 NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `permitireservas` tinyint(1) DEFAULT '1',
  `permitirbusqpri` tinyint(1) DEFAULT '1',
  `busquedaspu` tinyint(1) DEFAULT '1',
  `accesousuarios` tinyint(1) DEFAULT '1',
  `prestamos` tinyint(1) DEFAULT '1',
  `acccesoempl` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idbiblio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detalleprestamo`
--

DROP TABLE IF EXISTS `detalleprestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalleprestamo` (
  `iddetalleprestamo` varchar(27) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nadqui` int(11) DEFAULT NULL,
  `prestamo_id` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `contreno` int(11) DEFAULT '0',
  `fechadev` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetalleprestamo`),
  KEY `prestamo_fk_idx` (`prestamo_id`),
  KEY `dpn_fk_idx` (`nadqui`),
  CONSTRAINT `dpn_fk` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prestamo_fk` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`idprestamo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `editoriales`
--

DROP TABLE IF EXISTS `editoriales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `editoriales` (
  `ideditorial` int(11) NOT NULL AUTO_INCREMENT,
  `nameeditorial` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ideditorial`)
) ENGINE=InnoDB AUTO_INCREMENT=41403 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ejemplar`
--

DROP TABLE IF EXISTS `ejemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ejemplar` (
  `nadqui` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `nejemplar` int(11) DEFAULT NULL,
  `tomo` int(11) DEFAULT NULL,
  `volumen` int(11) DEFAULT NULL,
  `nficha` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`nadqui`),
  KEY `fichafk_idx` (`nficha`),
  CONSTRAINT `fichafk` FOREIGN KEY (`nficha`) REFERENCES `libros` (`idlibro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ejemplaresdescartados`
--

DROP TABLE IF EXISTS `ejemplaresdescartados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ejemplaresdescartados` (
  `iddescarte` varchar(25) NOT NULL,
  `nadqui` int(10) NOT NULL,
  `descartadopor` varchar(20) NOT NULL,
  `fechadescarte` datetime NOT NULL,
  `observaciones` mediumtext,
  `criterio` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`iddescarte`),
  UNIQUE KEY `nadqui_UNIQUE` (`nadqui`),
  KEY `fk_nadquides_idx` (`nadqui`),
  CONSTRAINT `fk_nadquides` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=ujis;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados` (
  `curp` varchar(18) NOT NULL,
  `cuenta` varchar(20) NOT NULL,
  `password_d` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fechaintento` datetime DEFAULT NULL,
  `intentos` int(11) DEFAULT '0',
  PRIMARY KEY (`curp`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `escuelas`
--

DROP TABLE IF EXISTS `escuelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escuelas` (
  `idescuela` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idescuela`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `escuelauser`
--

DROP TABLE IF EXISTS `escuelauser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escuelauser` (
  `idescuelauser` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(18) NOT NULL,
  `escuela_id` int(11) NOT NULL,
  PRIMARY KEY (`idescuelauser`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `escuela_id_fk_idx` (`escuela_id`),
  CONSTRAINT `escuela_id_fk` FOREIGN KEY (`escuela_id`) REFERENCES `escuelas` (`idescuela`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_esc` FOREIGN KEY (`user_id`) REFERENCES `usuariobiblio` (`pcurp`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `idgrupo` int(11) NOT NULL AUTO_INCREMENT,
  `namegrupo` varchar(30) NOT NULL,
  `montomulta` double NOT NULL DEFAULT '0',
  `diasentrega` int(11) NOT NULL DEFAULT '7',
  `renovacion` int(11) NOT NULL DEFAULT '2',
  `cantlibros` int(11) NOT NULL DEFAULT '3',
  `vigencia` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`idgrupo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupouser`
--

DROP TABLE IF EXISTS `grupouser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupouser` (
  `idgrupouser` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(18) NOT NULL,
  `grupo_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idgrupouser`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `grupo_id_idx` (`grupo_id`),
  CONSTRAINT `fk-user_gru` FOREIGN KEY (`user_id`) REFERENCES `personas` (`curp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`idgrupo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=840 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `impbarcode`
--

DROP TABLE IF EXISTS `impbarcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impbarcode` (
  `idcodigo` varchar(20) NOT NULL,
  `dato` varchar(20) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  PRIMARY KEY (`idcodigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `libros`
--

DROP TABLE IF EXISTS `libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libros` (
  `idlibro` int(11) NOT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `idioma` varchar(10) DEFAULT NULL,
  `clasificacion_id` int(11) DEFAULT NULL,
  `titulo` longtext,
  `edicion` varchar(10) DEFAULT NULL,
  `descfisica` varchar(205) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `notageneral` varchar(255) DEFAULT NULL,
  `contenido` longtext,
  `tema` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idlibro`),
  FULLTEXT KEY `tituft` (`titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `librosbloqueados`
--

DROP TABLE IF EXISTS `librosbloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `librosbloqueados` (
  `idbloqueado` varchar(25) NOT NULL,
  `nadqui` int(11) DEFAULT NULL,
  PRIMARY KEY (`idbloqueado`),
  UNIQUE KEY `nadqui_UNIQUE` (`nadqui`),
  CONSTRAINT `repfk` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materialibro`
--

DROP TABLE IF EXISTS `materialibro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materialibro` (
  `idmaterialibro` varchar(16) NOT NULL,
  `materia_id` int(11) DEFAULT NULL,
  `libro_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmaterialibro`),
  KEY `fk_materiafk_idx` (`materia_id`),
  KEY `fk_lbrom_idx` (`libro_id`),
  CONSTRAINT `fk_lbrom` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`idlibro`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_materiafk` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`idmateria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materias` (
  `idmateria` int(11) NOT NULL AUTO_INCREMENT,
  `namemateria` longtext NOT NULL,
  PRIMARY KEY (`idmateria`)
) ENGINE=InnoDB AUTO_INCREMENT=13864 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `multas`
--

DROP TABLE IF EXISTS `multas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multas` (
  `idmulta` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fechamulta` date DEFAULT NULL,
  `dtlleprestamo_id` varchar(27) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `pagado` double DEFAULT NULL,
  PRIMARY KEY (`idmulta`),
  KEY `dev_fk_idx` (`dtlleprestamo_id`),
  CONSTRAINT `fk_dtlle` FOREIGN KEY (`dtlleprestamo_id`) REFERENCES `detalleprestamo` (`iddetalleprestamo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `novedades`
--

DROP TABLE IF EXISTS `novedades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `novedades` (
  `idnovedad` varchar(15) NOT NULL,
  `nadqui` int(11) NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `imagen` varchar(80) NOT NULL,
  PRIMARY KEY (`idnovedad`),
  KEY `fknvnad_idx` (`nadqui`),
  CONSTRAINT `fknvnad` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `curp` varchar(18) NOT NULL,
  `pnombre` varchar(255) DEFAULT NULL,
  `apPat` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `apMat` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `cp` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `fechaNaci` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 NOT NULL,
  `ocupacion` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `tel2` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `nombreaval` varchar(255) DEFAULT NULL,
  `emailaval` varchar(200) DEFAULT NULL,
  `telefonoaval` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`curp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prestamos`
--

DROP TABLE IF EXISTS `prestamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestamos` (
  `idprestamo` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fechaprestamo` datetime NOT NULL,
  `tipo` varchar(4) NOT NULL,
  `cuentausuario` int(6) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`idprestamo`),
  KEY `cunta_fk_idx` (`cuentausuario`),
  CONSTRAINT `fk_user_prest` FOREIGN KEY (`cuentausuario`) REFERENCES `usuariobiblio` (`cuentausuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publica`
--

DROP TABLE IF EXISTS `publica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publica` (
  `idpublica` varchar(13) NOT NULL,
  `editorial_id` int(11) DEFAULT NULL,
  `libro_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpublica`),
  KEY `fkedi_idx` (`editorial_id`),
  KEY `fk_libro_idx` (`libro_id`),
  CONSTRAINT `fk_libro` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`idlibro`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkedi` FOREIGN KEY (`editorial_id`) REFERENCES `editoriales` (`ideditorial`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registroactividadte`
--

DROP TABLE IF EXISTS `registroactividadte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registroactividadte` (
  `idregistroactividadte` varchar(30) CHARACTER SET latin1 NOT NULL,
  `adultos` int(11) DEFAULT '0',
  `jovenes` int(11) DEFAULT '0',
  `ninos` int(11) DEFAULT '0',
  `actividadid` varchar(30) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  PRIMARY KEY (`idregistroactividadte`),
  KEY `fk_actividad_id_idx` (`actividadid`),
  CONSTRAINT `fk_actividad_id` FOREIGN KEY (`actividadid`) REFERENCES `actividades` (`idactividad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reparacion`
--

DROP TABLE IF EXISTS `reparacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reparacion` (
  `idrep` varchar(22) NOT NULL,
  `nadqui` int(11) DEFAULT NULL,
  PRIMARY KEY (`idrep`),
  UNIQUE KEY `nadqui_UNIQUE` (`nadqui`),
  CONSTRAINT `fk_rn` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservas` (
  `idreserva` varchar(60) CHARACTER SET latin1 NOT NULL,
  `fecha` datetime NOT NULL,
  `cuentausuario` int(6) unsigned zerofill NOT NULL,
  `nadqui` int(11) NOT NULL,
  PRIMARY KEY (`idreserva`),
  UNIQUE KEY `nadqui_UNIQUE` (`nadqui`),
  KEY `fk_user_res_idx` (`cuentausuario`),
  CONSTRAINT `ff_res` FOREIGN KEY (`nadqui`) REFERENCES `ejemplar` (`nadqui`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_res` FOREIGN KEY (`cuentausuario`) REFERENCES `usuariobiblio` (`cuentausuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userbloqueados`
--

DROP TABLE IF EXISTS `userbloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userbloqueados` (
  `iduserbloqueado` varchar(17) NOT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `cuentauser` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`iduserbloqueado`),
  UNIQUE KEY `cuentauser_UNIQUE` (`cuentauser`),
  CONSTRAINT `fk_user_bloq` FOREIGN KEY (`cuentauser`) REFERENCES `usuariobiblio` (`cuentausuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `iduser` varchar(25) NOT NULL,
  `idusuario` varchar(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `token` varchar(100) NOT NULL,
  `creado` datetime NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `idusuario_UNIQUE` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuariobiblio`
--

DROP TABLE IF EXISTS `usuariobiblio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuariobiblio` (
  `cuentausuario` int(6) unsigned zerofill NOT NULL,
  `password_d` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pcurp` varchar(18) DEFAULT NULL,
  `intentos` int(2) DEFAULT '0',
  `fechaintento` datetime DEFAULT NULL,
  PRIMARY KEY (`cuentausuario`),
  UNIQUE KEY `cuentausuario_UNIQUE` (`cuentausuario`),
  UNIQUE KEY `pcurp_UNIQUE` (`pcurp`),
  CONSTRAINT `curp_fk` FOREIGN KEY (`pcurp`) REFERENCES `personas` (`curp`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitas` (
  `idvisita` varchar(20) CHARACTER SET latin1 NOT NULL,
  `fechavisita` datetime DEFAULT NULL,
  `edad` varchar(2) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`idvisita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-10 11:44:54
