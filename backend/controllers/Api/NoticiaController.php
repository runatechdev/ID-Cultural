<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../helpers/MultimediaValidator.php';

use PDO;
use Exception;
use PDOException;
use MultimediaValidator;

class NoticiaController
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
                case 'get':
                    $this->get();
                    break;
                case 'add':
                    $this->create();
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function get()
    {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $stmt = $this->pdo->prepare("SELECT id, titulo, contenido, imagen_url, fecha_creacion FROM noticias WHERE id = ?");
            $stmt->execute([$id]);
            $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($noticia) echo json_encode($noticia);
            else $this->sendResponse('error', 'Noticia no encontrada', 404);
        } else {
            $stmt = $this->pdo->prepare("SELECT id, titulo, contenido, imagen_url, fecha_creacion FROM noticias ORDER BY fecha_creacion DESC");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    private function create()
    {
        $user = $this->requireAuth(['editor', 'admin']);

        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$titulo || !$contenido) $this->sendResponse('error', 'Título y contenido requeridos', 400);

        $imagen_url = $this->uploadImage();
        if (!$imagen_url) $this->sendResponse('error', 'La imagen de portada es obligatoria', 400);

        $stmt = $this->pdo->prepare("INSERT INTO noticias (titulo, contenido, imagen_url, editor_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titulo, $contenido, $imagen_url, $user['id']]);

        $this->sendResponse('ok', 'Noticia guardada con éxito');
    }

    private function update()
    {
        $this->requireAuth(['editor', 'admin']);

        $id = $_POST['id'] ?? 0;
        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$id || !$titulo || !$contenido) $this->sendResponse('error', 'Datos incompletos', 400);

        // Get current for image
        $stmt = $this->pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        $imagen_url = $current['imagen_url'] ?? null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $new_url = $this->uploadImage();
            if ($new_url) {
                // Delete old if exists
                if ($imagen_url && file_exists(__DIR__ . '/../../../public/' . $imagen_url)) {
                    unlink(__DIR__ . '/../../../public/' . $imagen_url);
                }
                $imagen_url = $new_url;
            }
        }

        $stmt = $this->pdo->prepare("UPDATE noticias SET titulo=?, contenido=?, imagen_url=? WHERE id=?");
        $stmt->execute([$titulo, $contenido, $imagen_url, $id]);

        $this->sendResponse('ok', 'Noticia actualizada');
    }

    private function delete()
    {
        $this->requireAuth(['editor', 'admin']);
        $id = $_POST['id'] ?? 0;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        $stmt = $this->pdo->prepare("SELECT imagen_url FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            if ($noticia && !empty($noticia['imagen_url']) && file_exists(__DIR__ . '/../../../public/' . $noticia['imagen_url'])) {
                unlink(__DIR__ . '/../../../public/' . $noticia['imagen_url']);
            }
            $this->sendResponse('ok', 'Noticia eliminada');
        } else {
            $this->sendResponse('error', 'No encontrada', 404);
        }
    }

    private function uploadImage()
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $validator = new MultimediaValidator();

            // Adapt to MultimediaValidator expected format
            $files = $_FILES['imagen'];
            if (!is_array($files['name'])) {
                // Single file
                $res = $validator->guardarArchivo($files, 'imagen'); // validator detects if it's single or array structure?
                // MultimediaValidator::guardarArchivo expects a single file array usually.
                // Let's assume standard $_FILES['imagen'] structure for single file.
                if ($res['exitoso']) return $res['ruta'];
                else throw new Exception($res['mensaje']);
            }
        }
        return null;
    }

    private function requireAuth($roleOrRoles)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_data'])) $this->sendResponse('error', 'No autenticado', 401);

        $userRole = $_SESSION['user_data']['role'];
        $roles = is_array($roleOrRoles) ? $roleOrRoles : [$roleOrRoles];

        if (!in_array($userRole, $roles)) $this->sendResponse('error', 'No autorizado', 403);
        return $_SESSION['user_data'];
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }
}
