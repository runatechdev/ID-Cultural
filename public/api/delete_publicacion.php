<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$publicacion_id = $_POST['id'] ?? '';
$usuario_id = $_SESSION['user_data']['id'] ?? null;
$usuario_role = $_SESSION['user_data']['role'] ?? null;

if (empty($publicacion_id) || empty($usuario_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos.']);
    exit;
}

try {
    // Un artista solo puede borrar sus propias publicaciones. Un admin puede borrar cualquiera.
    $sql = "DELETE FROM publicaciones WHERE id = ?";
    $params = [$publicacion_id];

    if ($usuario_role === 'artista') {
        $sql .= " AND usuario_id = ?";
        $params[] = $usuario_id;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok', 'message' => 'Publicación eliminada con éxito.']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No se encontró la publicación o no tienes permiso para eliminarla.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
}
?>
