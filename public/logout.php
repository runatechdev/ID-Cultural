<?php
// public/logout.php
session_start();
<?php
require_once __DIR__ . '/../../backend/helpers/security_headers.php';
require_once __DIR__ . '/../../backend/helpers/cors.php';


header('Content-Type: application/json');

// resto del código


require_once __DIR__ . '/../backend/helpers/security_logger.php';
require_once __DIR__ . '/../backend/config/connection.php';

global $pdo;

if (isset($_SESSION['user_data']['id'])) {
    log_security_event($pdo, 'LOGOUT', 'INFO', [
        'user_id' => $_SESSION['user_data']['id']
    ]);
}

// Limpiar sesión
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirigir
header("Location: /");
exit;
?>
