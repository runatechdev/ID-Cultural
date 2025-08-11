<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/conexion.php';

// Recibir los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$rol = $_POST['rol'] ?? '';
$password = $_POST['password'] ?? '';

// Validación básica
if (empty($nombre) || empty($email) || empty($rol) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

// Hashear la contraseña por seguridad
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hashed_password, $rol]);

    echo json_encode(['status' => 'ok', 'message' => 'Usuario agregado con éxito.']);

} catch (PDOException $e) {
    http_response_code(500);
    // Verificar si es un error de duplicado (código 23000)
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está registrado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>
