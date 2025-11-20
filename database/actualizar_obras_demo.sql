-- Script para actualizar las obras con textos profesionales para la demo
-- Estas obras pertenecen a Marcos Romano (ID: 8) 

-- Obra 1: ID 7 - Literatura (sdsada)
UPDATE publicaciones 
SET 
    titulo = 'Historias del Chaco Santiagueño',
    descripcion = 'Una colección de relatos cortos que capturan las tradiciones, leyendas y historias de vida de los pueblos del Chaco Santiagueño. Cada historia es un viaje por las raíces culturales de nuestra provincia, explorando la conexión entre las personas y el territorio que habitan. A través de prosa lírica y personajes auténticos, se presenta un retrato literario de la identidad santiagueña.',
    campos_extra = JSON_SET(campos_extra, "$.genero_literario", "Relatos y tradiciones", "$.editorial", "Publicaciones Regionales")
WHERE id = 5;

-- Obra 2: ID 6 - Música (aaaaaaaaaaa)
UPDATE publicaciones 
SET 
    titulo = 'Fusion Chacarera Contemporánea',
    descripcion = 'Proyecto musical innovador que reinterpreta la chacarera tradicional santiagueña con elementos de música contemporánea y fusiones electrónicas. Esta obra busca honrar nuestras raíces folclóricas mientras las proyecta hacia una audiencia moderna. Disponible en plataformas digitales de streaming, representa una propuesta única de identidad cultural reimaginada.',
    campos_extra = JSON_SET(campos_extra, "$.plataformas_distribucion", "Spotify, YouTube Music, Apple Music", "$.sello_discografico", "Sellos Independientes Santiagueños")
WHERE id = 6;

-- Obra 3: ID 7 - Música (sdasdadad)
UPDATE publicaciones 
SET 
    titulo = 'Voces del Nordeste: Antología Sonora',
    descripcion = 'Compilación sonora que reúne expresiones musicales diversas de artistas santiagueños contemporáneos. Desde la música tradicional hasta propuestas experimentales, esta antología representa la riqueza y diversidad del paisaje sonoro provincial. Cada tema es una ventana abierta a la creatividad artística que florece en nuestra región, documentando el pulso musical actual de Santiago del Estero.',
    campos_extra = JSON_SET(campos_extra, "$.plataformas_distribucion", "Bandcamp, SoundCloud, Plataformas locales", "$.sello_discografico", "Colectivo Sonoro Santiagueño")
WHERE id = 7;
