-- ============================================================
-- ARTISTAS DEMO PARA PRESENTACIÓN VISUAL DE LA PLATAFORMA
-- ============================================================
-- 10 perfiles de artistas santiagueños con datos realistas
-- Las fotos deben guardarse en: /public/uploads/imagenes/
-- Nombres de archivos: perfil1.jpg hasta perfil10.jpg
-- ============================================================

-- Limpiar artistas anteriores (opcional)
-- DELETE FROM intereses_artista;
-- DELETE FROM publicaciones;
-- DELETE FROM artistas;

-- ============================================================
-- ARTISTA 1: Mercedes del Carmen Sánchez - Música Folclórica
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Mercedes del Carmen',
    'Sánchez',
    '1985-03-15',
    'femenino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero',
    'mercedes.sanchez@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'artista',
    'validado',
    'Cantautora santiagueña especializada en folklore argentino. Con más de 15 años de trayectoria, he recorrido festivales de toda la provincia interpretando chacareras y zambas de mi tierra. Mi música busca preservar las raíces culturales mientras incorporo elementos contemporáneos.',
    'Canto folclórico, Composición, Guitarra',
    '@mercedes_folklorista',
    'Mercedes Sánchez Folklore',
    '@mercedesfolk',
    'https://mercedessanchez.com.ar',
    '/uploads/imagenes/perfil1.jpg',
    'validado'
);

-- Intereses del artista 1
INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'mercedes.sanchez@gmail.com'), 'musica'),
((SELECT id FROM artistas WHERE email = 'mercedes.sanchez@gmail.com'), 'literatura');

-- ============================================================
-- ARTISTA 2: Roberto Carlos Díaz - Artes Visuales
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Roberto Carlos',
    'Díaz',
    '1978-07-22',
    'masculino',
    'Argentina',
    'Santiago del Estero',
    'La Banda',
    'roberto.diaz.arte@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Pintor y muralista nacido en La Banda. Mi obra explora la identidad cultural del norte argentino a través de colores vibrantes y técnicas mixtas. He expuesto en galerías de Buenos Aires, Tucumán y Santiago del Estero. Especializado en murales que cuentan historias de nuestra región.',
    'Pintura, Muralismo, Arte urbano',
    '@roberto_diaz_arte',
    'Roberto Díaz - Artista Visual',
    '@roberdiazarte',
    'https://robertodiazarte.com',
    '/uploads/imagenes/perfil2.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'roberto.diaz.arte@gmail.com'), 'artes_visuales'),
((SELECT id FROM artistas WHERE email = 'roberto.diaz.arte@gmail.com'), 'fotografia');

-- ============================================================
-- ARTISTA 3: Ana María Fernández - Literatura
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Ana María',
    'Fernández',
    '1990-11-08',
    'femenino',
    'Argentina',
    'Santiago del Estero',
    'Termas de Río Hondo',
    'ana.fernandez.escritora@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Escritora y poeta santiagueña. Autora de tres libros de poesía que exploran la memoria colectiva y los paisajes del norte argentino. Mi obra ha sido reconocida con el Premio Provincial de Literatura 2022. Actualmente trabajo en mi primera novela ambientada en las termas.',
    'Poesía, Narrativa, Talleres literarios',
    '@anamaria_escribe',
    'Ana María Fernández - Escritora',
    '@anaferpoesia',
    'https://anafernandezescritora.com.ar',
    '/uploads/imagenes/perfil3.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'ana.fernandez.escritora@gmail.com'), 'literatura');

-- ============================================================
-- ARTISTA 4: Jorge Luis Gómez - Teatro
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Jorge Luis',
    'Gómez',
    '1982-05-30',
    'masculino',
    'Argentina',
    'Santiago del Estero',
    'Frías',
    'jorge.gomez.teatro@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Actor y director teatral con 20 años de trayectoria. Fundador del Grupo Teatral "Raíces del Norte". He dirigido más de 15 obras que rescatan historias locales y leyendas santiagueñas. Dicto talleres de teatro comunitario en escuelas rurales de la provincia.',
    'Actuación, Dirección teatral, Teatro comunitario',
    '@jorge_teatro_sde',
    'Jorge Gómez Teatro',
    '@jorgegomezteatro',
    'https://teatroraicesdelnorte.com.ar',
    '/uploads/imagenes/perfil4.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'jorge.gomez.teatro@gmail.com'), 'teatro'),
((SELECT id FROM artistas WHERE email = 'jorge.gomez.teatro@gmail.com'), 'danza');

-- ============================================================
-- ARTISTA 5: Carla Beatriz Ruiz - Danza Folklórica
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Carla Beatriz',
    'Ruiz',
    '1995-09-12',
    'femenino',
    'Argentina',
    'Santiago del Estero',
    'Añatuya',
    'carla.ruiz.danza@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Bailarina y coreógrafa especializada en danzas folklóricas argentinas. Directora del Ballet Folklórico "Alma Santiagueña". He representado a la provincia en festivales nacionales e internacionales. Mi trabajo busca innovar en la danza tradicional respetando sus raíces.',
    'Danza folklórica, Coreografía, Dirección de ballet',
    '@carla_danza_folklore',
    'Carla Ruiz - Ballet Folklórico',
    '@carlaruizdanza',
    'https://balletalmasan tiaguena.com.ar',
    '/uploads/imagenes/perfil5.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'carla.ruiz.danza@gmail.com'), 'danza'),
((SELECT id FROM artistas WHERE email = 'carla.ruiz.danza@gmail.com'), 'musica');

