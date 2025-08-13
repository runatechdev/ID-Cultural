<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Recibir el ID del usuario a eliminar desde la petición POST
$id = $_POST['id'] ?? '';

// Validación para asegurarse de que se envió un ID
if (empty($id)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
    exit;
}

try {
    // Preparar la sentencia SQL para eliminar el usuario
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // rowCount() devuelve el número de filas afectadas. Si es mayor que 0, el borrado fue exitoso.
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok', 'message' => 'Usuario eliminado con éxito.']);
    } else {
        // Si no se afectaron filas, significa que el usuario con ese ID no existía.
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => 'No se encontró el usuario a eliminar.']);
    }

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
