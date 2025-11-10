-- ============================================
-- TABLA: artistas_famosos
-- Descripción: Artistas históricos y destacados de Santiago del Estero
-- Autor: Ingeniero Web - ID Cultural
-- Fecha: 2025-11-10
-- ============================================

CREATE TABLE IF NOT EXISTS `artistas_famosos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` VARCHAR(255) NOT NULL,
  `nombre_artistico` VARCHAR(255) DEFAULT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `fecha_fallecimiento` DATE DEFAULT NULL COMMENT 'NULL si está vivo',
  `lugar_nacimiento` VARCHAR(255) DEFAULT NULL,
  `municipio` VARCHAR(100) NOT NULL DEFAULT 'Santiago del Estero',
  `provincia` VARCHAR(100) NOT NULL DEFAULT 'Santiago del Estero',
  `pais` VARCHAR(100) NOT NULL DEFAULT 'Argentina',
  `categoria` ENUM('musica', 'literatura', 'artes_plasticas', 'danza', 'teatro', 'cine', 'artesania', 'folklore') NOT NULL,
  `subcategoria` VARCHAR(100) DEFAULT NULL COMMENT 'Ej: Chacarera, Pintura, Escultura, etc',
  `biografia` TEXT NOT NULL,
  `logros_premios` TEXT DEFAULT NULL COMMENT 'Premios y reconocimientos',
  `obras_destacadas` TEXT DEFAULT NULL COMMENT 'Lista de obras principales',
  `foto_perfil` VARCHAR(255) DEFAULT NULL,
  `foto_galeria` TEXT DEFAULT NULL COMMENT 'JSON con array de fotos',
  `videos_youtube` TEXT DEFAULT NULL COMMENT 'JSON con links de YouTube',
  `sitio_web` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(255) DEFAULT NULL,
  `facebook` VARCHAR(255) DEFAULT NULL,
  `twitter` VARCHAR(255) DEFAULT NULL,
  `wikipedia_url` VARCHAR(255) DEFAULT NULL,
  `activo` BOOLEAN DEFAULT TRUE COMMENT 'Para mostrar/ocultar',
  `destacado` BOOLEAN DEFAULT FALSE COMMENT 'Para artistas más importantes',
  `orden_visualizacion` INT DEFAULT 0 COMMENT 'Para ordenar en la wiki',
  `visitas` INT DEFAULT 0 COMMENT 'Contador de visitas al perfil',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` INT(11) DEFAULT NULL COMMENT 'ID del admin que lo creó',
  `notas_admin` TEXT DEFAULT NULL COMMENT 'Notas internas',
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_municipio` (`municipio`),
  KEY `idx_destacado` (`destacado`),
  KEY `idx_activo` (`activo`),
  KEY `idx_orden` (`orden_visualizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERTAR ARTISTAS FAMOSOS DE SANTIAGO DEL ESTERO
-- ============================================

INSERT INTO `artistas_famosos` 
(`nombre_completo`, `nombre_artistico`, `fecha_nacimiento`, `fecha_fallecimiento`, `lugar_nacimiento`, `municipio`, `categoria`, `subcategoria`, `biografia`, `logros_premios`, `obras_destacadas`, `destacado`, `orden_visualizacion`) 
VALUES

-- MÚSICA / FOLKLORE
('Andrés Chazarreta', 'Don Andrés', '1876-05-16', '1960-10-24', 'Santiago del Estero', 'Santiago del Estero', 'musica', 'Folklore', 
'Músico, compositor y folclorista argentino. Considerado el "Patriarca del Folklore Argentino". Fue el primero en llevar música folklórica argentina a Buenos Aires en 1911, revolucionando la cultura musical del país. Recopiló y difundió la música tradicional santiagueña.',
'Pionero del folklore argentino. Fundó la primera orquesta de música nativa. Reconocido como Padre del Folklore Nacional.',
'La Telesita, Añoranzas, Zamba de Vargas, Chacarera del Rancho, El Crespín',
TRUE, 1),

('Mercedes Sosa', 'La Negra', '1935-07-09', '2009-10-04', 'San Miguel de Tucumán', 'Santiago del Estero', 'musica', 'Folklore/Nuevo Cancionero',
'Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano. Aunque nació en Tucumán, gran parte de su familia era santiagueña y su repertorio incluyó muchas obras del folklore de Santiago del Estero. Icono de la música argentina y latinoamericana.',
'6 Premios Grammy Latino. Premio Konex de Platino. Declarada Ciudadana Ilustre de América. Doctorado Honoris Causa.',
'Gracias a la Vida, Alfonsina y el Mar, Todo Cambia, Balderrama, Zamba para no morir, Como la cigarra',
TRUE, 2),

('Jacinto Piedra', NULL, '1920-03-15', '2007-05-10', 'Loreto', 'Loreto', 'musica', 'Chacarera',
'Músico y compositor santiagueño, especializado en chacarera. Uno de los grandes exponentes del folklore de Santiago del Estero. Su música representa la esencia de la cultura rural santiagueña.',
'Reconocido por su virtuosismo en la guitarra y bombo. Cosechó múltiples premios en festivales folklóricos.',
'Chacarera del Rancho, La Arunguita, Nostalgias Santiagueñas',
TRUE, 3),

('Raly Barrionuevo', NULL, '1967-12-14', NULL, 'Santiago del Estero', 'Santiago del Estero', 'musica', 'Folklore Contemporáneo',
'Cantautor argentino, exponente del folklore contemporáneo. Su música combina tradición santiagueña con elementos modernos. Uno de los artistas más reconocidos del nuevo folklore argentino.',
'Múltiples Premios Gardel. Disco de Oro. Reconocido internacionalmente.',
'Zamba del Laurel, Mariana, Melodía del Atardecer, Canto Versos',
FALSE, 4),

('Dúo Coplanacu', NULL, '1975-01-01', NULL, 'Santiago del Estero', 'Santiago del Estero', 'musica', 'Copla',
'Dúo musical formado por Jorge Fandermole y Roxana Carabajal. Representan la copla y el folklore santiagueño con excelencia artística.',
'Premios Gardel. Reconocimiento Nacional e Internacional.',
'Coplas de mi País, Zamba Azul, La Pomeña',
FALSE, 5),

-- LITERATURA
('Juan Carlos Dávalos', NULL, '1887-08-07', '1959-12-06', 'Santiago del Estero', 'Santiago del Estero', 'literatura', 'Narrativa/Poesía',
'Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino, especialmente Santiago del Estero. Considerado uno de los grandes escritores regionalistas.',
'Premio Nacional de Literatura. Sus cuentos son clásicos de la literatura argentina.',
'Los Buscadores de Oro, Airampo, La Flor del Cardón, Salta',
TRUE, 6),

('Bernardo Canal Feijóo', NULL, '1897-09-04', '1982-01-13', 'Santiago del Estero', 'Santiago del Estero', 'literatura', 'Ensayo/Historia',
'Escritor, ensayista, historiador y pensador argentino. Estudioso profundo de la cultura santiagueña y del noroeste argentino. Figura fundamental del pensamiento regionalista.',
'Doctor Honoris Causa. Premio Konex. Reconocido como intelectual fundamental del NOA.',
'Ensayo sobre la Expresión Popular Artística en Santiago, Burla, Credo, Culpa en la Creación Anónima',
TRUE, 7),

-- ARTES PLÁSTICAS
('Alfredo Gogna', NULL, '1878-01-15', '1972-03-20', 'Santiago del Estero', 'Santiago del Estero', 'artes_plasticas', 'Pintura',
'Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero. Pionero de las artes plásticas en la provincia.',
'Exposiciones nacionales e internacionales. Reconocido maestro de la pintura regional.',
'Paisajes Santiagueños, Serie del Monte, Retratos de Gauchos',
FALSE, 8),

('Ricardo y Francisco Sola', NULL, '1940-05-10', NULL, 'Santiago del Estero', 'Santiago del Estero', 'artes_plasticas', 'Escultura',
'Hermanos escultores reconocidos por sus obras monumentales en Santiago del Estero y Argentina.',
'Múltiples obras públicas y monumentos en la provincia.',
'Monumento a la Madre, Cristo del Cerro, Diversas esculturas urbanas',
FALSE, 9),

-- FOLKLORE/DANZA
('Los Manseros Santiagueños', NULL, '1950-01-01', NULL, 'Santiago del Estero', 'Santiago del Estero', 'musica', 'Folklore/Chacarera',
'Conjunto folklórico emblemático de Santiago del Estero. Formado en 1950, llevan décadas difundiendo la cultura santiagueña.',
'Referentes del folklore santiagueño. Múltiples discos y presentaciones internacionales.',
'Pa\' Santiago me Voy, Chacarera de un Triste, La Telesita',
TRUE, 10),

('Horacio Banegas', NULL, '1946-06-15', '2021-02-11', 'Fernández', 'Fernández', 'musica', 'Folklore/Chacarera',
'Músico, compositor y poeta santiagueño. Figura fundamental del folklore argentino. Sus letras son poesía pura del monte santiagueño.',
'Considerado uno de los mejores letristas del folklore argentino. Cosechó innumerables premios.',
'La Olvidada, Zamba del Quebrachal, De Puro Guapo, El Antigal',
TRUE, 11);

-- ============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ============================================

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_categoria_activo ON artistas_famosos(categoria, activo);
CREATE INDEX idx_destacado_orden ON artistas_famosos(destacado, orden_visualizacion);

-- Índice para búsqueda por nombre
CREATE FULLTEXT INDEX idx_busqueda_nombre ON artistas_famosos(nombre_completo, nombre_artistico, biografia);

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista de artistas vivos
CREATE OR REPLACE VIEW artistas_famosos_vivos AS
SELECT * FROM artistas_famosos 
WHERE fecha_fallecimiento IS NULL AND activo = TRUE
ORDER BY orden_visualizacion;

-- Vista de artistas destacados
CREATE OR REPLACE VIEW artistas_famosos_destacados AS
SELECT * FROM artistas_famosos 
WHERE destacado = TRUE AND activo = TRUE
ORDER BY orden_visualizacion;

-- Vista con edad/años desde fallecimiento
CREATE OR REPLACE VIEW artistas_famosos_con_edad AS
SELECT 
    *,
    CASE 
        WHEN fecha_fallecimiento IS NULL THEN 
            TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE())
        ELSE 
            TIMESTAMPDIFF(YEAR, fecha_nacimiento, fecha_fallecimiento)
    END as edad,
    CASE 
        WHEN fecha_fallecimiento IS NULL THEN 'Vivo'
        ELSE CONCAT('Fallecido hace ', TIMESTAMPDIFF(YEAR, fecha_fallecimiento, CURDATE()), ' años')
    END as estado_vital
FROM artistas_famosos
WHERE activo = TRUE;

-- ============================================
-- COMENTARIOS FINALES
-- ============================================
-- Esta tabla permite:
-- 1. Gestionar artistas históricos y contemporáneos
-- 2. Diferencia entre vivos y fallecidos
-- 3. Sistema de destacados para página principal
-- 4. Contador de visitas para analytics
-- 5. Ordenamiento personalizado
-- 6. Búsqueda eficiente por índices
-- 7. Multimedia: fotos, videos, redes sociales
-- 8. Auditoría completa (creación, actualización)
