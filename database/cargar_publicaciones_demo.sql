-- ============================================================================
-- SCRIPT DE CARGA DE PUBLICACIONES/OBRAS DEMO - ID Cultural
-- Generado: 20 de noviembre de 2025
-- Descripción: Carga de 30+ obras de los artistas demo creados
-- Prerequisito: Ejecutar primero cargar_usuarios_demo.sql
-- ============================================================================

-- ============================================================================
-- OBRAS DEL ARTISTA 1: JUAN REYES (Músico Folk)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    10,
    'Chacarera del Amanecer',
    'Composición original que captura la esencia del amanecer santiagueño. Una chacarera tradicional adaptada para guitarra y bombo, que ha sido tocada en festivales provinciales. La pieza refleja la identidad cultural de nuestra región con armonías auténticas.',
    'musica',
    'validado',
    JSON_OBJECT(
        'duracion_minutos', '3:45',
        'instrumentos', JSON_ARRAY('Guitarra', 'Bombo'),
        'genero', 'Chacarera',
        'año_composicion', '2022',
        'plataformas', JSON_ARRAY('Spotify', 'YouTube', 'SoundCloud')
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    10,
    'Zamba de la Nostalgia',
    'Zamba contemporánea que evoca los recuerdos de pueblo. Esta obra combina la tradición de la zamba santiagueña con armonías modernas, manteniendo la esencia folklórica. Disponible en plataformas digitales.',
    'musica',
    'validado',
    JSON_OBJECT(
        'duracion_minutos', '4:20',
        'instrumentos', JSON_ARRAY('Guitarra', 'Voz'),
        'genero', 'Zamba',
        'año_composicion', '2023',
        'colaboradores', JSON_ARRAY('Coro de Voces Santiagueñas')
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    10,
    'Vidala Contemporánea',
    'Versión moderna de la vidala tradicional. Reinterpretación de un clásico folclórico santiagueño con arreglos contemporáneos que mantienen la solemnidad del género.',
    'musica',
    'pendiente',
    JSON_OBJECT(
        'duracion_minutos', '5:10',
        'instrumentos', JSON_ARRAY('Guitarra Clásica', 'Orquesta de Cámara'),
        'genero', 'Vidala',
        'año_composicion', '2024'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 2: MARÍA FERNÁNDEZ (Poeta y Escritora)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    11,
    'Historias del Chaco Profundo',
    'Colección de poemas narrativos que cuentan historias de la gente del Chaco. Cada poema es una ventana a la vida cotidiana, los desafíos y las alegrías de comunidades rurales santiagueñas. Una obra que busca visibilizar las voces olvidadas de nuestra provincia.',
    'literatura',
    'validado',
    JSON_OBJECT(
        'numero_poemas', 12,
        'paginas', 95,
        'genero', 'Poesía Narrativa',
        'año_publicacion', '2023',
        'editorial', 'Ediciones Santiagueñas'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    11,
    'Mujeres de Arena y Agua',
    'Novela corta que explora la vida de tres mujeres en diferentes épocas de Santiago del Estero. A través de sus historias entrelazadas, la autora teje una narrativa sobre la resiliencia, el amor y la identidad femenina en contextos de marginación.',
    'literatura',
    'validado',
    JSON_OBJECT(
        'numero_paginas', 156,
        'tipo', 'Novela Corta',
        'año_publicacion', '2024',
        'premios', JSON_ARRAY('Mención Especial - Festival de Literatura de la Región')
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    11,
    'Versos de Otoño',
    'Colección de poemas líricos inspirados en las estaciones y cambios de la naturaleza santiagueña. Poesía intimista que reflexiona sobre el paso del tiempo y la transformación personal.',
    'literatura',
    'pendiente',
    JSON_OBJECT(
        'numero_poemas', 8,
        'tema', 'Naturaleza y Tiempo',
        'estilo', 'Lírico'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 3: CARLOS MÉNDEZ (Pintor)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    12,
    'Serie Territorios: La Tierra Recuerda',
    'Serie de tres lienzos de gran formato que exploran la relación entre la tierra y la memoria. Técnica mixta con acrílico, óleo y elementos naturales (arena del Chaco). Cada obra mide 2m x 1.5m y evoca los paisajes santiagueños desde una perspectiva onírica.',
    'artes_visuales',
    'pendiente',
    JSON_OBJECT(
        'medidas', '200cm x 150cm',
        'tecnica', JSON_ARRAY('Acrílico', 'Óleo', 'Elementos Naturales'),
        'series', 'Territorios',
        'numero_obras', 3,
        'año_creacion', '2024',
        'material_principal', 'Lienzo'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    12,
    'Abstracción Rural',
    'Obra abstracta inspirada en los patrones naturales del paisaje rural santiagueño. Combinación de líneas, formas geométricas y colores cálidos que sugieren los campos y pueblos de la provincia.',
    'artes_visuales',
    'pendiente',
    JSON_OBJECT(
        'medidas', '120cm x 90cm',
        'tecnica', 'Acrílico sobre Lienzo',
        'tema', 'Paisaje Rural Abstracto',
        'año_creacion', '2023'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 4: ANA GONZÁLEZ (Danzarín)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    13,
    'Raíces en Movimiento',
    'Coreografía original que integra danzas folclóricas santiagueñas con movimiento contemporáneo. La obra ha sido presentada en más de 15 ciudades argentinas. Duración: 35 minutos con banda sonora en vivo.',
    'danza',
    'validado',
    JSON_OBJECT(
        'duracion_minutos', 35,
        'elenco', 8,
        'coreografa', 'Ana González',
        'musica_en_vivo', true,
        'presentaciones', 15,
        'año_estreno', '2022'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    13,
    'Chacarera Contemporánea',
    'Reinterpretación coreográfica de la chacarera tradicional. Mantiene los pasos clásicos pero los integra con técnicas de danza moderna, creando un puente entre tradición e innovación.',
    'danza',
    'validado',
    JSON_OBJECT(
        'duracion_minutos', '12:00',
        'bailarines', 4,
        'tipo_danza', 'Folclórica + Contemporánea',
        'vestuario', 'Tradicional Santiagueño Reinterpretado'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    13,
    'Danzas Originarias',
    'Proyecto de rescate y documentación de danzas de pueblos originarios de Santiago del Estero. Combinación de investigación antropológica con expresión coreográfica.',
    'danza',
    'pendiente',
    JSON_OBJECT(
        'duracion_minutos', 45,
        'tipo', 'Documental Coreográfico',
        'investigacion', true,
        'etapa', 'En Desarrollo'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 5: LUCAS SILVA (Productor Musical)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    14,
    'Fusion.exe',
    'EP de 4 tracks que mezcla sonidos tradicionales santiagueños con beats electrónicos. Proyecto experimental que busca crear un nuevo género musical regional.',
    'musica',
    'rechazado',
    JSON_OBJECT(
        'numero_tracks', 4,
        'duracion_total', '16:30',
        'genero', 'Electrónica Experimental',
        'año_produccion', '2024',
        'estado', 'En Post-Producción',
        'motivo_rechazo', 'Requiere mayor desarrollo conceptual'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    14,
    'Chacarera Remix',
    'Remixado electrónico de una chacarera tradicional. Intento de actualizar los géneros folklóricos para audiencias contemporáneas de las redes sociales.',
    'musica',
    'pendiente',
    JSON_OBJECT(
        'duracion_minutos', '3:20',
        'track_original', 'Chacarera del Rancho - Anónimo',
        'genero_remix', 'Electrónica-House',
        'bpm', 128
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 6: ROSARIO DÍAZ (Artesana)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    15,
    'Tapices Teñidos Naturalmente',
    'Colección de 12 tapices tejidos a mano con técnicas ancestrales santiagueñas. Todos los tintes utilizados provienen de plantas regionales (cochinilla, añil, cártamo). Cada tapiz cuenta una historia de la cosmovisión Quechua-Lule de Santiago del Estero.',
    'artesania',
    'validado',
    JSON_OBJECT(
        'numero_piezas', 12,
        'tecnica', 'Telar Tradicional',
        'tinturas', 'Naturales Regionales',
        'dimensiones_promedio', '1.5m x 2m',
        'tiempo_produccion', 'Varios meses por pieza',
        'venta', true,
        'precio_unitario', 15000
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    15,
    'Prendas Tradicionales',
    'Línea de ropa tejida según patrones ancestrales santiagueños. Incluye ponchos, fajines y accesorios. Preserva el conocimiento textil tradicional mientras genera ingresos para comunidades locales.',
    'artesania',
    'validado',
    JSON_OBJECT(
        'tipos_prendas', JSON_ARRAY('Ponchos', 'Fajines', 'Mochilas', 'Bufandas'),
        'materiales', JSON_ARRAY('Lana Natural', 'Algodón'),
        'mercado', 'Local y Nacional'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 7: MIGUEL TORRES (Fotógrafo)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    16,
    'Frías: Retratos de Un Pueblo',
    'Serie fotográfica documental de la ciudad de Frías. 45 fotografías en blanco y negro que capturan la vida cotidiana, arquitectura colonial y tradiciones. Exposición itinerante por la provincia.',
    'fotografia',
    'pendiente',
    JSON_OBJECT(
        'numero_fotografias', 45,
        'formato', 'Blanco y Negro',
        'resolucion', '300dpi',
        'tamaño_impresion', '40cm x 60cm',
        'exhibitions', JSON_ARRAY('Museo Provincial', 'Centro Cultural La Banda'),
        'año_serie', '2023'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    16,
    'Identidad Rural',
    'Documental fotográfico sobre la identidad campesina en Santiago del Estero. 30 retratos de personas mayores que conocen las tradiciones ancestrales de la región.',
    'fotografia',
    'pendiente',
    JSON_OBJECT(
        'numero_fotografias', 30,
        'tipo', 'Retratos Documentales',
        'tema', 'Identidad Cultural',
        'año_creacion', '2024'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 8: FEDERICO LÓPEZ (Dramaturgo)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    17,
    'El Último Pueblo',
    'Obra teatral en un acto que aborda la despoblación rural de Santiago del Estero. Tres actos que cuentan la historia de una familia que decide abandonar su pueblo ancestral.',
    'teatro',
    'validado',
    JSON_OBJECT(
        'numero_actos', 3,
        'personajes', 5,
        'duracion_minutos', 60,
        'genero', 'Drama Contemporáneo',
        'presentaciones', 8,
        'año_estreno', '2023'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    17,
    'Chacarera Trágica',
    'Pieza teatral breve basada en una chacarera tradicional. Experimenta con la intersección entre la música folclórica y el teatro dramático. Duración: 20 minutos.',
    'teatro',
    'validado',
    JSON_OBJECT(
        'numero_actos', 1,
        'personajes', 2,
        'duracion_minutos', 20,
        'musica', 'Chacarera en vivo',
        'genero', 'Drama Musical'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    17,
    'Memorias de Tintina',
    'Obra dramática sobre la historia de la ciudad de Tintina. Combina narraciones de pobladores locales con recreaciones teatrales. Proyecto en desarrollo.',
    'teatro',
    'pendiente',
    JSON_OBJECT(
        'tipo', 'Docudrama',
        'basado_en', 'Historias Reales Locales',
        'etapa', 'Investigación y Preproducción'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 9: ISABELLA RUIZ (Pianista)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    18,
    'Composiciones de Compositores Santiagueños Olvidados',
    'Álbum de 12 composiciones para piano de autores santiagueños de los siglos XIX y XX que han sido poco difundidos. Proyecto de rescate y revitalización de patrimonio musical provincial.',
    'musica_clasica',
    'validado',
    JSON_OBJECT(
        'numero_piezas', 12,
        'instrumento', 'Piano',
        'duracion_total', '48:30',
        'tipo', 'Grabación Discográfica',
        'ano_grabacion', '2024',
        'sello', 'Grabaciones Culturales Santiagueñas'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    18,
    'Suite Folklórica para Piano',
    'Composición original de Isabella Ruiz que adapta temas folklóricos santiagueños (chacarera, zamba, vidala) al lenguaje del piano clásico.',
    'musica_clasica',
    'validado',
    JSON_OBJECT(
        'numero_movimientos', 4,
        'duracion_minutos', '18:45',
        'instrumento', 'Piano',
        'tema_folklore', 'Chacarera, Zamba, Vidala',
        'ano_composicion', '2023'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    18,
    'Concierto para Piano y Orquesta',
    'Concierto original en tres movimientos. Fusión entre las tradiciones clásicas europeas y la sensibilidad melódica santiagueña.',
    'musica_clasica',
    'pendiente',
    JSON_OBJECT(
        'numero_movimientos', 3,
        'duracion_minutos', '35:00',
        'orquestacion', 'Piano + Orquesta de Cámara',
        'etapa', 'Composición Final'
    ),
    NOW()
);

-- ============================================================================
-- OBRAS DEL ARTISTA 10: ROBERTO NAVARRO (Escultor)
-- ============================================================================

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    19,
    'Memoria de Lajas',
    'Instalación escultórica realizada con piedra lajas recuperadas de construcciones demolidas. La obra mide 3m de altura y simboliza la permanencia de la memoria colectiva santiagueña. Ubicada en plaza pública.',
    'escultura',
    'validado',
    JSON_OBJECT(
        'material', 'Piedra Laja Recuperada',
        'altura', '3 metros',
        'tecnica', 'Instalación Ambiental',
        'ubicacion', 'Espacio Público - Santiago del Estero',
        'ano_creacion', '2023'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    19,
    'Ronda de Ancestros',
    'Serie de 6 esculturas de tamaño natural que representan personajes históricos santiagueños. Talladas en madera de algarrobo. Exposición permanente en museo local.',
    'escultura',
    'validado',
    JSON_OBJECT(
        'numero_esculturas', 6,
        'altura_promedio', '1.8m',
        'material', 'Madera de Algarrobo',
        'tematica', 'Personajes Históricos',
        'ubicacion', 'Museo Provincial de Historia'
    ),
    NOW()
);

INSERT INTO publicaciones (
    artista_id, titulo, descripcion, tipo, status,
    campos_extra, created_at
) VALUES (
    19,
    'Proyecto Experimental: Esculturas Modulares',
    'Serie experimental de esculturas modulares que pueden reconfigurase. Participación comunitaria en la transformación de la obra. Proyecto educativo-artístico.',
    'escultura',
    'pendiente',
    JSON_OBJECT(
        'tipo', 'Arte Participativo',
        'modulos', 12,
        'material', 'Aluminio Reciclado y Hierro',
        'etapa', 'Desarrollo Conceptual'
    ),
    NOW()
);

-- ============================================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- ============================================================================

-- Contar publicaciones por artista
SELECT 
    a.nombre,
    a.apellido,
    COUNT(p.id) as numero_obras,
    GROUP_CONCAT(DISTINCT p.tipo ORDER BY p.tipo) as tipos_obra
FROM artistas a
LEFT JOIN publicaciones p ON a.id = p.artista_id
WHERE a.id BETWEEN 10 AND 19
GROUP BY a.id
ORDER BY a.id;

-- Total de publicaciones
SELECT COUNT(*) as total_publicaciones FROM publicaciones WHERE artista_id BETWEEN 10 AND 19;

-- Publicaciones por estado
SELECT 
    status,
    COUNT(*) as cantidad
FROM publicaciones 
WHERE artista_id BETWEEN 10 AND 19
GROUP BY status;

-- ============================================================================
-- RESUMEN DE CARGA
-- ============================================================================
-- Artista 1 (Juan Reyes):      3 obras (2 validadas, 1 pendiente)
-- Artista 2 (María Fernández): 3 obras (2 validadas, 1 pendiente)
-- Artista 3 (Carlos Méndez):   2 obras (2 pendientes)
-- Artista 4 (Ana González):    3 obras (2 validadas, 1 pendiente)
-- Artista 5 (Lucas Silva):     2 obras (1 rechazada, 1 pendiente)
-- Artista 6 (Rosario Díaz):    2 obras (2 validadas)
-- Artista 7 (Miguel Torres):   2 obras (2 pendientes)
-- Artista 8 (Federico López):  3 obras (2 validadas, 1 pendiente)
-- Artista 9 (Isabella Ruiz):   3 obras (2 validadas, 1 pendiente)
-- Artista 10 (Roberto Navarro): 3 obras (2 validadas, 1 pendiente)
--
-- TOTAL: 26 publicaciones
-- Estado: 14 validadas, 9 pendientes, 1 rechazada
-- ============================================================================
