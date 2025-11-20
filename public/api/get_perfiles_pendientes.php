<?php
/**
 * get_perfiles_pendientes.php
 * Endpoint para obtener perfiles con cambios pendientes de validación
 * Solo accesible por validadores y admin
 */

ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../backend/config/connection.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificación de seguridad
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['validador', 'admin'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'No tienes permisos para ver perfiles pendientes'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Obtener perfiles con cambios pendientes
    $stmt = $pdo->prepare("
        SELECT 
            pcp.id as cambio_id,
            pcp.artista_id,
            pcp.biografia,
            pcp.especialidades,
            pcp.instagram,
            pcp.facebook,
            pcp.twitter,
            pcp.sitio_web,
            pcp.foto_perfil,
            pcp.estado,
            pcp.fecha_solicitud,
            a.nombre,
            a.apellido,
            a.email,
            a.foto_perfil as foto_perfil_actual,
            a.biografia as biografia_actual,
            a.especialidades as especialidades_actual,
            a.municipio,
            a.status_perfil
        FROM perfil_cambios_pendientes pcp
        INNER JOIN artistas a ON pcp.artista_id = a.id
        WHERE pcp.estado = 'pendiente'
        ORDER BY pcp.fecha_solicitud ASC
    ");
    
    $stmt->execute();
    $cambios_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear datos para el frontend
    $resultado = array_map(function($cambio) {
        return [
            'id' => $cambio['cambio_id'],
            'artista_id' => $cambio['artista_id'],
            'tipo' => 'perfil', // Para diferenciar de obras
            'nombre_artista' => trim($cambio['nombre'] . ' ' . $cambio['apellido']),
            'email' => $cambio['email'],
            'municipio' => $cambio['municipio'],
            'fecha_solicitud' => $cambio['fecha_solicitud'],
            
            // Cambios propuestos
            'cambios' => [
                'biografia' => $cambio['biografia'],
                'especialidades' => $cambio['especialidades'],
                'instagram' => $cambio['instagram'],
                'facebook' => $cambio['facebook'],
                'twitter' => $cambio['twitter'],
                'sitio_web' => $cambio['sitio_web'],
                'foto_perfil' => $cambio['foto_perfil']
            ],
            
            // Valores actuales (para comparación)
            'valores_actuales' => [
                'biografia' => $cambio['biografia_actual'],
                'especialidades' => $cambio['especialidades_actual'],
                'foto_perfil' => $cambio['foto_perfil_actual']
            ],
            
            // Status
            'status_perfil' => $cambio['status_perfil'],
            'estado' => $cambio['estado']
        ];
    }, $cambios_pendientes);
    
    http_response_code(200);
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log("Error al obtener perfiles pendientes: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al cargar perfiles pendientes',
        'debug' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>