<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$id = $_POST['id'] ?? '';
$nuevo_estado = $_POST['estado'] ?? '';
$validador_id = $_SESSION['user_data']['id'] ?? null; // ID del admin/validador logueado

// Validaciones
if (empty($id) || empty($nuevo_estado) || empty($validador_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos para procesar la solicitud.']);
    exit;
}
if (!in_array($nuevo_estado, ['validado', 'rechazado'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Estado no válido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE publicaciones 
        SET estado = ?, validador_id = ?, fecha_validacion = CURRENT_TIMESTAMP 
        WHERE id = ? AND estado = 'pendiente_validacion'
    ");
    $stmt->execute([$nuevo_estado, $validador_id, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok', 'message' => 'El estado de la solicitud ha sido actualizado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la solicitud o ya fue procesada.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
