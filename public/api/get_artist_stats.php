<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    // Contar artistas en cada estado
    $stmt_pendientes = $pdo->prepare("SELECT COUNT(*) FROM artistas WHERE status = 'pendiente'");
    $stmt_pendientes->execute();
    $pendientes = $stmt_pendientes->fetchColumn();

    $stmt_validados = $pdo->prepare("SELECT COUNT(*) FROM artistas WHERE status = 'validado'");
    $stmt_validados->execute();
    $validados = $stmt_validados->fetchColumn();

    $stmt_rechazados = $pdo->prepare("SELECT COUNT(*) FROM artistas WHERE status = 'rechazado'");
    $stmt_rechazados->execute();
    $rechazados = $stmt_rechazados->fetchColumn();

    // Devolver los conteos en un objeto JSON
    echo json_encode([
        'pendientes' => $pendientes,
        'validados' => $validados,
        'rechazados' => $rechazados
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}
?>
