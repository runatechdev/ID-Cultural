<?php
// public/api/login.php
// Este es el único archivo PHP que el login.js debe llamar directamente.

session_start(); // Inicia la sesión al principio, antes de cualquier salida.

header('Content-Type: application/json');

// Incluir el archivo de lógica que contiene la función `checkUserCredentials`.
// La ruta es relativa: public/api/ -> public/ -> ID-CULTURAL/ -> backend/controllers/
require_once __DIR__ . '/../../backend/controllers/verificar_usuario.php';

// Obtener los datos del POST
$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');

// Llamar a la función de verificación de credenciales
$result = checkUserCredentials($email, $password);

// Si el login fue exitoso, guarda los datos del usuario en la sesión
if ($result['status'] === 'ok') {
    $_SESSION['user_id'] = $result['user_data']['id']; // Guarda solo el ID
    $_SESSION['user_role'] = $result['user_data']['role']; // Guarda el rol
    // Puedes guardar más datos si los `user_data` devueltos por la función son más completos.
}

// Devuelve la respuesta JSON al frontend (login.js)
echo json_encode($result);
?>