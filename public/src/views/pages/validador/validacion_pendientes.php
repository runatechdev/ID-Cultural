<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Admin/Validador ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['admin', 'validador'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Validación de Obras";
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
                        <h1 class="mb-0"><i class="bi bi-clipboard-check"></i> Validación de Obras</h1>
                        <p class="lead">Aprueba o rechaza las obras enviadas por los usuarios.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/<?php echo $_SESSION['user_data']['role']; ?>/dashboard-<?php echo substr($_SESSION['user_data']['role'], 0, 3); ?>.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="filtro-estado" class="form-select">
                            <option value="pendiente_validacion" selected>⏳ Pendientes de validación</option>
                            <option value="validado">✅ Validadas</option>
                            <option value="rechazado">❌ Rechazadas</option>
                            <option value="todos">Todas las obras</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filtro-categoria" class="form-select">
                            <option value="todos">Todas las categorías</option>
                            <option value="musica">🎵 Música</option>
                            <option value="artes_visuales">🎨 Artes Visuales</option>
                            <option value="literatura">📚 Literatura</option>
                            <option value="artes_escenicas">🎭 Artes Escénicas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="buscar-usuario" class="form-control" placeholder="🔍 Buscar por usuario...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" width="5%">ID</th>
                                <th width="25%">Obra</th>
                                <th width="20%">Usuario/Artista</th>
                                <th width="15%">Categoría</th>
                                <th class="text-center" width="15%">Estado</th>
                                <th class="text-center" width="20%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-publicaciones-body">
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
    <script src="<?php echo BASE_URL; ?>static/js/validacion_obras.js"></script>
</body>
</html>