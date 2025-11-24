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
  `telefono` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `status_perfil` varchar(20) DEFAULT 'pendiente',
  `motivo_rechazo` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_status_perfil` (`status_perfil`),
  KEY `idx_status_provincia` (`status_perfil`,`provincia`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artistas`
--

LOCK TABLES `artistas` WRITE;
/*!40000 ALTER TABLE `artistas` DISABLE KEYS */;
INSERT INTO `artistas` VALUES (9,'Omar Emilio','Antonio','2000-12-12','otro','Argentina','Santiago del Estero','Santiago del Estero','coo@gmail.com','$2y$10$aM1Pg3YXMYWLoADGREByke8gAnzgQCc8W4VYf5Fgv4RVPTdx.YhV.','artista','validado','Coo, el personaje que fue símbolo, escándalo y leyenda urbana','Musica','','','','https://youtu.be/sCnCb_c8b9k?si=cIpoOHrB0t7M2-YR',NULL,'','/uploads/imagenes/media_6921dd38853ac1.93522221.jpg','validado',NULL),(10,'Leopoldo Dante','Tevez','','masculino','Argentina','Santiago del Estero','Frías','leodan@gmail.com','$2y$10$JnmPx1tV4cmzP/wxEu9XnOjFE65MtO6Aip9zR7NLMp1TzKBwbxL12','artista','validado','Conocido como Leo Dan, fue un cantante, compositor y actor argentino.[','Musica','','','','',NULL,NULL,'/uploads/imagenes/media_691fa18a673b51.97776960.jpeg','validado',NULL),(12,'Horacio','Sequeira','1980-12-12','Masculino','Argentina','Santiago del Estero','Santiago del Estero','vasco@gmail.com','$2y$10$rd0yNqIujhjPt8cCv3bj1uxQvmN3iKY8mF/C2PkEQY1tQD3tUmVxW','artista','validado','Escultor, pintor y letrista creativo, autodidacta y multifacético. Su obra se caracteriza por la búsqueda constante de nuevas formas de expresión, explorando materiales, colores y palabras con la misma intensidad. Como escultor, transforma la materia en símbolos de identidad y memoria; como pintor, plasma emociones y paisajes interiores con una paleta vibrante; y como letrista, convierte experiencias en versos que dialogan con la música y la vida cotidiana.\r\nAutodidacta por convicción, ha forjado un estilo propio que se nutre de la experimentación y la curiosidad. Su carácter multifacético le permite transitar con naturalidad entre disciplinas, creando un universo artístico integral donde cada pieza, cada trazo y cada palabra se complementan.','Escultura','','https://www.facebook.com/horaciovictororlando.sequeira.3?locale=es_LA','','https://www.youtube.com/@Ni10NiFran','+54 9 385 613-8390','+54 9 385 613-8390','/uploads/imagenes/media_6920d18472d781.29826074.jpg','validado',NULL),(13,'Sebastian','Ruiz','1982-09-04','Masculino','Argentina','Santiago del Estero','La Banda','sebastianruizpui@gmail.com','$2y$10$yJT.72Itovd4GsatFnItZ.0DKjSHnNUhdxumpK3oeA8mgcRR4ujsG','artista','validado','Sebastián Ruiz nació en La Banda, Santiago del Estero, en 1987. Desde pequeño mostró pasión por la música popular y el folclore, aprendiendo a tocar la guitarra en reuniones familiares. Con el tiempo se convirtió en un referente local, llevando la chacarera y la zamba a escenarios provinciales y nacionales.\r\n\r\nAdemás de músico, Sebastián es docente en una escuela secundaria de su ciudad, donde transmite a sus alumnos el amor por las raíces culturales santiagueñas. Su obra se caracteriza por unir tradición y modernidad, con letras que hablan de la familia, la tierra y la esperanza.\r\n\r\nHoy continúa viviendo en La Banda, donde organiza peñas y talleres comunitarios, convencido de que la música es puente de identidad y unión para su pueblo.','Musica','','','','https://srdev-portafolio.netlify.app/','','','/uploads/imagenes/media_692205b2b1c126.45591009.jpg','validado',NULL),(14,'Thomas Nicolás','Tobar','2000-05-20','masculino','Argentina','Santiago del Estero','La Banda','rusher@gmail.com','$2y$10$Aj141RShjAWFi0ejYUW7Oe4uEv9rbMpO./LTR0xgX0dt0lYdwKK6q','artista','validado','Thomas Nicolás Tobar nació en la ciudad de Santiago del Estero, Argentina,[8]​ el 20 de mayo del año 2000.[9]​ Es hijo de un padre taxista y una madre ama de casa.[10]​ Creció y vivió en la ciudad de La Banda.[10]​ Su interés por el rap y el reguetón nació a los 15 años, edad con la cual comenzó a participar de varias competencias de freestyle en las plazas de su ciudad natal.[11]​[12]​ Su nombre artístico surgió cuando eligió el término «Rusher» seguida de la palabra en inglés «King» —en español: Rey— para su usuario en el videojuego Counter-Strike y con el cual también decidió inscribirse en las competencias de freestyle.[13]​','Musica','','https://open.spotify.com/intl-es/artist/3Apb2lGmGJaBmr0TTBJvIZ','','',NULL,NULL,'/uploads/imagenes/media_69212e2aeb1e93.94811452.webp','validado',NULL),(15,'Juan','Saavedra','1943-09-26','masculino','Argentina','Santiago del Estero','Santiago del Estero','juansaavedra@gmail.com','$2y$10$R3sNayxkrWQ4MRusm9PaHevXUShX1KfWVz.eCHnvVUd522615fhl.','artista','validado','\"El Bailarín de los Montes\", es un icónico bailarín, zapateador y coreógrafo santiagueño nacido en 1943, famoso por innovar la danza folklórica argentina. Su carrera incluye giras mundiales con grupos como el Ballet de Santiago Ayala y la compañía de Mercedes Sosa, la formación del trío \"Los Santiagueños\" con Peteco Carabajal y Jacinto Piedra, y la creación de proyectos como \"Los Indianos en París\". Hoy en día, continúa difundiendo la cultura santiagueña a través de seminarios y festivales en todo el país. ','Danza','','','','',NULL,'3855123456','/uploads/imagenes/media_6921a171db71a7.44890802.jpeg','validado',NULL),(16,'Sandra Soledad','Sanchez','1980-11-26','femenino','Argentina','Santiago del Estero','Santiago del Estero','solss2611@gmail.com','$2y$10$HLxc0DBxcNKifcBwHEqOmO4/bBcMK22WkwZLnSj2atqrdv6D7bMwy','artista','validado','Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','https://sandra-s-portfolio.vercel.app/',NULL,'3855075058','/uploads/imagenes/media_692221307e6499.35498737.jpg','validado',NULL),(17,'Virginia','Ledesma','1988-04-11','Femenino','Argentina','Santiago del Estero','Santiago del Estero','virgiartista@gmail.com','$2y$10$jeuT5N47u3vMTnLMT0544O4MqxK67Swvtc1GoB1ho19KVzedNmALK','artista','validado','','Danza','','','','','3854207523','','/uploads/imagenes/media_6921d4887c6677.81345064.webp','validado',NULL),(19,'«El Indio» Froilán','González','1951-08-25','masculino','Argentina','Santiago del Estero','Santiago del Estero','froilan@gmail.com','$2y$10$fGD7vMz8oUnc9MF4liRxZuncyxmOi1WzkqPGIKwajwxrZx21kk/n.','artista','validado','Este artesano del bombo, con más de 50 años de trayectoria, ha llevado su arte a niveles extraordinarios, convirtiéndose en el fabricante de instrumentos elegido por artistas nacionales e internacionales. Nombres como Shakira y el prestigioso Cirque Du Soleil han confiado en las manos expertas de Froilán para la confección de sus bombos.\r\nEl taller de Froilán se encuentra en un lugar tan singular como él: «La Boca del Tigre», ubicado al norte de la ciudad capital de Santiago del Estero. \r\nEste espacio no es solo un taller, sino un santuario de la música y la cultura. Todos los domingos, «La Boca del Tigre» se transforma en un punto de encuentro para artistas que se reúnen a cantar y tocar, creando un ambiente mágico que atrae a un público diverso, incluyendo a numerosos turistas que no quieren perderse estos vibrantes encuentros de música y danza.','Artesania','','','','',NULL,'3855012345','/uploads/imagenes/media_6921d4c5cf6b27.13238659.jpg','validado',NULL),(20,'MARCOS','ROMANO','1985-05-12','Masculino','Argentina','Santiago del Estero','Otro','marcosariel_romano@yahoo.com','$2y$10$dEdKIAtRUOGBt1fTm.DGxeToM3Yw4AzHqtFv/6vK.HrX.lpbzZTF6','artista','validado','','Artesania','','','','','3856253265','','/uploads/imagenes/media_69222a18c84833.42003318.jpg','validado',NULL),(22,'Lucas','Corbalan','2001-10-12','Masculino','Argentina','Santiago del Estero','Santiago del Estero','lukskpo11@gmail.com','$2y$10$bS5oFs4hSvSQLtrAlQMwSOB9yvhy13VyYBkSKL7oqm1pCLgGQ3lI.','artista','validado','','Audiovisual','','','','','','','/uploads/imagenes/media_6921e4b71a8516.43758066.jpeg','validado',NULL),(24,'Lohana','Corbalan','2016-03-07','femenino','Argentina','Santiago del Estero','Santiago del Estero','lohanacor@gmail.com','$2y$10$FkKQK/F1oyMEoNgRCBPawOnJp/QdyjtoOUszIo7cYYlloT4nYwvMS','artista','validado','','Audiovisual','','','','',NULL,'','/uploads/imagenes/media_6921f19eb73472.32574755.jpg','validado',NULL),(25,'ariel','enriquez','2000-01-01','Prefiero no especificar','Argentina','Santiago del Estero','Sumampa','arielartista@gmail.com','$2y$10$RQaEQlIrg9nVEPJOmME3SOv2XZOM.eVOihGqxJViFbzpnlwqNMcWy','artista','validado','','Teatro','arielartistaok','','','','3854776898','','/uploads/imagenes/media_6921f5e555ba84.92771830.webp','validado',NULL),(26,'Coraje','Abalos','1972-08-16','masculino','Argentina','Santiago del Estero','Santiago del Estero','coraje@gmail.com','$2y$10$Of.Rw.Ne2pytUZjV25iLHuXRjY3H0I42bz5CidlpxHCYShVtfzlme','artista','validado','Debutó en 1992 en el programa Jugate conmigo conducido y producido por Cris Morena. Luego actuó en las telenovelas Quereme, La hermana mayor y 90 60 90 modelos, esta última fue uno de sus mayores éxitos televisivos interpretando al galán de Natalia Oreiro.\r\nContinuo actuando en ficciones como RRDT, Son o se hacen y Drácula, y retomó su carrera de actor en 2003 en Son amores. Más tarde llegaron otros personajes en Mosca & Smith, Soy tu fan y Champs 12, y participaciones especiales en Ciega a citas, Un año para recordar, Herencia de amor, El hombre de tu vida, Historia clínica y Vecinos en guerra.\r\nEn 2007 debuta en teatro en la obra Extraña pareja, protagonizado por Carlin Calvo y Pablo Rago. En cine actuó en las películas Bacanal, en el año 1999 y Noche de silencio insomne en 2011.\r\nEn 2012 actúa en la serie Babylon, y al año siguiente integra el elenco de la telecomedia de Pol-ka Solamente vos como galán de la China Suárez.','Teatro','','','','',NULL,'','/uploads/imagenes/media_692201740bf138.00828409.jpg','validado',NULL),(31,'Nuev','Cuenta','1999-02-10','masculino','Argentina','Santiago del Estero','Villa Ojo de Agua','test@test.com','$2y$10$.MswowTgetq/9a/CwyNWe.jRkT89vCDE1IGNNU0OI7yLkwCvHrEZm','artista','validado','Buen pibe','Artesania','','','','',NULL,NULL,'/uploads/imagenes/media_6923d704882486.66576051.jpg','validado',NULL),(32,'ejemplo','dos','2000-12-12','otro','Argentina','Santiago del Estero','Sumampa','ejemplo@gmail.com','$2y$10$6hdzN.6FTAECmxkdkTSSr.zU8Gx7zrbsOH7Z16IK9PnAEsxfkaEfa','artista','pendiente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pendiente',NULL),(33,'ejemplo','uno','2000-12-12','otro','Argentina','Santiago del Estero','Clodomira','ejemplo1@gmail.com','$2y$10$I5WzrocTIamNk3I/58l7h.Rb5ESjbereFXBHyVnUd.PlD1gQ8t0Py','artista','pendiente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pendiente',NULL);
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
  `fecha_nacimiento` date DEFAULT NULL,
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
  `emoji` varchar(10) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `badge` varchar(50) DEFAULT 'Artista',
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_municipio` (`municipio`),
  KEY `idx_destacado` (`destacado`),
  KEY `idx_activo` (`activo`),
  KEY `idx_orden` (`orden_visualizacion`),
  KEY `idx_categoria_activo` (`categoria`,`activo`),
  KEY `idx_destacado_orden` (`destacado`,`orden_visualizacion`),
  FULLTEXT KEY `idx_busqueda_nombre` (`nombre_completo`,`nombre_artistico`,`biografia`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artistas_famosos`
