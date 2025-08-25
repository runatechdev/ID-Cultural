<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Obtener el filtro de estado desde la URL (ej: ?status=pendiente)
$status_filter = $_GET['status'] ?? null;

try {
    // Empezamos con la consulta base
    $sql = "SELECT id, nombre, apellido, email, status FROM artistas";
    $params = [];

    // Si se proporciona un filtro de estado, lo añadimos a la consulta
    if ($status_filter) {
        $sql .= " WHERE status = ?";
        $params[] = $status_filter;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($artistas);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>
