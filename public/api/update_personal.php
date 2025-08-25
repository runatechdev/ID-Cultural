<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Seguridad: Solo el admin puede hacer esto
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso.']);
    exit;
}

// Recibir los datos del formulario
$id = $_POST['id'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = trim($_POST['role'] ?? '');
$password = $_POST['password'] ?? ''; // La nueva contraseña (opcional)

if (empty($id) || empty($nombre) || empty($email) || empty($role)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Los campos nombre, email y rol son obligatorios.']);
    exit;
}

try {
    // Construir la consulta dinámicamente
    $sql_parts = [
        "nombre = :nombre",
        "email = :email",
        "role = :role"
    ];
    $params = [
        ':id' => $id,
        ':nombre' => $nombre,
        ':email' => $email,
        ':role' => $role
    ];

    // Si se proporcionó una nueva contraseña, la añadimos a la consulta
    if (!empty($password)) {
        if (strlen($password) < 8) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'La nueva contraseña debe tener al menos 8 caracteres.']);
            exit;
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_parts[] = "password = :password";
        $params[':password'] = $hashed_password;
    }

    $sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['status' => 'ok', 'message' => 'Usuario actualizado con éxito.']);

} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está en uso por otra cuenta.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>
