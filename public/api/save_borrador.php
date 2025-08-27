<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Seguridad: Solo artistas pueden crear borradores
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso.']);
    exit;
}

// Recibir datos principales
$usuario_id = $_SESSION['user_data']['id'];
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$estado = trim($_POST['estado'] ?? 'borrador'); // 'borrador' o 'pendiente_validacion'

if (empty($titulo) || empty($descripcion) || empty($categoria)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'El título, la descripción y la categoría son obligatorios.']);
    exit;
}

// Recopilar todos los campos extra en un array asociativo
$campos_extra = [];
foreach ($_POST as $key => $value) {
    if (!in_array($key, ['titulo', 'descripcion', 'categoria', 'estado'])) {
        $campos_extra[$key] = trim($value);
    }
}
$campos_extra_json = json_encode($campos_extra);

try {
    // Si se envía a validación, actualizamos la fecha de envío
    $fecha_envio = ($estado === 'pendiente_validacion') ? date('Y-m-d H:i:s') : null;

    $stmt = $pdo->prepare(
        "INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, estado, fecha_envio_validacion) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$usuario_id, $titulo, $descripcion, $categoria, $campos_extra_json, $estado, $fecha_envio]);

    // Si se envía a validación, también actualizamos el estado del artista a 'pendiente' si es la primera vez
    if ($estado === 'pendiente_validacion') {
        $update_artista = $pdo->prepare("UPDATE artistas SET status = 'pendiente' WHERE id = ? AND status != 'validado'");
        $update_artista->execute([$usuario_id]);
    }

    echo json_encode(['status' => 'ok', 'message' => 'Operación realizada con éxito.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
