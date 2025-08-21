<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, email, status FROM artistas ORDER BY id DESC");
    $stmt->execute();
    $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($artistas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>