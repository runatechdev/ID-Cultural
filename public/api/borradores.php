<?php
// 1. LIMPIEZA INICIAL: Iniciar buffer para capturar cualquier warning/error visual
ob_start();

session_start();
header('Content-Type: application/json');

// 2. CONFIGURACIÓN DE ERRORES: No mostrar en pantalla, pero sí reportar internamente
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../backend/config/connection.php';

// Obtener acción
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Helper de permisos
function checkArtistaPermissions() {
    if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
        if (ob_get_length()) ob_clean();
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
        exit;
    }
}

try {
    switch ($action) {
        case 'get':
            checkArtistaPermissions();
            $artista_id = $_SESSION['user_data']['id'];
            $id = $_GET['id'] ?? 0;

            if ($id) {
                $stmt = $pdo->prepare(
                    "SELECT id, titulo, descripcion, categoria, campos_extra, multimedia, fecha_creacion 
                     FROM publicaciones 
                     WHERE id = ? AND usuario_id = ?" // Quitamos restricción de estado para poder editar validados/pendientes
                );
                $stmt->execute([$id, $artista_id]);
                $borrador = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($borrador) {
                    $borrador['campos_extra'] = $borrador['campos_extra'] ? json_decode($borrador['campos_extra'], true) : [];
                    // Asegurar que multimedia sea un array o null
                    if ($borrador['multimedia']) {
                        // Intentar decodificar JSON, si falla asumir string simple
                        $decoded = json_decode($borrador['multimedia'], true);
                        $borrador['multimedia'] = is_array($decoded) ? $decoded : [$borrador['multimedia']];
                    }
                    if (ob_get_length()) ob_clean();
                    echo json_encode($borrador);
                } else {
                    throw new Exception('Obra no encontrada.');
                }
            } else {
                // Listado general
                $stmt = $pdo->prepare("SELECT id, titulo, fecha_creacion, estado FROM publicaciones WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
                $stmt->execute([$artista_id]);
                $borradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (ob_get_length()) ob_clean();
                echo json_encode($borradores);
            }
            break;

        case 'save': // CREAR NUEVO
            checkArtistaPermissions();
            $usuario_id = $_SESSION['user_data']['id'];
            
            // Recoger datos
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');
            $estado = trim($_POST['estado'] ?? 'borrador');

            if (empty($titulo) || empty($descripcion) || empty($categoria)) {
                throw new Exception('El título, la descripción y la categoría son obligatorios.');
            }

            // Campos extra
            $campos_extra = [];
            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['id', 'titulo', 'descripcion', 'categoria', 'estado', 'action', 'multimedia'])) {
                    $campos_extra[$key] = trim($value);
                }
            }
            $campos_extra_json = json_encode($campos_extra);
            $fecha_envio = ($estado === 'pendiente' || $estado === 'pendiente_validacion') ? date('Y-m-d H:i:s') : null;
            if ($estado === 'pendiente') $estado = 'pendiente_validacion'; // Normalizar

            // Procesar Multimedia (NUEVO)
            $multimedia_paths = [];
            if (isset($_FILES['multimedia']) && !empty($_FILES['multimedia']['tmp_name'])) {
                require_once __DIR__ . '/../../backend/helpers/MultimediaValidator.php';
                $validator = new MultimediaValidator();
                
                // Normalizar estructura $_FILES
                $files = $_FILES['multimedia'];
                $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 1;

                for ($i = 0; $i < $count; $i++) {
                    $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
                    if (empty($tmpName)) continue;

                    $fileData = [
                        'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                        'type' => is_array($files['type']) ? $files['type'][$i] : $files['type'],
                        'tmp_name' => $tmpName,
                        'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                        'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
                    ];

                    $result = $validator->guardarArchivo($fileData, 'imagen');
                    if ($result['exitoso']) {
                        $multimedia_paths[] = $result['ruta'];
                    }
                }
            }
            $multimedia_json = !empty($multimedia_paths) ? json_encode($multimedia_paths) : null;

            // Insertar
            $sql = "INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, estado, fecha_envio_validacion, multimedia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario_id, $titulo, $descripcion, $categoria, $campos_extra_json, $estado, $fecha_envio, $multimedia_json]);

            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'ok', 'message' => 'Obra guardada exitosamente.']);
            break;

        case 'update': // EDITAR EXISTENTE (Aquí estaba el error 500)
            checkArtistaPermissions();
            $usuario_id = $_SESSION['user_data']['id'];
            $id = $_POST['id'] ?? 0;

            if (!$id) throw new Exception('ID de publicación no proporcionado.');

            // 1. Obtener datos actuales (para saber qué imágenes ya existen)
            $stmtCurrent = $pdo->prepare("SELECT multimedia FROM publicaciones WHERE id = ? AND usuario_id = ?");
            $stmtCurrent->execute([$id, $usuario_id]);
            $currentData = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
            
            if (!$currentData) throw new Exception('Obra no encontrada o sin permisos.');

            // Normalizar imágenes actuales a Array
            $existingImages = [];
            if (!empty($currentData['multimedia'])) {
                $decoded = json_decode($currentData['multimedia'], true);
                // Si decodifica es array, si no (legacy string) lo metemos en array
                $existingImages = is_array($decoded) ? $decoded : [$currentData['multimedia']];
            }

            // 2. Procesar imágenes a borrar (que manda el frontend)
            $imagenes_a_borrar = $_POST['imagenes_a_borrar'] ?? [];
            if (is_string($imagenes_a_borrar)) {
                // Por si acaso llega como string separado por comas
                $imagenes_a_borrar = explode(',', $imagenes_a_borrar);
            }

            // Filtrar: De las existentes, quitamos las que están en "borrar"
            $finalImages = [];
            foreach ($existingImages as $img) {
                // Comparamos rutas. A veces vienen con '/' al principio o no. Intentamos normalizar básico.
                // Lo ideal es comparación directa si el frontend manda la ruta exacta.
                if (!in_array($img, $imagenes_a_borrar)) {
                    $finalImages[] = $img;
                } else {
                    // Opcional: Aquí podrías unlink() el archivo físico si quisieras limpiar disco
                }
            }

            // 3. Procesar Nuevas Imágenes (Append)
            if (isset($_FILES['multimedia']) && !empty($_FILES['multimedia']['tmp_name'])) {
                require_once __DIR__ . '/../../backend/helpers/MultimediaValidator.php';
                $validator = new MultimediaValidator();
                
                $files = $_FILES['multimedia'];
                $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 1;

                for ($i = 0; $i < $count; $i++) {
                    $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
                    if (empty($tmpName)) continue;

                    $fileData = [
                        'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                        'type' => is_array($files['type']) ? $files['type'][$i] : $files['type'],
                        'tmp_name' => $tmpName,
                        'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                        'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
                    ];

                    $result = $validator->guardarArchivo($fileData, 'imagen');
                    if ($result['exitoso']) {
                        $finalImages[] = $result['ruta'];
                    }
                }
            }

            // 4. Preparar datos finales
            $titulo = trim($_POST['titulo']);
            $descripcion = trim($_POST['descripcion']);
            $categoria = trim($_POST['categoria']);
            
            $campos_extra = [];
            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['id', 'titulo', 'descripcion', 'categoria', 'estado', 'action', 'multimedia', 'imagenes_a_borrar'])) {
                    $campos_extra[$key] = trim($value);
                }
            }
            $campos_extra_json = json_encode($campos_extra);
            
            // Codificar array final de imágenes a JSON para la BD
            // Usamos array_values para reindexar (0, 1, 2...) y que no queden huecos
            $multimedia_final_json = !empty($finalImages) ? json_encode(array_values($finalImages)) : null;

            // 5. Update SQL
            $sql = "UPDATE publicaciones 
                    SET titulo = ?, descripcion = ?, categoria = ?, campos_extra = ?, 
                        multimedia = ?, estado = 'pendiente', fecha_envio_validacion = CURRENT_TIMESTAMP
                    WHERE id = ? AND usuario_id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $titulo, 
                $descripcion, 
                $categoria, 
                $campos_extra_json, 
                $multimedia_final_json, 
                $id, 
                $usuario_id
            ]);

            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'ok', 'message' => 'Obra actualizada correctamente.']);
            break;
            
        case 'delete':
            checkArtistaPermissions();
            $id = $_POST['id'] ?? '';
            $artista_id = $_SESSION['user_data']['id'];

            if (!$id) throw new Exception('ID no proporcionado');

            $stmt = $pdo->prepare("DELETE FROM publicaciones WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $artista_id]);

            if (ob_get_length()) ob_clean();
            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'ok', 'message' => 'Borrado exitoso.']);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'No encontrado o sin permisos.']);
            }
            break;

        default:
            throw new Exception('Acción no válida');
    }

} catch (Exception $e) {
    // CAPTURA DE ERRORES GLOBAL
    // Limpiamos cualquier salida previa (HTML sucio, warnings)
    if (ob_get_length()) ob_clean();
    
    // Devolvemos JSON limpio con el error real
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
?>