<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Recibir el ID del artista a eliminar
$id = $_POST['id'] ?? '';

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de artista no proporcionado.']);
    exit;
}

try {
    // La tabla 'publicaciones' tiene una clave foránea a 'artistas' con ON DELETE CASCADE.
    // Esto significa que al borrar un artista, todas sus publicaciones se borrarán automáticamente.
    $stmt = $pdo->prepare("DELETE FROM artistas WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok', 'message' => 'Artista eliminado con éxito.']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No se encontró el artista a eliminar.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
