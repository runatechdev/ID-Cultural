<?php
/**
 * actualizar_perfil_publico.php
// Procesar foto si existe
if ($foto_perfil && $foto_perfil['error'] === UPLOAD_ERR_OK) {
    try {
        $validator = new MultimediaValidator();
        $resultado_guardado = $validator->guardarArchivo($foto_perfil, 'imagen');
        
        if (!$resultado_guardado['exitoso']) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => $resultado_guardado['mensaje'] ?? 'No se pudo guardar la imagen'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $foto_ruta = $resultado_guardado['ruta'];
        error_log("Foto guardada exitosamente en: " . $foto_ruta);
    } catch (Exception $e) {
        error_log("Excepción al guardar foto: " . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'error' => 'Excepción: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
} elseif ($foto_perfil && $foto_perfil['error'] !== UPLOAD_ERR_NO_FILE) {
    // Si hay un archivo pero con error (pero no UPLOAD_ERR_NO_FILE que significa que no enviaron foto)
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Error en la subida del archivo: Código ' . $foto_perfil['error']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}actualizar perfil público del artista (con validación)
 */

// Limpiar cualquier salida anterior si existe buffer
if (ob_get_length()) {
    ob_clean();
}

// Configurar error handling para que no muestre errores en pantalla
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../helpers/MultimediaValidator.php';

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado'], JSON_UNESCAPED_UNICODE);
    exit;
}

$usuario_id = $_SESSION['user_data']['id'];

// Preparar datos
$biografia = $_POST['biografia'] ?? '';
$especialidades = $_POST['especialidades'] ?? '';
$instagram = $_POST['instagram'] ?? '';
$facebook = $_POST['facebook'] ?? '';
$twitter = $_POST['twitter'] ?? '';
$sitio_web = $_POST['sitio_web'] ?? '';
$foto_perfil = $_FILES['foto_perfil'] ?? null;

$foto_ruta = null;

// Procesar foto si existe
if ($foto_perfil && $foto_perfil['error'] === UPLOAD_ERR_OK) {
    try {
        $validator = new MultimediaValidator();
        $resultado_guardado = $validator->guardarArchivo($foto_perfil, 'imagen');
        
        if (!$resultado_guardado['exitoso']) {
            http_response_code(200);
            echo json_encode(['success' => false, 'error' => $resultado_guardado['mensaje']], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $foto_ruta = $resultado_guardado['ruta'];
    } catch (Exception $e) {
        http_response_code(200);
        echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

try {
    // En lugar de actualizar directamente el perfil, 
    // guardamos los cambios en una tabla temporal para validación
    
    // Primero verificar si ya hay un cambio pendiente
    $stmt_check = $pdo->prepare("
        SELECT id FROM perfil_cambios_pendientes 
        WHERE artista_id = ? AND estado = 'pendiente'
    ");
    $stmt_check->execute([$usuario_id]);
    $cambio_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if ($cambio_existente) {
        // Actualizar el cambio pendiente existente
        $sql = "UPDATE perfil_cambios_pendientes SET ";
        $campos = [];
        $valores = [];
        
        $campos[] = "biografia = ?";
        $valores[] = $biografia;
        
        $campos[] = "especialidades = ?";
        $valores[] = $especialidades;
        
        $campos[] = "instagram = ?";
        $valores[] = $instagram;
        
        $campos[] = "facebook = ?";
        $valores[] = $facebook;
        
        $campos[] = "twitter = ?";
        $valores[] = $twitter;
        
        $campos[] = "sitio_web = ?";
        $valores[] = $sitio_web;
        
        if ($foto_ruta) {
            $campos[] = "foto_perfil = ?";
            $valores[] = $foto_ruta;
        }
        
        $campos[] = "fecha_solicitud = NOW()";
        
        $sql .= implode(", ", $campos) . " WHERE id = ?";
        $valores[] = $cambio_existente['id'];
        
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute($valores);
    } else {
        // Crear nuevo cambio pendiente
        $stmt = $pdo->prepare("
            INSERT INTO perfil_cambios_pendientes 
            (artista_id, biografia, especialidades, instagram, facebook, twitter, sitio_web, foto_perfil, estado, fecha_solicitud)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())
        ");
        $resultado = $stmt->execute([
            $usuario_id,
            $biografia,
            $especialidades,
            $instagram,
            $facebook,
            $twitter,
            $sitio_web,
            $foto_ruta
        ]);
    }

    if ($resultado) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'mensaje' => 'Tu perfil público ha sido enviado para revisión. Los cambios se aplicarán una vez que el equipo de validación los apruebe.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        http_response_code(200);
        echo json_encode(['success' => false, 'error' => 'No se pudo procesar la solicitud'], JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (Exception $e) {
    error_log("Error al actualizar perfil público: " . $e->getMessage());
    http_response_code(200);
    echo json_encode(['success' => false, 'error' => 'Error al procesar la solicitud: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}
