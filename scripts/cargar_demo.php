<?php
/**
 * Script de Carga de Datos Demo - ID Cultural
 * 
 * Este script carga datos de demostración en la base de datos:
 * - 10 artistas con perfiles completos
 * - 26 publicaciones/obras
 * - Intereses y especialidades
 * 
 * Uso desde línea de comandos:
 * php cargar_demo.php
 * 
 * O desde Docker:
 * docker compose exec web php /var/www/app/scripts/cargar_demo.php
 */

// Configuración de colores para terminal
class Colors {
    const RESET = "\033[0m";
    const GREEN = "\033[32m";
    const RED = "\033[31m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const CYAN = "\033[36m";
}

// Incluir configuración
require_once __DIR__ . '/../config.php';

// Función para mostrar mensajes coloridos
function log_success($message) {
    echo Colors::GREEN . "✅ " . $message . Colors::RESET . PHP_EOL;
}

function log_error($message) {
    echo Colors::RED . "❌ " . $message . Colors::RESET . PHP_EOL;
}

function log_info($message) {
    echo Colors::CYAN . "ℹ️  " . $message . Colors::RESET . PHP_EOL;
}

function log_header($message) {
    echo PHP_EOL . Colors::BLUE . "═══════════════════════════════════════════════════════" . Colors::RESET . PHP_EOL;
    echo Colors::BLUE . "  " . $message . Colors::RESET . PHP_EOL;
    echo Colors::BLUE . "═══════════════════════════════════════════════════════" . Colors::RESET . PHP_EOL;
}

// Conectar a la base de datos
try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_NAME
    );
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    log_success("Conexión a la base de datos establecida");
} catch (PDOException $e) {
    log_error("Error de conexión: " . $e->getMessage());
    exit(1);
}

log_header("🚀 CARGA DE DATOS DEMO - ID CULTURAL");

// ============================================================================
// PASO 1: CARGAR ARTISTAS DEMO
// ============================================================================
log_header("PASO 1: Cargando 10 Artistas Demo...");

$artistas_sql = file_get_contents(__DIR__ . '/../database/cargar_usuarios_demo.sql');

// Separar por puntos y coma y ejecutar cada statement
$statements = array_filter(
    array_map('trim', explode(';', $artistas_sql)),
    function($stmt) {
        return !empty($stmt) && strpos($stmt, '--') !== 0;
    }
);

$artistas_cargados = 0;
foreach ($statements as $stmt) {
    // Saltar comentarios y líneas vacías
    if (empty(trim($stmt)) || strpos(trim($stmt), '--') === 0 || strpos(trim($stmt), '/*') === 0) {
        continue;
    }
    
    try {
        // Solo ejecutar INSERT, UPDATE, SELECT
        if (preg_match('/^(INSERT|UPDATE|SELECT|DELETE)/i', trim($stmt))) {
            $pdo->exec($stmt . ';');
            
            // Contar solo los INSERT
            if (strpos(strtoupper($stmt), 'INSERT') === 0) {
                $artistas_cargados++;
            }
        }
    } catch (Exception $e) {
        log_error("Error ejecutando statement: " . substr($stmt, 0, 50) . "...");
        log_error("Detalle: " . $e->getMessage());
    }
}

// Verificar artistas cargados
$result = $pdo->query("SELECT COUNT(*) as total FROM artistas WHERE id BETWEEN 10 AND 19");
$count = $result->fetch()['total'];
log_success("$count artistas demo cargados/verificados");

// ============================================================================
// PASO 2: CARGAR PUBLICACIONES DEMO
// ============================================================================
log_header("PASO 2: Cargando Publicaciones/Obras Demo...");

$publicaciones_sql = file_get_contents(__DIR__ . '/../database/cargar_publicaciones_demo.sql');
$statements = array_filter(
    array_map('trim', explode(';', $publicaciones_sql)),
    function($stmt) {
        return !empty($stmt) && strpos($stmt, '--') !== 0;
    }
);

$publicaciones_cargadas = 0;
foreach ($statements as $stmt) {
    if (empty(trim($stmt)) || strpos(trim($stmt), '--') === 0) {
        continue;
    }
    
    try {
        if (preg_match('/^(INSERT|UPDATE|SELECT|DELETE)/i', trim($stmt))) {
            $pdo->exec($stmt . ';');
            
            if (strpos(strtoupper($stmt), 'INSERT') === 0) {
                $publicaciones_cargadas++;
            }
        }
    } catch (Exception $e) {
        log_error("Error en publicación: " . substr($stmt, 0, 50) . "...");
    }
}

// Verificar publicaciones cargadas
$result = $pdo->query("SELECT COUNT(*) as total FROM publicaciones WHERE artista_id BETWEEN 10 AND 19");
$count = $result->fetch()['total'];
log_success("$count publicaciones demo cargadas");

// ============================================================================
// PASO 3: MOSTRAR RESUMEN ESTADÍSTICO
// ============================================================================
log_header("ESTADÍSTICAS DE DATOS DEMO");

