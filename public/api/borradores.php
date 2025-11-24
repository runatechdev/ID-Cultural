<?php
// Iniciar buffer de salida para capturar cualquier output no deseado
ob_start();

session_start();
header('Content-Type: application/json');

// Deshabilitar display_errors para evitar que errores se muestren en la respuesta JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../backend/config/connection.php';

// Obtener acción desde GET o POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Verificar permisos de artista
function checkArtistaPermissions() {
    if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
        exit;
    }
}

try {
    switch ($action) {
        case 'get':
            // OBTENER BORRADORES (solo artista)
            checkArtistaPermissions();
            
            $artista_id = $_SESSION['user_data']['id'];
            $id = $_GET['id'] ?? 0;

            if ($id) {
                // Obtener borrador específico
                $stmt = $pdo->prepare(
                    "SELECT id, titulo, descripcion, categoria, campos_extra, fecha_creacion 
                     FROM publicaciones 
                     WHERE id = ? AND usuario_id = ? AND estado = 'borrador'"
                );
                $stmt->execute([$id, $artista_id]);
                $borrador = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($borrador) {
                    // Decodificar campos extra si existen
                    if ($borrador['campos_extra']) {
                        $borrador['campos_extra'] = json_decode($borrador['campos_extra'], true);
                    } else {
                        $borrador['campos_extra'] = [];
                    }
                    echo json_encode($borrador);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'Borrador no encontrado.']);
                }
            } else {
                // Obtener todos los borradores del artista
                $stmt = $pdo->prepare(
                    "SELECT id, titulo, fecha_creacion 
                     FROM publicaciones 
                     WHERE usuario_id = ? AND estado = 'borrador'
                     ORDER BY fecha_creacion DESC"
                );
                
                $stmt->execute([$artista_id]);
                $borradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode($borradores);
            }
            break;

        case 'save':
            // GUARDAR BORRADOR (solo artista)
            checkArtistaPermissions();
            
            $usuario_id = $_SESSION['user_data']['id'];
            $id = $_POST['id'] ?? 0; // Para actualización
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');
            $estado = trim($_POST['estado'] ?? 'borrador'); // 'borrador' o 'pendiente_validacion'

            // Validaciones básicas
            if (empty($titulo) || empty($descripcion) || empty($categoria)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'El título, la descripción y la categoría son obligatorios.']);
                exit;
            }

            // Recopilar campos extra
            $campos_extra = [];
            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['id', 'titulo', 'descripcion', 'categoria', 'estado'])) {
                    $campos_extra[$key] = trim($value);
                }
            }
            $campos_extra_json = json_encode($campos_extra);

            // Si se envía a validación (estado='pendiente' o 'pendiente_validacion'), actualizamos la fecha de envío
            $fecha_envio = ($estado === 'pendiente' || $estado === 'pendiente_validacion') ? date('Y-m-d H:i:s') : null;
            
            // Normalizar el estado 'pendiente' a 'pendiente_validacion'
            if ($estado === 'pendiente') {
                $estado = 'pendiente_validacion';
            }

            // Procesar multimedia (múltiples archivos)
            $multimedia_paths = [];
            if (isset($_FILES['multimedia']) && !empty($_FILES['multimedia']['tmp_name'])) {
                require_once __DIR__ . '/../../backend/helpers/MultimediaValidator.php';
                $validator = new MultimediaValidator();
                
                // Determinar si es un array de archivos o un solo archivo
                $file_count = is_array($_FILES['multimedia']['tmp_name']) ? count($_FILES['multimedia']['tmp_name']) : 1;
                
                for ($i = 0; $i < $file_count; $i++) {
                    // Obtener datos del archivo
                    if (is_array($_FILES['multimedia']['tmp_name'])) {
                        $file_data = [
                            'name' => $_FILES['multimedia']['name'][$i],
                            'type' => $_FILES['multimedia']['type'][$i],
                            'tmp_name' => $_FILES['multimedia']['tmp_name'][$i],
                            'error' => $_FILES['multimedia']['error'][$i],
                            'size' => $_FILES['multimedia']['size'][$i]
                        ];
                    } else {
                        $file_data = [
                            'name' => $_FILES['multimedia']['name'],
                            'type' => $_FILES['multimedia']['type'],
                            'tmp_name' => $_FILES['multimedia']['tmp_name'],
                            'error' => $_FILES['multimedia']['error'],
                            'size' => $_FILES['multimedia']['size']
                        ];
                    }
                    
                    // Saltar si el archivo está vacío
                    if (empty($file_data['tmp_name'])) {
                        continue;
                    }
                    
                    $result = $validator->guardarArchivo($file_data, 'imagen');
                    if ($result['exitoso']) {
                        $multimedia_paths[] = $result['ruta'];
                    } else {
                        throw new Exception("Error al guardar imagen: " . ($result['mensaje'] ?: 'Error desconocido'));
                    }
                }
            }
            
            // Convertir array de paths a JSON (o null si está vacío)
            $multimedia_json = !empty($multimedia_paths) ? json_encode($multimedia_paths) : null;

            $pdo->beginTransaction();

            try {
                if ($id) {
                    // ACTUALIZAR borrador existente
                    $sql = "UPDATE publicaciones 
                         SET titulo = ?, descripcion = ?, categoria = ?, campos_extra = ?, estado = ?, fecha_envio_validacion = ?";
                    
                    if ($multimedia_json) {
                        $sql .= ", multimedia = ?";
                    }
                    
                    $sql .= " WHERE id = ? AND usuario_id = ? AND estado = 'borrador'";
                    
                    $params = [$titulo, $descripcion, $categoria, $campos_extra_json, $estado, $fecha_envio];
                    if ($multimedia_json) {
                        $params[] = $multimedia_json;
                    }
                    $params[] = $id;
                    $params[] = $usuario_id;
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $message = ($estado === 'pendiente_validacion') 
                        ? 'Publicación enviada a validación con éxito.' 
                        : 'Borrador actualizado con éxito.';
                } else {
                    // CREAR nuevo borrador
                    $sql = "INSERT INTO publicaciones (usuario_id, titulo, descripcion, categoria, campos_extra, estado, fecha_envio_validacion";
                    if ($multimedia_json) {
                        $sql .= ", multimedia";
                    }
                    $sql .= ") VALUES (?, ?, ?, ?, ?, ?, ?";
                    if ($multimedia_json) {
                        $sql .= ", ?";
                    }
                    $sql .= ")";
                    
                    $params = [$usuario_id, $titulo, $descripcion, $categoria, $campos_extra_json, $estado, $fecha_envio];
                    if ($multimedia_json) {
                        $params[] = $multimedia_json;
                    }
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $message = ($estado === 'pendiente_validacion') 
                        ? 'Publicación creada y enviada a validación con éxito.' 
                        : 'Borrador guardado con éxito.';
                }

                // Si se envía a validación, actualizar estado del artista si es necesario
                if ($estado === 'pendiente_validacion') {
                    $update_artista = $pdo->prepare("UPDATE artistas SET status = 'pendiente' WHERE id = ? AND status != 'validado'");
                    $update_artista->execute([$usuario_id]);
                }

                $pdo->commit();
                echo json_encode(['status' => 'ok', 'message' => $message]);

            } catch (PDOException $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        case 'delete':
            // ELIMINAR BORRADOR (solo artista)
            checkArtistaPermissions();
            
            $id = $_POST['id'] ?? '';
            $artista_id = $_SESSION['user_data']['id'];

            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID de borrador no proporcionado.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM publicaciones WHERE id = ? AND usuario_id = ? AND estado = 'borrador'");
            $stmt->execute([$id, $artista_id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'ok', 'message' => 'Borrador eliminado con éxito.']);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'No se encontró el borrador a eliminar.']);
            }
            break;

        case 'submit':
            // ENVIAR BORRADOR A VALIDACIÓN (acción específica)
            checkArtistaPermissions();
            
            $id = $_POST['id'] ?? '';
            $artista_id = $_SESSION['user_data']['id'];

            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID de borrador no proporcionado.']);
                exit;
            }

            $pdo->beginTransaction();

            try {
                // Actualizar estado a pendiente_validacion
                $stmt = $pdo->prepare(
                    "UPDATE publicaciones 
                     SET estado = 'pendiente_validacion', fecha_envio_validacion = CURRENT_TIMESTAMP 
                     WHERE id = ? AND usuario_id = ? AND estado = 'borrador'"
                );
                $stmt->execute([$id, $artista_id]);

                if ($stmt->rowCount() > 0) {
                    // Actualizar estado del artista si es necesario
                    $update_artista = $pdo->prepare("UPDATE artistas SET status = 'pendiente' WHERE id = ? AND status != 'validado'");
                    $update_artista->execute([$artista_id]);

                    $pdo->commit();
                    echo json_encode(['status' => 'ok', 'message' => 'Borrador enviado a validación con éxito.']);
                } else {
                    $pdo->rollBack();
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el borrador a validación.']);
                }

            } catch (PDOException $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        case 'update':
            // ACTUALIZAR PUBLICACIÓN EXISTENTE Y REENVIAR A VALIDACIÓN
            checkArtistaPermissions();
            
            $usuario_id = $_SESSION['user_data']['id'];
            $id = $_POST['id'] ?? '';
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');

            // Validaciones básicas
            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID de publicación no proporcionado.']);
                exit;
            }

            if (empty($titulo) || empty($descripcion) || empty($categoria)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'El título, la descripción y la categoría son obligatorios.']);
                exit;
            }

            // Recopilar campos extra
            $campos_extra = [];
            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['action', 'id', 'titulo', 'descripcion', 'categoria'])) {
                    $campos_extra[$key] = trim($value);
                }
            }
            $campos_extra_json = json_encode($campos_extra);

            // Procesar multimedia (solo si hay archivos nuevos)
            $multimedia_path = null;
            if (isset($_FILES['multimedia']) && !empty($_FILES['multimedia']['tmp_name'][0])) {
                require_once __DIR__ . '/../../backend/helpers/MultimediaValidator.php';
                $validator = new MultimediaValidator();
                
                // Procesar primer archivo
                $file_data = [
                    'name' => $_FILES['multimedia']['name'][0],
                    'type' => $_FILES['multimedia']['type'][0],
                    'tmp_name' => $_FILES['multimedia']['tmp_name'][0],
                    'error' => $_FILES['multimedia']['error'][0],
                    'size' => $_FILES['multimedia']['size'][0]
                ];
                
                $result = $validator->guardarArchivo($file_data, 'imagen');
                if ($result['exitoso']) {
                    $multimedia_path = $result['ruta'];
                } else {
                    throw new Exception("Error al guardar multimedia: " . ($result['mensaje'] ?: 'Error desconocido'));
                }
            }

            $pdo->beginTransaction();

            try {
                // Actualizar la publicación y cambiar estado a pendiente
                $sql = "UPDATE publicaciones 
                        SET titulo = ?, descripcion = ?, categoria = ?, campos_extra = ?, 
                            estado = 'pendiente', fecha_envio_validacion = CURRENT_TIMESTAMP";
                
                $params = [$titulo, $descripcion, $categoria, $campos_extra_json];
                
                if ($multimedia_path) {
                    $sql .= ", multimedia = ?";
                    $params[] = $multimedia_path;
                }
                
                $sql .= " WHERE id = ? AND usuario_id = ?";
                $params[] = $id;
                $params[] = $usuario_id;
                
                error_log("UPDATE borradores.php: id={$id}, usuario_id={$usuario_id}, titulo={$titulo}");
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                error_log("UPDATE borradores.php: rowCount=" . $stmt->rowCount());

                if ($stmt->rowCount() > 0) {
                    $pdo->commit();
                    echo json_encode(['status' => 'ok', 'message' => 'Obra actualizada y enviada a validación con éxito.']);
                } else {
                    $pdo->rollBack();
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'No se encontró la publicación o no tienes permiso para modificarla.']);
                }

            } catch (PDOException $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida. Acciones permitidas: get, save, delete, submit, update']);
            break;
    }

} catch (PDOException $e) {
    // Rollback si hay alguna transacción activa
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>