<?php
/**
 * API para obtener y gestionar artistas famosos de Santiago del Estero
 * Archivo: /public/api/artistas_famosos.php
 * Métodos: GET, POST, PUT, DELETE
 */

// Suprimir warnings/notices que rompan JSON
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../backend/config/connection.php';
require_once __DIR__ . '/../../config.php';

// Ejecutar migración automática si es necesaria
$migracion_file = __DIR__ . '/../../database/migrations/add_imagen_column.php';
if (file_exists($migracion_file)) {
    @include_once $migracion_file;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // === SECCIÓN PÚBLICA (GET) ===
    if ($method === 'GET') {
        // Obtener acción desde GET
        $action = $_GET['action'] ?? 'get';
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            obtenerArtistaPorId($pdo, $id);
        } else {
            switch ($action) {
                case 'get':
                    obtenerTodosArtistas($pdo);
                    break;
                case 'get_categorias':
                    obtenerCategorias($pdo);
                    break;
                case 'buscar':
                    buscarArtistas($pdo);
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
            }
        }
        exit;
    }

    // === SECCIÓN PRIVADA (POST, PUT, DELETE) ===
    if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit();
    }

    $id = $_GET['id'] ?? null;

    if ($method === 'POST') {
        // POST para crear Y actualizar (si viene ID)
        if ($id) {
            actualizarArtista($pdo, $id);
        } else {
            crearArtista($pdo);
        }
    } elseif ($method === 'PUT') {
        // PUT también para actualizar (por compatibilidad)
        if (!$id) {
            throw new Exception('ID requerido para actualizar');
        }
        actualizarArtista($pdo, $id);
    } elseif ($method === 'DELETE') {
        if (!$id) {
            throw new Exception('ID requerido para eliminar');
        }
        eliminarArtista($pdo, $id);
    } else {
        throw new Exception('Método HTTP no soportado');
    }
    
} catch (PDOException $e) {
    error_log("Error en artistas_famosos.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// ===== FUNCIONES AUXILIARES =====
function normalizarCategoria($categoria) {
    $map = [
        'Música' => 'musica',
        'Musica' => 'musica',
        'Literatura' => 'literatura',
        'Artes Plásticas' => 'artes_plasticas',
        'Artes Plasticas' => 'artes_plasticas',
        'Danza' => 'danza',
        'Teatro' => 'teatro'
    ];
    return $map[$categoria] ?? $categoria;
}

function denormalizarCategoria($categoria) {
    $map = [
        'musica' => 'Música',
        'literatura' => 'Literatura',
        'artes_plasticas' => 'Artes Plásticas',
        'danza' => 'Danza',
        'teatro' => 'Teatro'
    ];
    return $map[$categoria] ?? $categoria;
}

function obtenerEmojiPorCategoria($categoria) {
    $emojis = [
        'Música' => '🎤',
        'musica' => '🎤',
        'Literatura' => '📚',
        'literatura' => '📚',
        'Artes Plásticas' => '🎨',
        'artes_plasticas' => '🎨',
        'Danza' => '💃',
        'danza' => '💃',
        'Teatro' => '🎭',
        'teatro' => '🎭'
    ];
    return $emojis[$categoria] ?? '⭐';
}

function obtenerDatos() {
    // Verificar CONTENT_TYPE
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    error_log("CONTENT_TYPE: '$contentType'");
    error_log("_POST length: " . count($_POST));
    error_log("_FILES length: " . count($_FILES));
    
    // Para multipart/form-data, PHP automáticamente rellena $_POST y $_FILES
    if (!empty($_POST) || !empty($_FILES)) {
        error_log("Retornando datos de _POST");
        return $_POST;
    }
    
    // Para JSON o x-www-form-urlencoded
    $input = file_get_contents('php://input');
    if ($input) {
        // Intentar parsear como JSON primero
        $json = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($json)) {
            error_log("Retornando datos de JSON");
            return $json;
        }
        // Si no es JSON, parsear como x-www-form-urlencoded
        parse_str($input, $datos);
        if (!empty($datos)) {
            return $datos;
        }
    }
    
    // Último recurso: retornar $_POST aunque esté vacío
    return $_POST ?? [];
}

// ===== FUNCIONES PÚBLICAS =====

function obtenerTodosArtistas($pdo) {
    $categoria_filter = $_GET['categoria'] ?? null;
    $destacado_filter = $_GET['destacado'] ?? null;
    
    $sql = "SELECT 
        id, 
        nombre_completo, 
        nombre_artistico, 
        categoria,
        subcategoria,
        biografia,
        logros_premios,
        emoji,
        badge,
        imagen,
        orden_visualizacion
    FROM artistas_famosos 
    WHERE activo = TRUE";
    
    $params = [];
    
    if ($categoria_filter && $categoria_filter !== 'todos') {
        $sql .= " AND categoria = ?";
        $params[] = normalizarCategoria($categoria_filter);
    }
    
    if ($destacado_filter === '1') {
        $sql .= " AND destacado = TRUE";
    }
    
    $sql .= " ORDER BY orden_visualizacion ASC, nombre_completo ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Normalizar categorías para retornarlas en formato legible
    foreach ($artistas as &$artista) {
        $artista['categoria'] = denormalizarCategoria($artista['categoria']);
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $artistas,
        'total' => count($artistas)
    ]);
}

