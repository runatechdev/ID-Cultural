-- ====================================================================
-- SCRIPT DE DEMO COMPLETO - ID CULTURAL
-- Crea 20 artistas con credenciales + obras validadas y pendientes
-- ====================================================================

-- Paso 1: Limpiar datos existentes
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE publicaciones;
TRUNCATE TABLE intereses_artista;
TRUNCATE TABLE artistas;
SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
-- GRUPO 1: 10 ARTISTAS CON OBRAS VALIDADAS (1 obra validada + 3 pendientes c/u)
-- ====================================================================

-- Password: clave123 (hash bcrypt)
-- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil, motivo_rechazo) VALUES
-- 1. Carolina López - Música
(1, 'Carolina', 'López', '1985-03-15', 'femenino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero', 'carolina.lopez@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado', 
 'Cantautora folclórica con más de 15 años de trayectoria. Ganadora del Festival de Cosquín 2018. Mi música fusiona las raíces santiagueñas con sonoridades contemporáneas.', 
 'Canto, Composición, Guitarra', '@carolina.lopez', 'carolina.lopez.musica', '@carolinalopez', 'https://carolinalopez.com.ar', 
 '/uploads/imagenes/perfil1.jpg', 'validado', NULL),

-- 2. Martín Gómez - Artes Visuales
(2, 'Martín', 'Gómez', '1990-07-22', 'masculino', 'Argentina', 'Santiago del Estero', 'La Banda', 'martin.gomez@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Artista visual especializado en pintura mural y arte urbano. Mis obras reflejan la identidad cultural del norte argentino con técnicas modernas de muralismo.',
 'Pintura Mural, Arte Urbano, Ilustración', '@martin.gomez.arte', 'martin.gomez.artista', '@martingomezarte', 'https://martingomez.art',
 '/uploads/imagenes/perfil2.jpg', 'validado', NULL),

-- 3. Lucía Fernández - Danza
(3, 'Lucía', 'Fernández', '1988-11-05', 'femenino', 'Argentina', 'Santiago del Estero', 'Termas de Río Hondo', 'lucia.fernandez@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Bailarina y coreógrafa especializada en danzas folclóricas argentinas. Directora del Ballet Folclórico Termas. Representé a Argentina en festivales internacionales.',
 'Danza Folclórica, Coreografía, Chacarera', '@lucia.fernandez.danza', 'lucia.fernandez.ballet', '@luciafernandezdanza', 'https://luciafernandez.com.ar',
 '/uploads/imagenes/perfil3.jpg', 'validado', NULL),

-- 4. Roberto Díaz - Literatura
(4, 'Roberto', 'Díaz', '1975-04-18', 'masculino', 'Argentina', 'Santiago del Estero', 'Añatuya', 'roberto.diaz@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Escritor y poeta. Autor de 5 libros de poesía y 2 novelas. Premio Nacional de Literatura 2020. Mi obra explora la cosmovisión del monte santiagueño.',
 'Poesía, Narrativa, Ensayo', '@roberto.diaz.escritor', 'roberto.diaz.autor', '@robertodiaz', 'https://robertodiaz.com.ar',
 '/uploads/imagenes/perfil4.jpg', 'validado', NULL),

-- 5. Sofía Morales - Teatro
(5, 'Sofía', 'Morales', '1992-09-30', 'femenino', 'Argentina', 'Santiago del Estero', 'Frías', 'sofia.morales@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Actriz y directora teatral. Fundadora del grupo Teatro del Norte. Especializada en dramaturgia regional y obras que revalorizan las historias locales.',
 'Actuación, Dirección Teatral, Dramaturgia', '@sofia.morales.teatro', 'sofia.morales.actriz', '@sofiamorales', 'https://sofiamorales.com.ar',
 '/uploads/imagenes/perfil5.jpg', 'validado', NULL),

-- 6. Gabriel Ruiz - Fotografía
(6, 'Gabriel', 'Ruiz', '1987-12-08', 'masculino', 'Argentina', 'Santiago del Estero', 'Suncho Corral', 'gabriel.ruiz@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Fotógrafo documental especializado en retratos de comunidades rurales. Mis fotografías documentan la vida cotidiana y las tradiciones del interior santiagueño.',
 'Fotografía Documental, Retrato, Paisaje', '@gabriel.ruiz.foto', 'gabriel.ruiz.fotografo', '@gabrielruizfoto', 'https://gabrielruiz.photo',
 '/uploads/imagenes/perfil6.jpg', 'validado', NULL),

