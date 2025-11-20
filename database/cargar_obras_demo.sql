-- ================================================================
-- CARGAR OBRAS PARA DEMO
-- ================================================================
-- Para los 10 artistas existentes (IDs 1-10):
--   - 1 obra validada cada uno
--   - 3 obras pendientes cada uno
--
-- Para 10 artistas nuevos (IDs 11-20):
--   - 1 obra pendiente cada uno
--
-- NOTA: Las imágenes deben guardarse en:
-- /home/runatechdev/Documentos/Github/ID-Cultural/public/uploads/imagenes/
-- Nombradas como: obra1.jpg, obra2.jpg, ..., obra50.jpg
-- ================================================================

USE idcultural;

-- Primero limpiar obras existentes
TRUNCATE TABLE publicaciones;

-- ================================================================
-- ARTISTAS 1-10: 1 OBRA VALIDADA + 3 OBRAS PENDIENTES CADA UNO
-- ================================================================

-- ARTISTA 1: Laura Fernández (Música)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(1, 'Chacarera del Alba', 'Composición original que fusiona chacarera tradicional con arreglos contemporáneos. Inspirada en los amaneceres del monte santiagueño.', 'musica', '{"plataformas":"Spotify, YouTube Music","sello":"Independiente"}', '/uploads/imagenes/obra1.jpg', 'validado', NOW() - INTERVAL 30 DAY, NOW() - INTERVAL 30 DAY, 1, NOW() - INTERVAL 25 DAY),
(1, 'Vidala de la Luna', 'Vidala interpretada con instrumentos autóctonos del NOA. Grabada en vivo en el Festival de Folklore.', 'musica', '{"plataformas":"Spotify","sello":"Folklore Vivo"}', '/uploads/imagenes/obra2.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY, NULL, NULL),
(1, 'Zamba del Olvido', 'Pieza emotiva que rememora las tradiciones perdidas de nuestros ancestros.', 'musica', '{"plataformas":"YouTube","sello":"Independiente"}', '/uploads/imagenes/obra3.jpg', 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY, NULL, NULL),
(1, 'Gato Santiagueño', 'Interpretación renovada del gato tradicional con percusión moderna.', 'musica', '{"plataformas":"SoundCloud","sello":"Raíces del Norte"}', '/uploads/imagenes/obra4.jpg', 'pendiente_validacion', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY, NULL, NULL);

-- ARTISTA 2: Carlos Rodríguez (Literatura)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(2, 'Cuentos del Monte', 'Recopilación de relatos cortos sobre la vida rural en Santiago del Estero. 15 historias que capturan la esencia de nuestra tierra.', 'literatura', '{"genero-lit":"Narrativa","editorial":"Editorial del Norte"}', '/uploads/imagenes/obra5.jpg', 'validado', NOW() - INTERVAL 28 DAY, NOW() - INTERVAL 28 DAY, 1, NOW() - INTERVAL 20 DAY),
(2, 'Poesía del Silencio', 'Poemario que explora la soledad y belleza del campo santiagueño.', 'literatura', '{"genero-lit":"Poesía","editorial":"Independiente"}', '/uploads/imagenes/obra6.jpg', 'pendiente_validacion', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY, NULL, NULL),
(2, 'El Camino del Quebracho', 'Novela histórica sobre los obrajes en el Chaco Santiagueño.', 'literatura', '{"genero-lit":"Novela","editorial":"Sudamericana"}', '/uploads/imagenes/obra7.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY, NULL, NULL),
(2, 'Leyendas del Río Dulce', 'Compilación de mitos y leyendas transmitidas oralmente por generaciones.', 'literatura', '{"genero-lit":"Leyendas","editorial":"Folklore Editores"}', '/uploads/imagenes/obra8.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY, NULL, NULL);

-- ARTISTA 3: Marta Silva (Danza)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(3, 'Danza de la Tierra', 'Coreografía contemporánea inspirada en las danzas folclóricas del NOA. Presentada en el Teatro 25 de Mayo.', 'danza', '{"tipo-danza":"Contemporáneo","duracion":"12 minutos"}', '/uploads/imagenes/obra9.jpg', 'validado', NOW() - INTERVAL 26 DAY, NOW() - INTERVAL 26 DAY, 1, NOW() - INTERVAL 18 DAY),
(3, 'Chacarera Escénica', 'Fusión de chacarera tradicional con técnicas de danza moderna.', 'danza', '{"tipo-danza":"Folklore Fusión","duracion":"8 minutos"}', '/uploads/imagenes/obra10.jpg', 'pendiente_validacion', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY, NULL, NULL),
(3, 'Ritual del Agua', 'Performance que celebra la importancia del agua en nuestra cultura.', 'danza', '{"tipo-danza":"Performance","duracion":"15 minutos"}', '/uploads/imagenes/obra11.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY, NULL, NULL),
(3, 'Zamba en Movimiento', 'Interpretación coreográfica de la zamba santiagueña.', 'danza', '{"tipo-danza":"Folklore","duracion":"6 minutos"}', '/uploads/imagenes/obra12.jpg', 'pendiente_validacion', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY, NULL, NULL);

-- ARTISTA 4: Roberto Gómez (Teatro)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(4, 'La Salamanca', 'Obra teatral basada en la leyenda popular de La Salamanca. Tres actos que exploran el misterio y la tradición.', 'teatro', '{"duracion-obra":"90 minutos","elenco":"8 actores"}', '/uploads/imagenes/obra13.jpg', 'validado', NOW() - INTERVAL 24 DAY, NOW() - INTERVAL 24 DAY, 1, NOW() - INTERVAL 16 DAY),
(4, 'Los Hacheros', 'Drama sobre la vida de los trabajadores de los obrajes.', 'teatro', '{"duracion-obra":"75 minutos","elenco":"6 actores"}', '/uploads/imagenes/obra14.jpg', 'pendiente_validacion', NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 8 DAY, NULL, NULL),
(4, 'Canto del Crespín', 'Monólogo poético sobre la identidad santiagueña.', 'teatro', '{"duracion-obra":"45 minutos","elenco":"1 actor"}', '/uploads/imagenes/obra15.jpg', 'pendiente_validacion', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY, NULL, NULL),
(4, 'Bajo el Algarrobo', 'Comedia costumbrista sobre la vida en el campo.', 'teatro', '{"duracion-obra":"60 minutos","elenco":"5 actores"}', '/uploads/imagenes/obra16.jpg', 'pendiente_validacion', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY, NULL, NULL);

-- ARTISTA 5: Ana Torres (Artesanía)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(5, 'Tejidos Ancestrales', 'Colección de ponchos y mantas tejidas con técnicas tradicionales transmitidas por generaciones. Lana de oveja natural.', 'artesania', '{"materiales":"Lana de oveja, tintes naturales","tecnica":"Telar tradicional"}', '/uploads/imagenes/obra17.jpg', 'validado', NOW() - INTERVAL 22 DAY, NOW() - INTERVAL 22 DAY, 1, NOW() - INTERVAL 14 DAY),
(5, 'Cerámica del Monte', 'Vasijas decoradas con motivos geométricos ancestrales.', 'artesania', '{"materiales":"Arcilla local","tecnica":"Modelado a mano"}', '/uploads/imagenes/obra18.jpg', 'pendiente_validacion', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 9 DAY, NULL, NULL),
(5, 'Cestería Tradicional', 'Canastos y cestas tejidas con fibras vegetales del monte.', 'artesania', '{"materiales":"Fibras de palma","tecnica":"Tejido en espiral"}', '/uploads/imagenes/obra19.jpg', 'pendiente_validacion', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY, NULL, NULL),
(5, 'Tallas en Quebracho', 'Esculturas pequeñas talladas en madera de quebracho colorado.', 'artesania', '{"materiales":"Quebracho colorado","tecnica":"Tallado"}', '/uploads/imagenes/obra20.jpg', 'pendiente_validacion', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY, NULL, NULL);

-- ARTISTA 6: Diego Martínez (Audiovisual)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(6, 'Santiago en Imágenes', 'Documental de 30 minutos sobre las tradiciones culturales de Santiago del Estero. Seleccionado en Festival de Cine NOA.', 'audiovisual', '{"duracion":"30 minutos","formato":"Documental","plataforma":"YouTube, Vimeo"}', '/uploads/imagenes/obra21.jpg', 'validado', NOW() - INTERVAL 20 DAY, NOW() - INTERVAL 20 DAY, 1, NOW() - INTERVAL 12 DAY),
(6, 'Voces del Río Dulce', 'Serie documental sobre comunidades a orillas del río.', 'audiovisual', '{"duracion":"4 episodios x 15 min","formato":"Serie","plataforma":"YouTube"}', '/uploads/imagenes/obra22.jpg', 'pendiente_validacion', NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 10 DAY, NULL, NULL),
(6, 'Festival de Folklore', 'Cortometraje sobre el festival anual de música folclórica.', 'audiovisual', '{"duracion":"12 minutos","formato":"Cortometraje","plataforma":"Vimeo"}', '/uploads/imagenes/obra23.jpg', 'pendiente_validacion', NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 8 DAY, NULL, NULL),
(6, 'Artesanos del Norte', 'Documental sobre artesanos tradicionales de la provincia.', 'audiovisual', '{"duracion":"25 minutos","formato":"Documental","plataforma":"YouTube"}', '/uploads/imagenes/obra24.jpg', 'pendiente_validacion', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY, NULL, NULL);

-- ARTISTA 7: Elena Paz (Artes Visuales)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(7, 'Paisajes del Chaco', 'Serie de 12 pinturas al óleo que capturan la belleza del paisaje chaqueño. Técnica impresionista con paleta cálida.', 'artes_visuales', '{"tecnica":"Óleo sobre lienzo","dimensiones":"80x60 cm","cantidad":"12 obras"}', '/uploads/imagenes/obra25.jpg', 'validado', NOW() - INTERVAL 18 DAY, NOW() - INTERVAL 18 DAY, 1, NOW() - INTERVAL 10 DAY),
(7, 'Retratos del Pueblo', 'Colección de retratos de habitantes de comunidades rurales.', 'artes_visuales', '{"tecnica":"Acuarela","dimensiones":"50x40 cm","cantidad":"8 obras"}', '/uploads/imagenes/obra26.jpg', 'pendiente_validacion', NOW() - INTERVAL 11 DAY, NOW() - INTERVAL 11 DAY, NULL, NULL),
(7, 'Texturas del Monte', 'Obras abstractas inspiradas en las texturas de la flora nativa.', 'artes_visuales', '{"tecnica":"Técnica mixta","dimensiones":"100x70 cm","cantidad":"6 obras"}', '/uploads/imagenes/obra27.jpg', 'pendiente_validacion', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 9 DAY, NULL, NULL),
(7, 'El Color del Atardecer', 'Serie fotográfica sobre crepúsculos en el campo santiagueño.', 'artes_visuales', '{"tecnica":"Fotografía digital","dimensiones":"60x40 cm","cantidad":"15 fotos"}', '/uploads/imagenes/obra28.jpg', 'pendiente_validacion', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY, NULL, NULL);

-- ARTISTA 8: Pablo Sánchez (Escultura)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(8, 'Monumento al Hachero', 'Escultura en bronce de 2 metros representando la figura del trabajador forestal. Ubicada en plaza pública.', 'escultura', '{"material":"Bronce","dimensiones":"200 cm altura","peso":"150 kg"}', '/uploads/imagenes/obra29.jpg', 'validado', NOW() - INTERVAL 16 DAY, NOW() - INTERVAL 16 DAY, 1, NOW() - INTERVAL 8 DAY),
(8, 'Danza en Piedra', 'Escultura abstracta en piedra que sugiere movimiento de danza.', 'escultura', '{"material":"Piedra caliza","dimensiones":"180 cm altura","peso":"200 kg"}', '/uploads/imagenes/obra30.jpg', 'pendiente_validacion', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, NULL, NULL),
(8, 'Familia Campesina', 'Conjunto escultórico de tres figuras en terracota.', 'escultura', '{"material":"Terracota","dimensiones":"150 cm altura","peso":"80 kg"}', '/uploads/imagenes/obra31.jpg', 'pendiente_validacion', NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 10 DAY, NULL, NULL),
(8, 'El Algarrobo', 'Escultura en madera tallada representando árbol emblemático.', 'escultura', '{"material":"Quebracho","dimensiones":"250 cm altura","peso":"120 kg"}', '/uploads/imagenes/obra32.jpg', 'pendiente_validacion', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY, NULL, NULL);

-- ARTISTA 9: Lucía Herrera (Fotografía)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(9, 'Rostros de Santiago', 'Exposición fotográfica de 20 retratos en blanco y negro de habitantes de la provincia. Técnica documental.', 'fotografia', '{"tecnica":"B/N digital","cantidad":"20 fotografías","formato":"50x70 cm"}', '/uploads/imagenes/obra33.jpg', 'validado', NOW() - INTERVAL 14 DAY, NOW() - INTERVAL 14 DAY, 1, NOW() - INTERVAL 6 DAY),
(9, 'Atardeceres del Dulce', 'Serie de paisajes del Río Dulce al atardecer.', 'fotografia', '{"tecnica":"Color digital","cantidad":"15 fotografías","formato":"60x40 cm"}', '/uploads/imagenes/obra34.jpg', 'pendiente_validacion', NOW() - INTERVAL 13 DAY, NOW() - INTERVAL 13 DAY, NULL, NULL),
(9, 'Arquitectura Colonial', 'Registro fotográfico de edificios históricos de la capital.', 'fotografia', '{"tecnica":"Color digital","cantidad":"25 fotografías","formato":"40x60 cm"}', '/uploads/imagenes/obra35.jpg', 'pendiente_validacion', NOW() - INTERVAL 11 DAY, NOW() - INTERVAL 11 DAY, NULL, NULL),
(9, 'Flora Nativa', 'Macrofotografía de plantas autóctonas del monte santiagueño.', 'fotografia', '{"tecnica":"Color digital macro","cantidad":"30 fotografías","formato":"30x40 cm"}', '/uploads/imagenes/obra36.jpg', 'pendiente_validacion', NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 8 DAY, NULL, NULL);

-- ARTISTA 10: Javier Luna (Música)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
(10, 'Bombo Legüero', 'Álbum de música folclórica con 10 temas originales. Grabado con músicos locales en estudio de la capital.', 'musica', '{"plataformas":"Spotify, Apple Music, YouTube","sello":"Folklore del Norte"}', '/uploads/imagenes/obra37.jpg', 'validado', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, 1, NOW() - INTERVAL 4 DAY),
(10, 'Cantos del Campo', 'EP con 5 canciones sobre la vida rural santiagueña.', 'musica', '{"plataformas":"YouTube Music","sello":"Independiente"}', '/uploads/imagenes/obra38.jpg', 'pendiente_validacion', NOW() - INTERVAL 14 DAY, NOW() - INTERVAL 14 DAY, NULL, NULL),
(10, 'Folklore Contemporáneo', 'Fusión de folklore tradicional con elementos de rock.', 'musica', '{"plataformas":"SoundCloud, Bandcamp","sello":"Independiente"}', '/uploads/imagenes/obra39.jpg', 'pendiente_validacion', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, NULL, NULL),
(10, 'Instrumental Santiagueño', 'Piezas instrumentales con guitarra y bombo.', 'musica', '{"plataformas":"Spotify","sello":"Raíces Musicales"}', '/uploads/imagenes/obra40.jpg', 'pendiente_validacion', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 9 DAY, NULL, NULL);

-- ================================================================
-- CREAR 10 ARTISTAS NUEVOS (IDs 11-20) CON 1 OBRA PENDIENTE CADA UNO
-- ================================================================

-- Primero insertamos los artistas
INSERT INTO artistas (nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, role, status, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, status_perfil, motivo_rechazo) VALUES
-- Artista 11
('Sofía', 'Morales', '1992-06-15', 'femenino', 'Argentina', 'Santiago del Estero', 'Frías', 'sofia.morales@email.com', '$2y$10$dummyhashedpassword11', 'artista', 'validado', 'Cantante de folklore con 8 años de trayectoria en peñas del interior provincial.', 'Canto, Guitarra, Composición', '@sofia_morales_folk', 'sofia.morales.folk', '@sofiamorales', 'https://sofiamorales.com.ar', '/uploads/imagenes/perfil11.jpg', 'validado', NULL),
-- Artista 12
('Martín', 'Castro', '1985-09-20', 'masculino', 'Argentina', 'Santiago del Estero', 'Loreto', 'martin.castro@email.com', '$2y$10$dummyhashedpassword12', 'artista', 'validado', 'Escritor de cuentos regionales y novelas históricas sobre Santiago del Estero.', 'Narrativa, Novela histórica', '@martin_castro_escritor', 'martin.castro.escritor', '@mcastro_escritor', 'https://martincastro.com.ar', '/uploads/imagenes/perfil12.jpg', 'validado', NULL),
-- Artista 13
('Gabriela', 'Ríos', '1990-03-10', 'femenino', 'Argentina', 'Santiago del Estero', 'Quimilí', 'gabriela.rios@email.com', '$2y$10$dummyhashedpassword13', 'artista', 'validado', 'Bailarina y coreógrafa especializada en danzas folclóricas del NOA.', 'Danza folclórica, Coreografía', '@gaby_rios_danza', 'gabriela.rios.danza', '@gabyriosdanza', 'https://gabrielarrios.com.ar', '/uploads/imagenes/perfil13.jpg', 'validado', NULL),
-- Artista 14
('Fernando', 'Vega', '1988-11-25', 'masculino', 'Argentina', 'Santiago del Estero', 'Añatuya', 'fernando.vega@email.com', '$2y$10$dummyhashedpassword14', 'artista', 'validado', 'Actor y director teatral con énfasis en teatro comunitario y popular.', 'Teatro, Dirección, Dramaturgia', '@fer_vega_teatro', 'fernando.vega.teatro', '@fervegateatro', 'https://fernandovega.com.ar', '/uploads/imagenes/perfil14.jpg', 'validado', NULL),
-- Artista 15
('Carolina', 'Díaz', '1995-01-08', 'femenino', 'Argentina', 'Santiago del Estero', 'Monte Quemado', 'carolina.diaz@email.com', '$2y$10$dummyhashedpassword15', 'artista', 'validado', 'Artesana textil especializada en telar mapuche y técnicas ancestrales.', 'Telar, Textil, Teñido natural', '@caro_diaz_artesana', 'carolina.diaz.textil', '@carodiaztextil', 'https://carolinadiaz.com.ar', '/uploads/imagenes/perfil15.jpg', 'validado', NULL),
-- Artista 16
('Ramiro', 'Ochoa', '1987-07-30', 'masculino', 'Argentina', 'Santiago del Estero', 'Suncho Corral', 'ramiro.ochoa@email.com', '$2y$10$dummyhashedpassword16', 'artista', 'validado', 'Documentalista y realizador audiovisual enfocado en temáticas sociales y culturales.', 'Documental, Edición, Fotografía', '@ramiro_ochoa_cine', 'ramiro.ochoa.cine', '@ramirochoac', 'https://ramiroochoa.com.ar', '/uploads/imagenes/perfil16.jpg', 'validado', NULL),
-- Artista 17
('Valeria', 'Campos', '1993-04-12', 'femenino', 'Argentina', 'Santiago del Estero', 'Pinto', 'valeria.campos@email.com', '$2y$10$dummyhashedpassword17', 'artista', 'validado', 'Pintora contemporánea que fusiona técnicas tradicionales con arte abstracto.', 'Pintura, Óleo, Arte abstracto', '@vale_campos_arte', 'valeria.campos.arte', '@valecamposarte', 'https://valeriacampos.com.ar', '/uploads/imagenes/perfil17.jpg', 'validado', NULL),
-- Artista 18
('Hernán', 'Molina', '1991-10-05', 'masculino', 'Argentina', 'Santiago del Estero', 'Clodomira', 'hernan.molina@email.com', '$2y$10$dummyhashedpassword18', 'artista', 'validado', 'Escultor que trabaja con materiales reciclados y técnicas de soldadura artística.', 'Escultura, Metal, Arte reciclado', '@hernan_molina_escultor', 'hernan.molina.escultor', '@hernanmolinarte', 'https://hernanmolina.com.ar', '/uploads/imagenes/perfil18.jpg', 'validado', NULL),
-- Artista 19
('Romina', 'Suárez', '1989-12-18', 'femenino', 'Argentina', 'Santiago del Estero', 'Villa Ojo de Agua', 'romina.suarez@email.com', '$2y$10$dummyhashedpassword19', 'artista', 'validado', 'Fotógrafa documental especializada en fotografía social y de naturaleza.', 'Fotografía documental, Retrato', '@romina_suarez_foto', 'romina.suarez.foto', '@romisuarezfoto', 'https://rominasuarez.com.ar', '/uploads/imagenes/perfil19.jpg', 'validado', NULL),
-- Artista 20
('Gustavo', 'Pereyra', '1994-08-22', 'masculino', 'Argentina', 'Santiago del Estero', 'Sumampa', 'gustavo.pereyra@email.com', '$2y$10$dummyhashedpassword20', 'artista', 'validado', 'Músico multi-instrumentista especializado en chacarera y gato santiagueño.', 'Guitarra, Bombo legüero, Composición', '@gusti_pereyra_musico', 'gustavo.pereyra.musica', '@gustipereyra', 'https://gustavopereyra.com.ar', '/uploads/imagenes/perfil20.jpg', 'validado', NULL);

-- Ahora agregamos intereses para los nuevos artistas
INSERT INTO intereses_artista (artista_id, interes) VALUES
(11, 'musica'), (12, 'literatura'), (13, 'danza'), (14, 'teatro'), (15, 'artesania'),
(16, 'audiovisual'), (17, 'artes_visuales'), (18, 'escultura'), (19, 'fotografia'), (20, 'musica');

-- Obras pendientes para artistas 11-20 (1 obra pendiente cada uno)
INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion) VALUES
-- Obra 41 - Artista 11
(11, 'Zambas del Interior', 'Álbum debut con 8 zambas originales inspiradas en paisajes rurales.', 'musica', '{"plataformas":"YouTube","sello":"Independiente"}', '/uploads/imagenes/obra41.jpg', 'pendiente_validacion', NOW() - INTERVAL 15 DAY, NOW() - INTERVAL 15 DAY, NULL, NULL),
-- Obra 42 - Artista 12
(12, 'Relatos del Quebracho', 'Colección de cuentos sobre la vida en los obrajes santiagueños.', 'literatura', '{"genero-lit":"Cuento","editorial":"Editorial Regional"}', '/uploads/imagenes/obra42.jpg', 'pendiente_validacion', NOW() - INTERVAL 14 DAY, NOW() - INTERVAL 14 DAY, NULL, NULL),
-- Obra 43 - Artista 13
(13, 'Folklore en Escena', 'Obra coreográfica que recorre las danzas tradicionales del NOA.', 'danza', '{"tipo-danza":"Folklore","duracion":"20 minutos"}', '/uploads/imagenes/obra43.jpg', 'pendiente_validacion', NOW() - INTERVAL 13 DAY, NOW() - INTERVAL 13 DAY, NULL, NULL),
-- Obra 44 - Artista 14
(14, 'Memorias del Campo', 'Obra de teatro testimonial sobre migrantes rurales.', 'teatro', '{"duracion-obra":"65 minutos","elenco":"4 actores"}', '/uploads/imagenes/obra44.jpg', 'pendiente_validacion', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, NULL, NULL),
-- Obra 45 - Artista 15
(15, 'Mantas del Salado', 'Serie de mantas tejidas con lana natural y tintes vegetales.', 'artesania', '{"materiales":"Lana oveja, tintes vegetales","tecnica":"Telar vertical"}', '/uploads/imagenes/obra45.jpg', 'pendiente_validacion', NOW() - INTERVAL 11 DAY, NOW() - INTERVAL 11 DAY, NULL, NULL),
-- Obra 46 - Artista 16
(16, 'Caminos de Tierra', 'Documental sobre rutas rurales y sus pobladores.', 'audiovisual', '{"duracion":"35 minutos","formato":"Documental","plataforma":"Vimeo"}', '/uploads/imagenes/obra46.jpg', 'pendiente_validacion', NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 10 DAY, NULL, NULL),
-- Obra 47 - Artista 17
(17, 'Colores del Desierto', 'Serie de pinturas abstractas inspiradas en el paisaje chaqueño.', 'artes_visuales', '{"tecnica":"Acrílico","dimensiones":"90x60 cm","cantidad":"10 obras"}', '/uploads/imagenes/obra47.jpg', 'pendiente_validacion', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 9 DAY, NULL, NULL),
-- Obra 48 - Artista 18
(18, 'Metamorfosis', 'Escultura cinética en metal reciclado.', 'escultura', '{"material":"Metal reciclado","dimensiones":"220 cm altura","peso":"180 kg"}', '/uploads/imagenes/obra48.jpg', 'pendiente_validacion', NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 8 DAY, NULL, NULL),
-- Obra 49 - Artista 19
(19, 'Identidad Santiagueña', 'Serie fotográfica sobre tradiciones y costumbres locales.', 'fotografia', '{"tecnica":"Color digital","cantidad":"18 fotografías","formato":"50x70 cm"}', '/uploads/imagenes/obra49.jpg', 'pendiente_validacion', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY, NULL, NULL),
-- Obra 50 - Artista 20
(20, 'Ecos del Bombo', 'Álbum instrumental con percusión tradicional santiagueña.', 'musica', '{"plataformas":"Spotify, Bandcamp","sello":"Percusión del Norte"}', '/uploads/imagenes/obra50.jpg', 'pendiente_validacion', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY, NULL, NULL);

-- ================================================================
-- RESUMEN
-- ================================================================
-- Total artistas: 20 (todos validados)
-- Total obras validadas: 10 (1 por cada uno de los primeros 10 artistas)
-- Total obras pendientes: 40 (3 por artista IDs 1-10, 1 por artista IDs 11-20)
-- 
-- IMPORTANTE: Guarda las imágenes en:
-- /home/runatechdev/Documentos/Github/ID-Cultural/public/uploads/imagenes/
-- 
-- Nombres de archivos:
-- - Perfiles: perfil1.jpg hasta perfil20.jpg
-- - Obras: obra1.jpg hasta obra50.jpg
-- ================================================================

SELECT 'Carga completada:' as status, 
       COUNT(*) as total_artistas 
FROM artistas;

SELECT 'Obras cargadas:' as status,
       estado,
       COUNT(*) as cantidad
FROM publicaciones
GROUP BY estado;
