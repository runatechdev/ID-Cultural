<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Admin ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Gestión de Personal";
$specific_css_files = ['dashboard.css', 'abm_usuarios.css'];

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">

        <!-- ===== INICIO DEL CONTENEDOR PRINCIPAL UNIFICADO ===== -->
        <div class="card">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="mb-0">Gestión de Personal</h1>
                        <p class="lead">Añade, edita o elimina las cuentas del equipo del sitio.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/admin/dashboard-adm.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <!-- Acordeón para el Formulario de "Añadir Usuario" -->
                <div class="accordion mb-4" id="accordionAnadirUsuario">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="bi bi-plus-circle-fill me-2"></i> Añadir Nuevo Personal
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionAnadirUsuario">
                            <div class="accordion-body">
                                <form id="form-add-usuario">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="add-nombre" class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control" id="add-nombre" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="add-email" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="add-email" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="add-password" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" id="add-password" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="add-rol" class="form-label">Rol</label>
                                            <select class="form-select" id="add-rol" required>
                                                <option value="" selected disabled>Seleccionar...</option>
                                                <option value="editor">Editor</option>
                                                <option value="validador">Validador</option>
                                                <option value="admin">Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de la Tabla de Usuarios -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Personal Registrado</h5>
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="buscador" class="form-control ps-5" placeholder="Buscar...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-usuarios-body">
                            <!-- Filas cargadas por JS -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <nav aria-label="Paginación de usuarios">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
         <!-- ===== FIN DEL CONTENEDOR PRINCIPAL UNIFICADO ===== -->

    </main>

    <!-- Modal para Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Personal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-usuario">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="edit-nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rol" class="form-label">Rol</label>
                            <select class="form-select" id="edit-rol" required>
                                <option value="editor">Editor</option>
                                <option value="validador">Validador</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-password" class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" class="form-control" id="edit-password">
                            <div class="form-text">Dejar en blanco para no cambiar la contraseña.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="save-edit-button">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/abm_usuarios.js"></script>
</body>
</html>