-- 7. María Torres - Artesanía
(7, 'María', 'Torres', '1980-06-25', 'femenino', 'Argentina', 'Santiago del Estero', 'Loreto', 'maria.torres@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Artesana textil especializada en tejidos tradicionales santiagueños. Trabajo con lana de oveja y técnicas ancestrales transmitidas por generaciones.',
 'Tejido Artesanal, Telar, Hilado', '@maria.torres.artesana', 'maria.torres.tejidos', '@mariatorres', 'https://mariatorres.com.ar',
 '/uploads/imagenes/perfil7.jpg', 'validado', NULL),

-- 8. Diego Castro - Escultura
(8, 'Diego', 'Castro', '1983-02-14', 'masculino', 'Argentina', 'Santiago del Estero', 'Monte Quemado', 'diego.castro@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Escultor que trabaja con madera de quebracho y algarrobo. Mis esculturas representan figuras mitológicas y elementos de la fauna autóctona santiagueña.',
 'Escultura en Madera, Talla, Arte Público', '@diego.castro.escultor', 'diego.castro.arte', '@diegocastro', 'https://diegocastro.art',
 '/uploads/imagenes/perfil8.jpg', 'validado', NULL),

-- 9. Valentina Sosa - Música
(9, 'Valentina', 'Sosa', '1995-08-19', 'femenino', 'Argentina', 'Santiago del Estero', 'Quimilí', 'valentina.sosa@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Violinista de música folclórica y tango. Integrante del Cuarteto Santiago. Mi música fusiona el violín clásico con ritmos tradicionales del norte.',
 'Violín, Música Folclórica, Tango', '@valentina.sosa.violin', 'valentina.sosa.musica', '@valentinasosa', 'https://valentinasosa.com.ar',
 '/uploads/imagenes/perfil9.jpg', 'validado', NULL),

-- 10. Federico Ríos - Audiovisual
(10, 'Federico', 'Ríos', '1991-05-11', 'masculino', 'Argentina', 'Santiago del Estero', 'Clodomira', 'federico.rios@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Realizador audiovisual y documentalista. Mis documentales exploran la cultura, música y tradiciones del interior de Santiago del Estero.',
 'Dirección, Producción Audiovisual, Documentales', '@federico.rios.cine', 'federico.rios.audiovisual', '@federicorioscine', 'https://federicorios.com.ar',
 '/uploads/imagenes/perfil10.jpg', 'validado', NULL),

-- ====================================================================
-- GRUPO 2: 10 ARTISTAS SIN OBRAS VALIDADAS (solo 1 obra pendiente c/u)
-- ====================================================================

(11, 'Carla', 'Medina', '1994-01-20', 'femenino', 'Argentina', 'Santiago del Estero', 'Selva', 'carla.medina@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Pintora contemporánea. Mis obras exploran la identidad femenina en el contexto rural santiagueño con técnicas mixtas y colores vibrantes.',
 'Pintura Contemporánea, Técnicas Mixtas', '@carla.medina.arte', 'carla.medina.pintora', '@carlamedina', NULL,
 '/uploads/imagenes/perfil11.jpg', 'validado', NULL),

(12, 'Juan', 'Paz', '1989-10-03', 'masculino', 'Argentina', 'Santiago del Estero', 'Bandera', 'juan.paz@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Guitarrista y compositor. Mi música fusiona el folclore argentino con elementos de jazz y blues, creando un sonido único e innovador.',
 'Guitarra, Composición Musical, Jazz Folclórico', '@juan.paz.musica', 'juan.paz.guitarrista', '@juanpazmusic', NULL,
 '/uploads/imagenes/perfil12.jpg', 'validado', NULL),

(13, 'Ana', 'Vega', '1993-07-16', 'femenino', 'Argentina', 'Santiago del Estero', 'Villa Ojo de Agua', 'ana.vega@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Ceramista y escultora. Trabajo con arcilla local creando piezas funcionales y decorativas inspiradas en la cerámica precolombina.',
 'Cerámica, Escultura, Alfarería', '@ana.vega.ceramica', 'ana.vega.artesana', '@anavega', NULL,
 '/uploads/imagenes/perfil13.jpg', 'validado', NULL),

