<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Artista ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Crear Borrador";
$specific_css_files = ['dashboard.css'];

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="mb-0">Crear Borrador de Publicación</h1>
                        <p class="lead">Completa la información de tu obra o perfil para compartirlo con la comunidad.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <form id="form-borrador">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="titulo" class="form-label">Título de la Obra/Publicación <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="categoria" class="form-label">Categoría Cultural <span class="text-danger">*</span></label>
                            <select id="categoria" class="form-select" required>
                                <option value="" selected disabled>Seleccionar...</option>
                                <option value="musica">Música</option>
                                <option value="literatura">Literatura</option>
                                <option value="artes_visuales">Artes Visuales</option>
                                <option value="escultura">Escultura</option>
                                <option value="artesanias">Artesanías</option>
                                <option value="danza">Danza</option>
                                <option value="teatro">Teatro</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" rows="5" required></textarea>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Detalles Específicos de la Categoría</h5>

                    <!-- Campos Condicionales -->
                    <div id="campos-condicionales-container">
                        <!-- Los campos para cada categoría se insertarán aquí con JS -->
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="submit" id="btn-guardar-borrador" class="btn btn-secondary">Guardar Borrador</button>
                        <button type="submit" id="btn-enviar-validacion" class="btn btn-primary">Enviar para Validación</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/crear-borrador.js"></script>
</body>
</html>
