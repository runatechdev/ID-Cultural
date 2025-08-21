<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$id = $_POST['id'] ?? '';
$nuevo_estado = $_POST['estado'] ?? '';
$motivo = trim($_POST['motivo'] ?? ''); // Nuevo campo para el motivo/comentario
$validador_id = $_SESSION['user_data']['id'] ?? null;
$validador_nombre = $_SESSION['user_data']['nombre'] ?? 'Usuario Desconocido';

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
    // Iniciar una transacción para asegurar que ambas operaciones (update y log) se completen
    $pdo->beginTransaction();

    // 1. Actualizar el estado de la publicación
    $stmt = $pdo->prepare("
        UPDATE publicaciones 
        SET estado = ?, validador_id = ?, fecha_validacion = CURRENT_TIMESTAMP 
        WHERE id = ? AND estado = 'pendiente_validacion'
    ");
    $stmt->execute([$nuevo_estado, $validador_id, $id]);

    if ($stmt->rowCount() > 0) {
        // 2. Crear el registro en el log del sistema
        $action = ($nuevo_estado == 'validado') ? 'VALIDACIÓN DE ARTISTA' : 'RECHAZO DE ARTISTA';
        $details = "Se ha {$nuevo_estado} la solicitud con ID: {$id}.";
        if (!empty($motivo)) {
            $details .= ($nuevo_estado == 'validado') ? " Comentario: {$motivo}" : " Motivo: {$motivo}";
        }

        $log_stmt = $pdo->prepare("INSERT INTO system_logs (user_id, user_name, action, details) VALUES (?, ?, ?, ?)");
        $log_stmt->execute([$validador_id, $validador_nombre, $action, $details]);

        // Si todo fue bien, confirmar los cambios
        $pdo->commit();
        echo json_encode(['status' => 'ok', 'message' => 'El estado de la solicitud ha sido actualizado.']);

    } else {
        // Si no se actualizó nada, revertir
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la solicitud o ya fue procesada.']);
    }

} catch (PDOException $e) {
    // Si algo falla, revertir todos los cambios
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
