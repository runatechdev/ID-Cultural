<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$id = $_POST['id'] ?? '';
$nuevo_status = $_POST['status'] ?? '';
$motivo = trim($_POST['motivo'] ?? '');
$admin_id = $_SESSION['user_data']['id'] ?? null;
$admin_nombre = $_SESSION['user_data']['nombre'] ?? 'Admin';

if (empty($id) || empty($nuevo_status) || !in_array($nuevo_status, ['validado', 'rechazado'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("UPDATE artistas SET status = ? WHERE id = ?");
    $stmt->execute([$nuevo_status, $id]);

    if ($stmt->rowCount() > 0) {
        $action = ($nuevo_status == 'validado') ? 'VALIDACIÓN DE ARTISTA' : 'RECHAZO DE ARTISTA';
        $details = "Se ha cambiado el estado del artista ID: {$id} a {$nuevo_status}.";
        if (!empty($motivo)) {
            $details .= ($nuevo_status == 'validado') ? " Comentario: {$motivo}" : " Motivo: {$motivo}";
        }

        $log_stmt = $pdo->prepare("INSERT INTO system_logs (user_id, user_name, action, details) VALUES (?, ?, ?, ?)");
        $log_stmt->execute([$admin_id, $admin_nombre, $action, $details]);

        $pdo->commit();
        echo json_encode(['status' => 'ok', 'message' => 'El estado del artista ha sido actualizado.']);
    } else {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado del artista.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
}
?>
