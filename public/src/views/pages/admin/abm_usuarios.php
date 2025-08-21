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

    <!-- Script para ABM y Tooltips -->
    <script>
        // Variable global para guardar la lista de personal
        let listaPersonal = [];

        // Función para inicializar los tooltips de Bootstrap
        function inicializarTooltips() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }

        // Función para renderizar la tabla
        function renderizarTabla(personal) {
            const tbody = document.getElementById('tabla-usuarios-body');
            tbody.innerHTML = '';

            if (personal.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay personal registrado.</td></tr>';
                return;
            }

            personal.forEach(usuario => {
                let badgeClass = 'bg-secondary';
                if (usuario.role === 'admin') badgeClass = 'bg-primary';
                if (usuario.role === 'editor') badgeClass = 'bg-info';
                if (usuario.role === 'validador') badgeClass = 'bg-success';

                const fila = `
              <tr id="user-row-${usuario.id}">
                <td><strong>${usuario.nombre}</strong></td>
                <td>${usuario.email}</td>
                <td><span class="badge ${badgeClass}">${usuario.role}</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="${usuario.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                  <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${usuario.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                </td>
              </tr>
            `;
                tbody.innerHTML += fila;
            });

            inicializarTooltips();
        }

        // Función para obtener y mostrar el personal
        async function cargarPersonal() {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>api/get_personal.php');
                if (!response.ok) throw new Error('Error en la respuesta de la red.');
                listaPersonal = await response.json();
                renderizarTabla(listaPersonal);
            } catch (error) {
                document.getElementById('tabla-usuarios-body').innerHTML = `<tr><td colspan="4" class="text-center text-danger">Error al cargar los datos.</td></tr>`;
            }
        }

        // Lógica para añadir un nuevo usuario
        document.getElementById('form-add-usuario').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('nombre', document.getElementById('add-nombre').value);
            formData.append('email', document.getElementById('add-email').value);
            formData.append('password', document.getElementById('add-password').value);
            formData.append('rol', document.getElementById('add-rol').value);

            try {
                const response = await fetch('<?php echo BASE_URL; ?>api/add_personal.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (response.ok && result.status === 'ok') {
                    Swal.fire('¡Éxito!', result.message, 'success');
                    document.getElementById('form-add-usuario').reset();
                    const accordion = bootstrap.Collapse.getInstance(document.getElementById('collapseOne'));
                    if (accordion) accordion.hide();
                    cargarPersonal();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
            }
        });

        // Lógica para el buscador
        document.getElementById('buscador').addEventListener('keyup', (e) => {
            const texto = e.target.value.toLowerCase();
            const personalFiltrado = listaPersonal.filter(usuario => {
                return usuario.nombre.toLowerCase().includes(texto) || usuario.email.toLowerCase().includes(texto);
            });
            renderizarTabla(personalFiltrado);
        });

        // Lógica para eliminar y editar (usando delegación de eventos)
        document.getElementById('tabla-usuarios-body').addEventListener('click', async (e) => {
            const deleteBtn = e.target.closest('.btn-delete');
            const editBtn = e.target.closest('.btn-edit');

            if (deleteBtn) {
                const userId = deleteBtn.dataset.id;
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('id', userId);
                        const response = await fetch('<?php echo BASE_URL; ?>api/delete_personal.php', {
                            method: 'POST',
                            body: formData
                        });
                        const res = await response.json();
                        if (response.ok && res.status === 'ok') {
                            Swal.fire('¡Eliminado!', res.message, 'success');
                            cargarPersonal();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    }
                });
            }

            if (editBtn) {
                const userId = editBtn.dataset.id;
                const usuario = listaPersonal.find(u => u.id == userId);
                if (usuario) {
                    document.getElementById('edit-id').value = usuario.id;
                    document.getElementById('edit-nombre').value = usuario.nombre;
                    document.getElementById('edit-email').value = usuario.email;
                    document.getElementById('edit-rol').value = usuario.role;
                    document.getElementById('edit-password').value = '';

                    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    editModal.show();
                }
            }
        });

        // Lógica para guardar los cambios del modal de edición
        document.getElementById('save-edit-button').addEventListener('click', async () => {
            // Aquí irá la lógica para llamar a una API 'update_personal.php'
            // Por ahora, solo cerramos el modal y mostramos un mensaje.
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            editModal.hide();
            Swal.fire('¡Guardado!', 'Los cambios se han guardado (simulación).', 'success');
            // En una implementación real, aquí llamarías a cargarPersonal() después de la respuesta exitosa de la API.
        });

        // Ejecutar las funciones cuando la página haya cargado
        document.addEventListener('DOMContentLoaded', () => {
            cargarPersonal();
        });
    </script>
</body>

</html>