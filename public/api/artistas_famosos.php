<?php
/**
 * API para obtener artistas famosos de Santiago del Estero
 * Archivo: /public/api/artistas_famosos.php
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

try {
    // Obtener acción desde GET
    $action = $_GET['action'] ?? 'get';
    
    switch ($action) {
        case 'get':
            // Obtener todos los artistas famosos activos
            $categoria_filter = $_GET['categoria'] ?? null;
            $destacado_filter = $_GET['destacado'] ?? null;
            
            $sql = "SELECT 
                id, 
                nombre_completo, 
                nombre_artistico, 
                fecha_nacimiento, 
                fecha_fallecimiento, 
                lugar_nacimiento,
                municipio,
                categoria,
                subcategoria,
                biografia,
                logros_premios,
                obras_destacadas,
                foto_perfil,
                foto_galeria,
                videos_youtube,
                sitio_web,
                instagram,
                facebook,
                twitter,
                wikipedia_url,
                activo,
                destacado,
                orden_visualizacion,
                visitas
            FROM artistas_famosos 
            WHERE activo = TRUE";
            
            $params = [];
            
            // Aplicar filtro de categoría si existe
            if ($categoria_filter && $categoria_filter !== 'todos') {
                $sql .= " AND categoria = ?";
                $params[] = $categoria_filter;
            }
            
            // Aplicar filtro de destacados si existe
            if ($destacado_filter === '1') {
                $sql .= " AND destacado = TRUE";
            }
            
            // Ordenar por orden de visualización
            $sql .= " ORDER BY orden_visualizacion ASC, fecha_nacimiento DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar datos
            foreach ($artistas as &$artista) {
                // Calcular si está vivo
                $artista['esta_vivo'] = $artista['fecha_fallecimiento'] === null || $artista['fecha_fallecimiento'] === '';
                
                // Parsear JSON si existe
                if (!empty($artista['foto_galeria'])) {
                    $artista['foto_galeria'] = json_decode($artista['foto_galeria'], true);
                } else {
                    $artista['foto_galeria'] = [];
                }
                
                if (!empty($artista['videos_youtube'])) {
                    $artista['videos_youtube'] = json_decode($artista['videos_youtube'], true);
                } else {
                    $artista['videos_youtube'] = [];
                }
            }
            
            echo json_encode([
                'status' => 'success',
                'total' => count($artistas),
                'artistas' => $artistas
            ]);
            break;
            
        case 'get_categorias':
            // Obtener lista de categorías disponibles
            $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM artistas_famosos WHERE activo = TRUE ORDER BY categoria");
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo json_encode([
                'status' => 'success',
                'categorias' => $categorias
            ]);
            break;
            
        case 'get_id':
            // Obtener un artista específico por ID
            $id = intval($_GET['id'] ?? 0);
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
                exit;
            }
            
            $stmt = $pdo->prepare("
                SELECT * FROM artistas_famosos 
                WHERE id = ? AND activo = TRUE
            ");
            $stmt->execute([$id]);
            $artista = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($artista) {
                // Procesar datos
                $artista['esta_vivo'] = $artista['fecha_fallecimiento'] === null || $artista['fecha_fallecimiento'] === '';
                
                if (!empty($artista['foto_galeria'])) {
                    $artista['foto_galeria'] = json_decode($artista['foto_galeria'], true);
                }
                
                if (!empty($artista['videos_youtube'])) {
                    $artista['videos_youtube'] = json_decode($artista['videos_youtube'], true);
                }
                
                // Incrementar contador de visitas
                $stmt_update = $pdo->prepare("UPDATE artistas_famosos SET visitas = visitas + 1 WHERE id = ?");
                $stmt_update->execute([$id]);
                
                echo json_encode([
                    'status' => 'success',
                    'artista' => $artista
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Artista no encontrado']);
            }
            break;
            
        case 'buscar':
            // Búsqueda de artistas por nombre o biografía
            $busqueda = trim($_GET['q'] ?? '');
            
            if (strlen($busqueda) < 2) {
                echo json_encode([
                    'status' => 'success',
                    'total' => 0,
                    'artistas' => []
                ]);
                exit;
            }
            
            $busqueda = '%' . $busqueda . '%';
            
            $stmt = $pdo->prepare("
                SELECT 
                    id, 
                    nombre_completo, 
                    nombre_artistico, 
                    categoria,
                    foto_perfil,
                    biografia
                FROM artistas_famosos 
                WHERE activo = TRUE 
                AND (nombre_completo LIKE ? OR nombre_artistico LIKE ? OR biografia LIKE ?)
                ORDER BY orden_visualizacion ASC
                LIMIT 20
            ");
            $stmt->execute([$busqueda, $busqueda, $busqueda]);
            $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'status' => 'success',
                'total' => count($artistas),
                'artistas' => $artistas
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }
    
} catch (PDOException $e) {
    error_log("Error en artistas_famosos.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la base de datos'
    ]);
}
?>
