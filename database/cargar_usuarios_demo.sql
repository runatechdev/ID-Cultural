-- ============================================================================
-- SCRIPT DE CARGA DE USUARIOS DEMO - ID Cultural
-- Generado: 20 de noviembre de 2025
-- Descripción: Carga de 10 artistas demo con perfiles completos
-- ============================================================================

-- Nota: Las contraseñas hasheadas a continuación son:
-- password_hash("demo123", PASSWORD_DEFAULT) = $2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom
-- password_hash("demo456", PASSWORD_DEFAULT) = $2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm

-- ============================================================================
-- LIMPIEZA SELECTIVA (COMENTAR SI NO DESEA ELIMINAR DATOS)
-- ============================================================================
-- DELETE FROM intereses_artista WHERE artista_id > 9;
-- DELETE FROM publicaciones WHERE artista_id > 9;
-- DELETE FROM artistas WHERE id > 9;

-- ============================================================================
-- INSERCIÓN DE ARTISTAS DEMO
-- ============================================================================

-- Artista 1: Músico Folk
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    10, 'Juan', 'Reyes', '1990-05-15', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'juan.reyes.demo@demo.com', '$2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom', 'artista', 'validado',
    'Músico folk tradicional especializado en chacarera y vidala. Con 15 años de experiencia en la difusión de la música folklórica santiagueña.',
    'Música, Chacarera, Composición',
    '@juanreyesmusic', 'Juan Reyes Música', '@juanreyes_folk', 'https://juanreyes.local',
    '/uploads/avatars/juan_reyes.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 2: Poeta y Escritor
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    11, 'María', 'Fernández', '1985-08-22', 'femenino', 'Argentina', 'Santiago del Estero', 'La Banda',
    'maria.fernandez.demo@demo.com', '$2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm', 'artista', 'validado',
    'Poetisa y escritora contemporánea. Sus obras reflejan la identidad cultural y la historia de Santiago del Estero. Publicada en antologías nacionales.',
    'Literatura, Poesía, Narrativa',
    '@maria.fernandez.writes', 'María Fernández', '@mfernandez_lit', 'https://mariafernandez.local',
    '/uploads/avatars/maria_fernandez.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 3: Pintor
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    12, 'Carlos', 'Méndez', '1992-03-10', 'masculino', 'Argentina', 'Santiago del Estero', 'Termas de Río Hondo',
    'carlos.mendez.demo@demo.com', '$2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom', 'artista', 'pendiente',
    'Artista plástico dedicado a la pintura abstracta y figurativa. Sus obras exploran la relación entre la naturaleza santiagueña y la modernidad.',
    'Artes Visuales, Pintura, Diseño',
    '@carlos_mendez_art', 'Carlos Méndez Art', '@cmendez_painter', NULL,
    '/uploads/avatars/carlos_mendez.jpg', 'pendiente'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 4: Danzarín
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    13, 'Ana', 'González', '1995-07-18', 'femenino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'ana.gonzalez.demo@demo.com', '$2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm', 'artista', 'validado',
    'Bailarina profesional y coreógrafa. Especializada en danzas folclóricas santiagueñas. Ha participado en festivales nacionales e internacionales.',
    'Danza, Coreografía, Folklore',
    '@ana_gonzalez_danza', 'Ana González Danza', '@agonzalez_dance', 'https://anagdanza.local',
    '/uploads/avatars/ana_gonzalez.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 5: Músico Contemporáneo
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    14, 'Lucas', 'Silva', '1998-11-05', 'masculino', 'Argentina', 'Santiago del Estero', 'La Banda',
    'lucas.silva.demo@demo.com', '$2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom', 'artista', 'rechazado',
    'Productor musical y beatmaker. Fusiona géneros tradicionales con electrónica. Aún en desarrollo de su identidad artística.',
    'Música Electrónica, Producción',
    '@lucas_silva_beats', 'Lucas Silva Music', NULL, NULL,
    '/uploads/avatars/lucas_silva.jpg', 'rechazado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 6: Artesana
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    15, 'Rosario', 'Díaz', '1980-02-14', 'femenino', 'Argentina', 'Santiago del Estero', 'Tintina',
    'rosario.diaz.demo@demo.com', '$2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm', 'artista', 'validado',
    'Maestra artesana en textiles y tejidos tradicionales. Preserva técnicas ancestrales santiagueñas. Sus obras son expuestas en galerías nacionales.',
    'Artesanía, Textiles, Tejidos',
    '@rosario_textiles', 'Rosario Díaz Artesanías', '@rosario_tejedora', 'https://rosariodiaz.local',
    '/uploads/avatars/rosario_diaz.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 7: Fotógrafo
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    16, 'Miguel', 'Torres', '1988-09-20', 'masculino', 'Argentina', 'Santiago del Estero', 'Frías',
    'miguel.torres.demo@demo.com', '$2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom', 'artista', 'pendiente',
    'Fotógrafo documentalista. Sus series fotográficas capturan la cotidianeidad y tradiciones de las comunidades santiagueñas.',
    'Fotografía, Documental Visual',
    '@miguel.torres.foto', 'Miguel Torres Fotografía', '@mtorres_cam', NULL,
    '/uploads/avatars/miguel_torres.jpg', 'pendiente'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 8: Dramaturgo
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    17, 'Federico', 'López', '1991-06-08', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'federico.lopez.demo@demo.com', '$2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm', 'artista', 'validado',
    'Dramaturgo y director teatral. Sus obras abordan temas de identidad cultural y conflictos sociales. Ha dirigido más de 20 producciones teatrales.',
    'Teatro, Dramaturgia, Dirección',
    '@federico_teatro', 'Federico López Teatro', '@flopez_director', 'https://federicoteatro.local',
    '/uploads/avatars/federico_lopez.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 9: Música Clásica
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    18, 'Isabella', 'Ruiz', '1993-04-25', 'femenino', 'Argentina', 'Santiago del Estero', 'La Banda',
    'isabella.ruiz.demo@demo.com', '$2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom', 'artista', 'validado',
    'Pianista y compositora clásica. Egresada del Conservatorio Nacional. Interpreta obras de compositores santiagueños olvidados.',
    'Música Clásica, Piano, Composición',
    '@isabella_piano', 'Isabella Ruiz Música', '@iruiz_classica', 'https://isabellaruiz.local',
    '/uploads/avatars/isabella_ruiz.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- Artista 10: Escultor
