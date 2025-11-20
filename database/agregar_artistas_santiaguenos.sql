-- ==================================================
-- AGREGAR ARTISTAS SANTIAGUEÑOS FAMOSOS
-- Este script AGREGA (no borra) 10 artistas famosos
-- ==================================================

-- Los IDs empezarán desde 21 en adelante (después de los 20 existentes)

-- 1. Ramona Galarza - Cantante folklórica
INSERT INTO users (nombre, email, password, role) 
VALUES ('Ramona Galarza', 'ramona.galarza@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @ramona_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @ramona_id,
    'Ramona',
    'Galarza',
    '01/09/1940',
    'Femenino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'ramona.galarza@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Reconocida cantante folklórica santiagueña, conocida como "La Novia del Paraná". Con una trayectoria de décadas en la música folklórica argentina, ha llevado las chacareras y zambas santiagueñas a escenarios de todo el país y el mundo.',
    'Música Folklórica, Canto',
    'ramonagalarzaoficial',
    'https://facebook.com/ramonagalarza',
    'ramonagalarza',
    'https://ramonagalarza.com.ar',
    '/uploads/imagenes/ramona.jpg',
    'validado'
);

-- 2. Jorge Rojas - Cantante y compositor
INSERT INTO users (nombre, email, password, role) 
VALUES ('Jorge Rojas', 'jorge.rojas@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @jorge_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @jorge_id,
    'Jorge',
    'Rojas',
    '15/08/1969',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'jorge.rojas@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Cantautor y músico santiagueño, reconocido tanto por su carrera solista como por su paso por Los Nocheros. Su voz potente y sus composiciones han conquistado al público argentino y latinoamericano.',
    'Música Folklórica, Composición, Canto',
    'jorgerojasoficial',
    'https://facebook.com/jorgerojasoficial',
    'jorgerojas',
    'https://jorgerojas.com.ar',
    '/uploads/imagenes/jorge.jpg',
    'validado'
);

-- 3. Ricardo "Pelusa" Suárez - Músico y compositor
INSERT INTO users (nombre, email, password, role) 
VALUES ('Ricardo Suárez', 'ricardo.suarez@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @ricardo_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @ricardo_id,
    'Ricardo',
    'Suárez',
    '10/03/1950',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'ricardo.suarez@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Conocido como "Pelusa" Suárez, es uno de los guitarristas y compositores más destacados de Santiago del Estero. Su virtuosismo con la guitarra y su profundo conocimiento de la música folklórica lo han convertido en una referencia ineludible.',
    'Música Folklórica, Guitarra, Composición',
    'pelusasuarez',
    'https://facebook.com/pelusasuarez',
    NULL,
    NULL,
    '/uploads/imagenes/ricardo.jpg',
    'validado'
);

-- 4. Peteco Carabajal - Músico, compositor y productor
INSERT INTO users (nombre, email, password, role) 
VALUES ('Peteco Carabajal', 'peteco.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @peteco_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @peteco_id,
    'Peteco',
    'Carabajal',
    '05/12/1956',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'La Banda',
    'peteco.carabajal@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Miembro de la legendaria familia Carabajal, Peteco es un referente absoluto de la música santiagueña. Guitarrista, compositor y productor, ha sabido mantener viva la tradición folklórica mientras la renueva constantemente.',
    'Música Folklórica, Composición, Producción Musical',
    'petecocarabajal',
    'https://facebook.com/petecocarabajal',
    'petecocarabajal',
    'https://petecocarabajal.com',
    '/uploads/imagenes/peteco.jpg',
    'validado'
);

-- 5. Jacqueline "Cacho" Carabajal - Cantante
INSERT INTO users (nombre, email, password, role) 
VALUES ('Jacqueline Carabajal', 'jacqueline.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @jacqueline_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @jacqueline_id,
    'Jacqueline',
    'Carabajal',
    '20/07/1965',
    'Femenino',
    'Argentina',
    'Santiago del Estero',
    'La Banda',
    'jacqueline.carabajal@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Conocida como "Cacho" Carabajal, es parte de la reconocida familia musical santiagueña. Su voz dulce y potente ha cautivado a audiencias de todo el país.',
    'Canto Folklórico, Música Tradicional',
    'cachocbarajal',
    'https://facebook.com/cachocarabajal',
    NULL,
    NULL,
    '/uploads/imagenes/jacqueline.jpg',
    'validado'
);

