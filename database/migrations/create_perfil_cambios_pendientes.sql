-- Tabla para almacenar cambios pendientes de perfil
-- Los cambios solo se aplican al perfil cuando el validador los aprueba

CREATE TABLE IF NOT EXISTS perfil_cambios_pendientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artista_id INT NOT NULL,
    
    -- Campos del perfil que pueden cambiar
    biografia TEXT DEFAULT NULL,
    especialidades VARCHAR(500) DEFAULT NULL,
    instagram VARCHAR(100) DEFAULT NULL,
    facebook VARCHAR(255) DEFAULT NULL,
    twitter VARCHAR(100) DEFAULT NULL,
    sitio_web VARCHAR(255) DEFAULT NULL,
    foto_perfil VARCHAR(255) DEFAULT NULL,
    
    -- Estado del cambio
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    
    -- Información de validación
    validador_id INT DEFAULT NULL,
    motivo_rechazo TEXT DEFAULT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_validacion TIMESTAMP NULL DEFAULT NULL,
    
    -- Claves foráneas
    FOREIGN KEY (artista_id) REFERENCES artistas(id) ON DELETE CASCADE,
    FOREIGN KEY (validador_id) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Índices
    INDEX idx_artista_estado (artista_id, estado),
    INDEX idx_estado (estado),
    INDEX idx_fecha_solicitud (fecha_solicitud)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comentario de la tabla
ALTER TABLE perfil_cambios_pendientes 
COMMENT = 'Almacena cambios de perfil pendientes de validación antes de aplicarlos al perfil público';
