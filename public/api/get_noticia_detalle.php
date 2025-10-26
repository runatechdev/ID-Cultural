<?php
/**
 * API para obtener detalle completo de una noticia
 * Archivo: /public/api/get_noticia_detalle.php
 * VERSIÓN COMPATIBLE CON BD ACTUAL - CON FALLBACK
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

$noticia_id = $_GET['id'] ?? null;

if (empty($noticia_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de noticia requerido']);
    exit;
}

try {
    // Intentar primero con JOIN a users
    try {
        $stmt = $pdo->prepare("
            SELECT 
                n.id,
                n.titulo,
                n.contenido,
                n.imagen_url,
                n.fecha_creacion,
                u.nombre as editor_nombre,
                u.email as editor_email
            FROM noticias n
            LEFT JOIN users u ON n.editor_id = u.id
            WHERE n.id = :id
        ");
        
        $stmt->bindValue(':id', $noticia_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Si falla el JOIN, intentar sin el editor
        $stmt = $pdo->prepare("
            SELECT 
                id,
                titulo,
                contenido,
                imagen_url,
                fecha_creacion,
                NULL as editor_nombre,
                NULL as editor_email
            FROM noticias
            WHERE id = :id
        ");
        
        $stmt->bindValue(':id', $noticia_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$noticia) {
        http_response_code(404);
        echo json_encode(['error' => 'Noticia no encontrada']);
        exit;
    }

    echo json_encode($noticia);

} catch (PDOException $e) {
    error_log("Error en get_noticia_detalle.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al consultar la base de datos',
        'message' => $e->getMessage()
    ]);
}