<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';

use PDO;
use Exception;
use PDOException;

class AdminController
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
                case 'get_personal':
                    $this->getPersonal();
                    break;
                case 'add_personal':
                    $this->addPersonal();
                    break;
                case 'update_personal':
                    $this->updatePersonal();
                    break;
                case 'delete_personal':
                    $this->deletePersonal();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getPersonal()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? 0;

        if ($id) {
            $stmt = $this->pdo->prepare("SELECT id, nombre, email, role FROM users WHERE id = ? AND role IN ('admin', 'editor', 'validador')");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) echo json_encode($user);
            else $this->sendResponse('error', 'Usuario no encontrado', 404);
        } else {
            $stmt = $this->pdo->prepare("SELECT id, nombre, email, role FROM users WHERE role IN ('admin', 'editor', 'validador') ORDER BY id DESC");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    private function addPersonal()
    {
        $this->requireAdmin();
        $data = $_POST;

        $nombre = trim($data['nombre'] ?? '');
        $email = trim($data['email'] ?? '');
        $role = trim($data['rol'] ?? ''); // frontend sends 'rol'
        $password = $data['password'] ?? '';

        if (!$nombre || !$email || !$role || !$password) $this->sendResponse('error', 'Datos incompletos', 400);
        if (strlen($password) < 8) $this->sendResponse('error', 'Contraseña min 8 caracteres', 400);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->sendResponse('error', 'Email inválido', 400);
        if (!in_array($role, ['admin', 'editor', 'validador'])) $this->sendResponse('error', 'Rol inválido', 400);

        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (nombre, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $hash, $role]);
            $this->sendResponse('ok', 'Usuario agregado');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) $this->sendResponse('error', 'Email ya registrado', 400);
            throw $e;
        }
    }

    private function updatePersonal()
    {
        $this->requireAdmin();
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$nombre || !$email || !$role) $this->sendResponse('error', 'Datos obligatorios', 400);

        $sql = "UPDATE users SET nombre=?, email=?, role=?";
        $params = [$nombre, $email, $role];

        if ($password) {
            if (strlen($password) < 8) $this->sendResponse('error', 'Contraseña min 8 caracteres', 400);
            $sql .= ", password=?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        $sql .= " WHERE id=?";
        $params[] = $id;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $this->sendResponse('ok', 'Usuario actualizado');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) $this->sendResponse('error', 'Email ya registrado', 400);
            throw $e;
        }
    }

    private function deletePersonal()
    {
        $me = $this->requireAdmin();
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);
        if ($id == $me['id']) $this->sendResponse('error', 'No puedes eliminarte a ti mismo', 400);

        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) $this->sendResponse('ok', 'Usuario eliminado');
        else $this->sendResponse('error', 'No encontrado', 404);
    }

    private function requireAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'admin') {
            $this->sendResponse('error', 'No autorizado', 403);
        }
        return $_SESSION['user_data'];
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }
}
