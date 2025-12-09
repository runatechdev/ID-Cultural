<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../helpers/MultimediaValidator.php';

use PDO;
use Exception;
use PDOException;
use MultimediaValidator;

class ObraController
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
                case 'save':
                case 'create':
                    $this->save();
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'change_status': // from solicitudes.php
                case 'update_status':
                    $this->updateStatus();
                    break;
                case 'validate':
                    $this->validatePublication();
                    break;
                case 'public_gallery':
                    $this->getPublicGallery();
                    break;
                case 'public_detail':
                    $this->getPublicDetail();
                    break;
                case 'get_validator_list':
                    $this->getValidatorList();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getValidatorList()
    {
        $this->requireAuth(['admin', 'validador']);

        $estado = $_GET['estado'] ?? null;
        $categoria = $_GET['categoria'] ?? null;
        $municipio = $_GET['municipio'] ?? null;
        $artistaId = $_GET['artista_id'] ?? null; // Support filtering by artist

        $sql = "SELECT p.id, p.titulo, p.descripcion, p.categoria, p.estado, 
                p.fecha_envio_validacion, p.fecha_creacion,
                a.id AS usuario_id, CONCAT(a.nombre, ' ', a.apellido) AS artista_nombre,
                a.municipio, a.provincia, a.email AS artista_email, a.status,
                CASE WHEN a.status = 'validado' THEN 1 ELSE 0 END AS es_artista_validado
                FROM publicaciones p
                INNER JOIN artistas a ON p.usuario_id = a.id
                WHERE 1=1";

        $params = [];
        if ($estado && $estado !== 'all') { // Skip filter if 'all'
            if ($estado === 'pendiente') {
                $sql .= " AND p.estado IN (?, ?)";
                $params[] = 'pendiente';
                $params[] = 'pendiente_validacion';
            } else {
                $sql .= " AND p.estado = ?";
                $params[] = $estado;
            }
        }
        if ($categoria) {
            $sql .= " AND p.categoria = ?";
            $params[] = $categoria;
        }
        if ($municipio) {
            $sql .= " AND a.municipio = ?";
            $params[] = $municipio;
        }
        if ($artistaId) {
            $sql .= " AND p.usuario_id = ?";
            $params[] = $artistaId;
        }

        $sql .= " ORDER BY p.fecha_envio_validacion DESC, p.fecha_creacion DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cast boolean
        foreach ($res as &$r) $r['es_artista_validado'] = (bool)$r['es_artista_validado'];

        echo json_encode($res);
    }

    private function getPublicGallery()
    {
        $categoria = $_GET['categoria'] ?? null;
        $municipio = $_GET['municipio'] ?? null;
        $artista = $_GET['artista'] ?? null;
        $busqueda = $_GET['busqueda'] ?? null;

        $sql = "SELECT p.id, p.titulo, p.descripcion, p.categoria, p.estado, p.multimedia, p.fecha_creacion,
                a.id AS artista_id, CONCAT(a.nombre, ' ', a.apellido) AS artista_nombre,
                a.municipio, a.provincia, a.email AS artista_email, a.status AS artista_status
                FROM publicaciones p
                INNER JOIN artistas a ON p.usuario_id = a.id
                WHERE p.estado = 'validado' AND a.status = 'validado'";

        $params = [];
        if ($categoria) {
            $sql .= " AND p.categoria = ?";
            $params[] = $categoria;
        }
        if ($municipio) {
            $sql .= " AND a.municipio = ?";
            $params[] = $municipio;
        }
        if ($artista) {
            $sql .= " AND a.id = ?";
            $params[] = $artista;
        }
        if ($busqueda) {
            $sql .= " AND (p.titulo LIKE ? OR p.descripcion LIKE ? OR CONCAT(a.nombre, ' ', a.apellido) LIKE ?)";
            $b = "%$busqueda%";
            $params[] = $b;
            $params[] = $b;
            $params[] = $b;
        }

        $sql .= " ORDER BY p.fecha_creacion DESC LIMIT 100";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Multimedia processing
        foreach ($obras as &$obra) {
            $img = '/static/img/placeholder-obra.png';
            if (!empty($obra['multimedia'])) {
                $decoded = json_decode($obra['multimedia'], true);
                if (is_array($decoded) && !empty($decoded)) $img = $decoded[0];
                elseif (!is_array($decoded)) $img = $obra['multimedia'];
            }
            $obra['imagen_url'] = $img;
        }

        echo json_encode(['status' => 'success', 'total' => count($obras), 'obras' => $obras]);
    }

    private function getPublicDetail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        $stmt = $this->pdo->prepare("SELECT p.*, a.nombre, a.apellido, a.biografia, a.foto_perfil, a.email, a.telefono, a.instagram, a.facebook, a.twitter, a.sitio_web
                                     FROM publicaciones p
                                     INNER JOIN artistas a ON p.usuario_id = a.id
                                     WHERE p.id = ? AND p.estado = 'validado'");
        $stmt->execute([$id]);
        $obra = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$obra) $this->sendResponse('error', 'Obra no encontrada', 404);

        // Process multimedia
        $obra['multimedia_urls'] = [];
        if (!empty($obra['multimedia'])) {
            $decoded = json_decode($obra['multimedia'], true);
            $obra['multimedia_urls'] = is_array($decoded) ? $decoded : [$obra['multimedia']];
        }

        echo json_encode($obra);
    }

    private function validatePublication()
    {
        $admin = $this->requireAuth(['admin', 'validador']);
        $id = $_POST['id'] ?? null;
        $accion = $_POST['accion'] ?? null;
        $motivo = $_POST['motivo'] ?? '';

        if (!$id || !in_array($accion, ['validar', 'rechazar'])) {
            $this->sendResponse('error', 'Datos inválidos', 400);
        }
        if ($accion === 'rechazar' && empty($motivo)) {
            $this->sendResponse('error', 'Motivo requerido', 400);
        }

        require_once __DIR__ . '/../../helpers/NotificationHelper.php';
        \NotificationHelper::init($this->pdo);

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("SELECT p.usuario_id, p.titulo, p.status as obra_status, a.status as artista_status
                                          FROM publicaciones p
                                          INNER JOIN artistas a ON p.usuario_id = a.id
                                          WHERE p.id = ?");
            // Fix: p.status doesn't exist, it's p.estado
            $stmt = $this->pdo->prepare("SELECT p.usuario_id, p.titulo, p.estado as obra_status, a.status as artista_status
                                          FROM publicaciones p
                                          INNER JOIN artistas a ON p.usuario_id = a.id
                                          WHERE p.id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) throw new Exception("Obra no encontrada");

            if ($accion === 'validar') {
                $nuevo = 'validado';
                $this->pdo->prepare("UPDATE publicaciones SET estado=?, validador_id=?, fecha_validacion=NOW() WHERE id=?")
                    ->execute([$nuevo, $admin['id'], $id]);

                // Upgrade artist status if first work
                if ($data['artista_status'] !== 'validado') {
                    $this->pdo->prepare("UPDATE artistas SET status='validado' WHERE id=?")->execute([$data['usuario_id']]);
                }

                \NotificationHelper::notificarPublicacionValidada($data['usuario_id'], $data['titulo'], $id);
            } else {
                $nuevo = 'rechazado';
                $this->pdo->prepare("UPDATE publicaciones SET estado=?, validador_id=?, fecha_validacion=NOW() WHERE id=?")
                    ->execute([$nuevo, $admin['id'], $id]);

                \NotificationHelper::notificarPublicacionRechazada($data['usuario_id'], $data['titulo'], $motivo);
            }

            // Log
            $logMsg = "Publicación $id ($accion). " . ($motivo ? "Motivo: $motivo" : "");
            $this->pdo->prepare("INSERT INTO system_logs (user_id, user_name, action, details) VALUES (?, ?, 'VALIDACION_OBRA', ?)")
                ->execute([$admin['id'], $admin['nombre'], $logMsg]);

            $this->pdo->commit();
            $this->sendResponse('ok', $accion === 'validar' ? 'Obra validada' : 'Obra rechazada');
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function get()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = $_SESSION['user_data'] ?? null;
        if (!$user) $this->sendResponse('error', 'No autorizado', 401);

        $id = $_GET['id'] ?? 0;

        // Admin/Validator can see all or filter by state
        if (in_array($user['role'], ['admin', 'validador'])) {
            if ($id) {
                $stmt = $this->pdo->prepare("
                    SELECT p.*, CONCAT(a.nombre, ' ', a.apellido) as nombre_artista, a.email, a.municipio 
                    FROM publicaciones p 
                    JOIN artistas a ON p.usuario_id = a.id 
                    WHERE p.id = ?");
                $stmt->execute([$id]);
                $obra = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($obra) {
                    $this->formatObra($obra);
                    echo json_encode($obra);
                } else $this->sendResponse('error', 'No encontrada', 404);
            } else {
                $status = $_GET['estado'] ?? 'pendiente_validacion';
                $sql = "SELECT p.*, CONCAT(a.nombre, ' ', a.apellido) as nombre_artista 
                        FROM publicaciones p 
                        JOIN artistas a ON p.usuario_id = a.id ";

                $params = [];
                if ($status !== 'all') {
                    $sql .= "WHERE p.estado = ?";
                    $params[] = $status;
                }
                $sql .= " ORDER BY p.fecha_envio_validacion ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            return;
        }

        // Artist logic (only their own)
        if ($user['role'] !== 'artista') $this->sendResponse('error', 'Rol no válido', 403);

        if ($id) {
            $stmt = $this->pdo->prepare("SELECT id, titulo, descripcion, categoria, campos_extra, multimedia, fecha_creacion, estado FROM publicaciones WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $user['id']]);
            $obra = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($obra) {
                $this->formatObra($obra);
                echo json_encode($obra);
            } else {
                $this->sendResponse('error', 'Obra no encontrada', 404);
            }
        } else {
            $stmt = $this->pdo->prepare("SELECT id, titulo, fecha_creacion, estado, multimedia, fecha_envio_validacion FROM publicaciones WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
            $stmt->execute([$user['id']]);
            $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($obras);
        }
    }

    private function save()
    {
        $user = $this->requireAuth('artista');

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $estado = trim($_POST['estado'] ?? 'borrador');

        if (empty($titulo) || empty($descripcion) || empty($categoria)) {
            $this->sendResponse('error', 'Título, descripción y categoría son obligatorios', 400);
        }

        // Multimedia
        $multimedia_paths = $this->processUploads();
        $multimedia_json = !empty($multimedia_paths) ? json_encode($multimedia_paths) : null;

        // Extra fields
        $campos_extra = [];
        $ignored = ['id', 'titulo', 'descripcion', 'categoria', 'estado', 'action', 'multimedia', 'imagenes_a_borrar'];
        foreach ($_POST as $k => $v) {
            if (!in_array($k, $ignored)) $campos_extra[$k] = trim($v);
        }

        if ($estado === 'pendiente') $estado = 'pendiente_validacion';
        $fecha_envio = ($estado === 'pendiente_validacion') ? date('Y-m-d H:i:s') : null;

        $stmt = $this->pdo->prepare("INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, estado, fecha_envio_validacion, multimedia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user['id'], $titulo, $descripcion, $categoria, json_encode($campos_extra), $estado, $fecha_envio, $multimedia_json]);

        $this->sendResponse('ok', 'Obra guardada exitosamente');
    }

    private function update()
    {
        $user = $this->requireAuth('artista');
        $id = $_POST['id'] ?? 0;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        // Get current for existing images
        $stmt = $this->pdo->prepare("SELECT multimedia FROM publicaciones WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user['id']]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$current) $this->sendResponse('error', 'Obra no encontrada', 404);

        $existing = [];
        if (!empty($current['multimedia'])) {
            $dec = json_decode($current['multimedia'], true);
            $existing = is_array($dec) ? $dec : [$current['multimedia']];
        }

        // Remove deleted
        $to_delete = $_POST['imagenes_a_borrar'] ?? [];
        if (is_string($to_delete)) $to_delete = explode(',', $to_delete);

        $final = [];
        foreach ($existing as $img) {
            if (!in_array($img, $to_delete)) $final[] = $img;
        }

        // Add new
        $new_paths = $this->processUploads();
        $final = array_merge($final, $new_paths);

        // Update fields
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $categoria = trim($_POST['categoria']);

        $campos_extra = [];
        $ignored = ['id', 'titulo', 'descripcion', 'categoria', 'estado', 'action', 'multimedia', 'imagenes_a_borrar'];
        foreach ($_POST as $k => $v) {
            if (!in_array($k, $ignored)) $campos_extra[$k] = trim($v);
        }

        $stmt = $this->pdo->prepare("UPDATE publicaciones SET titulo=?, descripcion=?, categoria=?, campos_extra=?, multimedia=?, estado='pendiente', fecha_envio_validacion=NOW() WHERE id=? AND usuario_id=?");
        $stmt->execute([$titulo, $descripcion, $categoria, json_encode($campos_extra), json_encode(array_values($final)), $id, $user['id']]);

        $this->sendResponse('ok', 'Obra actualizada correctamente');
    }

    private function delete()
    {
        $user = $this->requireAuth('artista');

        // Handle JSON input (from eliminar_obra.php legacy) or POST
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
        }

        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        $stmt = $this->pdo->prepare("DELETE FROM publicaciones WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user['id']]);

        if ($stmt->rowCount() > 0) {
            // Unify response format. eliminar_obra.php returned {success: true}, we usually return {status: ok}.
            // I will return {status: ok, success: true} to be safe.
            echo json_encode(['status' => 'ok', 'success' => true, 'message' => 'Obra eliminada']);
            exit;
        } else {
            $this->sendResponse('error', 'No encontrada o sin permiso', 404);
        }
    }

    private function updateStatus()
    {
        $admin = $this->requireAuth(['admin', 'validador']);
        $id = $_POST['id'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $motivo = $_POST['motivo'] ?? '';

        if (!$id || !in_array($estado, ['validado', 'rechazado'])) {
            $this->sendResponse('error', 'Datos inválidos', 400);
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE publicaciones SET estado=?, validador_id=?, fecha_validacion=NOW() WHERE id=?");
            $stmt->execute([$estado, $admin['id'], $id]);

            if ($stmt->rowCount() > 0) {
                $logMsg = "Publicación $estado. ID: $id.";
                if ($motivo) $logMsg .= " Motivo: $motivo";
                $this->pdo->prepare("INSERT INTO system_logs (user_id, user_name, action, details) VALUES (?, ?, 'UPDATE_OBRA', ?)")
                    ->execute([$admin['id'], $admin['nombre'], $logMsg]);

                $this->pdo->commit();
                $this->sendResponse('ok', 'Estado actualizado');
            } else {
                $this->pdo->rollBack();
                $this->sendResponse('error', 'No se pudo actualizar', 400);
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function formatObra(&$obra)
    {
        $obra['campos_extra'] = $obra['campos_extra'] ? json_decode($obra['campos_extra'], true) : [];
        if ($obra['multimedia']) {
            $dec = json_decode($obra['multimedia'], true);
            $obra['multimedia'] = is_array($dec) ? $dec : [$obra['multimedia']];
        }
    }

    private function requireAuth($roleOrRoles)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_data'])) $this->sendResponse('error', 'No autenticado', 401);

        $userRole = $_SESSION['user_data']['role'];
        $roles = is_array($roleOrRoles) ? $roleOrRoles : [$roleOrRoles];

        if (!in_array($userRole, $roles)) {
            $this->sendResponse('error', 'No autorizado', 403);
        }
        return $_SESSION['user_data'];
    }

    private function processUploads()
    {
        $paths = [];
        if (isset($_FILES['multimedia']) && !empty($_FILES['multimedia']['tmp_name'])) {
            $validator = new MultimediaValidator();
            $files = $_FILES['multimedia'];
            $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 1;

            for ($i = 0; $i < $count; $i++) {
                $tmp = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
                if (empty($tmp)) continue;

                $fileData = [
                    'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                    'type' => is_array($files['type']) ? $files['type'][$i] : $files['type'],
                    'tmp_name' => $tmp,
                    'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                    'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
                ];

                $res = $validator->guardarArchivo($fileData, 'imagen');
                if ($res['exitoso']) $paths[] = $res['ruta'];
            }
        }
        return $paths;
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message, 'success' => ($status === 'ok')]);
        exit;
    }
}