(14, 'Pablo', 'Luna', '1986-04-29', 'masculino', 'Argentina', 'Santiago del Estero', 'Pinto', 'pablo.luna@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Actor y dramaturgo. Mis obras teatrales abordan temáticas sociales del interior argentino con humor y crítica constructiva.',
 'Actuación, Dramaturgia, Dirección', '@pablo.luna.teatro', 'pablo.luna.actor', '@pabloluna', NULL,
 '/uploads/imagenes/perfil14.jpg', 'validado', NULL),

(15, 'Laura', 'Ortiz', '1990-12-07', 'femenino', 'Argentina', 'Santiago del Estero', 'Ojo de Agua', 'laura.ortiz@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Bailarina de danza contemporánea. Mi trabajo explora la conexión entre el cuerpo y el paisaje santiagueño a través del movimiento.',
 'Danza Contemporánea, Performance, Coreografía', '@laura.ortiz.danza', 'laura.ortiz.bailarina', '@lauraortiz', NULL,
 '/uploads/imagenes/perfil15.jpg', 'validado', NULL),

(16, 'Ramiro', 'Silva', '1984-09-23', 'masculino', 'Argentina', 'Santiago del Estero', 'Fernández', 'ramiro.silva@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Ilustrador y diseñador gráfico. Mis ilustraciones reflejan leyendas y mitos del folclore santiagueño con un estilo moderno y colorido.',
 'Ilustración, Diseño Gráfico, Arte Digital', '@ramiro.silva.ilustra', 'ramiro.silva.arte', '@ramirosilva', NULL,
 '/uploads/imagenes/perfil16.jpg', 'validado', NULL),

(17, 'Daniela', 'Rojas', '1996-03-12', 'femenino', 'Argentina', 'Santiago del Estero', 'Sumampa', 'daniela.rojas@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Cantante lírica y profesora de canto. Mi objetivo es acercar la música clásica a las comunidades rurales y formar nuevos talentos.',
 'Canto Lírico, Pedagogía Musical, Dirección Coral', '@daniela.rojas.soprano', 'daniela.rojas.canto', '@danielarojas', NULL,
 '/uploads/imagenes/perfil17.jpg', 'validado', NULL),

(18, 'Nicolás', 'Vargas', '1988-06-28', 'masculino', 'Argentina', 'Santiago del Estero', 'Tintina', 'nicolas.vargas@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Fotógrafo de naturaleza especializado en fauna autóctona. Mis fotografías documentan la biodiversidad del monte santiagueño.',
 'Fotografía de Naturaleza, Fauna, Conservación', '@nicolas.vargas.foto', 'nicolas.vargas.naturaleza', '@nicolasvargas', NULL,
 '/uploads/imagenes/perfil18.jpg', 'validado', NULL),

(19, 'Florencia', 'Bravo', '1992-11-15', 'femenino', 'Argentina', 'Santiago del Estero', 'Campo Gallo', 'florencia.bravo@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Escritora de cuentos infantiles. Mis historias rescatan leyendas santiagueñas adaptadas para niños, promoviendo la lectura y la identidad cultural.',
 'Literatura Infantil, Narración Oral, Ilustración', '@florencia.bravo.cuentos', 'florencia.bravo.escritora', '@florenciabravo', NULL,
 '/uploads/imagenes/perfil19.jpg', 'validado', NULL),

(20, 'Ezequiel', 'Herrera', '1987-08-04', 'masculino', 'Argentina', 'Santiago del Estero', 'Pozo Hondo', 'ezequiel.herrera@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista', 'validado',
 'Percusionista de música folclórica. Especializado en bombo legüero, mi trabajo mantiene viva la tradición percusiva del norte argentino.',
 'Percusión, Bombo Legüero, Música Folclórica', '@ezequiel.herrera.bombo', 'ezequiel.herrera.percusion', '@ezequielherrera', NULL,
 '/uploads/imagenes/perfil20.jpg', 'validado', NULL);

-- ====================================================================
-- INTERESES DE ARTISTAS
-- ====================================================================

INSERT INTO intereses_artista (artista_id, interes) VALUES
(1, 'musica'), (2, 'artes_visuales'), (3, 'danza'), (4, 'literatura'), (5, 'teatro'),
(6, 'fotografia'), (7, 'artesania'), (8, 'escultura'), (9, 'musica'), (10, 'audiovisual'),
(11, 'artes_visuales'), (12, 'musica'), (13, 'artesania'), (14, 'teatro'), (15, 'danza'),
(16, 'artes_visuales'), (17, 'musica'), (18, 'fotografia'), (19, 'literatura'), (20, 'musica');

