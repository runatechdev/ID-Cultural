<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Validador o Admin ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['validador', 'admin'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Artistas Pendientes";
$specific_css_files = ['dashboard.css', 'abm_usuarios.css'];

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="mb-0">Artistas Pendientes de Validación</h1>
                        <p class="lead">Revisa la información de cada artista y aprueba o rechaza su cuenta.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/validador/panel_validador.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <!-- ===== CORRECCIÓN AQUÍ ===== -->
                                <th class="ps-3">Nombre del Artista</th>
                                <th>Municipio</th>
                                <th>Fecha de Envío</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-artistas-pendientes-body">
                            <!-- Las filas se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/gestion_pendientes.js"></script>
</body>
</html>
