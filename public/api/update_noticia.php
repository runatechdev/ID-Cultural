<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Solo el editor o el admin pueden editar
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso.']);
    exit;
}

$id = $_POST['id'] ?? '';
$titulo = trim($_POST['titulo'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');
$imagen_url = null;

if (empty($id) || empty($titulo) || empty($contenido)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'El ID, título y contenido son obligatorios.']);
    exit;
}

try {
    // Obtener la URL de la imagen actual para borrarla si se sube una nueva
    $stmt = $pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $noticia_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql_imagen = "";
    // Lógica para subir la nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $upload_dir = __DIR__ . '/../../static/uploads/noticias/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['imagen']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            // Si se subió una nueva, borramos la vieja si existía
            if ($noticia_actual && !empty($noticia_actual['imagen_url'])) {
                $old_file_path = __DIR__ . '/../../' . $noticia_actual['imagen_url'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            $imagen_url = 'static/uploads/noticias/' . $file_name;
            $sql_imagen = ", imagen_url = :imagen_url";
        }
    }

    $stmt = $pdo->prepare("UPDATE noticias SET titulo = :titulo, contenido = :contenido $sql_imagen WHERE id = :id");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':contenido', $contenido);
    $stmt->bindParam(':id', $id);
    if (!empty($sql_imagen)) {
        $stmt->bindParam(':imagen_url', $imagen_url);
    }
    
    $stmt->execute();
    echo json_encode(['status' => 'ok', 'message' => 'Noticia actualizada con éxito.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