-- ============================================================
-- ARTISTA 6: Miguel Ángel Torres - Artesanía
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Miguel Ángel',
    'Torres',
    '1970-04-18',
    'masculino',
    'Argentina',
    'Santiago del Estero',
    'Sumampa',
    'miguel.torres.artesano@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Artesano en cuero y madera con técnicas ancestrales. Aprendí el oficio de mi abuelo y hoy enseño a las nuevas generaciones. Creo monturas, riendas, mates y objetos decorativos que reflejan la tradición gaucha santiagueña. Mis piezas han sido exhibidas en ferias artesanales de todo el país.',
    'Talabartería, Platería criolla, Artesanía en madera',
    '@miguel_artesano_sde',
    'Miguel Torres - Artesanías Criollas',
    '@migueltorresartesano',
    NULL,
    '/uploads/imagenes/perfil6.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'miguel.torres.artesano@gmail.com'), 'artesania');

-- ============================================================
-- ARTISTA 7: Lucía Soledad Ramírez - Fotografía
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Lucía Soledad',
    'Ramírez',
    '1988-12-03',
    'femenino',
    'Argentina',
    'Santiago del Estero',
    'Santiago del Estero',
    'lucia.ramirez.foto@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Fotógrafa documental especializada en retratos de comunidades rurales. Mi trabajo registra la vida cotidiana, las tradiciones y los paisajes de Santiago del Estero. He publicado en medios nacionales y participado en exposiciones colectivas. Actualmente desarrollo un proyecto sobre artesanos de la provincia.',
    'Fotografía documental, Fotoperiodismo, Retratos',
    '@lucia_fotografia_sde',
    'Lucía Ramírez Fotografía',
    '@luciafoto',
    'https://luciaramirezfoto.com',
    '/uploads/imagenes/perfil7.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'lucia.ramirez.foto@gmail.com'), 'fotografia'),
((SELECT id FROM artistas WHERE email = 'lucia.ramirez.foto@gmail.com'), 'audiovisual');

-- ============================================================
-- ARTISTA 8: Pablo Martín Herrera - Escultura
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Pablo Martín',
    'Herrera',
    '1975-08-25',
    'masculino',
    'Argentina',
    'Santiago del Estero',
    'Loreto',
    'pablo.herrera.escultor@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Escultor en piedra y madera. Mis obras están inspiradas en la iconografía precolombina y las leyendas santiagueñas. He creado monumentos y esculturas públicas en plazas de varias localidades. Trabajo principalmente con algarrobo, quebracho y piedras de la región.',
    'Escultura en madera, Talla en piedra, Arte público',
    '@pablo_escultor',
    'Pablo Herrera - Escultor',
    '@pabloherreraarte',
    NULL,
    '/uploads/imagenes/perfil8.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'pablo.herrera.escultor@gmail.com'), 'escultura'),
((SELECT id FROM artistas WHERE email = 'pablo.herrera.escultor@gmail.com'), 'artesania');

-- ============================================================
-- ARTISTA 9: Carolina Inés Morales - Audiovisual
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Carolina Inés',
    'Morales',
    '1992-06-14',
    'femenino',
    'Argentina',
    'Santiago del Estero',
    'Monte Quemado',
    'carolina.morales.cine@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Directora y productora audiovisual. Especializada en documentales sobre cultura e identidad regional. Mi cortometraje "Voces del Monte" ganó el Premio Provincial de Cine 2023. Actualmente produzco contenidos para redes que visibilizan artistas y tradiciones santiagueñas.',
    'Dirección audiovisual, Producción, Edición',
    '@caro_cine_sde',
    'Carolina Morales - Cine Documental',
    '@caromoralescine',
    'https://carolinamoralescine.com',
    '/uploads/imagenes/perfil9.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'carolina.morales.cine@gmail.com'), 'audiovisual'),
((SELECT id FROM artistas WHERE email = 'carolina.morales.cine@gmail.com'), 'fotografia');

-- ============================================================
-- ARTISTA 10: Daniel Eduardo Silva - Música Contemporánea
-- ============================================================
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status,
    biografia, especialidades, instagram, facebook, twitter, sitio_web,
    foto_perfil, status_perfil
) VALUES (
    'Daniel Eduardo',
    'Silva',
    '1987-02-20',
    'masculino',
    'Argentina',
    'Santiago del Estero',
    'Quimilí',
    'daniel.silva.musica@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista',
    'validado',
    'Músico multi-instrumentista y productor. Fusiono el folklore santiagueño con ritmos urbanos contemporáneos. He lanzado dos álbumes independientes y colaborado con artistas de todo el país. Mi música celebra nuestras raíces mientras explora nuevas sonoridades.',
    'Producción musical, Guitarra, Bombo legüero, Charango',
    '@daniel_silva_music',
    'Daniel Silva - Música Fusión',
    '@danielsilvamusic',
    'https://danielsilvamusic.com',
    '/uploads/imagenes/perfil10.jpg',
    'validado'
);

INSERT INTO intereses_artista (artista_id, interes) VALUES
((SELECT id FROM artistas WHERE email = 'daniel.silva.musica@gmail.com'), 'musica'),
((SELECT id FROM artistas WHERE email = 'daniel.silva.musica@gmail.com'), 'audiovisual');

-- ============================================================
-- VERIFICACIÓN
-- ============================================================
SELECT 
    CONCAT(nombre, ' ', apellido) AS artista,
    municipio,
    especialidades,
    status,
    foto_perfil
FROM artistas
WHERE status = 'validado'
ORDER BY id;
