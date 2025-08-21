<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Recibir los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$rol = trim($_POST['rol'] ?? '');
$password = $_POST['password'] ?? '';

// Validación básica de los datos
if (empty($nombre) || empty($email) || empty($rol) || empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'El formato del correo electrónico no es válido.']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 8 caracteres.']);
    exit;
}


// Hashear la contraseña por seguridad antes de guardarla
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (nombre, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hashed_password, $rol]);

    echo json_encode(['status' => 'ok', 'message' => 'Usuario agregado con éxito.']);

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    // Verificar si es un error de correo duplicado (código de error SQL 23000)
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está registrado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>
