<?php

/**
 * Configuración de Conexión a Base de Datos
 * Usa prepared statements por defecto para prevenir SQL Injection
 */

try {
    // Usar credenciales desde constantes (ya cargadas desde .env en config.php)
    $dsn = sprintf(
        "mysql:host=%s;dbname=%s;charset=utf8mb4",
        DB_HOST,
        DB_NAME
    );

    $options = [
        // Modo de error: lanzar excepciones
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        
        // Usar prepared statements nativos
        PDO::ATTR_EMULATE_PREPARES => false,
        
        // Fetch asociativo por defecto
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        // Convertir strings vacíos a NULL
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
        
        // Timeout de conexión
        PDO::ATTR_TIMEOUT => 5,
        
        // Persistencia de conexión (optimización)
        PDO::ATTR_PERSISTENT => false
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Log de conexión exitosa (solo en desarrollo)
    if (defined('Backend\Config\Environment') && Backend\Config\Environment::isDevelopment()) {
        error_log("Database connection established successfully");
    }

} catch (PDOException $e) {
    // Log del error (no mostrar detalles al usuario en producción)
    error_log("Database connection failed: " . $e->getMessage());
    
    // Mostrar error genérico
    http_response_code(503);
    die(json_encode([
        'error' => 'Service Unavailable',
        'message' => 'No se pudo conectar a la base de datos. Intenta nuevamente más tarde.'
    ]));
}