// Artistas por estado
$result = $pdo->query("
    SELECT status_perfil, COUNT(*) as cantidad 
    FROM artistas 
    WHERE id BETWEEN 10 AND 19 
    GROUP BY status_perfil
");
log_info("Artistas por estado de perfil:");
foreach ($result->fetchAll() as $row) {
    echo "  " . Colors::YELLOW . "• " . ucfirst($row['status_perfil']) . ": " . $row['cantidad'] . Colors::RESET . PHP_EOL;
}

// Publicaciones por estado
$result = $pdo->query("
    SELECT status, COUNT(*) as cantidad 
    FROM publicaciones 
    WHERE artista_id BETWEEN 10 AND 19 
    GROUP BY status
");
log_info("Publicaciones por estado de validación:");
foreach ($result->fetchAll() as $row) {
    echo "  " . Colors::YELLOW . "• " . ucfirst($row['status']) . ": " . $row['cantidad'] . Colors::RESET . PHP_EOL;
}

// Publicaciones por tipo
$result = $pdo->query("
    SELECT tipo, COUNT(*) as cantidad 
    FROM publicaciones 
    WHERE artista_id BETWEEN 10 AND 19 
    GROUP BY tipo
    ORDER BY cantidad DESC
");
log_info("Publicaciones por tipo de obra:");
foreach ($result->fetchAll() as $row) {
    echo "  " . Colors::YELLOW . "• " . ucfirst(str_replace('_', ' ', $row['tipo'])) . ": " . $row['cantidad'] . Colors::RESET . PHP_EOL;
}

// Tabla completa de artistas
log_header("LISTADO COMPLETO DE ARTISTAS DEMO");

$result = $pdo->query("
    SELECT 
        id,
        CONCAT(nombre, ' ', apellido) as nombre_completo,
        email,
        especialidades,
        status_perfil,
        COUNT(p.id) as numero_obras
    FROM artistas a
    LEFT JOIN publicaciones p ON a.id = p.artista_id
    WHERE a.id BETWEEN 10 AND 19
    GROUP BY a.id
    ORDER BY a.id
");

$artistas = $result->fetchAll();
echo str_pad("ID", 4) . " | " . 
     str_pad("NOMBRE", 25) . " | " . 
     str_pad("EMAIL", 30) . " | " . 
     str_pad("ESTADO", 12) . " | " . 
     str_pad("OBRAS", 5) . PHP_EOL;
echo str_repeat("-", 120) . PHP_EOL;

foreach ($artistas as $artista) {
    echo str_pad($artista['id'], 4) . " | " . 
         str_pad(substr($artista['nombre_completo'], 0, 25), 25) . " | " . 
         str_pad(substr($artista['email'], 0, 30), 30) . " | " . 
         str_pad(ucfirst($artista['status_perfil']), 12) . " | " . 
         str_pad($artista['numero_obras'], 5) . PHP_EOL;
}

// ============================================================================
// PASO 4: INFORMACIÓN DE ACCESO DEMO
// ============================================================================
log_header("🔐 CREDENCIALES DE ACCESO DEMO");

log_info("Artistas Demo - Contraseña Estándar: demo123 o demo456");
echo PHP_EOL;
echo Colors::YELLOW . "Ejemplos de Login:" . Colors::RESET . PHP_EOL;
echo "  Email:    juan.reyes.demo@demo.com" . PHP_EOL;
echo "  Password: demo123" . PHP_EOL;
echo PHP_EOL;
echo "  Email:    maria.fernandez.demo@demo.com" . PHP_EOL;
echo "  Password: demo456" . PHP_EOL;
echo PHP_EOL;

// ============================================================================
// PASO 5: INFORMACIÓN ÚTIL
// ============================================================================
log_header("📋 INFORMACIÓN ÚTIL");

log_info("Acceso a la plataforma:");
echo "  URL: " . Colors::CYAN . "http://localhost:8080" . Colors::RESET . PHP_EOL;

log_info("PhpMyAdmin:");
echo "  URL: " . Colors::CYAN . "http://localhost:8081" . Colors::RESET . PHP_EOL;
echo "  Usuario: root" . PHP_EOL;
echo "  Contraseña: root" . PHP_EOL;

log_info("Conexión Directa a BD:");
echo "  " . Colors::CYAN . "docker compose exec db mysql -u runatechdev -p1234 idcultural" . Colors::RESET . PHP_EOL;

log_info("Eliminar datos demo (si es necesario):");
echo "  " . Colors::CYAN . "DELETE FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19;" . Colors::RESET . PHP_EOL;
echo "  " . Colors::CYAN . "DELETE FROM publicaciones WHERE artista_id BETWEEN 10 AND 19;" . Colors::RESET . PHP_EOL;
echo "  " . Colors::CYAN . "DELETE FROM artistas WHERE id BETWEEN 10 AND 19;" . Colors::RESET . PHP_EOL;

// ============================================================================
// RESUMEN FINAL
// ============================================================================
log_header("✨ CARGA DE DATOS DEMO COMPLETADA");

echo PHP_EOL;
log_success("Total de artistas demo: " . $count);
$result = $pdo->query("SELECT COUNT(*) as total FROM publicaciones WHERE artista_id BETWEEN 10 AND 19");
$pub_count = $result->fetch()['total'];
log_success("Total de publicaciones demo: " . $pub_count);

echo PHP_EOL;
log_info("Los datos demo están listos para ser utilizados en demostraciones y pruebas.");
log_info("Todos los artistas tienen el estado de perfil 'validado' excepto algunos que están como 'pendiente' o 'rechazado' para simular casos reales.");

echo PHP_EOL . Colors::GREEN . "¡Listo para demostrar! 🎉" . Colors::RESET . PHP_EOL . PHP_EOL;

?>
