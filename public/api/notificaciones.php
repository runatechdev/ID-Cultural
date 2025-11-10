<?php
/**
 * API de Notificaciones - ID Cultural
 * Endpoints para gestión de notificaciones del usuario
 */

require_once '../../backend/config/connection.php';
require_once '../../backend/helpers/ErrorHandler.php';
require_once '../../backend/helpers/RateLimiter.php';

// Inicializar
ErrorHandler::init();
session_start();

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    ErrorHandler::unauthorized('Debe iniciar sesión');
}

$userId = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? null;

// Rate limiting
if (!RateLimiter::check('api_general')) {
    $info = RateLimiter::getInfo('api_general');
    RateLimiter::addHeaders('api_general');
    http_response_code(429);
    ErrorHandler::error('Demasiadas solicitudes. Intente más tarde.', 429);
}

RateLimiter::addHeaders('api_general');

class NotificationManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtener notificaciones del usuario
     */
    public function getNotifications($userId, $limit = 20, $offset = 0, $leidas = null) {
        $query = "SELECT * FROM notificaciones WHERE usuario_id = ? ";
        $types = 'i';
        $params = [$userId];

        if ($leidas !== null) {
            $query .= "AND leida = ? ";
            $types .= 'i';
            $params[] = (int)$leidas;
        }

        $query .= "ORDER BY created_at DESC LIMIT ?, ?";
        $types .= 'ii';
        $params[] = (int)$offset;
        $params[] = (int)$limit;

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['datos_adicionales']) {
                $row['datos_adicionales'] = json_decode($row['datos_adicionales'], true);
            }
            $notifications[] = $row;
        }

        return $notifications;
    }

    /**
     * Obtener notificaciones no leídas
     */
    public function getUnreadCount($userId) {
        $query = "SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leida = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    /**
     * Marcar como leída
     */
    public function markAsRead($userId, $notificationId) {
        // Verificar que pertenece al usuario
        $query = "UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() 
                  WHERE id = ? AND usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $notificationId, $userId);

        if (!$stmt->execute()) {
            throw new Exception('Error al marcar notificación');
        }

        return $this->conn->affected_rows > 0;
    }

    /**
     * Marcar todas como leídas
     */
    public function markAllAsRead($userId) {
        $query = "UPDATE notificaciones SET leida = TRUE, fecha_lectura = NOW() 
                  WHERE usuario_id = ? AND leida = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);

        if (!$stmt->execute()) {
            throw new Exception('Error al marcar notificaciones');
        }

        return $this->conn->affected_rows;
    }

    /**
     * Eliminar notificación
     */
    public function deleteNotification($userId, $notificationId) {
        $query = "DELETE FROM notificaciones WHERE id = ? AND usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $notificationId, $userId);

        if (!$stmt->execute()) {
            throw new Exception('Error al eliminar notificación');
        }

        return $this->conn->affected_rows > 0;
    }

    /**
     * Eliminar todas las notificaciones leídas
     */
    public function deleteReadNotifications($userId) {
        $query = "DELETE FROM notificaciones WHERE usuario_id = ? AND leida = TRUE";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);

        if (!$stmt->execute()) {
            throw new Exception('Error al eliminar notificaciones');
        }

        return $this->conn->affected_rows;
    }

    /**
     * Crear notificación
     */
    public static function create($conn, $userId, $tipo, $titulo, $mensaje, $urlAccion = null, $datosAdicionales = null) {
        $query = "INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje, url_accion, datos_adicionales) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        $datosJson = $datosAdicionales ? json_encode($datosAdicionales) : null;
        $stmt->bind_param('isssss', $userId, $tipo, $titulo, $mensaje, $urlAccion, $datosJson);

        if (!$stmt->execute()) {
            return false;
        }

        return $conn->insert_id;
    }

    /**
     * Obtener preferencias
     */
    public function getPreferences($userId) {
        $query = "SELECT * FROM preferencias_notificaciones WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Crear preferencias por defecto
            $this->createDefaultPreferences($userId);
            return $result->fetch_assoc();
        }

        return $result->fetch_assoc();
    }

    /**
     * Actualizar preferencias
     */
    public function updatePreferences($userId, $preferences) {
        $query = "UPDATE preferencias_notificaciones SET 
                  notificaciones_email = ?,
                  notificaciones_perfil = ?,
                  notificaciones_validacion = ?,
                  notificaciones_comentarios = ?,
                  notificaciones_mensajes = ?,
                  frecuencia_email = ?
                  WHERE usuario_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            'bbbbbsi',
            $preferences['notificaciones_email'] ?? true,
            $preferences['notificaciones_perfil'] ?? true,
            $preferences['notificaciones_validacion'] ?? true,
            $preferences['notificaciones_comentarios'] ?? true,
            $preferences['notificaciones_mensajes'] ?? true,
            $preferences['frecuencia_email'] ?? 'diario',
            $userId
        );

        return $stmt->execute();
    }

    /**
     * Crear preferencias por defecto
     */
    private function createDefaultPreferences($userId) {
        $query = "INSERT INTO preferencias_notificaciones (usuario_id) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }
}

