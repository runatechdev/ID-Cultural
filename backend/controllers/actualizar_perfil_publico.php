<?php
/**
 * actualizar_perfil_publico.php
 * Controller para actualizar perfil público del artista (con validación)
 */
session_start();
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../helpers/MultimediaValidator.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
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
        $foto_ruta = $validator->validarImagen($foto_perfil);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

try {
    // Crear registro de cambios pendientes de validación
    $stmt = $pdo->prepare("
        INSERT INTO cambios_pendientes_validacion 
        (usuario_id, tipo_cambio, datos_antiguos, datos_nuevos, estado) 
        VALUES (?, 'perfil_publico', ?, ?, 'pendiente')
    ");

    $datos_nuevos = [
        'biografia' => $biografia,
        'especialidades' => $especialidades,
        'instagram' => $instagram,
        'facebook' => $facebook,
        'twitter' => $twitter,
        'sitio_web' => $sitio_web,
        'foto_perfil' => $foto_ruta
    ];

    // Obtener datos antiguos
    $stmt_get = $pdo->prepare("SELECT biografia, especialidades, instagram, facebook, twitter, sitio_web FROM artistas WHERE id = ?");
    $stmt_get->execute([$usuario_id]);
    $datos_antiguos = $stmt_get->fetch(PDO::FETCH_ASSOC);

    $resultado = $stmt->execute([
        $usuario_id,
        json_encode($datos_antiguos),
        json_encode($datos_nuevos)
    ]);

    if ($resultado) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'mensaje' => 'Tu perfil público ha sido enviado a validación. Te notificaremos cuando sea aprobado.'
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No se pudo procesar la solicitud']);
    }
} catch (Exception $e) {
    error_log("Error al actualizar perfil público: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la solicitud']);
}
