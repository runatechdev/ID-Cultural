<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';

use PDO;
use Exception;
use PDOException;

class LogController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function handleRequest($action)
    {
        try {
            switch ($action) {
                case 'get_all':
                    $this->getAll();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getAll()
    {
        $this->requireAuth(['admin', 'validador']);

        $stmt = $this->pdo->prepare("SELECT id, user_name, action, details, timestamp FROM system_logs ORDER BY timestamp DESC");
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($logs);
    }

    private function requireAuth($roles)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], $roles)) {
            $this->sendResponse('error', 'No autorizado', 403);
        }
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }
}
