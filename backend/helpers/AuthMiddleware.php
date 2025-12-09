<?php



class AuthMiddleware
{

    /**
     * Verifica si el usuario está logueado.
     * Retorna los datos del usuario o termina la ejecución con 401.
     */
    public static function requireLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_data'])) {
            self::sendJsonError('No has iniciado sesión.', 401);
        }

        return $_SESSION['user_data'];
    }

    /**
     * Verifica si el usuario tiene uno de los roles permitidos.
     * @param array $allowedRoles Lista de roles ('admin', 'validador', 'artista', 'editor')
     */
    public static function requireRole(array $allowedRoles)
    {
        $user = self::requireLogin();

        if (!in_array($user['role'], $allowedRoles)) {
            self::sendJsonError('No tienes permisos para realizar esta acción.', 403);
        }
    }

    /**
     * Helper para enviar errores JSON y cortar ejecución
     */
    private static function sendJsonError($message, $code)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit;
    }
}
