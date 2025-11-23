<?php
/**
 * API de Notificaciones - ID Cultural
 * Endpoints para gestión de notificaciones del usuario
 */

session_start();
header('Content-Type: application/json; charset=UTF-8');

// Verificar autenticación
if (!isset($_SESSION['user_data'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
    exit;
}

require_once '../../backend/config/connection.php';

$usuario_id = $_SESSION['user_data']['id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Log temporal para depuración
error_log("=== NOTIFICACIONES API ===");
error_log("Action recibida: " . $action);
error_log("Usuario ID: " . $usuario_id);
error_log("GET params: " . print_r($_GET, true));

try {
    switch ($action) {
        case 'get':
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
            $no_leidas = isset($_GET['no_leidas']) && $_GET['no_leidas'] === 'true';
            
            $query = "SELECT * FROM notificaciones WHERE usuario_id = :usuario_id";
            
            if ($no_leidas) {
                $query .= " AND leida = FALSE";
            }
            
            $query .= " ORDER BY created_at DESC LIMIT :limit";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($notificaciones as &$notif) {
                if (!empty($notif['datos_adicionales'])) {
                    $notif['datos_adicionales'] = json_decode($notif['datos_adicionales'], true);
                }
            }
            
            echo json_encode(['status' => 'success', 'notifications' => $notificaciones]);
            break;
            
        case 'count_no_leidas':
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leida = FALSE");
            $stmt->execute([$usuario_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'total' => intval($result['total'] ?? 0)]);
            break;
            
        case 'mark_read':
            $notificacion_id = $_POST['notification_id'] ?? null;
            
            if (!$notificacion_id) {
                throw new Exception('ID de notificación requerido');
            }
            
            $stmt = $pdo->prepare("UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$notificacion_id, $usuario_id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Notificación marcada como leída']);
            break;
            
        case 'mark_all_read':
            $stmt = $pdo->prepare("UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() WHERE usuario_id = ? AND leida = FALSE");
            $stmt->execute([$usuario_id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Todas las notificaciones marcadas como leídas']);
            break;
            
        case 'delete':
            $notificacion_id = $_POST['notification_id'] ?? null;
            
            if (!$notificacion_id) {
                throw new Exception('ID de notificación requerido');
            }
            
            $stmt = $pdo->prepare("DELETE FROM notificaciones WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$notificacion_id, $usuario_id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Notificación eliminada']);
            break;
            
        case 'marcar_leida':
            $notificacion_id = $_POST['notificacion_id'] ?? null;
            
            if (!$notificacion_id) {
                throw new Exception('ID de notificación requerido');
            }
            
            $stmt = $pdo->prepare("UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$notificacion_id, $usuario_id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Notificación marcada como leída']);
            break;
            
        case 'marcar_todas_leidas':
            $stmt = $pdo->prepare("UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() WHERE usuario_id = ? AND leida = FALSE");
            $stmt->execute([$usuario_id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Todas las notificaciones marcadas como leídas']);
            break;
            
        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
