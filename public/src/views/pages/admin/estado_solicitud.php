<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Admin o Validador ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Validar Cuentas de Artistas";
$specific_css_files = ['dashboard.css', 'estado_solicitud.css']; // Reutilizamos los mismos estilos

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<body>

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <!-- ===== INICIO DEL CONTENEDOR WRAPPER ===== -->
    <div class="dashboard-wrapper">
        <main class="container my-5">
            <!-- ===== INICIO DEL CONTENEDOR PRINCIPAL UNIFICADO ===== -->
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="mb-0">Validación de Artistas</h1>
                            <p class="lead">Revisa las publicaciones pendientes y apruébalas o recházalas.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>src/views/pages/admin/dashboard-adm.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al Panel
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Artista</th>
                                    <th>Título de la Publicación</th>
                                    <th>Fecha de Envío</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-solicitudes-body">
                                <!-- Las filas se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ===== FIN DEL CONTENEDOR PRINCIPAL UNIFICADO ===== -->
        </main>
    </div>
    <!-- ===== FIN DEL CONTENEDOR WRAPPER ===== -->

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/estado_solicitud.js"></script>
</body>
</html>
