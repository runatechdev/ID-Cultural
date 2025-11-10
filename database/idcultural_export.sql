/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: idcultural
-- ------------------------------------------------------
-- Server version	10.5.29-MariaDB-ubu2004

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
-- Table structure for table `analytics_busquedas`
--

DROP TABLE IF EXISTS `analytics_busquedas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_busquedas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `termino_busqueda` varchar(255) NOT NULL,
  `resultados_encontrados` int(11) DEFAULT 0,
  `fecha_busqueda` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_termino` (`termino_busqueda`),
  KEY `idx_fecha` (`fecha_busqueda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_busquedas`
--

LOCK TABLES `analytics_busquedas` WRITE;
/*!40000 ALTER TABLE `analytics_busquedas` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_busquedas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_eventos`
--

DROP TABLE IF EXISTS `analytics_eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `categoria` varchar(100) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `etiqueta` varchar(255) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `fecha_evento` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_accion` (`accion`),
  KEY `idx_fecha` (`fecha_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_eventos`
--

LOCK TABLES `analytics_eventos` WRITE;
/*!40000 ALTER TABLE `analytics_eventos` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_visitas`
--

DROP TABLE IF EXISTS `analytics_visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `pagina` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `referrer` varchar(500) DEFAULT NULL,
  `duracion_segundos` int(11) DEFAULT 0,
  `fecha_visita` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pagina` (`pagina`),
  KEY `idx_fecha` (`fecha_visita`),
  KEY `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_visitas`
--

LOCK TABLES `analytics_visitas` WRITE;
/*!40000 ALTER TABLE `analytics_visitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_visitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artistas`
--

DROP TABLE IF EXISTS `artistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `fecha_nacimiento` varchar(20) NOT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'artista',
  `status` varchar(50) NOT NULL DEFAULT 'pendiente',
  `biografia` text DEFAULT NULL,
  `especialidades` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `status_perfil` varchar(20) DEFAULT 'pendiente',
  `motivo_rechazo` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_status_perfil` (`status_perfil`),
  KEY `idx_status_provincia` (`status_perfil`,`provincia`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artistas`
--

LOCK TABLES `artistas` WRITE;
/*!40000 ALTER TABLE `artistas` DISABLE KEYS */;
INSERT INTO `artistas` VALUES (2,'nuevo','nuevo','2000-12-12','femenino','Argentina','Buenos Aires','La Plata','nuevo@gmail.com','$2y$10$7nxg3IMycH8sDjm0RbHDaO3DlYedW8ZOdsX4dXcJ3vV/K9IA.o8rq','artista','rechazado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rechazado','hbgb'),(3,'prueba','prueba','2001-02-21','masculino','Argentina','Buenos Aires','La Plata','prueba@gmail.com','$2y$10$Swtb6xK8KSKsuNXFLfcJtOZPfLgqUKYeWMBDJqVFqCO/c7C8UYDwi','artista','validado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rechazado','jnn'),(5,'Carlos','Gomez','1995-03-15','masculino','Argentina','Santiago del Estero','La Banda','carlos@gmail.com','$2y$10$...','artista','pendiente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'validado',NULL),(6,'Maria','Ledezma','1988-07-22','femenino','Argentina','Santiago del Estero','Termas de Río Hondo','maria@gmail.com','$2y$10$...','artista','rechazado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'validado',NULL),(7,'Marcos','Romano','1999-03-10','masculino','Argentina','Santiago del Estero','Santiago del Estero','ejemplo@ejemplo.com','$2y$10$59P2kCjWBNm4xTkxCzTr9O45Jv5B2e/b2.e5U2R/lvyNX/8wTIhwe','artista','validado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'validado',NULL),(8,'Marcos','Romano','1949-05-15','Masculino','Argentina','Santiago del Estero','Tintina','tralalero@tralala.com','$2y$10$QWpJ6kpCFoLzBNN3TXOP7uqXmpqsL7SsF6XqCKHDrqaukbKJrraLu','artista','validado','Un buen pibe (sera?)','Escultor de carne','@marcos_romano_updated','marcos.romano.updated','@marcos_romano_updated','https://marcos-romano-updated.com','/uploads/imagens/media_690f8d41bdf2f6.84231658.jpeg','pendiente',NULL);
/*!40000 ALTER TABLE `artistas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artistas_famosos`
--

DROP TABLE IF EXISTS `artistas_famosos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artistas_famosos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) NOT NULL,
  `nombre_artistico` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `fecha_fallecimiento` date DEFAULT NULL COMMENT 'NULL si está vivo',
  `lugar_nacimiento` varchar(255) DEFAULT NULL,
  `municipio` varchar(100) NOT NULL DEFAULT 'Santiago del Estero',
  `provincia` varchar(100) NOT NULL DEFAULT 'Santiago del Estero',
  `pais` varchar(100) NOT NULL DEFAULT 'Argentina',
  `categoria` enum('musica','literatura','artes_plasticas','danza','teatro','cine','artesania','folklore') NOT NULL,
  `subcategoria` varchar(100) DEFAULT NULL COMMENT 'Ej: Chacarera, Pintura, Escultura, etc',
  `biografia` text NOT NULL,
  `logros_premios` text DEFAULT NULL COMMENT 'Premios y reconocimientos',
  `obras_destacadas` text DEFAULT NULL COMMENT 'Lista de obras principales',
  `foto_perfil` varchar(255) DEFAULT NULL,
  `foto_galeria` text DEFAULT NULL COMMENT 'JSON con array de fotos',
  `videos_youtube` text DEFAULT NULL COMMENT 'JSON con links de YouTube',
  `sitio_web` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `wikipedia_url` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1 COMMENT 'Para mostrar/ocultar',
  `destacado` tinyint(1) DEFAULT 0 COMMENT 'Para artistas más importantes',
  `orden_visualizacion` int(11) DEFAULT 0 COMMENT 'Para ordenar en la wiki',
  `visitas` int(11) DEFAULT 0 COMMENT 'Contador de visitas al perfil',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creado_por` int(11) DEFAULT NULL COMMENT 'ID del admin que lo creó',
  `notas_admin` text DEFAULT NULL COMMENT 'Notas internas',
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_municipio` (`municipio`),
  KEY `idx_destacado` (`destacado`),
  KEY `idx_activo` (`activo`),
  KEY `idx_orden` (`orden_visualizacion`),
  KEY `idx_categoria_activo` (`categoria`,`activo`),
  KEY `idx_destacado_orden` (`destacado`,`orden_visualizacion`),
  FULLTEXT KEY `idx_busqueda_nombre` (`nombre_completo`,`nombre_artistico`,`biografia`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artistas_famosos`
--

LOCK TABLES `artistas_famosos` WRITE;
/*!40000 ALTER TABLE `artistas_famosos` DISABLE KEYS */;
INSERT INTO `artistas_famosos` VALUES (1,'Andrés Chazarreta','Don Andrés','1876-05-16','1960-10-24','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore','Músico, compositor y folclorista argentino. Considerado el \"Patriarca del Folklore Argentino\". Fue el primero en llevar música folklórica argentina a Buenos Aires en 1911, revolucionando la cultura musical del país. Recopiló y difundió la música tradicional santiagueña.','Pionero del folklore argentino. Fundó la primera orquesta de música nativa. Reconocido como Padre del Folklore Nacional.','La Telesita, Añoranzas, Zamba de Vargas, Chacarera del Rancho, El Crespín',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(2,'Mercedes Sosa','La Negra','1935-07-09','2009-10-04','San Miguel de Tucumán','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Nuevo Cancionero','Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano. Aunque nació en Tucumán, gran parte de su familia era santiagueña y su repertorio incluyó muchas obras del folklore de Santiago del Estero. Icono de la música argentina y latinoamericana.','6 Premios Grammy Latino. Premio Konex de Platino. Declarada Ciudadana Ilustre de América. Doctorado Honoris Causa.','Gracias a la Vida, Alfonsina y el Mar, Todo Cambia, Balderrama, Zamba para no morir, Como la cigarra',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,2,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(3,'Jacinto Piedra',NULL,'1920-03-15','2007-05-10','Loreto','Loreto','Santiago del Estero','Argentina','musica','Chacarera','Músico y compositor santiagueño, especializado en chacarera. Uno de los grandes exponentes del folklore de Santiago del Estero. Su música representa la esencia de la cultura rural santiagueña.','Reconocido por su virtuosismo en la guitarra y bombo. Cosechó múltiples premios en festivales folklóricos.','Chacarera del Rancho, La Arunguita, Nostalgias Santiagueñas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,3,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(4,'Raly Barrionuevo',NULL,'1967-12-14',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore Contemporáneo','Cantautor argentino, exponente del folklore contemporáneo. Su música combina tradición santiagueña con elementos modernos. Uno de los artistas más reconocidos del nuevo folklore argentino.','Múltiples Premios Gardel. Disco de Oro. Reconocido internacionalmente.','Zamba del Laurel, Mariana, Melodía del Atardecer, Canto Versos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,4,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(5,'Dúo Coplanacu',NULL,'1975-01-01',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Copla','Dúo musical formado por Jorge Fandermole y Roxana Carabajal. Representan la copla y el folklore santiagueño con excelencia artística.','Premios Gardel. Reconocimiento Nacional e Internacional.','Coplas de mi País, Zamba Azul, La Pomeña',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,5,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(6,'Juan Carlos Dávalos',NULL,'1887-08-07','1959-12-06','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','literatura','Narrativa/Poesía','Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino, especialmente Santiago del Estero. Considerado uno de los grandes escritores regionalistas.','Premio Nacional de Literatura. Sus cuentos son clásicos de la literatura argentina.','Los Buscadores de Oro, Airampo, La Flor del Cardón, Salta',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,6,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(7,'Bernardo Canal Feijóo',NULL,'1897-09-04','1982-01-13','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','literatura','Ensayo/Historia','Escritor, ensayista, historiador y pensador argentino. Estudioso profundo de la cultura santiagueña y del noroeste argentino. Figura fundamental del pensamiento regionalista.','Doctor Honoris Causa. Premio Konex. Reconocido como intelectual fundamental del NOA.','Ensayo sobre la Expresión Popular Artística en Santiago, Burla, Credo, Culpa en la Creación Anónima',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,7,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(8,'Alfredo Gogna',NULL,'1878-01-15','1972-03-20','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','artes_plasticas','Pintura','Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero. Pionero de las artes plásticas en la provincia.','Exposiciones nacionales e internacionales. Reconocido maestro de la pintura regional.','Paisajes Santiagueños, Serie del Monte, Retratos de Gauchos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,8,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(9,'Ricardo y Francisco Sola',NULL,'1940-05-10',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','artes_plasticas','Escultura','Hermanos escultores reconocidos por sus obras monumentales en Santiago del Estero y Argentina.','Múltiples obras públicas y monumentos en la provincia.','Monumento a la Madre, Cristo del Cerro, Diversas esculturas urbanas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,9,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(10,'Los Manseros Santiagueños',NULL,'1950-01-01',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Conjunto folklórico emblemático de Santiago del Estero. Formado en 1950, llevan décadas difundiendo la cultura santiagueña.','Referentes del folklore santiagueño. Múltiples discos y presentaciones internacionales.','Pa\' Santiago me Voy, Chacarera de un Triste, La Telesita',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,10,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL),(11,'Horacio Banegas',NULL,'1946-06-15','2021-02-11','Fernández','Fernández','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Músico, compositor y poeta santiagueño. Figura fundamental del folklore argentino. Sus letras son poesía pura del monte santiagueño.','Considerado uno de los mejores letristas del folklore argentino. Cosechó innumerables premios.','La Olvidada, Zamba del Quebrachal, De Puro Guapo, El Antigal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,11,0,'2025-11-10 04:31:14','2025-11-10 04:31:14',NULL,NULL);
/*!40000 ALTER TABLE `artistas_famosos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `artistas_famosos_con_edad`
--

DROP TABLE IF EXISTS `artistas_famosos_con_edad`;
/*!50001 DROP VIEW IF EXISTS `artistas_famosos_con_edad`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `artistas_famosos_con_edad` AS SELECT
 1 AS `id`,
  1 AS `nombre_completo`,
  1 AS `nombre_artistico`,
  1 AS `fecha_nacimiento`,
  1 AS `fecha_fallecimiento`,
  1 AS `lugar_nacimiento`,
  1 AS `municipio`,
  1 AS `provincia`,
  1 AS `pais`,
  1 AS `categoria`,
  1 AS `subcategoria`,
  1 AS `biografia`,
  1 AS `logros_premios`,
  1 AS `obras_destacadas`,
  1 AS `foto_perfil`,
  1 AS `foto_galeria`,
  1 AS `videos_youtube`,
  1 AS `sitio_web`,
  1 AS `instagram`,
  1 AS `facebook`,
  1 AS `twitter`,
  1 AS `wikipedia_url`,
  1 AS `activo`,
  1 AS `destacado`,
  1 AS `orden_visualizacion`,
  1 AS `visitas`,
  1 AS `fecha_creacion`,
  1 AS `fecha_actualizacion`,
  1 AS `creado_por`,
  1 AS `notas_admin`,
  1 AS `edad`,
  1 AS `estado_vital` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `artistas_famosos_destacados`
--

DROP TABLE IF EXISTS `artistas_famosos_destacados`;
/*!50001 DROP VIEW IF EXISTS `artistas_famosos_destacados`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `artistas_famosos_destacados` AS SELECT
 1 AS `id`,
  1 AS `nombre_completo`,
  1 AS `nombre_artistico`,
  1 AS `fecha_nacimiento`,
  1 AS `fecha_fallecimiento`,
  1 AS `lugar_nacimiento`,
  1 AS `municipio`,
  1 AS `provincia`,
  1 AS `pais`,
  1 AS `categoria`,
  1 AS `subcategoria`,
  1 AS `biografia`,
  1 AS `logros_premios`,
  1 AS `obras_destacadas`,
  1 AS `foto_perfil`,
  1 AS `foto_galeria`,
  1 AS `videos_youtube`,
  1 AS `sitio_web`,
  1 AS `instagram`,
  1 AS `facebook`,
  1 AS `twitter`,
  1 AS `wikipedia_url`,
  1 AS `activo`,
  1 AS `destacado`,
  1 AS `orden_visualizacion`,
  1 AS `visitas`,
  1 AS `fecha_creacion`,
  1 AS `fecha_actualizacion`,
  1 AS `creado_por`,
  1 AS `notas_admin` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `artistas_famosos_vivos`
--

DROP TABLE IF EXISTS `artistas_famosos_vivos`;
/*!50001 DROP VIEW IF EXISTS `artistas_famosos_vivos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `artistas_famosos_vivos` AS SELECT
 1 AS `id`,
  1 AS `nombre_completo`,
  1 AS `nombre_artistico`,
  1 AS `fecha_nacimiento`,
  1 AS `fecha_fallecimiento`,
  1 AS `lugar_nacimiento`,
  1 AS `municipio`,
  1 AS `provincia`,
  1 AS `pais`,
  1 AS `categoria`,
  1 AS `subcategoria`,
  1 AS `biografia`,
  1 AS `logros_premios`,
  1 AS `obras_destacadas`,
  1 AS `foto_perfil`,
  1 AS `foto_galeria`,
  1 AS `videos_youtube`,
  1 AS `sitio_web`,
  1 AS `instagram`,
  1 AS `facebook`,
  1 AS `twitter`,
  1 AS `wikipedia_url`,
  1 AS `activo`,
  1 AS `destacado`,
  1 AS `orden_visualizacion`,
  1 AS `visitas`,
  1 AS `fecha_creacion`,
  1 AS `fecha_actualizacion`,
  1 AS `creado_por`,
  1 AS `notas_admin` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `intereses_artista`
--

DROP TABLE IF EXISTS `intereses_artista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `intereses_artista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artista_id` int(11) DEFAULT NULL,
  `interes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artista_id` (`artista_id`),
  CONSTRAINT `intereses_artista_ibfk_1` FOREIGN KEY (`artista_id`) REFERENCES `artistas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intereses_artista`
--

LOCK TABLES `intereses_artista` WRITE;
/*!40000 ALTER TABLE `intereses_artista` DISABLE KEYS */;
INSERT INTO `intereses_artista` VALUES (1,7,'musica'),(2,7,'artes_visuales'),(3,8,'musica'),(4,8,'danza'),(5,8,'teatro');
/*!40000 ALTER TABLE `intereses_artista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_validacion_perfiles`
--

DROP TABLE IF EXISTS `logs_validacion_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs_validacion_perfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artista_id` int(11) NOT NULL,
  `validador_id` int(11) DEFAULT NULL,
  `accion` varchar(20) NOT NULL,
  `motivo_rechazo` text DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_artista_id` (`artista_id`),
  KEY `idx_validador_id` (`validador_id`),
  KEY `idx_fecha_accion` (`fecha_accion`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_validacion_perfiles`
--

LOCK TABLES `logs_validacion_perfiles` WRITE;
/*!40000 ALTER TABLE `logs_validacion_perfiles` DISABLE KEYS */;
INSERT INTO `logs_validacion_perfiles` VALUES (1,8,1,'validar',NULL,'2025-11-09 00:10:27'),(2,7,1,'validar',NULL,'2025-11-09 00:21:06'),(3,6,1,'rechazar','lo','2025-11-09 00:24:58'),(4,6,1,'validar',NULL,'2025-11-09 00:25:02'),(5,5,1,'validar',NULL,'2025-11-09 00:26:54'),(6,3,1,'rechazar','jnn','2025-11-09 14:26:36'),(7,2,1,'rechazar','hbgb','2025-11-09 14:26:43'),(8,8,1,'validar',NULL,'2025-11-10 00:40:48');
/*!40000 ALTER TABLE `logs_validacion_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `editor_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `editor_id` (`editor_id`),
  CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`editor_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
INSERT INTO `noticias` VALUES (1,'¡Gran Apertura del Festival de Arte!','Este fin de semana se celebra el festival anual de arte con más de 50 artistas locales...','http://localhost:8080/static/uploads/noticias/noticia_1761572259_68ff75a35a30c.jpeg',2,'2025-08-14 19:38:03'),(7,'Evento','Test','http://localhost:8080/static/uploads/noticias/noticia_1761540083_68fef7f38e1ad.jpeg',2,'2025-10-27 04:31:57');
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('info','success','warning','error') DEFAULT 'info',
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) DEFAULT 0,
  `url_accion` varchar(500) DEFAULT NULL,
  `datos_adicionales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_adicionales`)),
  `fecha_lectura` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_leida` (`leida`),
  KEY `idx_fecha` (`created_at`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_usuario_usado` (`usuario_id`,`usado`),
  KEY `idx_fecha_expiracion` (`fecha_expiracion`),
  CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `artistas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferencias_notificaciones`
--

DROP TABLE IF EXISTS `preferencias_notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferencias_notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `notificaciones_email` tinyint(1) DEFAULT 1,
  `notificaciones_perfil` tinyint(1) DEFAULT 1,
  `notificaciones_validacion` tinyint(1) DEFAULT 1,
  `notificaciones_comentarios` tinyint(1) DEFAULT 1,
  `notificaciones_mensajes` tinyint(1) DEFAULT 1,
  `frecuencia_email` enum('inmediato','diario','semanal','nunca') DEFAULT 'diario',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `preferencias_notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferencias_notificaciones`
--

LOCK TABLES `preferencias_notificaciones` WRITE;
/*!40000 ALTER TABLE `preferencias_notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `preferencias_notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicaciones`
--

DROP TABLE IF EXISTS `publicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `campos_extra` text DEFAULT NULL,
  `multimedia` text DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'borrador',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_envio_validacion` timestamp NULL DEFAULT NULL,
  `validador_id` int(11) DEFAULT NULL,
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `validador_id` (`validador_id`),
  CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `artistas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `publicaciones_ibfk_2` FOREIGN KEY (`validador_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicaciones`
--

LOCK TABLES `publicaciones` WRITE;
/*!40000 ALTER TABLE `publicaciones` DISABLE KEYS */;
INSERT INTO `publicaciones` VALUES (5,8,'sdsada','dsasadd','literatura','{\"action\":\"save\",\"genero-lit\":\"sdad\",\"editorial\":\"aasdsad\"}',NULL,'validado','2025-11-08 21:55:42','2025-11-08 21:55:42',3,'2025-11-08 22:06:24'),(6,8,'aaaaaaaaaaa','aaaaaaaaaaaaaaa','musica','{\"action\":\"save\",\"plataformas\":\"aaaa\",\"sello\":\"aaaaa\"}','/uploads/imagens/media_690fc13a934708.23834792.jpeg','validado','2025-11-08 22:16:26','2025-11-08 22:16:26',3,'2025-11-08 22:17:00'),(7,8,'sdasdadad','asdasdasdsadad','musica','{\"action\":\"save\",\"plataformas\":\"asdasdsad\",\"sello\":\"dasdsad\"}',NULL,'pendiente_validacion','2025-11-10 00:02:00','2025-11-10 00:07:01',NULL,NULL);
/*!40000 ALTER TABLE `publicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_content`
--

DROP TABLE IF EXISTS `site_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_key` varchar(100) NOT NULL,
  `content_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_key` (`content_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_content`
--

LOCK TABLES `site_content` WRITE;
/*!40000 ALTER TABLE `site_content` DISABLE KEYS */;
INSERT INTO `site_content` VALUES (1,'welcome_title','Bienvenidos a ID Cultural'),(2,'welcome_paragraph','<strong>ID Cultural</strong> es una plataforma digital dedicada a visibilizar, preservar y promover la identidad artística y cultural de Santiago del Estero. Te invitamos a explorar, descubrir y formar parte de este espacio pensado para fortalecer nuestras raíces.'),(3,'welcome_slogan','La identidad de un pueblo, en un solo lugar.'),(4,'carousel_image_1','https://placehold.co/1200x450/367789/FFFFFF?text=Cultura+Santiagueña'),(5,'carousel_image_2','https://placehold.co/1200x450/C30135/FFFFFF?text=Nuestros+Artistas'),(6,'carousel_image_3','https://placehold.co/1200x450/efc892/333333?text=Biblioteca+Digital');
/*!40000 ALTER TABLE `site_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
INSERT INTO `system_logs` VALUES (1,1,'Administrador Principal','INICIO DE SESIÓN','El usuario ha iniciado sesión correctamente.','2025-08-14 18:21:33'),(2,2,'Editor de Contenidos','CREACIÓN DE NOTICIA','Se ha creado la noticia con ID: 101.','2025-08-14 18:21:33'),(3,3,'Validador de Artistas','VALIDACIÓN DE ARTISTA','Se ha aprobado la solicitud del artista con ID: 1. Comentario: Excelente portfolio.','2025-08-14 18:21:33'),(4,3,'Validador de Artistas','RECHAZO DE ARTISTA','Se ha rechazado la solicitud del artista con ID: 2. Motivo: Faltan referencias comprobables.','2025-08-14 18:21:33'),(5,1,'Usuario Desconocido','VALIDACIÓN DE ARTISTA','Se ha validado la solicitud con ID: 1.','2025-08-14 18:39:49'),(6,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 1 a validado.','2025-08-14 19:15:57'),(7,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 4 a validado. Comentario: buen pibe','2025-08-14 19:21:30'),(8,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 3 a validado.','2025-08-14 19:32:05'),(9,1,'Admin','RECHAZO DE ARTISTA','Se ha cambiado el estado del artista ID: 2 a rechazado. Motivo: ninguna cancion es tuya','2025-08-14 19:32:25'),(10,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 7 a validado.','2025-10-27 05:21:21'),(11,3,' ','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 2 del artista \'marcos romano\' (ID: 8) ha sido validada.','2025-11-08 03:52:07'),(12,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 3 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 21:08:42'),(13,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 5 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 22:06:24'),(14,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 6 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 22:17:00');
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador Principal','admin@idcultural.com','$2y$10$cv2EG9pZ/4y1H.z.QztN.OuGTO9x8resRsMrnJxdaKFPqreWtndf6','admin'),(2,'Editor de Contenidos','editor@idcultural.com','$2y$10$9/iW1.fVT0I8E2PiYzNGv.q5AKtnboEwl4rBAHuMgV2rVcDW6wd6W','editor'),(3,'Validador de Artistas','validador@idcultural.com','$2y$10$SFb4oh3S6IiTZ/LFr/e20uVodzb7n9u/I5OQu11A8AtoUNzns5QHW','validador');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `artistas_famosos_con_edad`
--

/*!50001 DROP VIEW IF EXISTS `artistas_famosos_con_edad`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`runatechdev`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `artistas_famosos_con_edad` AS select `artistas_famosos`.`id` AS `id`,`artistas_famosos`.`nombre_completo` AS `nombre_completo`,`artistas_famosos`.`nombre_artistico` AS `nombre_artistico`,`artistas_famosos`.`fecha_nacimiento` AS `fecha_nacimiento`,`artistas_famosos`.`fecha_fallecimiento` AS `fecha_fallecimiento`,`artistas_famosos`.`lugar_nacimiento` AS `lugar_nacimiento`,`artistas_famosos`.`municipio` AS `municipio`,`artistas_famosos`.`provincia` AS `provincia`,`artistas_famosos`.`pais` AS `pais`,`artistas_famosos`.`categoria` AS `categoria`,`artistas_famosos`.`subcategoria` AS `subcategoria`,`artistas_famosos`.`biografia` AS `biografia`,`artistas_famosos`.`logros_premios` AS `logros_premios`,`artistas_famosos`.`obras_destacadas` AS `obras_destacadas`,`artistas_famosos`.`foto_perfil` AS `foto_perfil`,`artistas_famosos`.`foto_galeria` AS `foto_galeria`,`artistas_famosos`.`videos_youtube` AS `videos_youtube`,`artistas_famosos`.`sitio_web` AS `sitio_web`,`artistas_famosos`.`instagram` AS `instagram`,`artistas_famosos`.`facebook` AS `facebook`,`artistas_famosos`.`twitter` AS `twitter`,`artistas_famosos`.`wikipedia_url` AS `wikipedia_url`,`artistas_famosos`.`activo` AS `activo`,`artistas_famosos`.`destacado` AS `destacado`,`artistas_famosos`.`orden_visualizacion` AS `orden_visualizacion`,`artistas_famosos`.`visitas` AS `visitas`,`artistas_famosos`.`fecha_creacion` AS `fecha_creacion`,`artistas_famosos`.`fecha_actualizacion` AS `fecha_actualizacion`,`artistas_famosos`.`creado_por` AS `creado_por`,`artistas_famosos`.`notas_admin` AS `notas_admin`,case when `artistas_famosos`.`fecha_fallecimiento` is null then timestampdiff(YEAR,`artistas_famosos`.`fecha_nacimiento`,curdate()) else timestampdiff(YEAR,`artistas_famosos`.`fecha_nacimiento`,`artistas_famosos`.`fecha_fallecimiento`) end AS `edad`,case when `artistas_famosos`.`fecha_fallecimiento` is null then 'Vivo' else concat('Fallecido hace ',timestampdiff(YEAR,`artistas_famosos`.`fecha_fallecimiento`,curdate()),' años') end AS `estado_vital` from `artistas_famosos` where `artistas_famosos`.`activo` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `artistas_famosos_destacados`
--

/*!50001 DROP VIEW IF EXISTS `artistas_famosos_destacados`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`runatechdev`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `artistas_famosos_destacados` AS select `artistas_famosos`.`id` AS `id`,`artistas_famosos`.`nombre_completo` AS `nombre_completo`,`artistas_famosos`.`nombre_artistico` AS `nombre_artistico`,`artistas_famosos`.`fecha_nacimiento` AS `fecha_nacimiento`,`artistas_famosos`.`fecha_fallecimiento` AS `fecha_fallecimiento`,`artistas_famosos`.`lugar_nacimiento` AS `lugar_nacimiento`,`artistas_famosos`.`municipio` AS `municipio`,`artistas_famosos`.`provincia` AS `provincia`,`artistas_famosos`.`pais` AS `pais`,`artistas_famosos`.`categoria` AS `categoria`,`artistas_famosos`.`subcategoria` AS `subcategoria`,`artistas_famosos`.`biografia` AS `biografia`,`artistas_famosos`.`logros_premios` AS `logros_premios`,`artistas_famosos`.`obras_destacadas` AS `obras_destacadas`,`artistas_famosos`.`foto_perfil` AS `foto_perfil`,`artistas_famosos`.`foto_galeria` AS `foto_galeria`,`artistas_famosos`.`videos_youtube` AS `videos_youtube`,`artistas_famosos`.`sitio_web` AS `sitio_web`,`artistas_famosos`.`instagram` AS `instagram`,`artistas_famosos`.`facebook` AS `facebook`,`artistas_famosos`.`twitter` AS `twitter`,`artistas_famosos`.`wikipedia_url` AS `wikipedia_url`,`artistas_famosos`.`activo` AS `activo`,`artistas_famosos`.`destacado` AS `destacado`,`artistas_famosos`.`orden_visualizacion` AS `orden_visualizacion`,`artistas_famosos`.`visitas` AS `visitas`,`artistas_famosos`.`fecha_creacion` AS `fecha_creacion`,`artistas_famosos`.`fecha_actualizacion` AS `fecha_actualizacion`,`artistas_famosos`.`creado_por` AS `creado_por`,`artistas_famosos`.`notas_admin` AS `notas_admin` from `artistas_famosos` where `artistas_famosos`.`destacado` = 1 and `artistas_famosos`.`activo` = 1 order by `artistas_famosos`.`orden_visualizacion` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `artistas_famosos_vivos`
--

/*!50001 DROP VIEW IF EXISTS `artistas_famosos_vivos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`runatechdev`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `artistas_famosos_vivos` AS select `artistas_famosos`.`id` AS `id`,`artistas_famosos`.`nombre_completo` AS `nombre_completo`,`artistas_famosos`.`nombre_artistico` AS `nombre_artistico`,`artistas_famosos`.`fecha_nacimiento` AS `fecha_nacimiento`,`artistas_famosos`.`fecha_fallecimiento` AS `fecha_fallecimiento`,`artistas_famosos`.`lugar_nacimiento` AS `lugar_nacimiento`,`artistas_famosos`.`municipio` AS `municipio`,`artistas_famosos`.`provincia` AS `provincia`,`artistas_famosos`.`pais` AS `pais`,`artistas_famosos`.`categoria` AS `categoria`,`artistas_famosos`.`subcategoria` AS `subcategoria`,`artistas_famosos`.`biografia` AS `biografia`,`artistas_famosos`.`logros_premios` AS `logros_premios`,`artistas_famosos`.`obras_destacadas` AS `obras_destacadas`,`artistas_famosos`.`foto_perfil` AS `foto_perfil`,`artistas_famosos`.`foto_galeria` AS `foto_galeria`,`artistas_famosos`.`videos_youtube` AS `videos_youtube`,`artistas_famosos`.`sitio_web` AS `sitio_web`,`artistas_famosos`.`instagram` AS `instagram`,`artistas_famosos`.`facebook` AS `facebook`,`artistas_famosos`.`twitter` AS `twitter`,`artistas_famosos`.`wikipedia_url` AS `wikipedia_url`,`artistas_famosos`.`activo` AS `activo`,`artistas_famosos`.`destacado` AS `destacado`,`artistas_famosos`.`orden_visualizacion` AS `orden_visualizacion`,`artistas_famosos`.`visitas` AS `visitas`,`artistas_famosos`.`fecha_creacion` AS `fecha_creacion`,`artistas_famosos`.`fecha_actualizacion` AS `fecha_actualizacion`,`artistas_famosos`.`creado_por` AS `creado_por`,`artistas_famosos`.`notas_admin` AS `notas_admin` from `artistas_famosos` where `artistas_famosos`.`fecha_fallecimiento` is null and `artistas_famosos`.`activo` = 1 order by `artistas_famosos`.`orden_visualizacion` */;
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

-- Dump completed on 2025-11-10  4:32:54
