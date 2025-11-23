<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Gestionar Artistas";
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
                        <h1 class="mb-0">Gestión de Artistas</h1>
                        <p class="lead">Ver, modificar y eliminar artistas registrados en la plataforma.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/admin/dashboard-adm.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <!-- Alerta informativa -->
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>¡Atención!</strong> Al eliminar un artista, se eliminarán también todas sus obras publicadas. Esta acción no se puede deshacer.
                    </div>
                </div>

                <!-- Filtros de búsqueda -->
                <div class="row g-3 mb-4 p-3 bg-light border rounded">
                    <div class="col-md-4">
                        <label for="filter-status" class="form-label">Estado del Artista</label>
                        <select id="filter-status" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="validado">Validados</option>
                            <option value="rechazado">Rechazados</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter-search" class="form-label">Buscar por nombre</label>
                        <input type="text" id="filter-search" class="form-control" placeholder="Nombre del artista...">
                    </div>
                    <div class="col-md-4">
                        <label for="filter-disciplina" class="form-label">Categoría</label>
                        <input type="text" id="filter-disciplina" class="form-control" placeholder="Categoría...">
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary" id="stat-total">0</h3>
                                <p class="text-muted mb-0">Total de Artistas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success" id="stat-validados">0</h3>
                                <p class="text-muted mb-0">Validados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-warning" id="stat-pendientes">0</h3>
                                <p class="text-muted mb-0">Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de artistas -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Obras</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-artistas-body">
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/gestionar_artistas.js"></script>
</body>
</html>