-- ====================================================================
-- PUBLICACIONES/OBRAS
-- ====================================================================

-- OBRAS VALIDADAS (1 por cada uno de los primeros 10 artistas)
INSERT INTO publicaciones (id, usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(1, 1, 'Canto al Monte Santiagueño', 'Álbum de 12 canciones que explora las raíces folclóricas de Santiago del Estero. Grabado en estudios locales con músicos de la región.', 'musica', '{"genero": "Folclore", "duracion": "45 minutos", "año": "2024"}', '/uploads/imagenes/obra1.jpg', 'validado', NOW() - INTERVAL 30 DAY, NOW() - INTERVAL 30 DAY, 3, NOW() - INTERVAL 25 DAY),

(2, 2, 'Murales de la Identidad', 'Serie de 5 murales en escuelas públicas de La Banda representando la historia y cultura local con técnicas de arte urbano.', 'artes_visuales', '{"tecnica": "Acrílico sobre pared", "dimensiones": "Varios tamaños", "año": "2024"}', '/uploads/imagenes/obra2.jpg', 'validado', NOW() - INTERVAL 28 DAY, NOW() - INTERVAL 28 DAY, 3, NOW() - INTERVAL 23 DAY),

(3, 3, 'Raíces en Movimiento', 'Espectáculo de danza folclórica contemporánea que fusiona chacarera tradicional con danza moderna. Presentado en el Teatro 25 de Mayo.', 'danza', '{"duracion": "60 minutos", "elenco": "12 bailarines", "año": "2024"}', '/uploads/imagenes/obra3.jpg', 'validado', NOW() - INTERVAL 26 DAY, NOW() - INTERVAL 26 DAY, 3, NOW() - INTERVAL 21 DAY),

(4, 4, 'Voces del Monte', 'Poemario de 50 poemas que exploran la relación del hombre con el paisaje santiagueño. Publicado por Editorial del Norte.', 'literatura', '{"genero": "Poesía", "paginas": "120", "editorial": "Editorial del Norte", "año": "2024"}', '/uploads/imagenes/obra4.jpg', 'validado', NOW() - INTERVAL 24 DAY, NOW() - INTERVAL 24 DAY, 3, NOW() - INTERVAL 19 DAY),

(5, 5, 'Bajo el Quebracho', 'Obra teatral que narra la vida de una familia rural santiagueña a lo largo de tres generaciones. Estrenada en Teatro Municipal.', 'teatro', '{"duracion": "90 minutos", "elenco": "8 actores", "año": "2024"}', '/uploads/imagenes/obra5.jpg', 'validado', NOW() - INTERVAL 22 DAY, NOW() - INTERVAL 22 DAY, 3, NOW() - INTERVAL 17 DAY),

(6, 6, 'Retratos del Interior', 'Exposición fotográfica de 30 retratos de pobladores rurales de Santiago del Estero, capturando su vida cotidiana y tradiciones.', 'fotografia', '{"tecnica": "Fotografía analógica", "cantidad": "30 fotos", "año": "2024"}', '/uploads/imagenes/obra6.jpg', 'validado', NOW() - INTERVAL 20 DAY, NOW() - INTERVAL 20 DAY, 3, NOW() - INTERVAL 15 DAY),

(7, 7, 'Tejidos Ancestrales', 'Colección de 15 piezas textiles realizadas con lana de oveja y técnicas de telar tradicionales transmitidas por generaciones.', 'artesania', '{"tecnica": "Telar manual", "material": "Lana de oveja", "año": "2024"}', '/uploads/imagenes/obra7.jpg', 'validado', NOW() - INTERVAL 18 DAY, NOW() - INTERVAL 18 DAY, 3, NOW() - INTERVAL 13 DAY),

(8, 8, 'Guardianes del Bosque', 'Serie escultórica de 6 piezas en madera de quebracho representando animales autóctonos del monte santiagueño.', 'escultura', '{"material": "Quebracho", "cantidad": "6 esculturas", "año": "2024"}', '/uploads/imagenes/obra8.jpg', 'validado', NOW() - INTERVAL 16 DAY, NOW() - INTERVAL 16 DAY, 3, NOW() - INTERVAL 11 DAY),

(9, 9, 'Melodías del Norte', 'Concierto de violín con repertorio de chacareras, zambas y tangos. Grabado en vivo en el Teatro Sarmiento.', 'musica', '{"genero": "Folclore/Tango", "duracion": "55 minutos", "año": "2024"}', '/uploads/imagenes/obra9.jpg', 'validado', NOW() - INTERVAL 14 DAY, NOW() - INTERVAL 14 DAY, 3, NOW() - INTERVAL 9 DAY),

(10, 10, 'Historias de Tierra Adentro', 'Documental de 45 minutos sobre tradiciones y oficios rurales de Santiago del Estero. Proyectado en festivales nacionales.', 'audiovisual', '{"duracion": "45 minutos", "formato": "Documental", "año": "2024"}', '/uploads/imagenes/obra10.jpg', 'validado', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, 3, NOW() - INTERVAL 7 DAY);

-- OBRAS PENDIENTES PARA LOS PRIMEROS 10 ARTISTAS (3 por artista = 30 obras)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion) VALUES
-- Artista 1 - Carolina López
(1, 'Chacareras Nuevas', 'EP de 6 canciones inéditas con ritmos de chacarera renovados.', 'musica', '{"genero": "Folclore Contemporáneo"}', '/uploads/imagenes/obra11.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(1, 'Concierto Acústico', 'Registro de show acústico en Plaza Libertad.', 'musica', '{"genero": "Acústico"}', '/uploads/imagenes/obra12.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(1, 'Zamba del Reencuentro', 'Single dedicado a las raíces santiagueñas.', 'musica', '{"genero": "Zamba"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 2 - Martín Gómez
(2, 'Arte en las Calles', 'Intervención urbana en 10 esquinas de la ciudad.', 'artes_visuales', '{"tecnica": "Stencil y spray"}', '/uploads/imagenes/obra13.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(2, 'Colores del Noreste', 'Serie de pinturas sobre cartón reciclado.', 'artes_visuales', '{"tecnica": "Mixta"}', '/uploads/imagenes/obra14.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(2, 'Mural Comunitario', 'Obra colaborativa con vecinos del barrio.', 'artes_visuales', '{"tecnica": "Acrílico"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 3 - Lucía Fernández
(3, 'Danzas del Fuego', 'Coreografía inspirada en rituales ancestrales.', 'danza', '{"duracion": "30 minutos"}', '/uploads/imagenes/obra15.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(3, 'Pasos de la Tierra', 'Workshop de danza folclórica para niños.', 'danza', '{"duracion": "90 minutos"}', '/uploads/imagenes/obra16.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(3, 'Chacarera en Escena', 'Montaje escénico de chacarera tradicional.', 'danza', '{"duracion": "20 minutos"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 4 - Roberto Díaz
(4, 'Cuentos del Río Dulce', 'Colección de 10 cuentos cortos ambientados en la ribera.', 'literatura', '{"genero": "Cuento", "paginas": "80"}', '/uploads/imagenes/obra17.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(4, 'Poemas de Verano', 'Poemario sobre el calor y la vida en el norte.', 'literatura', '{"genero": "Poesía", "paginas": "60"}', '/uploads/imagenes/obra18.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(4, 'Memorias del Monte', 'Ensayo sobre la relación del hombre con la naturaleza.', 'literatura', '{"genero": "Ensayo"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 5 - Sofía Morales
(5, 'La Sequía', 'Obra sobre la crisis hídrica en comunidades rurales.', 'teatro', '{"duracion": "75 minutos", "elenco": "6 actores"}', '/uploads/imagenes/obra19.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(5, 'Monólogos del Interior', 'Serie de 5 monólogos sobre la vida rural.', 'teatro', '{"duracion": "60 minutos"}', '/uploads/imagenes/obra20.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(5, 'Teatro Comunitario', 'Obra creada con vecinos del barrio.', 'teatro', '{"duracion": "50 minutos"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 6 - Gabriel Ruiz
(6, 'Paisajes Olvidados', 'Fotografías de pueblos abandonados del interior.', 'fotografia', '{"cantidad": "25 fotos"}', '/uploads/imagenes/obra21.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(6, 'Retratos en Blanco y Negro', 'Serie de retratos de artesanos locales.', 'fotografia', '{"cantidad": "20 fotos"}', '/uploads/imagenes/obra22.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(6, 'Fauna Autóctona', 'Registro fotográfico de animales del monte.', 'fotografia', '{"cantidad": "30 fotos"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 7 - María Torres
(7, 'Mantas de Colores', 'Colección de mantas tejidas con lana natural.', 'artesania', '{"cantidad": "10 piezas"}', '/uploads/imagenes/obra23.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(7, 'Tapices Narrativos', 'Tapices que cuentan historias locales.', 'artesania', '{"cantidad": "8 piezas"}', '/uploads/imagenes/obra24.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(7, 'Telar Comunitario', 'Proyecto de telar colaborativo.', 'artesania', '{"cantidad": "1 pieza grande"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 8 - Diego Castro
(8, 'Pájaros del Monte', 'Esculturas de aves autóctonas en algarrobo.', 'escultura', '{"material": "Algarrobo", "cantidad": "5"}', '/uploads/imagenes/obra25.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(8, 'Mitología Santiagueña', 'Serie escultórica de figuras mitológicas.', 'escultura', '{"material": "Quebracho", "cantidad": "4"}', '/uploads/imagenes/obra26.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(8, 'Tótem del Norte', 'Escultura monumental de 3 metros.', 'escultura', '{"material": "Quebracho"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 9 - Valentina Sosa
(9, 'Tangos del Interior', 'Interpretación de tangos clásicos con violín.', 'musica', '{"genero": "Tango"}', '/uploads/imagenes/obra27.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(9, 'Fusión Norteña', 'Colaboración con músicos folclóricos.', 'musica', '{"genero": "Fusión"}', '/uploads/imagenes/obra28.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(9, 'Concierto de Cámara', 'Recital de música de cámara regional.', 'musica', '{"genero": "Clásico"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

-- Artista 10 - Federico Ríos
(10, 'Oficios del Monte', 'Cortometraje sobre oficios tradicionales.', 'audiovisual', '{"duracion": "20 minutos"}', '/uploads/imagenes/obra29.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(10, 'Músicos del Interior', 'Documental sobre músicos folclóricos.', 'audiovisual', '{"duracion": "35 minutos"}', '/uploads/imagenes/obra30.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(10, 'Santiago en Imágenes', 'Video documental turístico.', 'audiovisual', '{"duracion": "15 minutos"}', NULL, 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY);

-- OBRAS PENDIENTES PARA LOS ÚLTIMOS 10 ARTISTAS (1 por artista = 10 obras)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion) VALUES
(11, 'Paisajes Internos', 'Serie de pinturas abstractas inspiradas en el paisaje santiagueño.', 'artes_visuales', '{"tecnica": "Acrílico sobre tela"}', '/uploads/imagenes/obra31.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(12, 'Ritmos de la Tierra', 'EP de jazz folclórico con guitarra y bombo.', 'musica', '{"genero": "Jazz Folclórico"}', '/uploads/imagenes/obra32.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(13, 'Vasijas del Tiempo', 'Colección de vasijas cerámicas con diseños precolombinos.', 'artesania', '{"cantidad": "12 piezas"}', '/uploads/imagenes/obra33.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(14, 'Comedia del Interior', 'Obra teatral cómica sobre la vida en el pueblo.', 'teatro', '{"duracion": "70 minutos"}', '/uploads/imagenes/obra34.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(15, 'Cuerpos del Norte', 'Performance de danza contemporánea sobre identidad.', 'danza', '{"duracion": "35 minutos"}', '/uploads/imagenes/obra35.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(16, 'Leyendas Ilustradas', 'Libro ilustrado de leyendas santiagueñas.', 'artes_visuales', '{"paginas": "40"}', '/uploads/imagenes/obra36.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(17, 'Arias del Norte', 'Recital de arias clásicas en español.', 'musica', '{"genero": "Lírico"}', '/uploads/imagenes/obra37.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(18, 'Biodiversidad Santiagueña', 'Catálogo fotográfico de flora y fauna local.', 'fotografia', '{"cantidad": "50 fotos"}', '/uploads/imagenes/obra38.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(19, 'Cuentos del Quebracho', 'Libro infantil ilustrado con cuentos regionales.', 'literatura', '{"paginas": "32"}', '/uploads/imagenes/obra39.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(20, 'Ritmo Legüero', 'Video performance de bombo legüero.', 'musica', '{"duracion": "10 minutos"}', '/uploads/imagenes/obra40.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY);

-- ====================================================================
-- RESUMEN FINAL
-- ====================================================================
-- 20 Artistas validados con credenciales
-- 10 Obras validadas (artistas 1-10)
-- 40 Obras pendientes de validación (30 de artistas 1-10, 10 de artistas 11-20)
-- Total: 50 obras para demostración
-- ====================================================================
