<?php
/**
 * API para eliminar una noticia
 * Archivo: /public/api/delete_noticia.php
 */

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Verificar que sea editor o admin
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$noticia_id = $_POST['id'] ?? null;

if (empty($noticia_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de noticia requerido']);
    exit;
}

try {
    // Obtener la noticia para eliminar la imagen
    $stmt = $pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
    $stmt->execute([$noticia_id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Noticia no encontrada']);
        exit;
    }

    // Eliminar imagen si existe
    if ($noticia['imagen_url']) {
        $image_path = str_replace(BASE_URL, __DIR__ . '/../../', $noticia['imagen_url']);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Eliminar noticia de la base de datos
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->execute([$noticia_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'ok',
            'message' => 'Noticia eliminada exitosamente'
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la noticia']);
    }

} catch (PDOException $e) {
    error_log("Error en delete_noticia.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al eliminar la noticia: ' . $e->getMessage()
    ]);
}