INSERT INTO artistas (
    id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, biografia, especialidades,
    instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil
) VALUES (
    19, 'Roberto', 'Navarro', '1986-12-30', 'masculino', 'Argentina', 'Santiago del Estero', 'Frías',
    'roberto.navarro.demo@demo.com', '$2y$10$Qm3ZYvVqRzQn7eTq3xzF9.eZ2nQpJ8mKxL5pWsRvBqM6yKqN4DcTm', 'artista', 'validado',
    'Escultor contemporáneo. Trabaja con materiales locales y recuperados. Sus esculturas reflejan la memoria colectiva santiagueña.',
    'Escultura, Artes Visuales, Instalación',
    '@roberto_navarro_escultor', 'Roberto Navarro Escultura', '@rnavarro_sculpt', 'https://robertonavarro.local',
    '/uploads/avatars/roberto_navarro.jpg', 'validado'
) ON DUPLICATE KEY UPDATE id=id;

-- ============================================================================
-- INSERCIÓN DE INTERESES/ESPECIALIDADES
-- ============================================================================

INSERT INTO intereses_artista (artista_id, interes) VALUES
-- Juan Reyes
(10, 'musica'),
(10, 'folklore'),
(10, 'composicion'),

-- María Fernández
(11, 'literatura'),
(11, 'poesia'),
(11, 'narrativa'),

-- Carlos Méndez
(12, 'artes_visuales'),
(12, 'pintura'),
(12, 'diseño'),

-- Ana González
(13, 'danza'),
(13, 'coreografia'),
(13, 'folklore'),

-- Lucas Silva
(14, 'musica'),
(14, 'electronica'),
(14, 'produccion'),

-- Rosario Díaz
(15, 'artesania'),
(15, 'textiles'),
(15, 'tradicion'),

-- Miguel Torres
(16, 'fotografia'),
(16, 'documentalismo'),
(16, 'visual'),

-- Federico López
(17, 'teatro'),
(17, 'dramaturgia'),
(17, 'direccion'),

-- Isabella Ruiz
(18, 'musica_clasica'),
(18, 'piano'),
(18, 'composicion'),

-- Roberto Navarro
(19, 'escultura'),
(19, 'artes_visuales'),
(19, 'instalacion')
ON DUPLICATE KEY UPDATE artista_id=artista_id;

-- ============================================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- ============================================================================

-- Mostrar artistas insertados
SELECT 
    id, 
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    especialidades,
    status_perfil
FROM artistas 
WHERE id BETWEEN 10 AND 19
ORDER BY id;

-- Contar artistas demo
SELECT COUNT(*) as total_artistas_demo FROM artistas WHERE id BETWEEN 10 AND 19;

-- Contar intereses
SELECT COUNT(*) as total_intereses_demo FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19;

-- ============================================================================
-- NOTAS IMPORTANTES
-- ============================================================================
-- 
-- 1. CONTRASEÑAS DEMO:
--    Usuario: demo123  (contraseña 1 y 2)
--    Usuario: demo456  (contraseña 3, 4, 5, etc. alternado)
--
-- 2. ACCESO:
--    Todos los artistas tienen email y contraseña para login
--    Los emails son de formato: nombre.apellido.demo@demo.com
--
-- 3. ESTADOS:
--    - validado: 6 artistas (1, 2, 4, 6, 8, 9, 10)
--    - pendiente: 2 artistas (3, 7)
--    - rechazado: 1 artista (5)
--    Esto simula un sistema realista con diferentes estados
--
-- 4. FOTOS DE PERFIL:
--    Usa placeholders en /uploads/avatars/
--    Puedes reemplazar con imágenes reales después
--
-- 5. PARA ELIMINAR ESTOS ARTISTAS:
--    DELETE FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19;
--    DELETE FROM publicaciones WHERE artista_id BETWEEN 10 AND 19;
--    DELETE FROM artistas WHERE id BETWEEN 10 AND 19;
--
-- ============================================================================
