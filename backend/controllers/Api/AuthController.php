<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';
// AuthMiddleware is global now
// use Backend\Helpers\AuthMiddleware; 

use PDO;
use Exception;
use PDOException; // Importar PDOException globalmente

class AuthController
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
                case 'login':
                    $this->login();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'change_password':
                    $this->changePassword(); // Authenticated
                    break;
                case 'request_reset':
                    $this->requestPasswordReset();
                    break;
                case 'reset_token':
                    $this->resetPasswordWithToken();
                    break;
                case 'check_auth':
                    // Helper to check if logged in and return user data
                    $this->checkAuthStatus();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function checkAuthStatus()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_data'])) {
            echo json_encode([
                'status' => 'ok',
                'authenticated' => true,
                'user' => $_SESSION['user_data']
            ]);
        } else {
            echo json_encode(['status' => 'ok', 'authenticated' => false]);
        }
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Soporte para form-data (post normal)
        if (!$data) {
            $data = $_POST;
        }

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            $this->sendResponse('error', 'Faltan datos de acceso', 400);
        }

        // Reutilizamos la lógica del legacy verificador por ahora, o la reescribimos aquí.
        // INTEGRACIÓN DIRECTA PARA ELIMINAR DEPENDENCIA EXTRENA
        $user = $this->checkCredentials($email, $password);

        if ($user) {
            // Iniciar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_data'] = $user;

            // Determinar redirect
            $redirect = $this->getRedirectPath($user['role']);

            echo json_encode([
                'status' => 'ok',
                'authenticated' => true,
                'redirect' => $redirect,
                'user' => $user
            ]);
        } else {
            $this->sendResponse('error', 'Credenciales incorrectas', 401);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        echo json_encode(['status' => 'ok', 'message' => 'Sesión cerrada']);
    }

    public function requestPasswordReset()
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        if (!$email) $this->sendResponse('error', 'Email requerido', 400);

        // Check Artists first (as per legacy)
        $stmt = $this->pdo->prepare("SELECT id, nombre, apellido, email FROM artistas WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Check Users? Legacy only checked Artists. Sticking to Artists for now.
            $this->sendResponse('ok', 'Si el email existe, recibirás un enlace.');
        }

        $token = bin2hex(random_bytes(32));
        $exp = date('Y-m-d H:i:s', time() + 3600);

        $stmt = $this->pdo->prepare("INSERT INTO password_reset_tokens (usuario_id, token, fecha_expiracion) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=VALUES(token), fecha_expiracion=VALUES(fecha_expiracion), usado=0");
        $stmt->execute([$user['id'], $token, $exp]);

        require_once __DIR__ . '/../../helpers/EmailHelper.php';
        $mailer = new \EmailHelper();
        $sent = $mailer->enviarRecuperacionClave($user['email'], $user['nombre'] . ' ' . $user['apellido'], $token);

        if ($sent) $this->sendResponse('ok', 'Si el email existe, recibirás un enlace.');
        else $this->sendResponse('error', 'Error enviando email', 500);
    }

    public function resetPasswordWithToken()
    {
        $token = trim($_POST['token'] ?? '');
        $newPass = $_POST['nueva_clave'] ?? '';

        if (!$token || strlen($newPass) < 6) $this->sendResponse('error', 'Contraseña inválida (min 6 chars)', 400);

        $stmt = $this->pdo->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND usado = 0 AND fecha_expiracion > NOW()");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) $this->sendResponse('error', 'Token inválido o expirado', 400);

        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $this->pdo->prepare("UPDATE artistas SET password = ? WHERE id = ?")->execute([$hash, $row['usuario_id']]);
        $this->pdo->prepare("UPDATE password_reset_tokens SET usado = 1 WHERE token = ?")->execute([$token]);

        $this->sendResponse('ok', 'Contraseña actualizada');
    }

    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = $_SESSION['user_data'] ?? null;
        if (!$user) $this->sendResponse('error', 'No autorizado', 401);

        $old = $_POST['clave_actual'] ?? '';
        $new = $_POST['nueva_clave'] ?? '';
        if (!$old || !$new) $this->sendResponse('error', 'Faltan datos', 400);

        // Identify table
        $table = ($user['role'] === 'artista') ? 'artistas' : 'users';

        $stmt = $this->pdo->prepare("SELECT password FROM $table WHERE id = ?");
        $stmt->execute([$user['id']]);
        $current = $stmt->fetchColumn();

        if (!$current || !password_verify($old, $current)) {
            $this->sendResponse('error', 'Contraseña actual incorrecta', 400);
        }

        $hash = password_hash($new, PASSWORD_DEFAULT);
        $this->pdo->prepare("UPDATE $table SET password = ? WHERE id = ?")->execute([$hash, $user['id']]);

        $this->sendResponse('ok', 'Contraseña cambiada exitosamente');
    }


    // --- Private Helpers ---

    private function checkCredentials($email, $password)
    {
        $user = null;

        // 1. Buscar en Users (Sin apellido porque no existe en esa tabla)
        $stmt = $this->pdo->prepare("SELECT id, email, password, role, nombre FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data['apellido'] = ''; // Pad apellido for consistency
            $user = $data;
        } else {
            // 2. Buscar en Artistas
            $stmt = $this->pdo->prepare("SELECT id, email, password, nombre, apellido FROM artistas WHERE email = ?");
            $stmt->execute([$email]);
            $artistData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($artistData) {
                $artistData['role'] = 'artista';
                $user = $artistData;
            }
        }

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    private function getRedirectPath($role)
    {
        $paths = [
            'admin' => '/src/views/pages/admin/dashboard-adm.php',
            'editor' => '/src/views/pages/editor/panel_editor.php',
            'validador' => '/src/views/pages/validador/panel_validador.php',
            'artista' => '/src/views/pages/artista/dashboard-artista.php'
        ];
        return $paths[$role] ?? '/src/views/pages/public/home.php';
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }
}
