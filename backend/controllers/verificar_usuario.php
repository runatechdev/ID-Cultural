<?php
session_start();
require_once __DIR__ . '/../config/connection.php';

header('Content-Type: application/json');

// Obtener email y password
$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');

if (!$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos de acceso"
    ]);
    exit;
}

$user = null;
$origen = null;

// Buscar primero en users
$stmtUser = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
$stmtUser->execute([$email]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $origen = 'users';
}

// Si no se encontró en users, buscar en artistas
if (!$user) {
    $stmtArtista = $pdo->prepare("SELECT id, email, password FROM artistas WHERE email = ?");
    $stmtArtista->execute([$email]);
    $artista = $stmtArtista->fetch(PDO::FETCH_ASSOC);

    if ($artista) {
        $artista['role'] = 'artista';
        $user = $artista;
        $origen = 'artistas';
    }
}

// Verificación de contraseña
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;

    // Redirigir según rol
    switch ($user['role']) {
        case 'admin':
            $redirect = '/ID-Cultural/src/views/pages/admin/dashboard-adm.php';
            break;
        case 'editor':
            $redirect = '/ID-Cultural/src/views/pages/editor/panel_editor.php';
            break;
        case 'validador':
            $redirect = '/ID-Cultural/src/views/pages/validador/panel_validador.php';
            break;
        case 'artista':
            $redirect = '/ID-Cultural/src/views/pages/user/dashboard-user.php';
            break;
        default:
            $redirect = '/ID-Cultural/src/views/pages/public/home.php';
            break;
    }

    echo json_encode([
        "status" => "ok",
        "role" => $user['role'],
        "redirect" => $redirect,
        "source" => $origen
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Usuario o contraseña incorrectos"
    ]);
}
?>
