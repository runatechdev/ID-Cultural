<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    $stmt = $pdo->prepare("SELECT content_key, content_value FROM site_content");
    $stmt->execute();
    $content_items = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [key => value]
    
    echo json_encode($content_items);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>
