<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/config/connection.php';

// Obtener acción desde GET o POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Verificar permisos para acciones de admin/validador
function checkAdminPermissions() {
    if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para realizar esta acción.']);
        exit;
    }
}

try {
    switch ($action) {
        case 'get':
            // OBTENER ARTISTAS
            $status_filter = $_GET['status'] ?? null;
            $id = $_GET['id'] ?? 0;

            if ($id) {
                // Obtener artista específico
                $stmt = $pdo->prepare("
                    SELECT a.*, GROUP_CONCAT(ia.interes) as intereses 
                    FROM artistas a 
                    LEFT JOIN intereses_artista ia ON a.id = ia.artista_id 
                    WHERE a.id = ? 
                    GROUP BY a.id
                ");
                $stmt->execute([$id]);
                $artista = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($artista) {
                    // Convertir intereses de string a array
                    if ($artista['intereses']) {
                        $artista['intereses'] = explode(',', $artista['intereses']);
                    } else {
                        $artista['intereses'] = [];
                    }
                    echo json_encode($artista);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'Artista no encontrado.']);
                }
            } else {
                // Obtener lista de artistas (con filtro opcional)
                $sql = "SELECT a.id, a.nombre, a.apellido, a.email, a.status, a.foto_perfil,
                        a.especialidades as categoria, a.biografia, a.municipio, a.provincia, 
                        a.fecha_nacimiento, a.genero, a.instagram, a.facebook, a.twitter, 
                        a.sitio_web, a.telefono, a.whatsapp,
                        COUNT(p.id) as total_obras
                        FROM artistas a
                        LEFT JOIN publicaciones p ON a.id = p.usuario_id AND p.estado = 'validado'";
                $params = [];

                if ($status_filter) {
                    $sql .= " WHERE a.status = ?";
                    $params[] = $status_filter;
                }

                $sql .= " GROUP BY a.id ORDER BY a.id DESC";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($artistas);
            }
            break;

        case 'get_stats':
            // OBTENER ESTADÍSTICAS DE ARTISTAS (solo admin/validador)
            checkAdminPermissions();
            
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
            break;

        case 'register':
            // REGISTRAR NUEVO ARTISTA (público)
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
            $genero = trim($_POST['genero'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $provincia = trim($_POST['provincia'] ?? '');
            $municipio = trim($_POST['municipio'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $intereses = json_decode($_POST['intereses'] ?? '[]', true);

            // Validaciones
            if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($fecha_nacimiento)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Los campos con * son obligatorios. La fecha de nacimiento es obligatoria.']);
                exit;
            }
            if ($password !== $confirm_password) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
                exit;
            }
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Usar transacción para asegurar integridad
            $pdo->beginTransaction();

            try {
                // 1. Insertar el artista
                $stmt = $pdo->prepare(
                    "INSERT INTO artistas (nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')"
                );
                $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $genero, $pais, $provincia, $municipio, $email, $hashed_password]);

                // 2. Obtener ID del artista
                $artista_id = $pdo->lastInsertId();

                // 3. Insertar intereses
                if (!empty($intereses) && is_array($intereses)) {
                    $stmt_interes = $pdo->prepare("INSERT INTO intereses_artista (artista_id, interes) VALUES (?, ?)");
                    foreach ($intereses as $interes) {
                        $stmt_interes->execute([$artista_id, $interes]);
                    }
                }

                $pdo->commit();
                echo json_encode(['status' => 'ok', 'message' => '¡Registro exitoso! Ya puedes iniciar sesión.']);

            } catch (PDOException $e) {
                $pdo->rollBack();
                if ($e->getCode() == 23000) {
                    echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está registrado.']);
                } else {
                    throw $e;
                }
            }
            break;

        case 'update_status':
            // ACTUALIZAR ESTADO DEL ARTISTA (solo admin/validador)
            checkAdminPermissions();
            
            $id = $_POST['id'] ?? '';
            $nuevo_status = $_POST['status'] ?? '';
            $motivo = trim($_POST['motivo'] ?? '');
            $admin_id = $_SESSION['user_data']['id'] ?? null;
            $admin_nombre = $_SESSION['user_data']['nombre'] ?? 'Admin';

            if (empty($id) || empty($nuevo_status) || !in_array($nuevo_status, ['validado', 'rechazado'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
                exit;
            }

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE artistas SET status = ? WHERE id = ?");
            $stmt->execute([$nuevo_status, $id]);

            if ($stmt->rowCount() > 0) {
                // Registrar en logs
                $action_log = ($nuevo_status == 'validado') ? 'VALIDACIÓN DE ARTISTA' : 'RECHAZO DE ARTISTA';
                $details = "Se ha cambiado el estado del artista ID: {$id} a {$nuevo_status}.";
                if (!empty($motivo)) {
                    $details .= ($nuevo_status == 'validado') ? " Comentario: {$motivo}" : " Motivo: {$motivo}";
                }

                $log_stmt = $pdo->prepare("INSERT INTO system_logs (user_id, user_name, action, details) VALUES (?, ?, ?, ?)");
                $log_stmt->execute([$admin_id, $admin_nombre, $action_log, $details]);

                $pdo->commit();
                echo json_encode(['status' => 'ok', 'message' => 'El estado del artista ha sido actualizado.']);
            } else {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado del artista.']);
            }
            break;

        case 'delete':
        case 'eliminar_artista':
            // ELIMINAR ARTISTA Y TODAS SUS OBRAS (solo admin)
            checkAdminPermissions();
            
            $id = $_POST['id'] ?? $_POST['artista_id'] ?? '';

            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID de artista no proporcionado.']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Obtener nombre del artista antes de eliminar
                $stmt = $pdo->prepare("SELECT nombre, apellido FROM artistas WHERE id = ?");
                $stmt->execute([$id]);
                $artista = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$artista) {
                    $pdo->rollBack();
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'No se encontró el artista.']);
                    exit;
                }

                // Eliminar obras publicadas (la tabla usa usuario_id, no artista_id)
                $stmt = $pdo->prepare("DELETE FROM publicaciones WHERE usuario_id = ?");
                $stmt->execute([$id]);
                $obras_eliminadas = $stmt->rowCount();

                $borradores_eliminados = 0; // No existe tabla de borradores

                // Eliminar cambios pendientes de perfil
                $stmt = $pdo->prepare("DELETE FROM perfil_cambios_pendientes WHERE artista_id = ?");
                $stmt->execute([$id]);
                $cambios_eliminados = $stmt->rowCount();

                // Eliminar intereses
                $stmt = $pdo->prepare("DELETE FROM intereses_artista WHERE artista_id = ?");
                $stmt->execute([$id]);

                // Finalmente eliminar el artista
                $stmt = $pdo->prepare("DELETE FROM artistas WHERE id = ?");
                $stmt->execute([$id]);

                $pdo->commit();

                $mensaje = "Artista {$artista['nombre']} {$artista['apellido']} eliminado correctamente.";
                if ($obras_eliminadas > 0) {
                    $mensaje .= " Se eliminaron {$obras_eliminadas} obra(s) publicada(s).";
                }
                if ($cambios_eliminados > 0) {
                    $mensaje .= " Se eliminaron {$cambios_eliminados} cambio(s) pendiente(s).";
                }

                echo json_encode(['status' => 'success', 'message' => $mensaje]);

            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida. Acciones permitidas: get, get_stats, register, update_status, delete']);
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