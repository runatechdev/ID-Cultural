<?php
/**
 * API para editar una noticia existente
 * Archivo: /public/api/edit_noticia.php
 */

session_start();
header('Content-Type: application/json');

// Ocultar warnings para que no rompan el JSON
error_reporting(E_ERROR | E_PARSE);
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
    echo json_encode(['status' => 'error', 'message' => 'Error de configuración']);
    exit;
}

// Verificar que sea editor o admin
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$noticia_id = $_POST['id'] ?? null;
$titulo = trim($_POST['titulo'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');

// Validar datos
if (empty($noticia_id) || empty($titulo) || empty($contenido)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    // Obtener la noticia actual
    $stmt = $pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
    $stmt->execute([$noticia_id]);
    $noticia_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia_actual) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Noticia no encontrada']);
        exit;
    }

    $imagen_url = $noticia_actual['imagen_url'];

    // Procesar nueva imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Intentar varios paths posibles
        $possible_paths = [
            __DIR__ . '/../../static/uploads/noticias/',
            __DIR__ . '/../../../static/uploads/noticias/',
            $_SERVER['DOCUMENT_ROOT'] . '/static/uploads/noticias/'
        ];

        $upload_dir = null;
        foreach ($possible_paths as $path) {
            if (is_dir(dirname($path)) && is_writable(dirname($path))) {
                $upload_dir = $path;
                break;
            }
        }

        // Si ninguna carpeta existe o es escribible, intentar usar /tmp
        if (!$upload_dir) {
            $upload_dir = '/tmp/id_cultural_uploads/';
        }

        // Crear directorio si no existe y es posible
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        // Solo proceder si el directorio es escribible
        if (is_writable($upload_dir)) {
            $file_tmp = $_FILES['imagen']['tmp_name'];
            $file_name = $_FILES['imagen']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Validar extensión
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_ext, $allowed_extensions)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido']);
                exit;
            }

            // Validar tamaño (máximo 5MB)
            if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'La imagen no debe superar los 5MB']);
                exit;
            }

            // Eliminar imagen anterior si existe
            if ($imagen_url) {
                $old_image_path = str_replace(BASE_URL, __DIR__ . '/../../', $imagen_url);
                if (file_exists($old_image_path)) {
                    @unlink($old_image_path);
                }
            }

            // Generar nombre único
            $new_filename = 'noticia_' . time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            // Mover archivo
            if (@move_uploaded_file($file_tmp, $upload_path)) {
                $imagen_url = BASE_URL . 'static/uploads/noticias/' . $new_filename;
            } else {
                // Si falla, dejar imagen_url como null pero no fallar la actualización
                $imagen_url = $noticia_actual['imagen_url'];
            }
        }
    }

    // Actualizar noticia en la base de datos
    $stmt = $pdo->prepare("
        UPDATE noticias 
        SET titulo = :titulo, 
            contenido = :contenido, 
            imagen_url = :imagen_url
        WHERE id = :id
    ");

    $stmt->execute([
        ':titulo' => $titulo,
        ':contenido' => $contenido,
        ':imagen_url' => $imagen_url,
        ':id' => $noticia_id
    ]);

    echo json_encode([
        'status' => 'ok',
        'message' => 'Noticia actualizada exitosamente',
        'imagen_url' => $imagen_url
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al actualizar la noticia'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}