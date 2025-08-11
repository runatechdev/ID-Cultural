<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/conexion.php';

// Recibir el ID del usuario a eliminar
$id = $_POST['id'] ?? '';

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok', 'message' => 'Usuario eliminado con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró el usuario.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
