<?php
ini_set('display_errors', 0);
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Validar sesión
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    http_response_code(403);
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'error' => 'No autorizado.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$obra_id = $input['id'] ?? null;
$usuario_id = $_SESSION['user_data']['id'] ?? null;

if (!$obra_id || !$usuario_id) {
    http_response_code(400);
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    exit;
}

try {
    // Verificar que la obra pertenezca al usuario
    $stmt = $pdo->prepare('SELECT id FROM publicaciones WHERE id = ? AND usuario_id = ?');
    $stmt->execute([$obra_id, $usuario_id]);
    $obra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$obra) {
        http_response_code(404);
        if (ob_get_length()) ob_clean();
        echo json_encode(['success' => false, 'error' => 'Obra no encontrada o no autorizada.']);
        exit;
    }

    // Eliminar la obra
    $stmt = $pdo->prepare('DELETE FROM publicaciones WHERE id = ? AND usuario_id = ?');
    $stmt->execute([$obra_id, $usuario_id]);

    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'error' => 'Error al eliminar: ' . $e->getMessage()]);
}
