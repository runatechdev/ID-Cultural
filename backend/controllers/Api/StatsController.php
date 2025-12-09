<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';

use PDO;
use Exception;
use PDOException;

class StatsController
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
                case 'public':
                    $this->getPublicStats();
                    break;
                case 'admin':
                    $this->getAdminStats();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getPublicStats()
    {
        $artistas = $this->pdo->query("SELECT COUNT(*) FROM artistas WHERE status = 'validado'")->fetchColumn();
        $obras = $this->pdo->query("SELECT COUNT(*) FROM publicaciones WHERE estado = 'validado'")->fetchColumn();
        $noticias = $this->pdo->query("SELECT COUNT(*) FROM noticias")->fetchColumn();

        echo json_encode([
            'status' => 'ok',
            'artistas' => (int)$artistas,
            'obras' => (int)$obras,
            'noticias' => (int)$noticias
        ]);
    }

    private function getAdminStats()
    {
        $this->requireAuth(['admin', 'validador']);

        // Check columns to avoid errors if status/estado columns differ from assumptions
        // Artistas
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(CASE WHEN status = 'pendiente' THEN 1 END) as artistas_pendientes,
                COUNT(CASE WHEN status = 'validado' THEN 1 END) as artistas_validados,
                COUNT(CASE WHEN status = 'rechazado' THEN 1 END) as artistas_rechazados
            FROM artistas
        ");
        $stmt->execute();
        $statsArtistas = $stmt->fetch(PDO::FETCH_ASSOC);

        // Publicaciones
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as obras_pendientes,
                COUNT(CASE WHEN estado = 'validado' THEN 1 END) as obras_validadas,
                COUNT(CASE WHEN estado = 'rechazado' THEN 1 END) as obras_rechazadas,
                COUNT(CASE WHEN estado = 'borrador' THEN 1 END) as borradores
            FROM publicaciones
        ");
        $stmt->execute();
        $statsPublicaciones = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = [
            'artistas_pendientes' => (int)$statsArtistas['artistas_pendientes'],
            'artistas_validados' => (int)$statsArtistas['artistas_validados'],
            'artistas_rechazados' => (int)$statsArtistas['artistas_rechazados'],
            'obras_pendientes' => (int)$statsPublicaciones['obras_pendientes'],
            'obras_validadas' => (int)$statsPublicaciones['obras_validadas'],
            'obras_rechazadas' => (int)$statsPublicaciones['obras_rechazadas'],
            'borradores' => (int)$statsPublicaciones['borradores'],
            // Backwards compatibility
            'pendientes' => (int)$statsPublicaciones['obras_pendientes'],
            'validados' => (int)$statsPublicaciones['obras_validadas'],
            'rechazados' => (int)$statsPublicaciones['obras_rechazadas']
        ];

        echo json_encode($response);
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
