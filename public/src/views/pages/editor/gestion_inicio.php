<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad ---
if (!isset($_SESSION['user_data']) || !in_array($_SESSION['user_data']['role'], ['editor', 'admin'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Editar Página Principal";
$specific_css_files = ['dashboard.css'];

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
                        <h1 class="mb-0">Editar Página Principal</h1>
                        <p class="lead">Modifica los textos y las imágenes que se muestran en la página de inicio.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/editor/panel_editor.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <form id="form-edit-inicio">
                    <!-- Sección de Bienvenida -->
                    <h4 class="mb-3">Sección de Bienvenida</h4>
                    <div class="mb-3">
                        <label for="welcome_title" class="form-label">Título Principal</label>
                        <input type="text" class="form-control" id="welcome_title">
                    </div>
                    <div class="mb-3">
                        <label for="welcome_paragraph" class="form-label">Párrafo de Bienvenida</label>
                        <textarea class="form-control" id="welcome_paragraph" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="welcome_slogan" class="form-label">Eslogan</label>
                        <input type="text" class="form-control" id="welcome_slogan">
                    </div>

                    <hr class="my-4">

                    <!-- Sección del Carrusel -->
                    <h4 class="mb-3">Carrusel de Imágenes</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="carousel_image_1" class="form-label">Imagen 1</label>
                            <input class="form-control" type="file" id="carousel_image_1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="carousel_image_2" class="form-label">Imagen 2</label>
                            <input class="form-control" type="file" id="carousel_image_2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="carousel_image_3" class="form-label">Imagen 3</label>
                            <input class="form-control" type="file" id="carousel_image_3">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-edit-inicio');

            // Cargar datos actuales en el formulario
            async function cargarContenido() {
                try {
                    const response = await fetch('<?php echo BASE_URL; ?>api/site_content.php');
                    const content = await response.json();
                    
                    document.getElementById('welcome_title').value = content.welcome_title || '';
                    document.getElementById('welcome_paragraph').value = content.welcome_paragraph || '';
                    document.getElementById('welcome_slogan').value = content.welcome_slogan || '';
                    // Aquí podrías mostrar una vista previa de las imágenes actuales
                } catch (error) {
                    Swal.fire('Error', 'No se pudo cargar el contenido actual.', 'error');
                }
            }

            // Enviar formulario para guardar cambios
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                // Añadir los campos de texto al FormData
                formData.append('welcome_title', document.getElementById('welcome_title').value);
                formData.append('welcome_paragraph', document.getElementById('welcome_paragraph').value);
                formData.append('welcome_slogan', document.getElementById('welcome_slogan').value);

                try {
                    const response = await fetch('<?php echo BASE_URL; ?>api/update_site_content.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (response.ok && result.status === 'ok') {
                        Swal.fire('¡Guardado!', result.message, 'success');
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });

            cargarContenido();
        });
    </script>
</body>
</html>
