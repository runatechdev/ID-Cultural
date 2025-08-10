<?php
// public/api/login.php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../../backend/controllers/verificar_usuario.php';

$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');

$result = checkUserCredentials($email, $password);

if ($result['status'] === 'ok') {
    // 1. Regenerar el ID de sesión para prevenir ataques de Session Fixation
    session_regenerate_id(true);

    // 2. Guardar los datos del usuario en una sola variable de sesión, como un array.
    //    Esto mantiene la sesión más limpia y es consistente con nuestro navbar.
    $_SESSION['user_data'] = [
        'id' => $result['user_data']['id'],
        'role' => $result['user_data']['role']
        // Aquí puedes añadir más datos si los necesitas, como el nombre o email.
        // 'email' => $email
    ];
}

echo json_encode($result);
?>