--

LOCK TABLES `artistas_famosos` WRITE;
/*!40000 ALTER TABLE `artistas_famosos` DISABLE KEYS */;
INSERT INTO `artistas_famosos` VALUES (1,'Andrés Chazarreta','Don Andrés','1876-05-16','1960-10-24','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore','Músico, compositor y folclorista argentino. Considerado el \"Patriarca del Folklore Argentino\". Fue el primero en llevar música folklórica argentina a Buenos Aires en 1911, revolucionando la cultura musical del país. Recopiló y difundió la música tradicional santiagueña.','Pionero del folklore argentino. Fundó la primera orquesta de música nativa. Reconocido como Padre del Folklore Nacional.','La Telesita, Añoranzas, Zamba de Vargas, Chacarera del Rancho, El Crespín',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,0,'2025-11-10 04:31:14','2025-11-23 00:09:09',NULL,NULL,'🎤','artista_1763856549_692250a58c4e0.jpg','Actual'),(2,'Mercedes Sosa','La Negra','1935-07-09','2009-10-04','San Miguel de Tucumán','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Nuevo Cancionero','Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano. Aunque nació en Tucumán, gran parte de su familia era santiagueña y su repertorio incluyó muchas obras del folklore de Santiago del Estero. Icono de la música argentina y latinoamericana.','6 Premios Grammy Latino. Premio Konex de Platino. Declarada Ciudadana Ilustre de América. Doctorado Honoris Causa.','Gracias a la Vida, Alfonsina y el Mar, Todo Cambia, Balderrama, Zamba para no morir, Como la cigarra',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,2,0,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(3,'Jacinto Piedra',NULL,'1920-03-15','2007-05-10','Loreto','Loreto','Santiago del Estero','Argentina','musica','Chacarera','Músico y compositor santiagueño, especializado en chacarera. Uno de los grandes exponentes del folklore de Santiago del Estero. Su música representa la esencia de la cultura rural santiagueña.','Reconocido por su virtuosismo en la guitarra y bombo. Cosechó múltiples premios en festivales folklóricos.','Chacarera del Rancho, La Arunguita, Nostalgias Santiagueñas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,3,0,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(4,'Raly Barrionuevo',NULL,'1967-12-14',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore Contemporáneo','Cantautor argentino, exponente del folklore contemporáneo. Su música combina tradición santiagueña con elementos modernos. Uno de los artistas más reconocidos del nuevo folklore argentino.','Múltiples Premios Gardel. Disco de Oro. Reconocido internacionalmente.','Zamba del Laurel, Mariana, Melodía del Atardecer, Canto Versos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,4,1,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(5,'Dúo Coplanacu',NULL,'1975-01-01',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Copla','Dúo musical formado por Jorge Fandermole y Roxana Carabajal. Representan la copla y el folklore santiagueño con excelencia artística.','Premios Gardel. Reconocimiento Nacional e Internacional.','Coplas de mi País, Zamba Azul, La Pomeña',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,5,0,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(6,'Juan Carlos Dávalos',NULL,'1887-08-07','1959-12-06','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','literatura','Narrativa/Poesía','Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino, especialmente Santiago del Estero. Considerado uno de los grandes escritores regionalistas.','Premio Nacional de Literatura. Sus cuentos son clásicos de la literatura argentina.','Los Buscadores de Oro, Airampo, La Flor del Cardón, Salta',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,6,0,'2025-11-10 04:31:14','2025-11-17 04:10:55',NULL,NULL,'📚',NULL,'Artista'),(7,'Bernardo Canal Feijóo',NULL,'1897-09-04','1982-01-13','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','literatura','Ensayo/Historia','Escritor, ensayista, historiador y pensador argentino. Estudioso profundo de la cultura santiagueña y del noroeste argentino. Figura fundamental del pensamiento regionalista.','Doctor Honoris Causa. Premio Konex. Reconocido como intelectual fundamental del NOA.','Ensayo sobre la Expresión Popular Artística en Santiago, Burla, Credo, Culpa en la Creación Anónima',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,7,0,'2025-11-10 04:31:14','2025-11-17 04:10:55',NULL,NULL,'📚',NULL,'Artista'),(8,'Alfredo Gogna',NULL,'1878-01-15','1972-03-20','Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','artes_plasticas','Pintura','Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero. Pionero de las artes plásticas en la provincia.','Exposiciones nacionales e internacionales. Reconocido maestro de la pintura regional.','Paisajes Santiagueños, Serie del Monte, Retratos de Gauchos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,8,0,'2025-11-10 04:31:14','2025-11-17 04:10:55',NULL,NULL,'🎨',NULL,'Artista'),(9,'Ricardo y Francisco Sola',NULL,'1940-05-10',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','artes_plasticas','Escultura','Hermanos escultores reconocidos por sus obras monumentales en Santiago del Estero y Argentina.','Múltiples obras públicas y monumentos en la provincia.','Monumento a la Madre, Cristo del Cerro, Diversas esculturas urbanas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,9,0,'2025-11-10 04:31:14','2025-11-17 04:10:55',NULL,NULL,'🎨',NULL,'Artista'),(10,'Los Manseros Santiagueños',NULL,'1950-01-01',NULL,'Santiago del Estero','Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Conjunto folklórico emblemático de Santiago del Estero. Formado en 1950, llevan décadas difundiendo la cultura santiagueña.','Referentes del folklore santiagueño. Múltiples discos y presentaciones internacionales.','Pa\' Santiago me Voy, Chacarera de un Triste, La Telesita',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,10,0,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(11,'Horacio Banegas',NULL,'1946-06-15','2021-02-11','Fernández','Fernández','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Músico, compositor y poeta santiagueño. Figura fundamental del folklore argentino. Sus letras son poesía pura del monte santiagueño.','Considerado uno de los mejores letristas del folklore argentino. Cosechó innumerables premios.','La Olvidada, Zamba del Quebrachal, De Puro Guapo, El Antigal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,11,0,'2025-11-10 04:31:14','2025-11-17 04:10:09',NULL,NULL,'��',NULL,'Artista'),(12,'Mercedes Sosa',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Nuevo Cancionero','Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Leyenda'),(13,'Andrés Chazarreta',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore','Músico, compositor y folclorista argentino. Considerado el Patriarca del Folklore Argentino.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 04:45:03',NULL,NULL,'🎤',NULL,'Leyenda'),(14,'Jacinto Piedra',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Chacarera','Músico y compositor santiagueño, especializado en chacarera.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Regional'),(15,'Raly Barrionuevo',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore Contemporáneo','Cantautor argentino, exponente del folklore contemporáneo.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Actual'),(16,'Juan Carlos Dávalos',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','literatura','Narrativa/Poesía','Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Clásico'),(17,'Bernardo Canal Feijóo',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','literatura','Ensayo/Historia','Escritor, ensayista, historiador y pensador argentino.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Intelectual'),(18,'Los Manseros Santiagueños',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Conjunto folklórico emblemático de Santiago del Estero.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Legendario'),(19,'Horacio Banegas',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Folklore/Chacarera','Músico, compositor y poeta santiagueño.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'✍️',NULL,'Poeta'),(20,'Alfredo Gogna',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','musica','Pintura','Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,'2025-11-17 02:09:32','2025-11-23 00:08:49',NULL,NULL,'🎤','artista_1763856442_6922503a5d414.jpg','Clásico'),(21,'Ricardo y Francisco Sola',NULL,'0000-00-00',NULL,NULL,'Santiago del Estero','Santiago del Estero','Argentina','artes_plasticas','Escultura','Hermanos escultores reconocidos por sus obras monumentales.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2025-11-17 02:09:32','2025-11-17 02:09:32',NULL,NULL,'??',NULL,'Escultores');
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intereses_artista`
--

LOCK TABLES `intereses_artista` WRITE;
/*!40000 ALTER TABLE `intereses_artista` DISABLE KEYS */;
INSERT INTO `intereses_artista` VALUES (6,9,'musica'),(7,10,'musica'),(9,12,'artes_visuales'),(10,12,'artesanias'),(11,13,'musica'),(12,14,'musica'),(13,15,'danza'),(14,16,'artes_visuales'),(15,17,'musica'),(16,17,'danza'),(18,19,'artesanias'),(19,20,'artesanias'),(20,22,'artes_visuales'),(21,22,'cine'),(24,24,'artes_visuales'),(25,25,'teatro'),(26,26,'teatro'),(27,26,'cine'),(34,31,'artes_visuales'),(35,32,'cine'),(36,33,'letras');
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_validacion_perfiles`
--

LOCK TABLES `logs_validacion_perfiles` WRITE;
/*!40000 ALTER TABLE `logs_validacion_perfiles` DISABLE KEYS */;
INSERT INTO `logs_validacion_perfiles` VALUES (1,8,1,'validar',NULL,'2025-11-09 00:10:27'),(2,7,1,'validar',NULL,'2025-11-09 00:21:06'),(3,6,1,'rechazar','lo','2025-11-09 00:24:58'),(4,6,1,'validar',NULL,'2025-11-09 00:25:02'),(5,5,1,'validar',NULL,'2025-11-09 00:26:54'),(6,3,1,'rechazar','jnn','2025-11-09 14:26:36'),(7,2,1,'rechazar','hbgb','2025-11-09 14:26:43'),(8,8,1,'validar',NULL,'2025-11-10 00:40:48'),(9,8,1,'validar',NULL,'2025-11-17 12:13:15'),(10,9,1,'validar',NULL,'2025-11-17 15:22:04'),(11,8,3,'validar',NULL,'2025-11-20 22:50:34'),(12,9,1,'validar',NULL,'2025-11-20 23:03:20'),(13,10,3,'validar',NULL,'2025-11-20 23:18:07'),(14,8,3,'validar',NULL,'2025-11-21 18:53:13'),(15,10,3,'validar',NULL,'2025-11-21 20:19:24'),(16,10,3,'validar',NULL,'2025-11-21 20:20:58'),(17,12,3,'validar',NULL,'2025-11-21 20:50:14'),(18,12,3,'validar',NULL,'2025-11-21 20:54:40'),(19,13,3,'validar',NULL,'2025-11-22 03:23:10'),(20,14,3,'validar',NULL,'2025-11-22 03:31:25'),(21,14,3,'validar',NULL,'2025-11-22 03:46:57'),(22,8,3,'validar',NULL,'2025-11-22 04:27:16'),(23,12,3,'validar',NULL,'2025-11-22 11:25:28'),(24,15,3,'validar',NULL,'2025-11-22 11:42:09'),(25,15,3,'validar',NULL,'2025-11-22 11:45:15'),(26,16,3,'validar',NULL,'2025-11-22 13:41:38'),(27,16,3,'validar',NULL,'2025-11-22 13:44:22'),(28,16,3,'validar',NULL,'2025-11-22 14:38:59'),(29,16,3,'validar',NULL,'2025-11-22 14:45:33'),(30,18,3,'validar',NULL,'2025-11-22 15:13:48'),(31,17,3,'validar',NULL,'2025-11-22 15:20:55'),(32,19,3,'validar',NULL,'2025-11-22 15:20:58'),(33,9,3,'validar',NULL,'2025-11-22 15:36:37'),(34,9,3,'validar',NULL,'2025-11-22 15:57:28'),(35,22,3,'validar',NULL,'2025-11-22 16:29:54'),(36,20,3,'validar',NULL,'2025-11-22 16:56:53'),(37,20,3,'validar',NULL,'2025-11-22 17:03:03'),(38,24,3,'validar',NULL,'2025-11-22 17:24:09'),(39,25,3,'validar',NULL,'2025-11-22 18:08:11'),(40,26,3,'validar',NULL,'2025-11-22 19:03:43'),(41,13,3,'validar',NULL,'2025-11-22 19:03:52'),(42,16,3,'validar',NULL,'2025-11-22 20:42:14'),(43,16,3,'validar',NULL,'2025-11-22 20:47:22'),(44,20,3,'validar',NULL,'2025-11-22 21:04:12'),(45,20,3,'validar',NULL,'2025-11-22 22:39:54'),(46,27,3,'validar',NULL,'2025-11-23 04:59:43'),(47,23,3,'validar',NULL,'2025-11-23 04:59:54'),(48,28,3,'validar',NULL,'2025-11-23 05:00:03'),(49,28,3,'validar',NULL,'2025-11-23 05:27:52'),(50,29,1,'validar',NULL,'2025-11-24 00:35:30'),(51,31,1,'validar',NULL,'2025-11-24 02:06:23'),(52,31,3,'validar',NULL,'2025-11-24 03:55:03');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
INSERT INTO `noticias` VALUES (1,'¡Gran Apertura del Festival de Arte!','Este fin de semana se celebra el festival anual de arte con más de 50 artistas locales...','http://localhost:8080/static/uploads/noticias/noticia_1761572259_68ff75a35a30c.jpeg',2,'2025-08-14 19:38:03'),(7,'Evento','Test','static/uploads/noticias/1763759769_a.jpg',2,'2025-10-27 04:31:57'),(8,'🎤 Conferencia de Prensa – Presentación de “Retro Pop!”','En la jornada de hoy se llevó a cabo una conferencia de prensa encabezada por el subsecretario de Cultura, Lic. Juan Leguizamón, junto al director del Coro Estable de la Provincia, Prof. Roberto Peralta, quienes estuvieron acompañados por integrantes del coro y de la Orquesta Gioia Giovanile.\r\nDurante el encuentro se brindaron detalles del espectáculo “Retro Pop!”, una propuesta innovadora y llena de energía que se presentará el próximo viernes 21 de noviembre a las 21:00 hs en el Fórum, con entrada libre y gratuita.','static/uploads/noticias/1763759757_b.jpg',2,'2025-11-21 19:56:45'),(11,'Presentación de la plataforma ID-Cultural','ID Cultural es una plataforma web tipo \"Wikipedia local\". Proyecto desarrollado por el equipo \"RunaTech\" conformado por alumnos de la Tecnicatura en Desarrollo de Software del ITSE. Destinada a centralizar, validar y exhibir información sobre artistas y expresiones culturales de Santiago del Estero. El sistema permite a los artistas crear y gestionar borradores de perfiles culturales, que luego son sometidos a un proceso de validación por parte de moderadores. Una vez aprobados, estos perfiles se publican en una Wiki de Artistas abierta al público, conformando una valiosa biblioteca digital de contenido artístico local.','static/uploads/noticias/1763759949_3.jpg',2,'2025-11-21 20:05:05'),(12,'✨📚 Llega la 8.ª Feria del Libro de Frías 📚✨','Del 21 al 24 de noviembre, nuestra ciudad vuelve a encontrarse con la lectura, el arte y la palabra compartida.\r\nBajo el lema “Leamos libros, leamos libres; seamos libres, seamos libros”, presentamos una programación llena de talleres, presentaciones, poesía, música y actividades para todas las edades.\r\nEste año destacamos:\r\n⭐ Pies en el aire: música, poesía y danza en la apertura.\r\n⭐ Memoria de Minas Altas: un viaje al universo de Daniel Moyano.\r\n⭐ Conversatorio con Julio Salgado, Premio Konex 2024, sobre Frías Catábasis.\r\n⭐ El tradicional encuentro de Música y Poesía en el patio de la Cámara.\r\n⭐ Talleres, lecturas en barrios, presentaciones de libros y propuestas para niñas, niños, adolescentes y adultos.\r\n🎨 Todo esto es posible gracias al trabajo de voluntarios, comercios locales e instituciones que hacen crecer la feria año a año.\r\nDeslizá para ver el programa completo 👉📄\r\nTe esperamos para vivir juntos cuatro días de cultura, comunidad y celebración de la palabra.\r\n#FeriaDelLibroDeFrías #Frías #Cultura Lectura Libros Poesía Música MGlibros Bibliodiversidad','static/uploads/noticias/1763784105_1.jpg',2,'2025-11-22 04:01:45'),(13,'Anoche vivimos una noche increíble con RETRO POP! 🎶✨','El Coro Estable de la Provincia dependiente de la Subsecretaría de Cultura bajo la dirección del Prof. Roberto Peralta, junto a la orquesta Gioia Giovanile y músicos invitados, brindó un espectáculo lleno de energía, colores y grandes recuerdos musicales.\r\nMás de 1.300 personas disfrutaron de esta propuesta que nos conectó con lo mejor de la música retro y con el enorme talento de nuestros artistas provinciales.\r\n¡Gracias a todos los que acompañaron este evento inolvidable! 💛🎤🎼 \r\n📸 @davidestebanechegaray','static/uploads/noticias/1763833158_585041192_18349648228203317_2511851576668960591_n.jpg',2,'2025-11-22 17:39:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES (1,20,'fe6edaeeb1587b023221a33f1ecbe96b84f3d6bafc702d7e2140dc5948fdad43','2025-11-22 15:25:59','2025-11-22 16:25:59',0),(2,20,'06cef887e7e71a24411339db74a3dc2f4115a90b99aa685ddc5107d4f942a7b9','2025-11-22 15:26:01','2025-11-22 16:26:01',0),(3,20,'bd3a9df18367b7fb1b099cd3afcba424e739b3b7ddd166b9648b73cf62f9f801','2025-11-22 15:27:28','2025-11-22 16:27:28',0),(4,9,'887ca43b7400ba53367a5d677acf16cda9f59dabd494bc4426a7a17084df21f3','2025-11-22 15:29:00','2025-11-22 16:29:00',0),(5,9,'4958acf4204ff890ac6e0fcb059003d33f206776ad61903f0e221cb3be6f3323','2025-11-22 15:29:06','2025-11-22 16:29:06',0),(6,16,'1b46d1d337b69734c4991c7e307747f669728fc2e23146769d8b80caa25c88c0','2025-11-22 15:29:21','2025-11-22 16:29:21',0),(7,9,'ad68bd2756bc0fa6d6fb3522f3af095bd1084b001a177a2b2d032885f9aebaed','2025-11-23 13:59:06','2025-11-23 14:59:06',0),(8,9,'19d046b1e9bd9ca5309c5e14958e4e7313ab1838ad90dbf2b414a1a9863a6fdf','2025-11-23 13:59:14','2025-11-23 14:59:14',0),(9,9,'645ebc45411c39a9bd25af69e63dd63e6cf5bde3e0c1c092d42a80bbc35f4709','2025-11-23 13:59:16','2025-11-23 14:59:16',0),(10,16,'219a0fac50f523d6f1812a8ad86f14c721f67e640c83c31c8e9fe18a15647531','2025-11-23 20:29:27','2025-11-23 21:29:27',0),(11,16,'ab5648158ce67b027e895fb016a6677342106aa23ba962ad5213c10a5155e62e','2025-11-23 20:29:31','2025-11-23 21:29:31',0);
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_cambios_pendientes`
--

DROP TABLE IF EXISTS `perfil_cambios_pendientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_cambios_pendientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artista_id` int(11) NOT NULL,
  `biografia` text DEFAULT NULL,
  `especialidades` varchar(500) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `validador_id` int(11) DEFAULT NULL,
  `motivo_rechazo` text DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `validador_id` (`validador_id`),
  KEY `idx_artista_estado` (`artista_id`,`estado`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_solicitud` (`fecha_solicitud`),
  CONSTRAINT `perfil_cambios_pendientes_ibfk_1` FOREIGN KEY (`artista_id`) REFERENCES `artistas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `perfil_cambios_pendientes_ibfk_2` FOREIGN KEY (`validador_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Almacena cambios de perfil pendientes de validación antes de aplicarlos al perfil público';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_cambios_pendientes`
--

LOCK TABLES `perfil_cambios_pendientes` WRITE;
/*!40000 ALTER TABLE `perfil_cambios_pendientes` DISABLE KEYS */;
INSERT INTO `perfil_cambios_pendientes` VALUES (2,9,'Coo, el personaje que fue símbolo, escándalo y leyenda urbana','','','','','https://youtu.be/sCnCb_c8b9k?si=cIpoOHrB0t7M2-YR',NULL,'/uploads/imagenes/media_691f9de87e4a11.39972145.png','aprobado',1,NULL,'2025-11-20 23:02:00','2025-11-20 23:03:20'),(3,10,'onocido como Leo Dan, fue un cantante, compositor y actor argentino.[','','','','','',NULL,'/uploads/imagenes/media_691fa18a673b51.97776960.jpeg','aprobado',3,NULL,'2025-11-20 23:17:30','2025-11-20 23:18:07'),(5,10,'onocido como Leo Dan, fue un cantante, compositor y actor argentino.[','Musica','','','','',NULL,NULL,'aprobado',3,NULL,'2025-11-21 20:18:52','2025-11-21 20:19:24'),(6,10,'Conocido como Leo Dan, fue un cantante, compositor y actor argentino.[','Musica','','','','',NULL,NULL,'aprobado',3,NULL,'2025-11-21 20:20:37','2025-11-21 20:20:58'),(7,12,'','','','','','',NULL,NULL,'aprobado',3,NULL,'2025-11-21 20:42:39','2025-11-21 20:50:14'),(8,12,'Escultor, pintor y letrista creativo, autodidacta y multifacético. Su obra se caracteriza por la búsqueda constante de nuevas formas de expresión, explorando materiales, colores y palabras con la misma intensidad. Como escultor, transforma la materia en símbolos de identidad y memoria; como pintor, plasma emociones y paisajes interiores con una paleta vibrante; y como letrista, convierte experiencias en versos que dialogan con la música y la vida cotidiana.\r\nAutodidacta por convicción, ha forjado un estilo propio que se nutre de la experimentación y la curiosidad. Su carácter multifacético le permite transitar con naturalidad entre disciplinas, creando un universo artístico integral donde cada pieza, cada trazo y cada palabra se complementan.','Escultura','','https://www.facebook.com/horaciovictororlando.sequeira.3?locale=es_LA','','https://www.youtube.com/@Ni10NiFran',NULL,'/uploads/imagenes/media_6920d18472d781.29826074.jpg','aprobado',3,NULL,'2025-11-21 20:54:28','2025-11-21 20:54:40'),(9,13,'Sebastián Ruiz nació en La Banda, Santiago del Estero, en 1987. Desde pequeño mostró pasión por la música popular y el folclore, aprendiendo a tocar la guitarra en reuniones familiares. Con el tiempo se convirtió en un referente local, llevando la chacarera y la zamba a escenarios provinciales y nacionales.\r\n\r\nAdemás de músico, Sebastián es docente en una escuela secundaria de su ciudad, donde transmite a sus alumnos el amor por las raíces culturales santiagueñas. Su obra se caracteriza por unir tradición y modernidad, con letras que hablan de la familia, la tierra y la esperanza.\r\n\r\nHoy continúa viviendo en La Banda, donde organiza peñas y talleres comunitarios, convencido de que la música es puente de identidad y unión para su pueblo.','Musica','','','','',NULL,NULL,'aprobado',3,NULL,'2025-11-21 21:11:12','2025-11-22 03:23:10'),(10,14,'Thomas Nicolás Tobar nació en la ciudad de Santiago del Estero, Argentina,[8]​ el 20 de mayo del año 2000.[9]​ Es hijo de un padre taxista y una madre ama de casa.[10]​ Creció y vivió en la ciudad de La Banda.[10]​ Su interés por el rap y el reguetón nació a los 15 años, edad con la cual comenzó a participar de varias competencias de freestyle en las plazas de su ciudad natal.[11]​[12]​ Su nombre artístico surgió cuando eligió el término «Rusher» seguida de la palabra en inglés «King» —en español: Rey— para su usuario en el videojuego Counter-Strike y con el cual también decidió inscribirse en las competencias de freestyle.[13]​','Musica','','','','https://open.spotify.com/intl-es/artist/3Apb2lGmGJaBmr0TTBJvIZ',NULL,'/uploads/imagenes/media_69212e2aeb1e93.94811452.webp','aprobado',3,NULL,'2025-11-22 03:29:46','2025-11-22 03:31:25'),(11,14,'Thomas Nicolás Tobar nació en la ciudad de Santiago del Estero, Argentina,[8]​ el 20 de mayo del año 2000.[9]​ Es hijo de un padre taxista y una madre ama de casa.[10]​ Creció y vivió en la ciudad de La Banda.[10]​ Su interés por el rap y el reguetón nació a los 15 años, edad con la cual comenzó a participar de varias competencias de freestyle en las plazas de su ciudad natal.[11]​[12]​ Su nombre artístico surgió cuando eligió el término «Rusher» seguida de la palabra en inglés «King» —en español: Rey— para su usuario en el videojuego Counter-Strike y con el cual también decidió inscribirse en las competencias de freestyle.[13]​','Musica','','https://open.spotify.com/intl-es/artist/3Apb2lGmGJaBmr0TTBJvIZ','','',NULL,NULL,'aprobado',3,NULL,'2025-11-22 03:41:03','2025-11-22 03:46:57'),(13,12,'Escultor, pintor y letrista creativo, autodidacta y multifacético. Su obra se caracteriza por la búsqueda constante de nuevas formas de expresión, explorando materiales, colores y palabras con la misma intensidad. Como escultor, transforma la materia en símbolos de identidad y memoria; como pintor, plasma emociones y paisajes interiores con una paleta vibrante; y como letrista, convierte experiencias en versos que dialogan con la música y la vida cotidiana.\r\nAutodidacta por convicción, ha forjado un estilo propio que se nutre de la experimentación y la curiosidad. Su carácter multifacético le permite transitar con naturalidad entre disciplinas, creando un universo artístico integral donde cada pieza, cada trazo y cada palabra se complementan.','Escultura','','https://www.facebook.com/horaciovictororlando.sequeira.3?locale=es_LA','','https://www.youtube.com/@Ni10NiFran','+54 9 385 613-8390',NULL,'aprobado',3,NULL,'2025-11-22 11:24:47','2025-11-22 11:25:28'),(14,15,'','Danza','','','','','3855123456','/uploads/imagenes/media_6921a171db71a7.44890802.jpeg','aprobado',3,NULL,'2025-11-22 11:41:37','2025-11-22 11:42:09'),(15,15,'\"El Bailarín de los Montes\", es un icónico bailarín, zapateador y coreógrafo santiagueño nacido en 1943, famoso por innovar la danza folklórica argentina. Su carrera incluye giras mundiales con grupos como el Ballet de Santiago Ayala y la compañía de Mercedes Sosa, la formación del trío \"Los Santiagueños\" con Peteco Carabajal y Jacinto Piedra, y la creación de proyectos como \"Los Indianos en París\". Hoy en día, continúa difundiendo la cultura santiagueña a través de seminarios y festivales en todo el país. ','Danza','','','','','3855123456',NULL,'aprobado',3,NULL,'2025-11-22 11:44:36','2025-11-22 11:45:15'),(16,16,'','Literatura','','','','','','/uploads/imagenes/media_6921bd4ec04486.35859586.jpg','aprobado',3,NULL,'2025-11-22 13:40:30','2025-11-22 13:41:38'),(17,16,'Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','','3855075058',NULL,'aprobado',3,NULL,'2025-11-22 13:43:49','2025-11-22 13:44:22'),(18,16,'Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','https://sandra-s-portfolio.vercel.app/','3855075058','/uploads/imagenes/media_6921cac4d85e44.96805919.jpg','aprobado',3,NULL,'2025-11-22 14:37:56','2025-11-22 14:38:59'),(19,16,'Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','https://sandra-s-portfolio.vercel.app/','3855075058','/uploads/imagenes/media_6921cc86273526.98962306.jpeg','aprobado',3,NULL,'2025-11-22 14:45:26','2025-11-22 14:45:33'),(21,17,'','Danza','','','','','','/uploads/imagenes/media_6921d4887c6677.81345064.webp','aprobado',3,NULL,'2025-11-22 15:19:36','2025-11-22 15:20:55'),(22,19,'Este artesano del bombo, con más de 50 años de trayectoria, ha llevado su arte a niveles extraordinarios, convirtiéndose en el fabricante de instrumentos elegido por artistas nacionales e internacionales. Nombres como Shakira y el prestigioso Cirque Du Soleil han confiado en las manos expertas de Froilán para la confección de sus bombos.\r\nEl taller de Froilán se encuentra en un lugar tan singular como él: «La Boca del Tigre», ubicado al norte de la ciudad capital de Santiago del Estero. \r\nEste espacio no es solo un taller, sino un santuario de la música y la cultura. Todos los domingos, «La Boca del Tigre» se transforma en un punto de encuentro para artistas que se reúnen a cantar y tocar, creando un ambiente mágico que atrae a un público diverso, incluyendo a numerosos turistas que no quieren perderse estos vibrantes encuentros de música y danza.','Artesania','','','','','3855012345','/uploads/imagenes/media_6921d4c5cf6b27.13238659.jpg','aprobado',3,NULL,'2025-11-22 15:20:37','2025-11-22 15:20:58'),(23,9,'Coo, el personaje que fue símbolo, escándalo y leyenda urbana','Musica','','','','https://youtu.be/sCnCb_c8b9k?si=cIpoOHrB0t7M2-YR','','/uploads/imagenes/media_6921d8739a7cf2.88819690.jpg','aprobado',3,NULL,'2025-11-22 15:36:19','2025-11-22 15:36:37'),(24,9,'Coo, el personaje que fue símbolo, escándalo y leyenda urbana','Musica','','','','https://youtu.be/sCnCb_c8b9k?si=cIpoOHrB0t7M2-YR','','/uploads/imagenes/media_6921dd38853ac1.93522221.jpg','aprobado',3,NULL,'2025-11-22 15:56:40','2025-11-22 15:57:28'),(25,22,'','Audiovisual','','','','','','/uploads/imagenes/media_6921e4b71a8516.43758066.jpeg','aprobado',3,NULL,'2025-11-22 16:28:39','2025-11-22 16:29:54'),(26,20,'','Artesania','','','','','',NULL,'aprobado',3,NULL,'2025-11-22 16:55:53','2025-11-22 16:56:53'),(27,20,'','Artesania','','','','','',NULL,'aprobado',3,NULL,'2025-11-22 17:02:31','2025-11-22 17:03:03'),(28,24,'','Audiovisual','','','','','','/uploads/imagenes/media_6921f19eb73472.32574755.jpg','aprobado',3,NULL,'2025-11-22 17:23:42','2025-11-22 17:24:09'),(29,25,'','Teatro','arielartistaok','','','','','/uploads/imagenes/media_6921f5e555ba84.92771830.webp','aprobado',3,NULL,'2025-11-22 17:41:57','2025-11-22 18:08:11'),(30,26,'Debutó en 1992 en el programa Jugate conmigo conducido y producido por Cris Morena. Luego actuó en las telenovelas Quereme, La hermana mayor y 90 60 90 modelos, esta última fue uno de sus mayores éxitos televisivos interpretando al galán de Natalia Oreiro.\r\nContinuo actuando en ficciones como RRDT, Son o se hacen y Drácula, y retomó su carrera de actor en 2003 en Son amores. Más tarde llegaron otros personajes en Mosca & Smith, Soy tu fan y Champs 12, y participaciones especiales en Ciega a citas, Un año para recordar, Herencia de amor, El hombre de tu vida, Historia clínica y Vecinos en guerra.\r\nEn 2007 debuta en teatro en la obra Extraña pareja, protagonizado por Carlin Calvo y Pablo Rago. En cine actuó en las películas Bacanal, en el año 1999 y Noche de silencio insomne en 2011.\r\nEn 2012 actúa en la serie Babylon, y al año siguiente integra el elenco de la telecomedia de Pol-ka Solamente vos como galán de la China Suárez.','Teatro','','','','','','/uploads/imagenes/media_692201740bf138.00828409.jpg','aprobado',3,NULL,'2025-11-22 18:31:16','2025-11-22 19:03:43'),(31,13,'Sebastián Ruiz nació en La Banda, Santiago del Estero, en 1987. Desde pequeño mostró pasión por la música popular y el folclore, aprendiendo a tocar la guitarra en reuniones familiares. Con el tiempo se convirtió en un referente local, llevando la chacarera y la zamba a escenarios provinciales y nacionales.\r\n\r\nAdemás de músico, Sebastián es docente en una escuela secundaria de su ciudad, donde transmite a sus alumnos el amor por las raíces culturales santiagueñas. Su obra se caracteriza por unir tradición y modernidad, con letras que hablan de la familia, la tierra y la esperanza.\r\n\r\nHoy continúa viviendo en La Banda, donde organiza peñas y talleres comunitarios, convencido de que la música es puente de identidad y unión para su pueblo.','Musica','','','','https://srdev-portafolio.netlify.app/','','/uploads/imagenes/media_692205b2b1c126.45591009.jpg','aprobado',3,NULL,'2025-11-22 18:49:22','2025-11-22 19:03:52'),(32,16,'Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','https://sandra-s-portfolio.vercel.app/','3855075058','/uploads/imagenes/media_69221fe9ed0c21.58066840.jpg','aprobado',3,NULL,'2025-11-22 20:41:13','2025-11-22 20:42:14'),(33,16,'Escritora, diseñadora, desarrolladora, dibujante , entre otras ','Literatura','','','','https://sandra-s-portfolio.vercel.app/','3855075058','/uploads/imagenes/media_692221307e6499.35498737.jpg','aprobado',3,NULL,'2025-11-22 20:46:40','2025-11-22 20:47:22'),(34,20,'','Artesania','','','','','',NULL,'aprobado',3,NULL,'2025-11-22 21:00:39','2025-11-22 21:04:12'),(35,20,'','Artesania','','','','','','/uploads/imagenes/media_69222a18c84833.42003318.jpg','aprobado',3,NULL,'2025-11-22 21:24:40','2025-11-22 22:39:54'),(38,31,'Buen pibe','Artesania','','','','',NULL,'/uploads/imagenes/media_6923bd2b39ca82.91163957.jpg','aprobado',1,NULL,'2025-11-24 02:04:27','2025-11-24 02:06:23'),(39,31,'Buen pibe','Artesania','','','','',NULL,'/uploads/imagenes/media_6923d704882486.66576051.jpg','aprobado',3,NULL,'2025-11-24 03:54:44','2025-11-24 03:55:03');
/*!40000 ALTER TABLE `perfil_cambios_pendientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantillas_notificaciones`
--

DROP TABLE IF EXISTS `plantillas_notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plantillas_notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `titulo_template` varchar(255) NOT NULL,
  `mensaje_template` text NOT NULL,
  `tipo` enum('info','success','warning','error','validacion','mensaje') DEFAULT 'info',
  `icono` varchar(50) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `url_accion_template` varchar(500) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantillas_notificaciones`
--

LOCK TABLES `plantillas_notificaciones` WRITE;
/*!40000 ALTER TABLE `plantillas_notificaciones` DISABLE KEYS */;
INSERT INTO `plantillas_notificaciones` VALUES (1,'perfil_validado','Perfil Validado','Tu perfil ha sido validado exitosamente','success','bi-check-circle','success',NULL,'Se envía cuando un artista es validado','[\"artista_nombre\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(2,'perfil_rechazado','Perfil Rechazado','Tu perfil ha sido rechazado. Razón: {razon}','error','bi-x-circle','danger',NULL,'Se envía cuando un artista es rechazado','[\"razon\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(3,'solicitud_nueva','Nueva Solicitud de Validación','Nueva solicitud de validación: {artista_nombre}','info','bi-inbox','info',NULL,'Se envía a validadores','[\"artista_nombre\", \"url\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(4,'comentario_nuevo','Nuevo Comentario','{usuario_nombre} comentó en tu perfil','info','bi-chat-dots','info',NULL,'Se envía cuando hay comentario nuevo','[\"usuario_nombre\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(5,'obra_comentada','Nueva Obra Comentada','Comentaron en tu obra: {nombre_obra}','info','bi-chat-dots','info',NULL,'Comentarios en obras','[\"nombre_obra\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(6,'evento_proximo','Evento Próximo','No olvides: {nombre_evento} en {fecha}','warning','bi-calendar-event','warning',NULL,'Recordatorio de evento','[\"nombre_evento\", \"fecha\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(7,'mensaje_nuevo','Mensaje Nuevo','{usuario_nombre} te envió un mensaje','mensaje','bi-envelope','info',NULL,'Nuevo mensaje','[\"usuario_nombre\"]','2025-11-21 21:23:25','2025-11-21 21:23:25'),(8,'publicacion_validada','Publicación Validada','Tu publicación \"{titulo_obra}\" ha sido validada exitosamente y ya está visible en el sitio.','success','bi-check-circle-fill','success',NULL,'Se envía cuando una publicación es validada','[\"titulo_obra\"]','2025-11-21 21:23:48','2025-11-21 21:23:48'),(9,'publicacion_rechazada','Publicación Rechazada','Tu publicación \"{titulo_obra}\" ha sido rechazada. Motivo: {motivo}','error','bi-x-circle-fill','danger',NULL,'Se envía cuando una publicación es rechazada','[\"titulo_obra\", \"motivo\"]','2025-11-21 21:23:48','2025-11-21 21:23:48'),(10,'cambio_perfil_validado','Cambio de Perfil Validado','Los cambios en tu perfil han sido validados y aplicados exitosamente.','success','bi-person-check-fill','success',NULL,'Se envía cuando cambios de perfil son validados','[]','2025-11-21 21:23:48','2025-11-21 21:23:48'),(11,'cambio_perfil_rechazado','Cambio de Perfil Rechazado','Los cambios en tu perfil han sido rechazados. Motivo: {motivo}','error','bi-person-x-fill','danger',NULL,'Se envía cuando cambios de perfil son rechazados','[\"motivo\"]','2025-11-21 21:23:48','2025-11-21 21:23:48');
/*!40000 ALTER TABLE `plantillas_notificaciones` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicaciones`
--

LOCK TABLES `publicaciones` WRITE;
/*!40000 ALTER TABLE `publicaciones` DISABLE KEYS */;
INSERT INTO `publicaciones` VALUES (8,9,'Coo \"El Guarachero\"','Una figura de calle, un ritmo y una ciudad\r\nDurante años, Coo fue parte del paisaje habitual del microcentro santiagueño. Con su característico güiro en mano, su voz al ritmo de guaracha y su forma particular de vestir, se convirtió en un personaje querible, especialmente entre los más chicos y los adultos que aún veían en él el reflejo de una ciudad que no había perdido del todo su cantor.','musica','{\"plataformas\":\"\",\"sello\":\"\"}','/uploads/imagenes/media_6921de7d90ac11.93440956.png','validado','2025-11-17 15:24:26','2025-11-22 16:02:05',3,'2025-11-22 16:31:15'),(9,10,'Mary es mi amor','Cerca de 1966, él y su esposa, Marietta Tevez, decidieron emigrar a España, donde continuó grabando y produciendo canciones de éxito comercial, como «Mary es mi amor», «Con los brazos cruzados», «Siempre estoy pensando en ella», «Cómo poder saber si te amo» y «Será posible amor», entre otros.[34]​','musica','{\"action\":\"save\"}','/uploads/imagenes/media_691fa25564da82.76544730.jpg','validado','2025-11-20 23:20:53','2025-11-20 23:20:53',3,'2025-11-20 23:21:14'),(12,10,'Cómo Te Extraño Mi Amor (En Vivo) ft. Rubén Albarrán','Cómo te extraño \r\n\r\nCómo te extraño mi amor por qué será \r\nme falta todo en la vida si no estás \r\ncómo te extraño mi amor qué debo hacer \r\nte extraño tanto que voy a enloquecer.\r\n\r\nAy! amor divino pronto tienes que volver a mí.\r\n\r\nA veces pienso que tú nunca vendrás \r\npero te quiero y te tengo que esperar \r\nes el destino me lleva hasta el final \r\ndonde algún día mi amor te encontrará. \r\n\r\nAy! amor divino pronto tienes que volver a mí.\r\n\r\nEl dolor es fuerte y lo soporto \r\nporque sufro pensando en tu amor \r\nquiero verte tenerte y besarte \r\ny entregarte todo mi corazón. \r\n\r\nCómo te extraño mi amor por qué será \r\nme falta todo en la vida si no estás \r\ncómo te extraño mi amor qué debo hacer \r\nte extraño tanto que voy a enloquecer. \r\n\r\nAy! amor divino pronto tienes que volver a mí.\r\n\r\nA veces pienso que tú nunca vendrás \r\npero te quiero y te tengo que esperar \r\nes el destino me lleva hasta el final \r\ndonde algún día mi amor te encontrará.\r\n\r\nAy! amor divino pronto tienes que volver a mí. \r\n\r\nEl dolor es fuerte lo soporto \r\nporque vivo pensando en tu amor \r\nquiero verte tenerte y besarte \r\ny entregarte todo mi corazón \r\noh oh oh oh mi corazón\r\noh oh oh oh mi corazón\r\noh oh oh oh… \r\nmi corazón.\r\n\r\nDirector de Video: Gonzalo Ferrari para Sony Music Entertainment México / Héctor González para Sony Music Entertainment México \r\nRealizador de Video: Alberto Montiel para Sony Music Entertainment México / Miguel Tafich\r\n\r\nMusic video by Leo Dan performing Cómo Te Extraño Mi Amor (En Vivo). (C) 2018 Sony Music Entertainment México, S.A. de C.V.','musica','{\"action\":\"save\",\"plataformas\":\"https:\\/\\/youtu.be\\/RmsICVbs8Z4?si=1ysBbvXthLpxz2cT\"}','/uploads/imagenes/media_6920ca901ad7d5.34366168.jpg','validado','2025-11-21 20:24:48','2025-11-21 20:25:28',3,'2025-11-21 20:26:09'),(13,12,'Mural del rostro de Jacinto Piedra','Aquí tenemos ilustrado cultura folklorica con el rostro   de Jacinto piedra un gran referente acompañado de bailarines entre medio de música su fuego salamanquero bailarín y destreza nunca se apaga','artes_visuales','{\"action\":\"save\",\"ano_creacion\":\"2024\"}','/uploads/imagenes/media_6920d1138c1da9.50156296.jpg','validado','2025-11-21 20:52:35','2025-11-21 20:52:35',3,'2025-11-21 20:52:53'),(14,13,'Chacarera Cuatra Ramas','De un quebracho santiagueño\r\ncuatro ramas se soltaron,\r\ntres varones y una niña\r\ndel tronco se levantaron.\r\n\r\nEl monte les dio su canto,\r\nel río les dio camino,\r\ny en la copla de la tierra\r\nse hizo fuerte su destino.\r\n\r\n(Estribillo)\r\nCuatro ramas, cuatro vidas,\r\ndel árbol que los crió,\r\nSantiago los vio crecer\r\ncon chacarera y amor.\r\n\r\nLa niña lleva en su vuelo\r\nclaridad de luna llena,\r\nlos hermanos la protegen\r\ncomo guardias de la pena.\r\n\r\nY aunque el viento los aparte\r\npor senderos diferentes,\r\nla raíz nunca se olvida,\r\nsiempre vuelve a su querencia.\r\n\r\n(Estribillo)\r\nCuatro ramas, cuatro vidas,\r\ndel árbol que los crió,\r\nSantiago los vio crecer\r\ncon chacarera y amor.','musica','{\"action\":\"save\"}',NULL,'validado','2025-11-21 21:03:43','2025-11-21 21:04:03',3,'2025-11-22 03:23:20'),(15,14,'Los del Espacio','LIT killah, Duki, Emilia, Tiago PZK, FMK, Rusherking, Maria Becerra, Big One','musica','{\"plataformas\":\"\",\"sello\":\"\"}','/uploads/imagenes/media_692132b026f4c2.67950288.jpg','validado','2025-11-22 03:27:46','2025-11-22 03:49:04',3,'2025-11-22 03:49:14'),(16,15,'El Chúcaro','Ballet de Santiago Ayala \"El Chúcaro\" y de la Compañía de Mercedes','danza','{\"action\":\"save\"}','/uploads/imagenes/media_6921a130db1df4.15892848.jpeg','validado','2025-11-22 11:40:32','2025-11-22 11:40:32',3,'2025-11-22 11:42:24'),(17,16,'El sueño de la Mariquita','Cuento Infantil para mí hija Lohana. Cuenta la historia de una mariquita que soñaba con ver el amanecer pero no lograba levantarse a tiempo.','artes_visuales','{\"action\":\"save\",\"tecnica\":\"L\\u00e1piz\",\"dimensiones_av\":\"N5\",\"ano_creacion\":\"2019\"}','/uploads/imagenes/media_6921b99699bdc5.87557994.jpg','validado','2025-11-22 13:24:38','2025-11-22 13:24:38',3,'2025-11-22 13:29:58'),(19,17,'noche,check','bailando bajo la lluvia','danza','{\"action\":\"save\"}','/uploads/imagenes/media_6921d3c6891b78.86656894.webp','validado','2025-11-22 15:16:22','2025-11-22 15:16:22',3,'2025-11-22 15:18:37'),(20,19,'Bombos legüeros artesanales como obsequio para Messi y Scaloni','2023, pero hasta la fecha no hay noticias de que los haya entregado personalmente. Froilán expresó su deseo de entregárselos en persona y que el regalo fuera parte de un homenaje a los campeones del mundo. \r\nEl regalo: Froilán creó dos bombos legüeros artesanales como obsequio para Messi y Scaloni.\r\nEl diseño: Cada bombo tiene un diseño personalizado grabado a filo, con imágenes de ambos luciendo la Copa del Mundo.\r\nLa esperanza de Froilán: El luthier santiagueño manifestó su gran deseo de poder entregarles los bombos en persona para saludarlos y agradecerles, aunque hasta el momento no ha habido noticias de una entrega formal.\r\nEl contexto: Este gesto formó parte de una serie de homenajes de Froilán a los campeones del mundo tras ganar el Mundial de Qatar 2022.','artesanias','{\"action\":\"save\"}','/uploads/imagenes/media_6921d43e8089e8.74309554.png','validado','2025-11-22 15:18:22','2025-11-22 15:18:22',3,'2025-11-22 15:18:43'),(21,20,'una mano ayuda a la otra','barro negro','artesanias','{\"action\":\"save\"}','/uploads/imagenes/media_6921d8f2159698.61625759.webp','validado','2025-11-22 15:38:26','2025-11-22 15:38:26',3,'2025-11-22 15:47:15'),(22,9,'Recuerdo','mmm','musica','{\"plataformas\":\"\",\"sello\":\"\"}','/uploads/imagenes/media_6921ddd6488dc9.00783263.jpg','validado','2025-11-22 15:59:18','2025-11-22 16:02:33',3,'2025-11-22 16:31:10'),(23,9,'Recuerdo','Una figura de calle, un ritmo y una ciudad Durante años, Coo fue parte del paisaje habitual del microcentro santiagueño. Con su característico güiro en mano, su voz al ritmo de guaracha y su forma particular de vestir, se convirtió en un personaje querible, especialmente entre los más chicos y los adultos que aún veían en él el reflejo de una ciudad que no había perdido del todo su cantor.','musica','{\"action\":\"save\"}','/uploads/imagenes/media_6921df046c0a50.95870635.jpg','validado','2025-11-22 16:04:20','2025-11-22 16:04:20',3,'2025-11-22 16:04:29'),(24,22,'dungeon and dragon','Fantasía medieval inspirado en juegos de mesa','artes_visuales','{\"action\":\"save\"}','/uploads/imagenes/media_6921e078d94c44.28687952.webp','validado','2025-11-22 16:10:32','2025-11-22 16:10:32',3,'2025-11-22 16:12:11'),(25,24,'Dragon de papel','Stargazer 1.0','artes_visuales','{\"action\":\"save\",\"ano_creacion\":\"2025\"}','/uploads/imagenes/media_6921effe9691f8.73950214.jpg','validado','2025-11-22 17:16:46','2025-11-22 17:16:46',3,'2025-11-22 17:17:17'),(26,25,'unipersonal','Comming soon','teatro','{\"action\":\"save\"}','/uploads/imagenes/media_6921f61d9171f8.57335911.webp','validado','2025-11-22 17:42:53','2025-11-22 17:42:53',3,'2025-11-22 18:08:47'),(27,26,'Extraña pareja','Personificó a Felix Unger es un hombre de mediana edad, meticuloso y obsesionado con el orden y la limpieza, que es expulsado del hogar familiar por su esposa. Ante esta situación, a Felix no le queda otra opción que recurrir a su amigo Oscar Madison, para que le permita compartir su apartamento. El problema llega cuando Felix descubre que el carácter de Oscar es diametralmente opuesto al suyo, pues se trata de un tipo despreocupado y juerguista. La convivencia en absoluto resultará sencilla.','teatro','{\"action\":\"save\"}','/uploads/imagenes/media_6922023a0b2c19.60116783.png','validado','2025-11-22 18:34:34','2025-11-22 18:34:34',3,'2025-11-22 18:34:56'),(32,31,'Obra','Jux','escultura','{\"action\":\"save\"}',NULL,'validado','2025-11-24 02:02:45','2025-11-24 02:02:45',1,'2025-11-24 02:10:45'),(33,31,'Obra','Jux','escultura','{\"action\":\"save\"}','[\"\\/uploads\\/imagenes\\/media_6923bcd7329191.93454015.png\"]','validado','2025-11-24 02:03:03','2025-11-24 02:03:03',1,'2025-11-24 02:07:06'),(34,31,'Prot','Prueba','literatura','{\"action\":\"save\"}','[\"\\/uploads\\/imagenes\\/media_6923c09a725a41.66552470.jpg\"]','validado','2025-11-24 02:19:06','2025-11-24 02:19:06',3,'2025-11-24 02:19:42'),(35,31,'Obra','Lalalla','artesanias','{\"action\":\"save\"}','[\"\\/uploads\\/imagenes\\/media_6923d0b7841a28.64988528.png\"]','pendiente_validacion','2025-11-24 03:27:51','2025-11-24 03:27:51',NULL,NULL);
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
INSERT INTO `site_content` VALUES (1,'welcome_title','<p><strong style=\"color: rgb(102, 185, 102); background-color: rgb(230, 0, 0);\"><u><span class=\"ql-cursor\">﻿﻿</span></u></strong><strong style=\"color: rgb(102, 185, 102);\"><u>Bienvenidos a ID Cultural</u></strong></p>'),(2,'welcome_paragraph','<blockquote><strong>Es una plataforma digital dedicada a visibilizar, preservar y promover la identidad artística y cultural de Santiago del Estero. Te invitamos a explorar, descubrir y formar parte de este espacio pensado para fortalecer nuestras raíces.</strong></blockquote>'),(3,'welcome_slogan','<p><span class=\"ql-font-monospace\"><span class=\"ql-cursor\">﻿﻿﻿﻿</span></span><strong class=\"ql-font-arial\" style=\"color: rgb(230, 0, 0);\">La identidad de un pueblo, en un solo lugar.</strong></p>'),(4,'carousel_image_1','https://placehold.co/1200x450/367789/FFFFFF?text=Cultura+Santiagueña'),(5,'carousel_image_2','https://placehold.co/1200x450/C30135/FFFFFF?text=Nuestros+Artistas'),(6,'carousel_image_3','https://placehold.co/1200x450/efc892/333333?text=Biblioteca+Digital');
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
INSERT INTO `system_logs` VALUES (1,1,'Administrador Principal','INICIO DE SESIÓN','El usuario ha iniciado sesión correctamente.','2025-08-14 18:21:33'),(2,2,'Editor de Contenidos','CREACIÓN DE NOTICIA','Se ha creado la noticia con ID: 101.','2025-08-14 18:21:33'),(3,3,'Validador de Artistas','VALIDACIÓN DE ARTISTA','Se ha aprobado la solicitud del artista con ID: 1. Comentario: Excelente portfolio.','2025-08-14 18:21:33'),(4,3,'Validador de Artistas','RECHAZO DE ARTISTA','Se ha rechazado la solicitud del artista con ID: 2. Motivo: Faltan referencias comprobables.','2025-08-14 18:21:33'),(5,1,'Usuario Desconocido','VALIDACIÓN DE ARTISTA','Se ha validado la solicitud con ID: 1.','2025-08-14 18:39:49'),(6,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 1 a validado.','2025-08-14 19:15:57'),(7,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 4 a validado. Comentario: buen pibe','2025-08-14 19:21:30'),(8,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 3 a validado.','2025-08-14 19:32:05'),(9,1,'Admin','RECHAZO DE ARTISTA','Se ha cambiado el estado del artista ID: 2 a rechazado. Motivo: ninguna cancion es tuya','2025-08-14 19:32:25'),(10,1,'Admin','VALIDACIÓN DE ARTISTA','Se ha cambiado el estado del artista ID: 7 a validado.','2025-10-27 05:21:21'),(11,3,' ','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 2 del artista \'marcos romano\' (ID: 8) ha sido validada.','2025-11-08 03:52:07'),(12,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 3 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 21:08:42'),(13,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 5 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 22:06:24'),(14,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 6 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-08 22:17:00'),(15,1,'admin','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 7 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-17 12:13:40'),(16,1,'admin','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 8 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-17 15:25:43'),(17,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 9 del artista \'Leopoldo Dante Tevez\' (ID: 10) ha sido validada.','2025-11-20 23:21:14'),(18,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 12 del artista \'Leopoldo Dante Tevez\' (ID: 10) ha sido validada.','2025-11-21 20:26:09'),(19,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 13 del artista \'Horacio Sequeira\' (ID: 12) ha sido validada.','2025-11-21 20:52:53'),(20,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 14 del artista \'Sebastian Ruiz\' (ID: 13) ha sido validada.','2025-11-22 03:23:20'),(21,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 15 del artista \'Thomas Nicolás Tobar\' (ID: 14) ha sido validada.','2025-11-22 03:28:30'),(22,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 7 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-22 03:47:13'),(23,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 15 del artista \'Thomas Nicolás Tobar\' (ID: 14) ha sido validada.','2025-11-22 03:47:17'),(24,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 15 del artista \'Thomas Nicolás Tobar\' (ID: 14) ha sido validada.','2025-11-22 03:49:14'),(25,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 7 del artista \'Marcos Romano\' (ID: 8) ha sido validada.','2025-11-22 03:59:53'),(26,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 16 del artista \'Juan Saavedra\' (ID: 15) ha sido validada.','2025-11-22 11:42:24'),(27,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 17 del artista \'Sandra Soledad Sanchez\' (ID: 16) ha sido validada.','2025-11-22 13:29:58'),(28,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 18 del artista \'Max Pein\' (ID: 18) ha sido validada.','2025-11-22 15:14:05'),(29,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 19 del artista \'Virginia Ledesma\' (ID: 17) ha sido validada.','2025-11-22 15:18:37'),(30,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 20 del artista \'«El Indio» Froilán González\' (ID: 19) ha sido validada.','2025-11-22 15:18:43'),(31,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 21 del artista \'MARCOS ROMANO\' (ID: 20) ha sido validada.','2025-11-22 15:47:15'),(32,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 8 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 15:55:21'),(33,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 8 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 15:57:41'),(34,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 22 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 15:59:31'),(35,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 23 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 16:04:29'),(36,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 24 del artista \'Lucas Corbalan\' (ID: 22) ha sido validada.','2025-11-22 16:12:11'),(37,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 22 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 16:31:10'),(38,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 8 del artista \'Omar Emilio Antonio\' (ID: 9) ha sido validada.','2025-11-22 16:31:15'),(39,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 25 del artista \'Lohana Corbalan\' (ID: 24) ha sido validada.','2025-11-22 17:17:17'),(40,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 26 del artista \'ariel enriquez\' (ID: 25) ha sido validada.','2025-11-22 18:08:47'),(41,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 27 del artista \'Coraje Abalos\' (ID: 26) ha sido validada.','2025-11-22 18:34:56'),(42,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 28 del artista \'Liston Listin\' (ID: 28) ha sido validada.','2025-11-23 05:25:05'),(43,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 29 del artista \'Tralalero Tralala\' (ID: 29) ha sido validada.','2025-11-23 20:20:16'),(44,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 31 del artista \'Tralalero Tralala\' (ID: 29) ha sido validada.','2025-11-23 20:36:09'),(45,1,'admin','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 30 del artista \'Tralalero Tralala\' (ID: 29) ha sido validada.','2025-11-24 01:50:02'),(46,1,'admin','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 33 del artista \'Nuev Cuenta\' (ID: 31) ha sido validada.','2025-11-24 02:07:06'),(47,1,'admin','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 32 del artista \'Nuev Cuenta\' (ID: 31) ha sido validada.','2025-11-24 02:10:45'),(48,3,'validador','VALIDACIÓN DE PUBLICACIÓN','Publicación ID: 34 del artista \'Nuev Cuenta\' (ID: 31) ha sido validada.','2025-11-24 02:19:42');
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

-- Dump completed on 2025-11-24  4:09:20
