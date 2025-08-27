<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Recibir datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$pais = trim($_POST['pais'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$municipio = trim($_POST['municipio'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$intereses = json_decode($_POST['intereses'] ?? '[]', true); // Decodificar el JSON de intereses

// Validaciones
if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Los campos con * son obligatorios.']);
    exit;
}
if ($password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
    exit;
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Usar una transacción para asegurar que todo se guarde correctamente
    $pdo->beginTransaction();

    // 1. Insertar el artista en la tabla 'artistas'
    $stmt = $pdo->prepare(
        "INSERT INTO artistas (nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')"
    );
    $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $genero, $pais, $provincia, $municipio, $email, $hashed_password]);

    // 2. Obtener el ID del artista recién creado
    $artista_id = $pdo->lastInsertId();

    // 3. Insertar los intereses en la tabla 'intereses_artista'
    if (!empty($intereses) && is_array($intereses)) {
        $stmt_interes = $pdo->prepare("INSERT INTO intereses_artista (artista_id, interes) VALUES (?, ?)");
        foreach ($intereses as $interes) {
            $stmt_interes->execute([$artista_id, $interes]);
        }
    }

    // Si todo fue bien, confirmar los cambios
    $pdo->commit();
    
    echo json_encode(['status' => 'ok', 'message' => '¡Registro exitoso! Ya puedes iniciar sesión.']);

} catch (PDOException $e) {
    // Si algo falla, revertir todo
    $pdo->rollBack();
    http_response_code(500);
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está registrado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
    }
}
?>
