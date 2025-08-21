<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Seguridad: Solo editores y admins pueden hacer esto
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Actualizar los campos de texto
    $text_fields = ['welcome_title', 'welcome_paragraph', 'welcome_slogan'];
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            $stmt = $pdo->prepare("UPDATE site_content SET content_value = ? WHERE content_key = ?");
            $stmt->execute([$_POST[$field], $field]);
        }
    }

    // Actualizar las imágenes del carrusel
    for ($i = 1; $i <= 3; $i++) {
        $image_key = 'carousel_image_' . $i;
        if (isset($_FILES[$image_key]) && $_FILES[$image_key]['error'] == 0) {
            // Lógica de subida de archivo...
            // (Aquí iría el código para mover el archivo y obtener la nueva URL)
            // Por ahora, simulamos una nueva URL de placeholder
            $new_image_url = 'https://placehold.co/1200x450/28a745/FFFFFF?text=Imagen+Actualizada';
            
            $stmt = $pdo->prepare("UPDATE site_content SET content_value = ? WHERE content_key = ?");
            $stmt->execute([$new_image_url, $image_key]);
        }
    }

    $pdo->commit();
    echo json_encode(['status' => 'ok', 'message' => 'Contenido de la página de inicio actualizado con éxito.']);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
