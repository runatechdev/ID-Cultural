<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Editor (y Admin) ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Gestión de Artistas Famosos";
$specific_css_files = ['dashboard.css', 'dashboard-adm.css'];
$nombre_editor = $_SESSION['user_data']['nombre'] ?? 'Editor';

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="panel-gestion-header mb-4">
                    <h1>Gestión de Artistas Famosos</h1>
                    <p class="lead">Administra los artistas famosos que aparecen en la sección de Wiki.</p>
                </div>

                <!-- Botón para agregar nuevo artista -->
                <div class="mb-4">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalArtista">
                        <i class="bi bi-plus-circle"></i> Agregar Artista Famoso
                    </button>
                </div>

                <!-- Tabla de artistas -->
                <div class="table-responsive">
                    <table class="table table-hover" id="tablArtistas">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagen</th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Especialidad</th>
                                <th>Reconocimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyArtistas">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-hourglass-split"></i> Cargando artistas...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para agregar/editar artista -->
    <div class="modal fade" id="modalArtista" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Nuevo Artista Famoso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formArtista" enctype="multipart/form-data">
                        <input type="hidden" id="inputIdArtista" name="id" value="">
                        
                        <div class="mb-3">
                            <label for="inputImagen" class="form-label">Imagen del Artista</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="inputImagen" name="imagen" accept="image/*">
                            </div>
                            <small class="form-text text-muted">Formatos: JPG, PNG. Tamaño máximo: 5MB</small>
                            <div id="imagenPreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="inputNombre" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="inputNombre" name="nombre_completo" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputCategoria" class="form-label">Categoría *</label>
                            <select class="form-select" id="inputCategoria" name="categoria" required>
                                <option value="">Seleccionar categoría...</option>
                                <option value="Música">🎤 Música</option>
                                <option value="Literatura">📚 Literatura</option>
                                <option value="Artes Plásticas">🎨 Artes Plásticas</option>
                                <option value="Danza">💃 Danza</option>
                                <option value="Teatro">🎭 Teatro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputSubcategoria" class="form-label">Subcategoría o Especialidad *</label>
                            <input type="text" class="form-control" id="inputSubcategoria" name="subcategoria" placeholder="Ej: Folklore, Narrativa, Pintura, Danza Contemporánea, Comedia" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputBiografia" class="form-label">Biografía *</label>
                            <textarea class="form-control" id="inputBiografia" name="biografia" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="inputBadge" class="form-label">Tipo de Reconocimiento *</label>
                            <select class="form-select" id="inputBadge" name="badge" required>
                                <option value="">Seleccionar...</option>
                                <option value="Leyenda">Leyenda</option>
                                <option value="Regional">Regional</option>
                                <option value="Actual">Artista Actual</option>
                                <option value="Clásico">Clásico</option>
                                <option value="Intelectual">Intelectual</option>
                                <option value="Legendario">Legendario</option>
                                <option value="Maestro">Maestro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputLogros" class="form-label">Logros y Premios</label>
                            <textarea class="form-control" id="inputLogros" name="logros" rows="3" placeholder="Separar con comas: Grammy Latino, UNESCO"></textarea>
                        </div>

                        <div class="form-text text-muted">* Campos obligatorios</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarArtista">Guardar Artista</button>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // URLs de la API
        const apiBase = '<?php echo BASE_URL; ?>api/artistas_famosos.php';
        const modalArtista = new bootstrap.Modal(document.getElementById('modalArtista'));

        // Cargar artistas al iniciar
        document.addEventListener('DOMContentLoaded', cargarArtistas);

        // Botón para guardar
        document.getElementById('btnGuardarArtista').addEventListener('click', guardarArtista);

        // Preview de imagen
        document.getElementById('inputImagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewDiv = document.getElementById('imagenPreview');
                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = event.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Modal - limpiar formulario solo cuando se abre para crear nuevo
        document.getElementById('modalArtista').addEventListener('hidden.bs.modal', function(e) {
            limpiarFormulario();
        });

        async function cargarArtistas() {
            try {
                const response = await axios.get(apiBase);
                const artistas = response.data.data || [];
                
                const tbody = document.getElementById('tbodyArtistas');
                tbody.innerHTML = '';

                if (artistas.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay artistas registrados
                            </td>
                        </tr>
                    `;
                    return;
                }

                artistas.forEach(artista => {
                    const row = document.createElement('tr');
                    const emojiPorCategoria = {
                        'Música': '🎤',
                        'Literatura': '📚',
                        'Artes Plásticas': '🎨',
                        'Danza': '💃',
                        'Teatro': '🎭'
                    };
                    const emoji = emojiPorCategoria[artista.categoria] || '⭐';
                    const imagenHtml = artista.imagen 
                        ? `<img src="<?php echo BASE_URL; ?>uploads/artistas_famosos/${artista.imagen}" alt="${artista.nombre_completo}" style="max-width: 60px; max-height: 60px; border-radius: 8px; object-fit: cover;">` 
                        : `<span class="text-muted text-center" style="display: block;">-</span>`;
                    
                    row.innerHTML = `
                        <td>${imagenHtml}</td>
                        <td>${artista.id}</td>
                        <td><strong>${artista.nombre_completo}</strong></td>
                        <td><span class="badge bg-info">${emoji} ${artista.categoria}</span></td>
                        <td>${artista.subcategoria}</td>
                        <td><span class="badge bg-secondary">${artista.badge}</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-editar" data-id="${artista.id}">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${artista.id}">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Agregar listeners a botones
                document.querySelectorAll('.btn-editar').forEach(btn => {
                    btn.addEventListener('click', () => editarArtista(btn.dataset.id));
                });
                document.querySelectorAll('.btn-eliminar').forEach(btn => {
                    btn.addEventListener('click', () => eliminarArtista(btn.dataset.id));
                });
            } catch (error) {
                console.error('Error cargando artistas:', error);
                const tbody = document.getElementById('tbodyArtistas');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            <i class="bi bi-exclamation-triangle"></i> Error al cargar artistas
                        </td>
                    </tr>
                `;
            }
        }

        async function editarArtista(id) {
            try {
                const response = await axios.get(apiBase + '?id=' + id);
                const artista = response.data.data;

                document.getElementById('inputIdArtista').value = artista.id;
                document.getElementById('inputNombre').value = artista.nombre_completo;
                document.getElementById('inputCategoria').value = artista.categoria;
                document.getElementById('inputSubcategoria').value = artista.subcategoria;
                document.getElementById('inputBiografia').value = artista.biografia;
                document.getElementById('inputBadge').value = artista.badge;
                document.getElementById('inputLogros').value = artista.logros || '';
                document.getElementById('modalTitulo').textContent = 'Editar Artista Famoso';

                // Mostrar imagen actual si existe
                if (artista.imagen) {
                    const previewDiv = document.getElementById('imagenPreview');
                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = '<?php echo BASE_URL; ?>uploads/' + artista.imagen;
                    previewDiv.style.display = 'block';
                }

                modalArtista.show();
            } catch (error) {
                alert('Error al cargar el artista: ' + (error.response?.data?.message || error.message));
            }
        }

        async function guardarArtista() {
            const form = document.getElementById('formArtista');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const btnGuardar = document.getElementById('btnGuardarArtista');
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';

            try {
                // Crear FormData manualmente, solo con campos que tengan valor
                const formData = new FormData();
                const id = document.getElementById('inputIdArtista').value;
                
                // Agregar ID (siempre)
                formData.append('id', id);
                
                // Agregar solo campos con valor
                const nombre = document.getElementById('inputNombre').value.trim();
                if (nombre) formData.append('nombre_completo', nombre);
                
                const categoria = document.getElementById('inputCategoria').value.trim();
                if (categoria) formData.append('categoria', categoria);
                
                const subcategoria = document.getElementById('inputSubcategoria').value.trim();
                if (subcategoria) formData.append('subcategoria', subcategoria);
                
                const biografia = document.getElementById('inputBiografia').value.trim();
                if (biografia) formData.append('biografia', biografia);
                
                const badge = document.getElementById('inputBadge').value.trim();
                if (badge) formData.append('badge', badge);
                
                const logros = document.getElementById('inputLogros').value.trim();
                if (logros) formData.append('logros', logros);
                
                // Agregar imagen solo si se seleccionó
                const imagenInput = document.getElementById('inputImagen');
                if (imagenInput.files.length > 0) {
                    formData.append('imagen', imagenInput.files[0]);
                }
                
                const url = id ? apiBase + '?id=' + id : apiBase;
                const method = 'POST';

                // Usar fetch con credentials
                const response = await fetch(url, {
                    method: method,
                    credentials: 'include',
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Artista guardado correctamente');
                    modalArtista.hide();
                    limpiarFormulario();
                    cargarArtistas();
                } else {
                    alert('Error al guardar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                alert('Error al guardar: ' + error.message);
                console.error('Error:', error);
            } finally {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="bi bi-check-circle"></i> Guardar Artista';
            }
        }

        async function eliminarArtista(id) {
            if (!confirm('¿Está seguro que desea eliminar este artista?')) return;

            try {
                await axios.delete(apiBase + '?id=' + id);
                alert('Artista eliminado correctamente');
                cargarArtistas();
            } catch (error) {
                alert('Error al eliminar: ' + (error.response?.data?.message || error.message));
            }
        }

        function limpiarFormulario() {
            document.getElementById('formArtista').reset();
            document.getElementById('inputIdArtista').value = '';
            document.getElementById('modalTitulo').textContent = 'Nuevo Artista Famoso';
            document.getElementById('imagenPreview').style.display = 'none';
        }
    </script>
</body>
</html>
