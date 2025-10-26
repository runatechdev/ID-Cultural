<?php
/**
 * API para obtener lista de noticias
 * Archivo: /public/api/get_noticias.php
 * VERSIÓN COMPATIBLE CON BD ACTUAL
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Parámetros opcionales
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

try {
    // Primero verificar qué columnas tiene la tabla users
    $check_stmt = $pdo->query("DESCRIBE users");
    $columns = $check_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Determinar qué campo usar para el nombre
    $nombre_field = 'nombre'; // Por defecto
    
    // Obtener noticias
    $sql = "
        SELECT 
            n.id,
            n.titulo,
            n.contenido,
            n.imagen_url,
            n.fecha_creacion
    ";
    
    // Solo agregar el nombre del editor si existe la tabla users
    $table_check = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($table_check->rowCount() > 0) {
        $sql .= ", u.{$nombre_field} as editor_nombre";
        $sql .= " FROM noticias n LEFT JOIN users u ON n.editor_id = u.id";
    } else {
        $sql .= " FROM noticias n";
    }
    
    $sql .= " ORDER BY n.fecha_creacion DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($noticias);

} catch (PDOException $e) {
    error_log("Error en get_noticias.php: " . $e->getMessage());
    
    // Intentar una consulta más simple sin JOIN
    try {
        $simple_stmt = $pdo->prepare("
            SELECT 
                id,
                titulo,
                contenido,
                imagen_url,
                fecha_creacion
            FROM noticias
            ORDER BY fecha_creacion DESC
            LIMIT :limit OFFSET :offset
        ");
        $simple_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $simple_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $simple_stmt->execute();
        
        $noticias = $simple_stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($noticias);
        
    } catch (PDOException $e2) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Error al consultar la base de datos',
            'message' => $e2->getMessage(),
            'noticias' => []
        ]);
    }
}