-- 6. Raly Barrionuevo - Cantautor
INSERT INTO users (nombre, email, password, role) 
VALUES ('Raly Barrionuevo', 'raly.barrionuevo@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @raly_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @raly_id,
    'Raly',
    'Barrionuevo',
    '30/05/1967',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'raly.barrionuevo@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Cantautor santiagueño de reconocida trayectoria nacional e internacional. Su estilo único combina la esencia del folklore con influencias contemporáneas.',
    'Canto, Composición, Música Folklórica',
    'ralybarrionuevo',
    'https://facebook.com/ralybarrionuevo',
    'ralybarrionuevo',
    'https://ralybarrionuevo.com',
    '/uploads/imagenes/raly.jpg',
    'validado'
);

-- 7. Roxana Carabajal - Cantante y percusionista
INSERT INTO users (nombre, email, password, role) 
VALUES ('Roxana Carabajal', 'roxana.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @roxana_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @roxana_id,
    'Roxana',
    'Carabajal',
    '12/11/1970',
    'Femenino',
    'Argentina',
    'Santiago del Estero',
    'La Banda',
    'roxana.carabajal@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Artista integral de la familia Carabajal, Roxana se destaca tanto en el canto como en la percusión. Su presencia escénica y su versatilidad musical la han convertido en una figura imprescindible del folklore santiagueño.',
    'Canto, Percusión, Música Folklórica',
    'roxanacarabajal',
    'https://facebook.com/roxanacarabajal',
    NULL,
    NULL,
    '/uploads/imagenes/roxana.jpg',
    'validado'
);

-- 8. Luciano Carabajal - Guitarrista y compositor
INSERT INTO users (nombre, email, password, role) 
VALUES ('Luciano Carabajal', 'luciano.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @luciano_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @luciano_id,
    'Luciano',
    'Carabajal',
    '25/06/1985',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'La Banda',
    'luciano.carabajal@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Guitarrista virtuoso de la nueva generación de los Carabajal. Luciano ha heredado la maestría musical de su familia y la ha llevado a nuevas dimensiones.',
    'Guitarra, Composición, Música Folklórica',
    'lucianocarabajal',
    'https://facebook.com/lucianocarabajal',
    'lucianocarabajal',
    NULL,
    '/uploads/imagenes/luciano.jpg',
    'validado'
);

-- 9. Ale Brizuela - Cantante folklórica
INSERT INTO users (nombre, email, password, role) 
VALUES ('Ale Brizuela', 'ale.brizuela@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @ale_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @ale_id,
    'Ale',
    'Brizuela',
    '18/04/1975',
    'Femenino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'ale.brizuela@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Cantante santiagueña de gran sensibilidad y técnica vocal. Ale ha desarrollado una carrera sólida interpretando el repertorio folklórico tradicional con un estilo personal y emotivo.',
    'Canto Folklórico, Música Tradicional',
    'alebrizuela',
    'https://facebook.com/alebrizuela',
    NULL,
    NULL,
    '/uploads/imagenes/ale.jpg',
    'validado'
);

-- 10. Dúo Coplanacu - Dúo folklórico
INSERT INTO users (nombre, email, password, role) 
VALUES ('Dúo Coplanacu', 'duo.coplanacu@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artista');

SET @coplanacu_id = LAST_INSERT_ID();

INSERT INTO artistas (id, nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil)
VALUES (
    @coplanacu_id,
    'Dúo',
    'Coplanacu',
    '01/01/1977',
    'Masculino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero Capital',
    'duo.coplanacu@idcultural.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Dúo folklórico santiagueño integrado por Roberto "Cuti" Carabajal y Hernán Figueroa Reyes. Con décadas de trayectoria, son embajadores de la música del norte argentino.',
    'Canto Folklórico, Guitarra, Composición',
    'duocoplanacu',
    'https://facebook.com/duocoplanacu',
    'duocoplanacu',
    'https://coplanacu.com.ar',
    '/uploads/imagenes/coplanacu.jpg',
    'validado'
);

-- Verificación
SELECT 'Artistas santiagueños agregados exitosamente' as mensaje;
SELECT COUNT(*) as total_artistas FROM artistas;
SELECT COUNT(*) as total_users FROM users;
