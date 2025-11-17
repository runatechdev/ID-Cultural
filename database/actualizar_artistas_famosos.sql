-- Agregar campos faltantes a artistas_famosos si no existen
ALTER TABLE artistas_famosos ADD COLUMN IF NOT EXISTS emoji VARCHAR(2);
ALTER TABLE artistas_famosos ADD COLUMN IF NOT EXISTS badge VARCHAR(50) DEFAULT 'Artista';
ALTER TABLE artistas_famosos ADD COLUMN IF NOT EXISTS orden_visualizacion INT DEFAULT 0;
ALTER TABLE artistas_famosos ADD COLUMN IF NOT EXISTS activo BOOLEAN DEFAULT 1;
ALTER TABLE artistas_famosos ADD COLUMN IF NOT EXISTS destacado BOOLEAN DEFAULT 0;

-- NOTA: La columna categoria ya existe como ENUM en minúsculas ('musica','literatura','artes_plasticas',...)
-- No modificamos el ENUM para evitar conflictos.

-- Insertar los 10 artistas famosos (si no existen)
INSERT IGNORE INTO artistas_famosos (nombre_completo, categoria, subcategoria, biografia, emoji, badge, activo) VALUES
('Mercedes Sosa', 'musica', 'Folklore/Nuevo Cancionero', 'Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano.', '🎤', 'Leyenda', 1),
('Andrés Chazarreta', 'musica', 'Folklore', 'Músico, compositor y folclorista argentino. Considerado el Patriarca del Folklore Argentino.', '🎸', 'Leyenda', 1),
('Jacinto Piedra', 'musica', 'Chacarera', 'Músico y compositor santiagueño, especializado en chacarera.', '🎵', 'Regional', 1),
('Raly Barrionuevo', 'musica', 'Folklore Contemporáneo', 'Cantautor argentino, exponente del folklore contemporáneo.', '🎤', 'Actual', 1),
('Juan Carlos Dávalos', 'literatura', 'Narrativa/Poesía', 'Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino.', '📚', 'Clásico', 1),
('Bernardo Canal Feijóo', 'literatura', 'Ensayo/Historia', 'Escritor, ensayista, historiador y pensador argentino.', '📖', 'Intelectual', 1),
('Los Manseros Santiagueños', 'musica', 'Folklore/Chacarera', 'Conjunto folklórico emblemático de Santiago del Estero.', '🎶', 'Legendario', 1),
('Horacio Banegas', 'musica', 'Folklore/Chacarera', 'Músico, compositor y poeta santiagueño.', '✍️', 'Poeta', 1),
('Alfredo Gogna', 'artes_plasticas', 'Pintura', 'Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero.', '🎨', 'Pintor', 1),
('Ricardo y Francisco Sola', 'artes_plasticas', 'Escultura', 'Hermanos escultores reconocidos por sus obras monumentales.', '🗿', 'Escultores', 1);
