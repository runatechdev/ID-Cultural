<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$titulo = trim($_POST['titulo'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');
$editor_id = $_SESSION['user_data']['id'] ?? null;
$imagen_url = null;

if (empty($titulo) || empty($contenido) || empty($editor_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'El título y el contenido son obligatorios.']);
    exit;
}

// Lógica para subir la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $upload_dir = __DIR__ . '/../../static/uploads/noticias/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = time() . '_' . basename($_FILES['imagen']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
        // Guardamos la URL relativa
        $imagen_url = 'static/uploads/noticias/' . $file_name;
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO noticias (titulo, contenido, imagen_url, editor_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titulo, $contenido, $imagen_url, $editor_id]);
    echo json_encode(['status' => 'ok', 'message' => 'Noticia guardada con éxito.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
}
?>
