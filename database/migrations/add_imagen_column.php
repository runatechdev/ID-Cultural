<?php
/**
 * Script de migración para agregar columna imagen a artistas_famosos
 * Se ejecuta automáticamente si es necesario
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    // Verificar si la columna existe
    $stmt = $pdo->prepare("SHOW COLUMNS FROM artistas_famosos LIKE 'imagen'");
    $stmt->execute();
    $resultado = $stmt->fetch();
    
    if (!$resultado) {
        // Columna no existe, crearla
        $pdo->exec("ALTER TABLE artistas_famosos ADD COLUMN imagen VARCHAR(255) NULL DEFAULT NULL AFTER emoji");
        
        // Registrar en log
        error_log("✓ Columna 'imagen' agregada a 'artistas_famosos'");
        return true;
    }
    
    return false; // Columna ya existe
} catch (PDOException $e) {
    error_log("Error en migración: " . $e->getMessage());
    return false;
}
?>
