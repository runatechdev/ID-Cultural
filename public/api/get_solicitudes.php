<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    // Consulta mejorada que une 'publicaciones' con 'artistas' para obtener todos los detalles necesarios.
    $stmt = $pdo->prepare("
        SELECT 
            p.id AS publicacion_id, 
            p.titulo, 
            p.fecha_envio_validacion,
            a.id AS artista_id,
            CONCAT(a.nombre, ' ', a.apellido) AS nombre_artista,
            a.municipio -- Se añade el municipio
        FROM publicaciones p
        JOIN artistas a ON p.usuario_id = a.id
        WHERE p.estado = 'pendiente_validacion'
        ORDER BY p.fecha_envio_validacion ASC
    ");
    
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($solicitudes);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
?>
