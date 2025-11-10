-- Tablas para el sistema de Analytics
-- ID Cultural

-- Tabla de visitas a páginas
CREATE TABLE IF NOT EXISTS `analytics_visitas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pagina` VARCHAR(255) NOT NULL,
  `usuario_id` INT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT,
  `duracion` INT DEFAULT 0 COMMENT 'Duración en segundos',
  `referrer` VARCHAR(500),
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_pagina` (`pagina`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de eventos
CREATE TABLE IF NOT EXISTS `analytics_eventos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `categoria` VARCHAR(100) NOT NULL,
  `accion` VARCHAR(100) NOT NULL,
  `etiqueta` VARCHAR(255),
  `valor` INT,
  `usuario_id` INT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `pagina` VARCHAR(255),
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_categoria` (`categoria`),
  INDEX `idx_accion` (`accion`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de búsquedas
CREATE TABLE IF NOT EXISTS `analytics_busquedas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `termino_busqueda` VARCHAR(255) NOT NULL,
  `resultados` INT DEFAULT 0,
  `usuario_id` INT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_termino` (`termino_busqueda`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de notificaciones (si no existe)
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `tipo` ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
  `titulo` VARCHAR(255) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `leida` BOOLEAN DEFAULT FALSE,
  `url_accion` VARCHAR(500),
  `datos_adicionales` JSON,
  `fecha_lectura` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_usuario` (`usuario_id`),
  INDEX `idx_leida` (`leida`),
  INDEX `idx_fecha` (`created_at`),
  FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de preferencias de notificaciones
CREATE TABLE IF NOT EXISTS `preferencias_notificaciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL UNIQUE,
  `notificaciones_email` BOOLEAN DEFAULT TRUE,
  `notificaciones_perfil` BOOLEAN DEFAULT TRUE,
  `notificaciones_validacion` BOOLEAN DEFAULT TRUE,
  `notificaciones_comentarios` BOOLEAN DEFAULT TRUE,
  `notificaciones_mensajes` BOOLEAN DEFAULT TRUE,
  `frecuencia_email` ENUM('inmediato', 'diario', 'semanal', 'nunca') DEFAULT 'diario',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
