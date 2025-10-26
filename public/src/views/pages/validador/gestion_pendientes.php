<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Validador o Admin ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['validador', 'admin'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Gestión de Obras Pendientes";
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
                        <h1 class="mb-0">Obras Pendientes de Validación</h1>
                        <p class="lead mb-0">Revisa y valida las obras enviadas por los artistas</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/validador/panel_validador.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="filtro-busqueda" class="form-control" placeholder="Buscar por artista o título...">
                    </div>
                    <div class="col-md-3">
                        <select id="filtro-categoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            <option value="musica">Música</option>
                            <option value="artes_visuales">Artes Visuales</option>
                            <option value="literatura">Literatura</option>
                            <option value="teatro">Teatro</option>
                            <option value="danza">Danza</option>
                            <option value="fotografia">Fotografía</option>
                            <option value="artesania">Artesanía</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filtro-municipio" class="form-select">
                            <option value="">Todos los municipios</option>
                            <!-- Se llenan dinámicamente -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100" onclick="aplicarFiltros()">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                    </div>
                </div>

                <!-- Tabla de Obras Pendientes -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 35%;">Obra y Artista</th>
                                <th style="width: 15%;">Categoría</th>
                                <th style="width: 20%;">Ubicación</th>
                                <th style="width: 15%;">Fecha de Envío</th>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-obras-pendientes-body">
                            <!-- Las filas se cargarán aquí dinámicamente con JavaScript -->
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Cargando obras pendientes...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Paginación" class="mt-3">
                    <ul class="pagination justify-content-center" id="paginacion">
                        <!-- Se genera dinámicamente -->
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/gestion_pendientes.js"></script>
</body>
</html>