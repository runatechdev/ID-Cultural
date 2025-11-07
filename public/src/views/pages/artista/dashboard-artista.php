<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad: solo usuarios con rol 'artista' pueden ver esto ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    // Si no es un artista, lo redirigimos a la página de login o al index
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Panel de Artista";
// Reutilizamos los CSS de los otros paneles para mantener la coherencia
$specific_css_files = ['dashboard.css', 'dashboard-adm.css'];
$nombre_artista = $_SESSION['user_data']['nombre'] ?? 'Artista';

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>

<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="panel-gestion-header mb-4">
                    <h1>Bienvenido, <strong><?php echo htmlspecialchars($nombre_artista); ?></strong></h1>
                    <p class="lead">Desde aquí puedes gestionar tu perfil, tus obras y enviarlas a validación.</p>
                </div>

                <!-- Menú de Acciones del Artista -->
                <div class="card">
                    <div class="card-header">
                        <h4>Acciones Principales</h4>
                        <p class="mb-0 text-muted small">Crea y administra tus publicaciones para el wiki de artistas.</p>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-menu">
                            <a href="<?php echo BASE_URL; ?>src/views/pages/artista/editar_perfil.php>" class="dashboard-item" data-bs-toggle="tooltip" title="Crea un nuevo borrador para tu perfil, una obra o un evento.">
                                <i class="bi bi-person-bounding-box "> </i> Editar Perfil
                            </a>
                            <a href="<?php echo BASE_URL; ?>src/views/pages/artista/crear-borrador.php" class="dashboard-item" data-bs-toggle="tooltip" title="Crea un nuevo borrador para tu perfil, una obra o un evento.">
                                <i class="bi bi-plus-square-fill dashboard-icon"></i> Crear Borrador
                            </a>
                            <a href="<?php echo BASE_URL; ?>src/views/pages/artista/mis-borradores.php" class="dashboard-item" data-bs-toggle="tooltip" title="Revisa y edita los borradores que has guardado. (Próximamente)">
                                <i class="bi bi-journal-richtext dashboard-icon"></i> Ver Mis Borradores
                            </a>
                            <a href="<?php echo BASE_URL; ?>src/views/pages/artista/solicitudes-enviadas.php" class="dashboard-item" data-bs-toggle="tooltip" title="Consulta el estado de las publicaciones que has enviado a validación.">
                                <i class="bi bi-send-check-fill dashboard-icon"></i> Solicitudes Enviadas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para inicializar los tooltips en esta página
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
</body>

</html>