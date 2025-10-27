  <?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    $stmt = $pdo->prepare("SELECT id, titulo, contenido, imagen_url, fecha_creacion FROM noticias ORDER BY fecha_creacion DESC");
    $stmt->execute();
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($noticias);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>
