<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Seguridad: Asegurarse de que hay un artista logueado
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
    exit;
}

$artista_id = $_SESSION['user_data']['id'];

try {
    // Buscamos todas las publicaciones del artista que estén en estado 'borrador'
    $stmt = $pdo->prepare(
        "SELECT id, titulo, fecha_creacion 
         FROM publicaciones 
         WHERE usuario_id = ? AND estado = 'borrador'
         ORDER BY fecha_creacion DESC"
    );
    
    $stmt->execute([$artista_id]);
    $borradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($borradores);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>
