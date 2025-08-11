<?php
// Inicia el búfer de salida para capturar cualquier texto no deseado.
ob_start();

// Incluimos la conexión a la base de datos desde la ubicación correcta.
// Ruta corregida según tu indicación.
require_once __DIR__ . '/../../backend/config/connection.php'; 

// Limpiamos cualquier salida que se haya generado hasta ahora.
ob_clean();

// Establece el tipo de contenido que se devolverá: JSON
header('Content-Type: application/json');

try {
    // La consulta SQL ahora coincide con tu tabla 'users'
    $stmt = $pdo->prepare("SELECT id, nombre, email, role FROM users WHERE role IN ('admin', 'editor', 'validador')");
    
    // Ejecutamos la consulta
    $stmt->execute();
    
    // Obtenemos todos los resultados
    $personal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolvemos los resultados en formato JSON
    echo json_encode($personal);

} catch (PDOException $e) {
    // En caso de un error, limpiamos el búfer de nuevo para asegurar una respuesta JSON limpia.
    ob_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}

// Detenemos la ejecución para asegurar que no se envíe nada más.
exit;
?>
