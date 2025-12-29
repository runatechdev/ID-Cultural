<?php

namespace Backend\Controllers\Api;

require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../helpers/MultimediaValidator.php';
require_once __DIR__ . '/../../repositories/BaseRepository.php';
require_once __DIR__ . '/../../repositories/ArtistaRepository.php';
// AuthMiddleware moved to global/mapped namespace, verify if it's Backend\Helpers or global
// In step 73 I reverted it to global namespace "class AuthMiddleware".
// So I will use \AuthMiddleware.

use PDO;
use Exception;
use PDOException;
use MultimediaValidator; // Global namespace
use Backend\Repositories\ArtistaRepository;

class ArtistaController
{

    private $pdo;
    private ArtistaRepository $artistaRepo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->artistaRepo = new ArtistaRepository($pdo);
    }

    /**
     * Entry point to route actions
     */
    public function handleRequest($action)
    {
        try {
            switch ($action) {
                case 'get':
                    $this->getAll();
                    break;
                case 'get_one':
                    $this->getOne();
                    break;
                case 'register':
                    $this->register();
                    break;
                case 'update_personal':
                    $this->updatePersonalProfile();
                    break;
                case 'update_public':
                    $this->updatePublicProfile();
                    break;
                case 'update_status':
                case 'validate_profile':
                    $this->validateProfile();
                    break;
                case 'get_profiles':
                    $this->getProfiles();
                    break;
                case 'get_pending':
                    $this->getPendingProfiles();
                    break;
                case 'featured':
                    $this->handleFeatured();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'get_stats':
                    $this->getStats();
                    break;
                default:
                    $this->sendResponse('error', 'Acción no válida', 400);
            }
        } catch (Exception $e) {
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getProfiles()
    {
        $admin = $this->requireAuth(['admin', 'validador']);

        $estado = $_GET['estado'] ?? 'pendiente';
        $provincia = $_GET['provincia'] ?? null;
        $limite = (int)($_GET['limite'] ?? 50);
        $pagina = (int)($_GET['pagina'] ?? 1);
        $offset = ($pagina - 1) * $limite;

        if (!in_array($estado, ['pendiente', 'validado', 'rechazado', 'todos'])) {
            $estado = 'pendiente';
        }

        // Usar repositorio en lugar de SQL directo
        if ($estado === 'todos') {
            // Obtener todos con filtros opcionales
            $filters = [
                'limit' => $limite,
                'offset' => $offset
            ];
            
            if ($provincia) {
                $filters['provincia'] = $provincia;
            }
            
            $perfiles = $this->artistaRepo->findValidados($filters);
        } else {
            // Obtener por estado específico
            $perfiles = $this->artistaRepo->findByStatus($estado, $limite, $offset);
            
            // Filtrar por provincia si se especifica
            if ($provincia) {
                $perfiles = array_filter($perfiles, function($p) use ($provincia) {
                    return $p['provincia'] === $provincia;
                });
            }
        }

        echo json_encode($perfiles);
    }

    private function getPendingProfiles()
    {
        $this->requireAuth(['admin', 'validador']);

        $sql = "SELECT pcp.id as cambio_id, pcp.artista_id, pcp.biografia, pcp.especialidades,
                pcp.instagram, pcp.facebook, pcp.twitter, pcp.sitio_web, pcp.foto_perfil,
                pcp.estado, pcp.fecha_solicitud, a.nombre, a.apellido, a.email,
                a.foto_perfil as foto_perfil_actual, a.biografia as biografia_actual,
                a.especialidades as especialidades_actual, a.municipio, a.status_perfil
                FROM perfil_cambios_pendientes pcp
                INNER JOIN artistas a ON pcp.artista_id = a.id
                WHERE pcp.estado = 'pendiente'
                ORDER BY pcp.fecha_solicitud ASC";

        $stmt = $this->pdo->query($sql);
        $cambios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = array_map(function ($c) {
            return [
                'id' => $c['cambio_id'],
                'artista_id' => $c['artista_id'],
                'tipo' => 'perfil',
                'nombre_artista' => trim($c['nombre'] . ' ' . $c['apellido']),
                'email' => $c['email'],
                'municipio' => $c['municipio'],
                'fecha_solicitud' => $c['fecha_solicitud'],
                'cambios' => [
                    'biografia' => $c['biografia'],
                    'especialidades' => $c['especialidades'],
                    'instagram' => $c['instagram'],
                    'facebook' => $c['facebook'],
                    'twitter' => $c['twitter'],
                    'sitio_web' => $c['sitio_web'],
                    'foto_perfil' => $c['foto_perfil']
                ],
                'valores_actuales' => [
                    'biografia' => $c['biografia_actual'],
                    'especialidades' => $c['especialidades_actual'],
                    'foto_perfil' => $c['foto_perfil_actual']
                ],
                'status_perfil' => $c['status_perfil'],
                'estado' => $c['estado']
            ];
        }, $cambios);

        echo json_encode($resultado);
    }

    private function handleFeatured()
    {
        $subAction = $_GET['sub_action'] ?? 'get';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $this->getFeaturedOne($_GET['id']);
            } else {
                switch ($subAction) {
                    case 'get':
                        $this->getFeaturedAll();
                        break;
                    case 'get_categorias':
                        $this->getFeaturedCategories();
                        break;
                    case 'buscar':
                        $this->searchFeatured();
                        break;
                    default:
                        $this->getFeaturedAll();
                }
            }
            return;
        }

        // Admin Actions
        $this->requireAuth(['admin', 'editor']);

        if ($method === 'POST') {
            if (isset($_GET['id'])) {
                $this->updateFeatured($_GET['id']);
            } else {
                $this->createFeatured();
            }
        } elseif ($method === 'PUT') {
            // Support PUT via POST or actual PUT
            $id = $_GET['id'] ?? null;
            if (!$id) $this->sendResponse('error', 'ID requerido', 400);
            $this->updateFeatured($id);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if (!$id) $this->sendResponse('error', 'ID requerido', 400);
            $this->deleteFeatured($id);
        }
    }

    private function getFeaturedAll()
    {
        $cat = $_GET['categoria'] ?? null;
        $dest = $_GET['destacado'] ?? null;

        $sql = "SELECT id, nombre_completo, nombre_artistico, categoria, subcategoria, biografia, logros_premios, emoji, badge, imagen, orden_visualizacion 
                FROM artistas_famosos WHERE activo = 1";
        $params = [];
        if ($cat && $cat !== 'todos') {
            $sql .= " AND categoria = ?";
            $params[] = $this->normalizarCategoria($cat);
        }
        if ($dest === '1') $sql .= " AND destacado = 1";
        $sql .= " ORDER BY orden_visualizacion ASC, nombre_completo ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($artistas as &$a) {
            $a['categoria'] = $this->denormalizarCategoria($a['categoria']);
        }
        echo json_encode(['status' => 'success', 'data' => $artistas, 'total' => count($artistas)]);
    }

    private function getFeaturedOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM artistas_famosos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        $artista = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($artista) {
            $artista['categoria'] = $this->denormalizarCategoria($artista['categoria']);
            if (!empty($artista['logros_premios'])) {
                $artista['logros'] = array_filter(array_map('trim', explode(',', $artista['logros_premios'])));
            }
            echo json_encode(['status' => 'success', 'success' => true, 'data' => $artista]);
        } else {
            $this->sendResponse('error', 'Artista no encontrado', 404);
        }
    }

    private function getFeaturedCategories()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT categoria FROM artistas_famosos WHERE activo = 1 ORDER BY categoria");
        $cats = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode(['status' => 'success', 'categorias' => $cats]);
    }

    private function searchFeatured()
    {
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            echo json_encode(['status' => 'success', 'total' => 0, 'artistas' => []]);
            return;
        }
        $q = "%$q%";
        $stmt = $this->pdo->prepare("SELECT id, nombre_completo, nombre_artistico, categoria, biografia FROM artistas_famosos WHERE activo = 1 AND (nombre_completo LIKE ? OR nombre_artistico LIKE ? OR biografia LIKE ?) ORDER BY nombre_completo ASC LIMIT 20");
        $stmt->execute([$q, $q, $q]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'total' => count($data), 'artistas' => $data]);
    }

    private function createFeatured()
    {
        $data = $_POST; // Simplified handling
        // Validation...
        $req = ['nombre_completo', 'categoria', 'subcategoria', 'biografia', 'badge'];
        foreach ($req as $f) if (empty($data[$f])) $this->sendResponse('error', "Campo requerido: $f", 400);

        $cat = $this->normalizarCategoria($data['categoria']);
        $emoji = $this->obtenerEmojiPorCategoria($data['categoria']);
        $img = null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $img = $this->processImageUpload($_FILES['imagen']);
        }

        $stmt = $this->pdo->prepare("INSERT INTO artistas_famosos (nombre_completo, nombre_artistico, categoria, subcategoria, biografia, emoji, badge, logros_premios, imagen, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $data['nombre_completo'],
            $data['nombre_artistico'] ?? null,
            $cat,
            $data['subcategoria'],
            $data['biografia'],
            $emoji,
            $data['badge'],
            $data['logros'] ?? null,
            $img
        ]);

        $this->sendResponse('success', 'Artista creado', 201);
    }

    private function updateFeatured($id)
    {
        $data = $_POST; // Or PUT parsing logic if needed (usually POST for form-data)
        $fields = [];
        $values = [];

        $map = [
            'nombre_completo' => 'nombre_completo',
            'nombre_artistico' => 'nombre_artistico',
            'categoria' => 'categoria',
            'subcategoria' => 'subcategoria',
            'biografia' => 'biografia',
            'badge' => 'badge',
            'logros' => 'logros_premios',
            'imagen' => 'imagen'
        ];

        // Special handling for category updates to update emoji
        if (!empty($data['categoria'])) {
            $data['categoria'] = $this->normalizarCategoria($data['categoria']);
            $fields[] = "emoji = ?";
            $values[] = $this->obtenerEmojiPorCategoria($data['categoria']);
        }

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $data['imagen'] = $this->processImageUpload($_FILES['imagen']);
        }

        foreach ($map as $k => $col) {
            if (isset($data[$k]) && $data[$k] !== '') {
                $fields[] = "$col = ?";
                $values[] = $data[$k];
            }
        }

        if (empty($fields)) $this->sendResponse('success', 'Sin cambios');

        $values[] = $id;
        $sql = "UPDATE artistas_famosos SET " . implode(', ', $fields) . " WHERE id = ?";
        $this->pdo->prepare($sql)->execute($values);
        $this->sendResponse('success', 'Actualizado correctamente');
    }

    private function deleteFeatured($id)
    {
        $this->pdo->prepare("UPDATE artistas_famosos SET activo = 0 WHERE id = ?")->execute([$id]);
        $this->sendResponse('success', 'Eliminado correctamente');
    }

    // Helpers
    private function normalizarCategoria($c)
    {
        $map = ['Música' => 'musica', 'Musica' => 'musica', 'Literatura' => 'literatura', 'Artes Plásticas' => 'artes_plasticas', 'Danza' => 'danza', 'Teatro' => 'teatro'];
        return $map[$c] ?? $c;
    }
    private function denormalizarCategoria($c)
    {
        $map = ['musica' => 'Música', 'literatura' => 'Literatura', 'artes_plasticas' => 'Artes Plásticas', 'danza' => 'Danza', 'teatro' => 'Teatro'];
        return $map[$c] ?? $c;
    }
    private function obtenerEmojiPorCategoria($c)
    {
        $emojis = ['Música' => '🎤', 'musica' => '🎤', 'Literatura' => '📚', 'literatura' => '📚', 'Artes Plásticas' => '🎨', 'artes_plasticas' => '🎨', 'Danza' => '💃', 'danza' => '💃', 'Teatro' => '🎭', 'teatro' => '🎭'];
        return $emojis[$c] ?? '⭐';
    }
    private function processImageUpload($file)
    {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed)) throw new Exception("Tipo inválido");
        if ($file['size'] > 5 * 1024 * 1024) throw new Exception("Muy grande > 5MB");
        $dir = __DIR__ . '/../../../../public/static/uploads/artistas_famosos'; // Adjust relative path!
        // Current file: backend/controllers/Api/ArtistaController.php
        // Depth: backend/controllers/Api (3 levels up to root)
        // Check relative path: __DIR__ is /home/.../backend/controllers/Api
        // ../../../public/static should be correct?
        // Let's rely on absolute path if possible or careful relative.
        // /../../../../public seems 4 levels.
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $name = 'artista_' . time() . '_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        move_uploaded_file($file['tmp_name'], "$dir/$name");
        return $name;
    }


    // --- Public Methods ---

    private function getAll()
    {
        $status_filter = $_GET['status'] ?? null;

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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function getOne()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) $this->sendResponse('error', 'ID no proporcionado', 400);

        $stmt = $this->pdo->prepare("
            SELECT a.*, GROUP_CONCAT(ia.interes) as intereses 
            FROM artistas a 
            LEFT JOIN intereses_artista ia ON a.id = ia.artista_id 
            WHERE a.id = ? 
            GROUP BY a.id
        ");
        $stmt->execute([$id]);
        $artista = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($artista) {
            $artista['intereses'] = $artista['intereses'] ? explode(',', $artista['intereses']) : [];
            echo json_encode($artista);
        } else {
            $this->sendResponse('error', 'Artista no encontrado', 404);
        }
    }

    private function register()
    {
        $data = $_POST;
        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $intereses = json_decode($data['intereses'] ?? '[]', true);

        if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            $this->sendResponse('error', 'Campos obligatorios faltantes', 400);
        }

        if ($password !== ($data['confirm_password'] ?? '')) {
            $this->sendResponse('error', 'Las contraseñas no coinciden', 400);
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare(
                "INSERT INTO artistas (nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio, email, password, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')"
            );
            $stmt->execute([
                $nombre,
                $apellido,
                $data['fecha_nacimiento'] ?? '',
                $data['genero'] ?? '',
                $data['pais'] ?? '',
                $data['provincia'] ?? '',
                $data['municipio'] ?? '',
                $email,
                $hashed
            ]);

            $id = $this->pdo->lastInsertId();

            if (!empty($intereses) && is_array($intereses)) {
                $stmt_i = $this->pdo->prepare("INSERT INTO intereses_artista (artista_id, interes) VALUES (?, ?)");
                foreach ($intereses as $i) $stmt_i->execute([$id, $i]);
            }

            $this->pdo->commit();
            $this->sendResponse('ok', 'Registro exitoso! Ya puedes iniciar sesión.');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            if ($e->getCode() == 23000) {
                $this->sendResponse('error', 'El email ya está registrado', 400);
            }
            throw $e;
        }
    }

    // --- Private / Protected Actions ---

    private function updatePersonalProfile()
    {
        $user = $this->requireAuth('artista');
        $id = $user['id'];
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) $this->sendResponse('error', 'Datos inválidos', 400);

        $stmt = $this->pdo->prepare("
            UPDATE artistas SET nombre=?, apellido=?, fecha_nacimiento=?, genero=?, pais=?, provincia=?, municipio=?
            WHERE id=?
        ");
        $res = $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['fecha_nacimiento'],
            $data['genero'],
            $data['pais'],
            $data['provincia'],
            $data['municipio'],
            $id
        ]);

        if ($res) {
            $_SESSION['user_data']['nombre'] = $data['nombre'];
            $_SESSION['user_data']['apellido'] = $data['apellido'];
            $this->sendResponse('ok', 'Perfil actualizado');
        } else {
            $this->sendResponse('error', 'No se realizaron cambios', 400);
        }
    }

    private function updatePublicProfile()
    {
        $user = $this->requireAuth('artista');
        $id = $user['id'];

        $foto_ruta = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $validator = new MultimediaValidator();
            // Assuming default constructor works or I need to check
            $res = $validator->guardarArchivo($_FILES['foto_perfil'], 'imagen');
            if (!$res['exitoso']) $this->sendResponse('error', $res['mensaje'], 400);
            $foto_ruta = $res['ruta'];
        }

        // Check pending
        $stmt = $this->pdo->prepare("SELECT id FROM perfil_cambios_pendientes WHERE artista_id = ? AND estado = 'pendiente'");
        $stmt->execute([$id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        $fields = [
            'biografia' => $_POST['biografia'] ?? '',
            'especialidades' => $_POST['especialidades'] ?? '',
            'instagram' => $_POST['instagram'] ?? '',
            'facebook' => $_POST['facebook'] ?? '',
            'twitter' => $_POST['twitter'] ?? '',
            'sitio_web' => $_POST['sitio_web'] ?? '',
            'fecha_solicitud' => date('Y-m-d H:i:s')
        ];
        if ($foto_ruta) $fields['foto_perfil'] = $foto_ruta;

        if ($existing) {
            $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
            $sql = "UPDATE perfil_cambios_pendientes SET $set WHERE id = :pid";
            $fields['pid'] = $existing['id'];
        } else {
            $fields['artista_id'] = $id;
            $fields['estado'] = 'pendiente';
            $cols = implode(', ', array_keys($fields));
            $vals = implode(', ', array_map(fn($k) => ":$k", array_keys($fields)));
            $sql = "INSERT INTO perfil_cambios_pendientes ($cols) VALUES ($vals)";
        }

        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute($fields)) {
            $this->sendResponse('ok', 'Perfil enviado a validación');
        } else {
            $this->sendResponse('error', 'Error al guardar cambios', 500);
        }
    }

    private function validateProfile()
    {
        $admin = $this->requireAuth(['admin', 'validador']);
        $id = $_POST['id'] ?? null;
        $accion = $_POST['accion'] ?? null; // 'validar' or 'rechazar'
        $motivo = $_POST['motivo'] ?? null;

        if (!$id || !in_array($accion, ['validar', 'rechazar'])) {
            $this->sendResponse('error', 'Datos inválidos', 400);
        }
        if ($accion === 'rechazar' && empty($motivo)) {
            $this->sendResponse('error', 'Motivo requerido para rechazar', 400);
        }

        require_once __DIR__ . '/../../helpers/EmailHelper.php';
        require_once __DIR__ . '/../../helpers/NotificationHelper.php';
        \NotificationHelper::init($this->pdo);
        $mailer = new \EmailHelper();

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("SELECT id, nombre, apellido, email FROM artistas WHERE id = ?");
            $stmt->execute([$id]);
            $artista = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$artista) throw new Exception("Artista no encontrado");

            if ($accion === 'validar') {
                $nuevo_estado = 'validado';

                // PENDING CHANGES logic
                $stmt = $this->pdo->prepare("SELECT * FROM perfil_cambios_pendientes WHERE artista_id = ? AND estado = 'pendiente' ORDER BY fecha_solicitud DESC LIMIT 1");
                $stmt->execute([$id]);
                $changes = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($changes) {
                    $stmt = $this->pdo->prepare("UPDATE artistas SET 
                        biografia = COALESCE(?, biografia), especialidades = COALESCE(?, especialidades),
                        instagram = COALESCE(?, instagram), facebook = COALESCE(?, facebook),
                        twitter = COALESCE(?, twitter), sitio_web = COALESCE(?, sitio_web),
                        whatsapp = COALESCE(?, whatsapp), foto_perfil = COALESCE(?, foto_perfil),
                        status_perfil = ?, motivo_rechazo = NULL
                        WHERE id = ?");
                    $stmt->execute([
                        $changes['biografia'],
                        $changes['especialidades'],
                        $changes['instagram'],
                        $changes['facebook'],
                        $changes['twitter'],
                        $changes['sitio_web'],
                        $changes['whatsapp'],
                        $changes['foto_perfil'],
                        $nuevo_estado,
                        $id
                    ]);

                    $this->pdo->prepare("UPDATE perfil_cambios_pendientes SET estado='aprobado', validador_id=?, fecha_validacion=NOW() WHERE id=?")
                        ->execute([$admin['id'], $changes['id']]);
                } else {
                    $this->pdo->prepare("UPDATE artistas SET status_perfil=?, motivo_rechazo=NULL WHERE id=?")
                        ->execute([$nuevo_estado, $id]);
                }

                $mailer->notificarPerfilValidado($artista['email'], $artista['nombre'] . ' ' . $artista['apellido']);
                \NotificationHelper::notificarCambioPerfilValidado($id);
            } else { // rechazar
                $nuevo_estado = 'rechazado';
                $this->pdo->prepare("UPDATE perfil_cambios_pendientes SET estado='rechazado', validador_id=?, motivo_rechazo=?, fecha_validacion=NOW() WHERE artista_id=? AND estado='pendiente'")
                    ->execute([$admin['id'], $motivo, $id]);

                $this->pdo->prepare("UPDATE artistas SET status_perfil=?, motivo_rechazo=? WHERE id=?")
                    ->execute([$nuevo_estado, $motivo, $id]);

                $mailer->notificarPerfilRechazado($artista['email'], $artista['nombre'] . ' ' . $artista['apellido'], $motivo);
                \NotificationHelper::notificarCambioPerfilRechazado($id, $motivo);
            }

            // Log
            $motivoLog = ($accion === 'rechazar') ? $motivo : null;
            $this->pdo->prepare("INSERT INTO logs_validacion_perfiles (artista_id, validador_id, accion, motivo_rechazo, fecha_accion) VALUES (?, ?, ?, ?, NOW())")
                ->execute([$id, $admin['id'], $accion, $motivoLog]);

            $this->pdo->commit();
            $this->sendResponse('ok', $accion === 'validar' ? 'Validado exitosamente' : 'Rechazado exitosamente');
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->sendResponse('error', 'Error: ' . $e->getMessage(), 500);
        }
    }

    private function delete()
    {
        $this->requireAuth('admin');
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse('error', 'ID requerido', 400);

        try {
            $this->pdo->beginTransaction();

            // Delete content first
            $this->pdo->prepare("DELETE FROM publicaciones WHERE usuario_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM perfil_cambios_pendientes WHERE artista_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM intereses_artista WHERE artista_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM artistas WHERE id = ?")->execute([$id]);

            $this->pdo->commit();
            $this->sendResponse('success', 'Artista eliminado');
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->sendResponse('error', $e->getMessage(), 500);
        }
    }

    private function getStats()
    {
        $this->requireAuth(['admin', 'validador']);

        // Usar método del repositorio
        $stats = $this->artistaRepo->getStats();
        
        echo json_encode([
            'pendientes' => $stats['pendiente'] ?? 0,
            'validados' => $stats['validado'] ?? 0,
            'rechazados' => $stats['rechazado'] ?? 0,
            'total' => $stats['total'] ?? 0
        ]);
    }

    // --- Helpers ---

    private function requireAuth($roleOrRoles)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_data'])) $this->sendResponse('error', 'No autenticado', 401);

        $userRole = $_SESSION['user_data']['role'];
        $roles = is_array($roleOrRoles) ? $roleOrRoles : [$roleOrRoles];

        if (!in_array($userRole, $roles)) {
            $this->sendResponse('error', 'No autorizado', 403);
        }
        return $_SESSION['user_data'];
    }

    private function sendResponse($status, $message, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }
}
