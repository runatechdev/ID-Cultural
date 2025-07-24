-- MariaDB dump final
--
-- NOTA:
-- - Se ha añadido la sentencia CREATE DATABASE IF NOT EXISTS y USE para asegurar la selección de la DB.
-- - Se han eliminado las directivas PRAGMA, BEGIN TRANSACTION y COMMIT (específicas de SQLite).
-- - Se ha cambiado AUTOINCREMENT a AUTO_INCREMENT (sintaxis de MariaDB/MySQL).
-- - Se han ajustado los tipos de datos TEXT a VARCHAR con longitudes apropiadas para campos específicos.
-- - Se han eliminado las referencias a sqlite_sequence (internas de SQLite).
-- - Las tablas 'borradores' y 'borradores_pendientes' se han unificado en una única tabla 'publicaciones'.
-- - Se han añadido los campos 'fecha_envio_validacion', 'validador_id' y 'fecha_validacion' a 'publicaciones'.
-- - Los INSERT INTO de los borradores originales se han adaptado para la nueva tabla 'publicaciones'
--   asignando el 'estado' y otras columnas según su propósito original.

-- Crear la base de datos si no existe y seleccionarla
CREATE DATABASE IF NOT EXISTS idcultural;
USE idcultural;

-- Creación de la tabla 'users'
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(50)
);

-- Inserción de datos en 'users'
INSERT INTO users VALUES(1,'admin@idcultural.com','$2y$10$cv2EG9pZ/4y1H.z.QztN.OuGTO9x8resRsMrnJxdaKFPqreWtndf6','admin');
INSERT INTO users VALUES(2,'editor@idcultural.com','$2y$10$9/iW1.fVT0I8E2PiYzNGv.q5AKtnboEwl4rBAHuMgV2rVcDW6wd6W','editor');
INSERT INTO users VALUES(3,'validador@idcultural.com','$2y$10$0iyYHziq26tdcn8NjkqLZOXCQnxC0mOt5woXz/7mkBjqLZOXCQnxC0mOt5woXz/7mkObn3B7zHABvC','validador');

-- Creación de la tabla 'artistas'
CREATE TABLE artistas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    fecha_nacimiento VARCHAR(20) NOT NULL, -- Considerar usar DATE si el formato es YYYY-MM-DD
    genero VARCHAR(50),
    pais VARCHAR(100),
    provincia VARCHAR(100),
    municipio VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'artista'
);

-- Inserción de datos en 'artistas'
INSERT INTO artistas VALUES(1,'Sandra','Sanchez','1980-11-26','femenino','Argentina','Santiago del Estero','Santiago Capital','sandra@gmail.com','$2y$10$1TGf4vTRPk/kncv8BBxZZOYEOrVju7aytkLaIEshyNh4xutfSXbv.','artista');
INSERT INTO artistas VALUES(2,'nuevo','nuevo','2000-12-12','femenino','Argentina','Buenos Aires','La Plata','nuevo@gmail.com','$2y$10$7nxg3IMycH8sDjm0RbHDaO3DlYedW8ZOdsX4dXcJ3vV/K9IA.o8rq','artista');
INSERT INTO artistas VALUES(3,'prueba','prueba','2001-02-21','masculino','Argentina','Buenos Aires','La Plata','prueba@gmail.com','$2y$10$Swtb6xK8KSKsuNXFLfcJtOZPfLgqUKYeWMBDJqVFqCO/c7C8UYDwi','artista');
INSERT INTO artistas VALUES(4,'MARCOS','ROMANO','1985-05-12','masculino','Argentina','Santiago del Estero','Santiago Capital','marcos@gmail.com','$2y$10$aPjtajeTQD4YxwtIdeL7meI0IsrZtZsnTR2K8W1jShCChoR3nDJ72','artista');

-- Creación de la tabla 'intereses_artista'
CREATE TABLE intereses_artista (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artista_id INT,
    interes VARCHAR(255),
    FOREIGN KEY (artista_id) REFERENCES artistas(id)
);

-- Creación de la tabla unificada 'publicaciones'
CREATE TABLE publicaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL, -- FK a artistas(id)
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL, -- TEXT para descripciones largas
    categoria VARCHAR(100) NOT NULL,
    campos_extra TEXT, -- TEXT para JSON/datos variables
    multimedia TEXT,   -- TEXT para JSON/datos variables
    estado VARCHAR(50) DEFAULT 'borrador', -- 'borrador', 'pendiente_validacion', 'validado', 'rechazado'
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_envio_validacion TIMESTAMP NULL, -- Cuándo se envió a validar (NULL si aún es borrador)
    validador_id INT NULL,             -- Quién lo validó (FK a users(id), NULL si no validado)
    fecha_validacion TIMESTAMP NULL,    -- Cuándo se validó (NULL si no validado)
    FOREIGN KEY (usuario_id) REFERENCES artistas(id) ON DELETE CASCADE,
    FOREIGN KEY (validador_id) REFERENCES users(id) -- Asumiendo que los validadores son de la tabla 'users'
);

-- Inserción de datos en 'publicaciones' (combinando los datos de los borradores originales)
-- El borrador original con estado 'pendiente_validacion' se inserta aquí.
-- Se asume que el registro de 'borradores_pendientes' es el mismo que el de 'borradores' con estado 'pendiente_validacion'.
INSERT INTO publicaciones (id, usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion)
VALUES(1,1,'RunaTechDev','Probando la carga a la DB','artes_visuales','{"plataformas":"","sello":"","genero-lit":"","editorial":"","tecnica":"VAmosssss","dimensiones_av":"120x120","ano_creacion":"2000","materiales_esc":"","dimensiones_esc":"","apta_exterior":"no","material_art":"","tecnica_art":"","origen_region":"","compania_danza":"","duracion_danza":"","ficha_danza":"","compania_teatro":"","duracion_teatro":"","ficha_teatro":""}','','pendiente_validacion','2025-07-16 03:37:31', '2025-07-16 03:37:31', NULL, NULL);

-- Si tuvieras otros borradores con estado 'borrador' (no enviados a validación), los insertarías aquí.
-- Ejemplo:
-- INSERT INTO publicaciones (id, usuario_id, titulo, descripcion, categoria, campos_extra, multimedia, estado, fecha_creacion, fecha_envio_validacion, validador_id, fecha_validacion)
-- VALUES(2, 2, 'Mi Nuevo Borrador', 'Contenido del borrador', 'musica', '{"genero":"rock"}', '', 'borrador', '2025-07-23 10:00:00', NULL, NULL, NULL);
