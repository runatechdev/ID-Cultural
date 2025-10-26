<?php
/**
 * API para obtener publicaciones según estado
 * Archivo: /public/api/get_publicaciones.php
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Obtener el filtro de estado desde la URL (ej: ?estado=pendiente)
$estado_filter = $_GET['estado'] ?? null;
$categoria_filter = $_GET['categoria'] ?? null;
$municipio_filter = $_GET['municipio'] ?? null;

try {
    // Consulta mejorada que une 'publicaciones' con 'usuarios' (antes artistas)
    $sql = "
        SELECT 
            p.id,
            p.titulo,
            p.descripcion,
            p.categoria,
            p.estado,
            p.fecha_envio_validacion,
            p.fecha_creacion,
            u.id AS usuario_id,
            CONCAT(u.nombre, ' ', u.apellido) AS artista_nombre,
            u.municipio,
            u.provincia,
            u.email AS artista_email,
            u.role,
            CASE 
                WHEN u.role = 'artista_validado' THEN TRUE 
                ELSE FALSE 
            END AS es_artista_validado
        FROM publicaciones p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE 1=1
    ";
    
    $params = [];

    // Aplicar filtros
    if ($estado_filter) {
        $sql .= " AND p.estado = ?";
        $params[] = $estado_filter;
    }

    if ($categoria_filter) {
        $sql .= " AND p.categoria = ?";
        $params[] = $categoria_filter;
    }

    if ($municipio_filter) {
        $sql .= " AND u.municipio = ?";
        $params[] = $municipio_filter;
    }

    $sql .= " ORDER BY p.fecha_envio_validacion DESC, p.fecha_creacion DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convertir el campo booleano a true/false para JSON
    foreach ($publicaciones as &$pub) {
        $pub['es_artista_validado'] = (bool)$pub['es_artista_validado'];
    }
    
    echo json_encode($publicaciones);

} catch (PDOException $e) {
    error_log("Error en get_publicaciones.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos.']);
}