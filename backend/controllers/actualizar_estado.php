<?php
require_once __DIR__ . '/../config/connection.php'; // Incluye config y la conexión

header('Content-Type: application/json');

// 1. Verificación de seguridad: ¿Quien hace la petición es un validador o admin?
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'validador'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
    exit();
}

$id_artista = $_POST['id'] ?? null;
$nuevo_estado = $_POST['estado'] ?? null;
$estado_anterior = '';

if (!$id_artista || !$nuevo_estado) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos para la actualización.']);
    exit();
}

try {
    // Opcional: obtener estado anterior para revertir en caso de error
    $stmt = $pdo->prepare("SELECT estado FROM solicitudes WHERE id = ?"); // Asumiendo que la tabla se llama 'solicitudes'
    $stmt->execute([$id_artista]);
    $result = $stmt->fetch();
    if ($result) {
        $estado_anterior = $result['estado'];
    }

    // 2. Preparamos la actualización
    $fecha_validacion = ($nuevo_estado === 'Aprobado' || $nuevo_estado === 'Rechazado') ? date('Y-m-d H:i:s') : null;

    $updateStmt = $pdo->prepare("UPDATE solicitudes SET estado = ?, fecha_validacion = ? WHERE id = ?");
    $success = $updateStmt->execute([$nuevo_estado, $fecha_validacion, $id_artista]);

    if ($success) {
        echo json_encode([
            'status' => 'ok',
            'message' => 'Estado actualizado correctamente.',
            'fechaValidacion' => $fecha_validacion ? date('Y-m-d', strtotime($fecha_validacion)) : '-'
        ]);
    } else {
        throw new Exception("No se pudo actualizar la base de datos.");
    }

} catch (Exception $e) {
    error_log("Error al actualizar estado: " . $e->getMessage());
    echo json_encode([
        'status' => 'error', 
        'message' => 'Error del servidor al actualizar el estado.',
        'estadoAnterior' => $estado_anterior
    ]);
}
?>