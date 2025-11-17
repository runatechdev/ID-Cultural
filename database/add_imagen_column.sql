-- Agregar columna imagen a artistas_famosos si no existe
ALTER TABLE artistas_famosos 
ADD COLUMN IF NOT EXISTS imagen VARCHAR(255) NULL DEFAULT NULL AFTER emoji;

-- Crear tabla de control si no existe
CREATE TABLE IF NOT EXISTS migraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) UNIQUE NOT NULL,
    fecha_ejecucion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Registrar migración
INSERT IGNORE INTO migraciones (nombre) VALUES ('add_imagen_to_artistas_famosos');
