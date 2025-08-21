<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Solo el editor o el admin pueden borrar noticias
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para realizar esta acción.']);
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de noticia no proporcionado.']);
    exit;
}

try {
    // Primero, obtenemos la URL de la imagen para poder borrar el archivo físico
    $stmt = $pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ahora, borramos el registro de la base de datos
    $delete_stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $delete_stmt->execute([$id]);

    if ($delete_stmt->rowCount() > 0) {
        // Si se borró de la BD y tenía una imagen, borramos el archivo del servidor
        if ($noticia && !empty($noticia['imagen_url'])) {
            $file_path = __DIR__ . '/../../' . $noticia['imagen_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        echo json_encode(['status' => 'ok', 'message' => 'Noticia eliminada con éxito.']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No se encontró la noticia a eliminar.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
