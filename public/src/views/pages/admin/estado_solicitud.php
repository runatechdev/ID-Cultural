<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    // Consulta que une 'publicaciones' con 'artistas' para obtener el nombre del artista
    $stmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.titulo, 
            p.fecha_envio_validacion, 
            p.estado,
            CONCAT(a.nombre, ' ', a.apellido) AS nombre_artista
        FROM publicaciones p
        JOIN artistas a ON p.usuario_id = a.id
        WHERE p.estado = 'pendiente_validacion'
    ");
    
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($solicitudes);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
?>
