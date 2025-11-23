<?php
/**
 * API para obtener estadísticas del validador
 * Archivo: /public/api/get_estadisticas_validador.php
 */

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Verificar que el usuario sea validador o admin
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['validador', 'admin'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

try {
    // Obtener estadísticas de ARTISTAS por estado
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN status = 'pendiente' THEN 1 END) as artistas_pendientes,
            COUNT(CASE WHEN status = 'validado' THEN 1 END) as artistas_validados,
            COUNT(CASE WHEN status = 'rechazado' THEN 1 END) as artistas_rechazados
        FROM artistas
    ");
    $stmt->execute();
    $statsArtistas = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obtener estadísticas de PUBLICACIONES por estado
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as obras_pendientes,
            COUNT(CASE WHEN estado = 'validado' THEN 1 END) as obras_validadas,
            COUNT(CASE WHEN estado = 'rechazado' THEN 1 END) as obras_rechazadas,
            COUNT(CASE WHEN estado = 'borrador' THEN 1 END) as borradores
        FROM publicaciones
    ");
    $stmt->execute();
    $statsPublicaciones = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obtener total de artistas validados (para referencia)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_artistas
        FROM artistas
        WHERE status = 'validado'
    ");
    $stmt->execute();
    $totalArtistas = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Convertir a enteros y estructurar respuesta
    $estadisticas = [
        // Estadísticas de artistas
        'artistas_pendientes' => (int)$statsArtistas['artistas_pendientes'],
        'artistas_validados' => (int)$statsArtistas['artistas_validados'],
        'artistas_rechazados' => (int)$statsArtistas['artistas_rechazados'],
        
        // Estadísticas de obras/publicaciones
        'obras_pendientes' => (int)$statsPublicaciones['obras_pendientes'],
        'obras_validadas' => (int)$statsPublicaciones['obras_validadas'],
        'obras_rechazadas' => (int)$statsPublicaciones['obras_rechazadas'],
        'borradores' => (int)$statsPublicaciones['borradores'],
        
        // Totales generales
        'total_artistas_validados' => (int)$totalArtistas['total_artistas'],
        
        // Retrocompatibilidad (para scripts antiguos)
        'pendientes' => (int)$statsPublicaciones['obras_pendientes'],
        'validados' => (int)$statsPublicaciones['obras_validadas'],
        'rechazados' => (int)$statsPublicaciones['obras_rechazadas']
    ];
    
    echo json_encode($estadisticas);

} catch (PDOException $e) {
    error_log("Error en get_estadisticas_validador.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error del servidor']);
}