<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../backend/config/connection.php';

// Obtener ID del artista desde la URL
$artista_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Si no hay ID, mostrar error
if (!$artista_id) {
    header('Location: /index.php');
    exit;
}

// Consultar artista
try {
    // Obtener datos del artista
    $stmt = $pdo->prepare("
        SELECT a.*, 
               (SELECT COUNT(*) FROM publicaciones WHERE usuario_id = a.id AND estado = 'validado') as obras_validadas,
               (SELECT COUNT(*) FROM publicaciones WHERE usuario_id = a.id) as total_obras
        FROM artistas a 
        WHERE a.id = ?
    ");
    $stmt->execute([$artista_id]);
    $artista = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no existe, mostrar error
    if (!$artista) {
        header('Location: /index.php');
        exit;
    }

    // Verificar permisos de acceso
    $es_propietario = isset($_SESSION['user_data']) && $_SESSION['user_data']['role'] === 'artista' && $_SESSION['user_data']['id'] === $artista_id;
    $es_validado = $artista['status'] === 'validado';

    // Si no es propietario y el perfil no está validado, redirigir
    if (!$es_propietario && !$es_validado) {
        header('Location: /index.php');
        exit;
    }

    // Calcular edad si existe fecha_nacimiento
    $edad = null;
    if (!empty($artista['fecha_nacimiento'])) {
        $fecha_nac = new DateTime($artista['fecha_nacimiento']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nac)->y;
    }

    // Obtener año de registro
    $anio_registro = !empty($artista['fecha_creacion']) ? date('Y', strtotime($artista['fecha_creacion'])) : date('Y');
} catch (PDOException $e) {
    error_log("Error al obtener artista: " . $e->getMessage());
    header('Location: /index.php');
    exit;
}

// Variables para el header
$page_title = "{$artista['nombre']} {$artista['apellido']} - ID Cultural";
$specific_css_files = ['perfil-artista.css'];

include(__DIR__ . '/../components/header.php');
?>

<body class="profile-page">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <!-- Hero Header with Background Image -->
    <div class="hero-header" style="background-image: url('../assets/img/sgo.jpg'); background-position: center; background-size: cover; background-attachment: fixed; min-height: 500px; position: relative; display: flex; align-items: flex-start; justify-content: center; padding-top: 60px;">
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(54, 119, 137, 0.6), rgba(195, 1, 53, 0.4)), linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));"></div>
        <div style="position: relative; z-index: 2; text-align: center; color: white;">
            <h1 class="display-3 fw-bold mb-2">Perfil de Artista</h1>
            <p class="lead fs-5">Explora la trayectoria y obras de nuestros talentos locales</p>
        </div>
    </div>

    <main>
        <!-- Profile Section -->
        <section class="profile-section" style="margin-top: -180px; position: relative; z-index: 10;">
            <div class="container">
                <!-- Profile Card -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="card shadow-lg border-0 rounded-4 overflow-visible">
                            <div class="card-body text-center p-5">
                                <!-- Avatar -->
                                <div class="mb-4 avatar-container">
                                    <img src="<?php echo htmlspecialchars($artista['foto_perfil'] ?? '/static/img/default-avatar.png'); ?>" alt="<?php echo htmlspecialchars($artista['nombre'] . ' ' . $artista['apellido']); ?>"
                                        class="rounded-circle border border-4 border-white shadow"
                                        style="width: 220px; height: 220px; object-fit: cover; margin-top: -110px; background-color: white;">
                                </div>

                                <!-- Name and Title -->
                                <h2 class="h1 fw-bold mb-2">
                                    <?php echo htmlspecialchars($artista['nombre'] . ' ' . $artista['apellido']); ?>
                                    <?php if ($es_validado): ?>
                                        <span class="badge bg-success ms-2" style="font-size: 0.5em; vertical-align: middle;">
                                            <i class="bi bi-patch-check-fill"></i> Perfil Validado
                                        </span>
                                    <?php endif; ?>
                                </h2>
                                <p class="text-muted fs-5 mb-3">
                                    <?php echo htmlspecialchars($artista['especialidades'] ?? $artista['disciplina'] ?? 'Artista'); ?>
                                </p>

                                <!-- Estadísticas del Artista -->
                                <div class="row text-center mb-4">
                                    <div class="col-4">
                                        <div class="p-3">
                                            <h3 class="h2 fw-bold text-primary mb-0"><?php echo $artista['total_obras']; ?></h3>
                                            <small class="text-muted">Obras Totales</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-3">
                                            <h3 class="h2 fw-bold text-success mb-0"><?php echo $artista['obras_validadas']; ?></h3>
                                            <small class="text-muted">Obras Validadas</small>
                                        </div>
                                    </div>
                                    <?php if ($es_propietario): ?>
                                        <div class="col-4">
                                            <div class="p-3">
                                                <h3 class="h2 fw-bold text-warning mb-0"><?php echo $artista['total_obras'] - $artista['obras_validadas']; ?></h3>
                                                <small class="text-muted">Pendientes</small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Social Links -->
                                <div class="social-links mb-4">
                                    <?php if (!empty($artista['instagram'])): ?>
                                        <a href="<?php echo htmlspecialchars($artista['instagram']); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2" title="Instagram" target="_blank">
                                            <i class="bi bi-instagram me-1"></i> Instagram
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($artista['facebook'])): ?>
                                        <a href="<?php echo htmlspecialchars($artista['facebook']); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2" title="Facebook" target="_blank">
                                            <i class="bi bi-facebook me-1"></i> Facebook
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($artista['twitter'])): ?>
                                        <a href="<?php echo htmlspecialchars($artista['twitter']); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2" title="Twitter" target="_blank">
                                            <i class="bi bi-twitter-x me-1"></i> Twitter
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($artista['sitio_web'])): ?>
                                        <a href="<?php echo htmlspecialchars($artista['sitio_web']); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2" title="Sitio Web" target="_blank">
                                            <i class="bi bi-globe me-1"></i> Sitio Web
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Edit Profile Button (solo si es el artista logueado) -->
                                <?php if ($es_propietario): ?>
                                    <a href="<?php echo BASE_URL; ?>src/views/pages/artista/editar_perfil_publico.php" class="btn btn-primary btn-lg rounded-pill px-5 mb-4">
                                        <i class="bi bi-pencil me-2"></i>Editar Perfil
                                    </a>
                                <?php endif; ?>

                                <!-- Información Personal -->
                                <div class="row text-start g-3 mb-4">
                                    <?php if (!empty($artista['especialidades'])): ?>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-palette-fill text-primary fs-4 me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Disciplina</small>
                                                    <strong><?php echo htmlspecialchars($artista['especialidades']); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($artista['municipio']) || !empty($artista['provincia'])): ?>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt-fill text-danger fs-4 me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Ubicación</small>
                                                    <strong><?php echo htmlspecialchars(($artista['municipio'] ?? '') . ', ' . ($artista['provincia'] ?? '')); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($edad): ?>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-heart-fill text-info fs-4 me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Edad</small>
                                                    <strong><?php echo $edad; ?> años</strong>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-check-fill text-success fs-4 me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Miembro desde</small>
                                                <strong><?php echo $anio_registro; ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botones de Acción -->
                                    <?php if (!$es_propietario): ?>
                                        <div class="col-12">
                                            <div class="row g-2">
                                                <?php if (!empty($artista['whatsapp']) || !empty($artista['telefono'])): ?>
                                                    <div class="col-12">
                                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $artista['whatsapp'] ?? $artista['telefono']); ?>"
                                                            class="btn btn-success btn-lg w-100 rounded-pill" target="_blank">
                                                            <i class="bi bi-whatsapp me-2"></i> WhatsApp
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md-6">
                                                    <button class="btn btn-outline-primary w-100 rounded-pill" onclick="compartirPerfil()">
                                                        <i class="bi bi-share me-2"></i> Compartir Perfil
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-outline-danger w-100 rounded-pill" onclick="reportarPerfil()">
                                                        <i class="bi bi-flag me-2"></i> Reportar Perfil
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Description -->
                                <div class="alert alert-light border border-2 border-secondary rounded-3 p-4 text-start">
                                    <h5 class="mb-3"><i class="bi bi-file-text me-2"></i>Biografía</h5>
                                    <?php if (!empty($artista['biografia'])): ?>
                                        <?php if (strlen($artista['biografia']) > 500): ?>
                                            <p class="mb-0" id="biografia-texto">
                                                <?php echo nl2br(htmlspecialchars(substr($artista['biografia'], 0, 500))); ?>
                                                <span id="biografia-extra" style="display: none;">
                                                    <?php echo nl2br(htmlspecialchars(substr($artista['biografia'], 500))); ?>
                                                </span>
                                                <a href="#" id="leer-mas" class="text-primary fw-bold" onclick="toggleBiografia(event)">...Leer más</a>
                                            </p>
                                        <?php else: ?>
                                            <p class="mb-0">
                                                <?php echo nl2br(htmlspecialchars($artista['biografia'])); ?>
                                            </p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">
                                            <em>Este artista aún no ha añadido una biografía.</em>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <ul class="nav nav-tabs nav-fill border-bottom-2 mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="obras-tab" data-bs-toggle="tab" data-bs-target="#obras" type="button" role="tab" aria-controls="obras" aria-selected="true">
                                    <i class="bi bi-camera me-2"></i>Obras
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Obras Tab -->
                            <div class="tab-pane fade show active" id="obras" role="tabpanel" aria-labelledby="obras-tab">
                                <div class="row g-4">
                                    <?php
                                    // Traer obras validadas del artista
                                    try {
                                        $stmt_obras = $pdo->prepare("
                                            SELECT id, titulo, descripcion, categoria, multimedia, 
                                                   fecha_validacion, fecha_creacion, campos_extra
                                            FROM publicaciones 
                                            WHERE usuario_id = ? AND estado = 'validado'
                                            ORDER BY fecha_validacion DESC
                                        ");
                                        $stmt_obras->execute([$artista_id]);
                                        $obras = $stmt_obras->fetchAll(PDO::FETCH_ASSOC);

                                        if (empty($obras)):
                                    ?>
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Este artista aún no tiene obras validadas.
                                                </div>
                                            </div>
                                            <?php
                                        else:
                                            foreach ($obras as $obra):
                                                $multimedia = $obra['multimedia'];
                                                if ($multimedia) {
                                                    $mediaArr = json_decode($multimedia, true);
                                                    if (is_array($mediaArr)) {
                                                        $firstImg = $mediaArr[0];
                                                    } else {
                                                        $firstImg = $multimedia;
                                                    }
                                                    $thumbnail = BASE_URL . ltrim($firstImg, '/');
                                                } else {
                                                    $thumbnail = BASE_URL . 'static/img/paleta-de-pintura.png';
                                                }
                                                // Decodificar campos extra para obtener técnica, dimensiones, etc.
                                                $campos_extra = !empty($obra['campos_extra']) ? json_decode($obra['campos_extra'], true) : [];
                                            ?>
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="card h-100 shadow-sm border-0 overflow-hidden" style="cursor: pointer;"
                                                        data-bs-toggle="modal" data-bs-target="#obraModal"
                                                        onclick='mostrarObra(<?php echo json_encode($obra, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                                        <img src="<?php echo htmlspecialchars($thumbnail); ?>"
                                                            class="card-img-top"
                                                            alt="<?php echo htmlspecialchars($obra['titulo']); ?>"
                                                            style="height: 250px; object-fit: cover;">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo htmlspecialchars($obra['titulo']); ?></h5>
                                                            <p class="card-text text-muted small">
                                                                <?php echo htmlspecialchars(substr($obra['descripcion'], 0, 80) . (strlen($obra['descripcion']) > 80 ? '...' : '')); ?>
                                                            </p>
                                                            <?php if (!empty($obra['categoria'])): ?>
                                                                <span class="badge bg-info"><?php echo htmlspecialchars($obra['categoria']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="card-footer bg-light border-top-0">
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar-check"></i>
                                                                <?php echo date('d/m/Y', strtotime($obra['fecha_validacion'])); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php
                                            endforeach;
                                        endif;
                                    } catch (PDOException $e) {
                                        echo '<div class="col-12"><div class="alert alert-danger">Error al cargar obras</div></div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Colaboraciones Tab -->
                            <div class="tab-pane fade" id="colaboraciones" role="tabpanel" aria-labelledby="colaboraciones-tab">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Colaboración 1" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Colaboración 2" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Colaboración 3" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Colaboración 4" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Favoritos Tab -->
                            <div class="tab-pane fade" id="favoritos" role="tabpanel" aria-labelledby="favoritos-tab">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Favorito 1" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Favorito 2" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Favorito 3" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Favorito 4" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="overflow-hidden rounded-3">
                                            <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" class="img-fluid w-100" alt="Favorito 5" style="aspect-ratio: 1; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal para ver obra completa -->
    <style>
        /* Estilos específicos para el modal de obra */
        #obraModal .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        #obraModal .modal-header {
            background: linear-gradient(135deg, var(--color-primario, #367789) 0%, #2a5a69 100%);
            color: white;
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem 2rem;
            border: none;
        }

        #obraModal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #obraModal .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        #obraModal .btn-close:hover {
            opacity: 1;
        }

        #obraModal .modal-body {
            padding: 2rem;
            background: #f8f9fa;
        }

        .obra-imagen-container {
            position: relative;
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .obra-imagen-container img {
            border-radius: 0.75rem;
            max-height: 500px;
            object-fit: contain;
            width: 100%;
            display: block;
        }

        .obra-info-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .obra-titulo-principal {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .obra-categoria-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, #0dcaf0 0%, #0ba6c4 100%);
            color: white;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(13, 202, 240, 0.3);
        }

        .obra-seccion {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .obra-seccion:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .obra-seccion-titulo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: var(--color-primario, #367789);
            font-size: 1rem;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .obra-seccion-titulo i {
            font-size: 1.1rem;
        }

        .obra-seccion-contenido {
            color: #495057;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .obra-metadatos {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .obra-metadato-item {
            text-align: center;
            padding: 0.75rem;
        }

        .obra-metadato-label {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .obra-metadato-valor {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            color: #212529;
        }

        .obra-metadato-icono {
            font-size: 1.5rem;
            color: var(--color-primario, #367789);
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            #obraModal .modal-body {
                padding: 1rem;
            }

            .obra-info-card {
                padding: 1.25rem;
                margin-top: 1rem;
            }

            .obra-titulo-principal {
                font-size: 1.35rem;
            }

            .obra-imagen-container {
                margin-bottom: 1rem;
            }
        }
    </style>

    <!-- Modal para ver obra completa -->
    <div class="modal fade" id="obraModal" tabindex="-1" aria-labelledby="obraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="obraModalLabel">
                        <i class="bi bi-palette-fill"></i>
                        Detalle de Obra
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Columna de imagen -->
                        <div class="col-lg-7 col-md-12">
                            <div class="obra-imagen-container">
                                <img id="obraImagen" src="" alt="" loading="lazy">
                            </div>
                        </div>

                        <!-- Columna de información -->
                        <div class="col-lg-5 col-md-12">
                            <div class="obra-info-card">
                                <!-- Título y categoría -->
                                <h3 id="obraTitulo" class="obra-titulo-principal"></h3>
                                <span id="obraCategoria" class="obra-categoria-badge">
                                    <i class="bi bi-tag-fill"></i>
                                    <span id="obraCategoriaTexto"></span>
                                </span>

                                <!-- Descripción -->
                                <div class="obra-seccion">
                                    <div class="obra-seccion-titulo">
                                        <i class="bi bi-card-text"></i>
                                        Descripción
                                    </div>
                                    <p id="obraDescripcion" class="obra-seccion-contenido"></p>
                                </div>

                                <!-- Técnica (condicional) -->
                                <div class="obra-seccion" id="obraTecnicaContainer" style="display: none;">
                                    <div class="obra-seccion-titulo">
                                        <i class="bi bi-brush-fill"></i>
                                        Técnica Utilizada
                                    </div>
                                    <p id="obraTecnica" class="obra-seccion-contenido"></p>
                                </div>

                                <!-- Dimensiones (condicional) -->
                                <div class="obra-seccion" id="obraDimensionesContainer" style="display: none;">
                                    <div class="obra-seccion-titulo">
                                        <i class="bi bi-rulers"></i>
                                        Dimensiones
                                    </div>
                                    <p id="obraDimensiones" class="obra-seccion-contenido"></p>
                                </div>

                                <!-- Material (condicional) -->
                                <div class="obra-seccion" id="obraMaterialContainer" style="display: none;">
                                    <div class="obra-seccion-titulo">
                                        <i class="bi bi-box-seam"></i>
                                        Material
                                    </div>
                                    <p id="obraMaterial" class="obra-seccion-contenido"></p>
                                </div>

                                <!-- Metadatos (fechas) -->
                                <div class="obra-metadatos">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="obra-metadato-item">
                                                <i class="bi bi-calendar-plus obra-metadato-icono"></i>
                                                <span class="obra-metadato-label">Creada el</span>
                                                <span class="obra-metadato-valor" id="obraFechaCreacion">-</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="obra-metadato-item">
                                                <i class="bi bi-calendar-check obra-metadato-icono"></i>
                                                <span class="obra-metadato-label">Validada el</span>
                                                <span class="obra-metadato-valor" id="obraFechaValidacion">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function mostrarObra(obra) {
            console.log('Obra recibida:', obra);

            // Información básica
            const titulo = obra.titulo || 'Sin título';
            const descripcion = obra.descripcion || 'Sin descripción disponible.';
            const categoria = obra.categoria || 'Sin categoría';

            document.getElementById('obraTitulo').textContent = titulo;
            document.getElementById('obraDescripcion').textContent = descripcion;
            document.getElementById('obraCategoriaTexto').textContent = categoria;

            // ===== CORRECCIÓN: Parsear multimedia correctamente =====
            let imagenSrc = BASE_URL + 'static/img/paleta-de-pintura.png'; // Fallback por defecto

            if (obra.multimedia) {
                try {
                    // Si multimedia es un string JSON, parsearlo
                    let mediaArray = typeof obra.multimedia === 'string' ?
                        JSON.parse(obra.multimedia) :
                        obra.multimedia;

                    // Si es un array, tomar la primera imagen
                    if (Array.isArray(mediaArray) && mediaArray.length > 0) {
                        let primeraImagen = mediaArray[0];
                        // Limpiar slashes y construir URL correcta
                        primeraImagen = primeraImagen.replace(/^\/+/, ''); // Eliminar slashes al inicio
                        imagenSrc = BASE_URL + primeraImagen;
                    }
                    // Si es string directo (una sola imagen)
                    else if (typeof mediaArray === 'string') {
                        let imagen = mediaArray.replace(/^\/+/, '');
                        imagenSrc = BASE_URL + imagen;
                    }
                } catch (e) {
                    console.error('Error parseando multimedia:', e);
                    // Mantener fallback
                }
            }

            const imgElement = document.getElementById('obraImagen');
            imgElement.src = imagenSrc;
            imgElement.alt = titulo;

            // Manejo de error de imagen
            imgElement.onerror = function() {
                console.error('Error cargando imagen:', this.src);
                this.src = BASE_URL + 'static/img/paleta-de-pintura.png';
                this.alt = 'Imagen no disponible';
            };

            // Formatear y mostrar fechas
            const formatearFecha = (fecha) => {
                if (!fecha) return 'No disponible';
                try {
                    const date = new Date(fecha);
                    return date.toLocaleDateString('es-AR', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    return 'Fecha inválida';
                }
            };

            document.getElementById('obraFechaCreacion').textContent = formatearFecha(obra.fecha_creacion);
            document.getElementById('obraFechaValidacion').textContent = formatearFecha(obra.fecha_validacion);

            // Procesar campos extra
            let camposExtra = {};
            if (obra.campos_extra) {
                try {
                    camposExtra = typeof obra.campos_extra === 'string' ?
                        JSON.parse(obra.campos_extra) :
                        obra.campos_extra;
                } catch (e) {
                    console.error('Error parsing campos_extra:', e);
                }
            }

            // Helper para mostrar/ocultar secciones
            const mostrarSeccion = (containerId, contentId, valor) => {
                const container = document.getElementById(containerId);
                const content = document.getElementById(contentId);

                if (valor && valor.trim() !== '') {
                    content.textContent = valor;
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                }
            };

            // Técnica
            mostrarSeccion(
                'obraTecnicaContainer',
                'obraTecnica',
                camposExtra.tecnica || camposExtra.técnica
            );

            // Dimensiones
            mostrarSeccion(
                'obraDimensionesContainer',
                'obraDimensiones',
                camposExtra.dimensiones
            );

            // Material
            mostrarSeccion(
                'obraMaterialContainer',
                'obraMaterial',
                camposExtra.material
            );
        }

        function toggleBiografia(event) {
            event.preventDefault();
            const extra = document.getElementById('biografia-extra');
            const link = document.getElementById('leer-mas');

            if (extra.style.display === 'none') {
                extra.style.display = 'inline';
                link.textContent = ' Leer menos';
            } else {
                extra.style.display = 'none';
                link.textContent = '...Leer más';
            }
        }

        function compartirPerfil() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: copiar al portapapeles
                navigator.clipboard.writeText(window.location.href).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Enlace copiado!',
                        text: 'El enlace del perfil se copió al portapapeles',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }
        }

        function reportarPerfil() {
            Swal.fire({
                title: 'Reportar Perfil',
                input: 'textarea',
                inputLabel: 'Motivo del reporte',
                inputPlaceholder: 'Describe el problema con este perfil...',
                showCancelButton: true,
                confirmButtonText: 'Enviar Reporte',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes proporcionar un motivo';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí puedes enviar el reporte a una API
                    Swal.fire({
                        icon: 'success',
                        title: 'Reporte Enviado',
                        text: 'Gracias por tu reporte. Lo revisaremos pronto.',
                        timer: 2000
                    });
                }
            });
        }
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/perfil-artista.js"></script>
</body>

</html>