function obtenerArtistaPorId($pdo, $id) {
    $stmt = $pdo->prepare("
        SELECT * FROM artistas_famosos 
        WHERE id = ? AND activo = TRUE
    ");
    $stmt->execute([$id]);
    $artista = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($artista) {
        // Denormalizar categoría para retornarla en formato legible
        $artista['categoria'] = denormalizarCategoria($artista['categoria']);
        
        // Procesador de logros si vienen de la BD
        if (!empty($artista['logros_premios']) && is_string($artista['logros_premios'])) {
            $artista['logros'] = array_filter(array_map('trim', explode(',', $artista['logros_premios'])));
        }
        
        echo json_encode([
            'status' => 'success',
            'success' => true,
            'data' => $artista
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Artista no encontrado']);
    }
}

function obtenerCategorias($pdo) {
    $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM artistas_famosos WHERE activo = TRUE ORDER BY categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'status' => 'success',
        'categorias' => $categorias
    ]);
}

function buscarArtistas($pdo) {
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
            biografia
        FROM artistas_famosos 
        WHERE activo = TRUE 
        AND (nombre_completo LIKE ? OR nombre_artistico LIKE ? OR biografia LIKE ?)
        ORDER BY nombre_completo ASC
        LIMIT 20
    ");
    $stmt->execute([$busqueda, $busqueda, $busqueda]);
    $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'total' => count($artistas),
        'artistas' => $artistas
    ]);
}

// ===== FUNCIONES DE ADMINISTRACIÓN =====

function crearArtista($pdo) {
    $datos = obtenerDatos();
    
    // Validar campos obligatorios (SIN emoji)
    $campos_requeridos = ['nombre_completo', 'categoria', 'subcategoria', 'biografia', 'badge'];
    foreach ($campos_requeridos as $campo) {
        if (empty($datos[$campo])) {
            throw new Exception("Campo requerido: $campo");
        }
    }
    
    $nombre_completo = $datos['nombre_completo'];
    $nombre_artistico = $datos['nombre_artistico'] ?? null;
    $categoria_original = $datos['categoria'];
    $categoria = normalizarCategoria($categoria_original);
    $subcategoria = $datos['subcategoria'];
    $biografia = $datos['biografia'];
    // Generar emoji automáticamente basado en la categoría
    $emoji = obtenerEmojiPorCategoria($categoria_original);
    $badge = $datos['badge'];
    $logros_premios = isset($datos['logros']) ? $datos['logros'] : null;
    
    // Procesar imagen si existe
    $imagen_nombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = procesarSubidaImagen($_FILES['imagen']);
    }
    
    $sql = "INSERT INTO artistas_famosos 
            (nombre_completo, nombre_artistico, categoria, subcategoria, biografia, emoji, badge, logros_premios, imagen, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        $nombre_completo, $nombre_artistico, $categoria, $subcategoria,
        $biografia, $emoji, $badge, $logros_premios, $imagen_nombre
    ]);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Artista creado correctamente',
        'id' => $pdo->lastInsertId()
    ]);
}

function actualizarArtista($pdo, $id) {
    $datos = obtenerDatos();
    
    // Si viene categoría, normalizar y obtener emoji correspondiente
    if (isset($datos['categoria'])) {
        $categoria_original = $datos['categoria'];
        $datos['categoria'] = normalizarCategoria($categoria_original);
        // Actualizar emoji automáticamente basado en la nueva categoría
        $datos['emoji'] = obtenerEmojiPorCategoria($categoria_original);
    }
    
    // Procesar imagen si existe
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $datos['imagen'] = procesarSubidaImagen($_FILES['imagen']);
    }
    
    // Construir UPDATE dinámico
    $campos = [];
    $valores = [];
    
    $mapeo = [
        'nombre_completo' => 'nombre_completo',
        'nombre_artistico' => 'nombre_artistico',
        'categoria' => 'categoria',
        'subcategoria' => 'subcategoria',
        'biografia' => 'biografia',
        'emoji' => 'emoji',
        'badge' => 'badge',
        'logros' => 'logros_premios',
        'imagen' => 'imagen'
    ];
    
    foreach ($mapeo as $param => $columna) {
        if (isset($datos[$param]) && $datos[$param] !== '') {
            $campos[] = "$columna = ?";
            $valores[] = $datos[$param];
        }
    }
    
    if (empty($campos)) {
        // No hay campos para actualizar, pero la solicitud es válida
        // Simplemente retornar éxito sin hacer nada
        echo json_encode([
            'success' => true,
            'message' => 'No hay cambios que guardar'
        ]);
        return;
    }
    
    $valores[] = $id;
    
    $sql = "UPDATE artistas_famosos SET " . implode(', ', $campos) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if (!$stmt->execute($valores)) {
        throw new Exception('Error al actualizar artista');
    }
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Artista no encontrado');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Artista actualizado correctamente'
    ]);
}

function eliminarArtista($pdo, $id) {
    // Soft delete
    $sql = "UPDATE artistas_famosos SET activo = 0 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if (!$stmt->execute([$id])) {
        throw new Exception('Error al eliminar artista');
    }
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Artista no encontrado');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Artista eliminado correctamente'
    ]);
}

function procesarSubidaImagen($file) {
    // Validar que sea una imagen
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $tipos_permitidos)) {
        throw new Exception('Tipo de archivo no permitido. Solo se aceptan: JPG, PNG, GIF, WebP');
    }
    
    // Validar tamaño (máximo 5MB)
    $tamaño_maximo = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $tamaño_maximo) {
        throw new Exception('El archivo es demasiado grande. Tamaño máximo: 5MB');
    }
    
    // Crear directorio si no existe
    $directorio = __DIR__ . '/../uploads/artistas_famosos';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }
    
    // Generar nombre único para la imagen
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombre_archivo = 'artista_' . time() . '_' . uniqid() . '.' . $extension;
    $ruta_completa = $directorio . '/' . $nombre_archivo;
    
    // Mover archivo subido
    if (!move_uploaded_file($file['tmp_name'], $ruta_completa)) {
        throw new Exception('Error al guardar la imagen');
    }
    
    // Retornar nombre del archivo para guardar en BD
    return $nombre_archivo;
}


