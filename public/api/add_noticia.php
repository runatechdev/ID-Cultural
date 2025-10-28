<?php
/**
 * API para agregar una noticia nueva
 * Archivo: /public/api/add_noticia.php
 */

session_start();
header('Content-Type: application/json');

// Manejo de errores para que no rompa el JSON
error_reporting(0);
ini_set('display_errors', 0);

try {
    require_once __DIR__ . '/../../backend/config/connection.php';
    
    // Obtener BASE_URL de forma dinámica si no está definida
    if (!defined('BASE_URL')) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        define('BASE_URL', $protocol . $host . '/');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error de configuración: ' . $e->getMessage()]);
    exit;
}

// Verificar que sea editor o admin
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$titulo = trim($_POST['titulo'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');
$editor_id = $_SESSION['user_data']['id'];

// Validar datos
if (empty($titulo) || empty($contenido)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'El título y contenido son obligatorios']);
    exit;
}

try {
    $imagen_url = null;

    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../static/uploads/noticias/';
        
        // Crear directorio si no existe
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de uploads');
            }
        }

        // Verificar que el directorio sea escribible
        if (!is_writable($upload_dir)) {
            throw new Exception('El directorio de uploads no tiene permisos de escritura');
        }

        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_name = $_FILES['imagen']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validar extensión
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_ext, $allowed_extensions)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido. Use: jpg, png, gif, webp']);
            exit;
        }

        // Validar tamaño (máximo 5MB)
        if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'La imagen no debe superar los 5MB']);
            exit;
        }

        // Generar nombre único
        $new_filename = 'noticia_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_filename;

        // Mover archivo
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $imagen_url = BASE_URL . 'static/uploads/noticias/' . $new_filename;
        } else {
            throw new Exception('No se pudo guardar el archivo. Verifique los permisos del servidor');
        }
    }

    // Insertar noticia en la base de datos
    $stmt = $pdo->prepare("
        INSERT INTO noticias (titulo, contenido, imagen_url, editor_id, fecha_creacion)
        VALUES (:titulo, :contenido, :imagen_url, :editor_id, NOW())
    ");

    $stmt->execute([
        ':titulo' => $titulo,
        ':contenido' => $contenido,
        ':imagen_url' => $imagen_url,
        ':editor_id' => $editor_id
    ]);

    $noticia_id = $pdo->lastInsertId();

    echo json_encode([
        'status' => 'ok',
        'message' => 'Noticia creada exitosamente',
        'id' => $noticia_id,
        'imagen_url' => $imagen_url
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}