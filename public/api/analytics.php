<?php
/**
 * API de Analytics - ID Cultural
 * Endpoints para tracking y obtención de estadísticas
 */

require_once '../../backend/config/connection.php';
require_once '../../backend/helpers/Analytics.php';
require_once '../../backend/helpers/ErrorHandler.php';
require_once '../../backend/helpers/RateLimiter.php';

// Inicializar
ErrorHandler::init();
session_start();
Analytics::init($conn);

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

// Rate limiting
if (!RateLimiter::check('api_general')) {
    RateLimiter::addHeaders('api_general');
    ErrorHandler::error('Demasiadas solicitudes. Intente más tarde.', 429);
}

RateLimiter::addHeaders('api_general');

try {
    switch ($action) {
        case 'track_page':
            // Registrar visita a página
            $pagina = $_POST['pagina'] ?? $_GET['pagina'] ?? '';
            $duracion = (int)($_POST['duracion'] ?? 0);
            
            if (empty($pagina)) {
                ErrorHandler::validation(['pagina' => 'Requerido']);
            }
            
            Analytics::trackPageView($pagina, $userId, $duracion);
            ErrorHandler::success(null, 'Visita registrada');
            break;
            
        case 'track_event':
            // Registrar evento
            $categoria = $_POST['categoria'] ?? '';
            $accion = $_POST['accion'] ?? '';
            $etiqueta = $_POST['etiqueta'] ?? null;
            $valor = isset($_POST['valor']) ? (int)$_POST['valor'] : null;
            
            if (empty($categoria) || empty($accion)) {
                ErrorHandler::validation([
                    'categoria' => empty($categoria) ? 'Requerido' : null,
                    'accion' => empty($accion) ? 'Requerido' : null
                ]);
            }
            
            Analytics::trackEvent($categoria, $accion, $etiqueta, $valor, $userId);
            ErrorHandler::success(null, 'Evento registrado');
            break;
            
        case 'track_search':
            // Registrar búsqueda
            $termino = $_POST['termino'] ?? $_GET['q'] ?? '';
            $resultados = (int)($_POST['resultados'] ?? 0);
            
            if (empty($termino)) {
                ErrorHandler::validation(['termino' => 'Requerido']);
            }
            
            Analytics::trackSearch($termino, $resultados, $userId);
            ErrorHandler::success(null, 'Búsqueda registrada');
            break;
            
        case 'get_dashboard':
            // Verificar que sea admin o validador
            if (!isset($_SESSION['user_data']) || 
                !in_array($_SESSION['user_data']['role'], ['admin', 'validador', 'editor'])) {
                ErrorHandler::unauthorized('Acceso denegado');
            }
            
            $dashboard = Analytics::getDashboard();
            ErrorHandler::success($dashboard, 'Dashboard obtenido');
            break;
            
        case 'get_visits':
            // Verificar permisos
            if (!isset($_SESSION['user_data']) || 
                !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
                ErrorHandler::unauthorized('Acceso denegado');
            }
            
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;
            
            $stats = Analytics::getVisitStats($fechaInicio, $fechaFin);
            ErrorHandler::success($stats, 'Estadísticas obtenidas');
            break;
            
        case 'get_top_pages':
            // Verificar permisos
            if (!isset($_SESSION['user_data']) || 
                !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
                ErrorHandler::unauthorized('Acceso denegado');
            }
            
            $limit = (int)($_GET['limit'] ?? 10);
            $pages = Analytics::getTopPages($limit);
            ErrorHandler::success($pages, 'Páginas populares obtenidas');
            break;
            
        case 'get_top_events':
            // Verificar permisos
            if (!isset($_SESSION['user_data']) || 
                !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
                ErrorHandler::unauthorized('Acceso denegado');
            }
            
            $limit = (int)($_GET['limit'] ?? 10);
            $events = Analytics::getTopEvents($limit);
            ErrorHandler::success($events, 'Eventos populares obtenidos');
            break;
            
        case 'get_top_searches':
            // Verificar permisos
            if (!isset($_SESSION['user_data']) || 
                !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
                ErrorHandler::unauthorized('Acceso denegado');
            }
            
            $limit = (int)($_GET['limit'] ?? 10);
            $searches = Analytics::getTopSearches($limit);
            ErrorHandler::success($searches, 'Búsquedas populares obtenidas');
            break;
            
        default:
            ErrorHandler::error('Acción no válida', 400);
    }
} catch (Exception $e) {
    ErrorHandler::error($e->getMessage(), 500);
}