try {
    $manager = new NotificationManager($conn);

    switch ($action) {
        case 'get':
            $limit = (int)($_GET['limit'] ?? 20);
            $offset = (int)($_GET['offset'] ?? 0);
            $leidas = isset($_GET['leidas']) ? (bool)$_GET['leidas'] : null;

            $notifications = $manager->getNotifications($userId, $limit, $offset, $leidas);
            $unreadCount = $manager->getUnreadCount($userId);

            ErrorHandler::success([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ], 'Notificaciones obtenidas');
            break;

        case 'mark_read':
            $notificationId = (int)$_POST['notification_id'];

            if (empty($notificationId)) {
                ErrorHandler::validation(['notification_id' => 'Requerido']);
            }

            $marked = $manager->markAsRead($userId, $notificationId);

            if ($marked) {
                ErrorHandler::success(null, 'Notificación marcada como leída');
            } else {
                ErrorHandler::notFound('Notificación no encontrada');
            }
            break;

        case 'mark_all_read':
            $count = $manager->markAllAsRead($userId);
            ErrorHandler::success(['marked' => $count], 'Notificaciones marcadas como leídas');
            break;

        case 'delete':
            $notificationId = (int)$_POST['notification_id'];

            if (empty($notificationId)) {
                ErrorHandler::validation(['notification_id' => 'Requerido']);
            }

            $deleted = $manager->deleteNotification($userId, $notificationId);

            if ($deleted) {
                ErrorHandler::success(null, 'Notificación eliminada');
            } else {
                ErrorHandler::notFound('Notificación no encontrada');
            }
            break;

        case 'delete_read':
            $count = $manager->deleteReadNotifications($userId);
            ErrorHandler::success(['deleted' => $count], 'Notificaciones leídas eliminadas');
            break;

        case 'preferences':
            $method = $_SERVER['REQUEST_METHOD'];

            if ($method === 'GET') {
                $preferences = $manager->getPreferences($userId);
                ErrorHandler::success($preferences, 'Preferencias obtenidas');
            } elseif ($method === 'POST') {
                $preferences = json_decode(file_get_contents('php://input'), true);

                if (empty($preferences)) {
                    ErrorHandler::validation(['preferences' => 'Datos requeridos']);
                }

                if ($manager->updatePreferences($userId, $preferences)) {
                    ErrorHandler::success(null, 'Preferencias actualizadas');
                } else {
                    ErrorHandler::error('Error al actualizar preferencias');
                }
            }
            break;

        default:
            ErrorHandler::error('Acción no válida', 400);
    }
} catch (Exception $e) {
    ErrorHandler::error($e->getMessage(), 500);
}
