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

<style>
    /* Estilos específicos para crear-borrador.php */
    .dashboard-body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        min-height: 100vh;
    }

    .hero-welcome {
        background: linear-gradient(135deg, var(--color-primario, #367789) 0%, #2a5a69 100%);
        color: white;
        padding: 2rem 2.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(54, 119, 137, 0.2);
        position: relative;
        overflow: hidden;
    }

    .hero-welcome::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .hero-welcome h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .hero-welcome .lead {
        font-size: 1.05rem;
        opacity: 0.95;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .section-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 2px solid #dee2e6;
    }

    .section-header h4 {
        margin: 0 0 0.5rem 0;
        color: var(--color-primario, #367789);
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .form-section-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--color-primario, #367789);
        box-shadow: 0 0 0 0.25rem rgba(54, 119, 137, 0.15);
    }

    .drop-zone {
        border: 3px dashed #dee2e6;
        border-radius: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 200px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 2rem;
    }

    .drop-zone:hover {
        border-color: var(--color-primario, #367789);
        background: linear-gradient(135deg, rgba(54, 119, 137, 0.05) 0%, #f8f9fa 100%);
        transform: translateY(-2px);
    }

    .drop-zone.drag-over {
        border-color: var(--color-primario, #367789);
        background: linear-gradient(135deg, rgba(54, 119, 137, 0.1) 0%, #f8f9fa 100%);
        transform: scale(1.02);
    }

    .drop-zone-icon {
        font-size: 3.5rem;
        color: var(--color-primario, #367789);
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .preview-card {
        position: relative;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .preview-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .preview-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .preview-remove {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .preview-remove:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .btn-action-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        flex-wrap: wrap;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-top: 2px solid #dee2e6;
        margin: 0;
    }

    .btn-guardar {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-guardar:hover {
        background: linear-gradient(135deg, #5a6268 0%, #545b62 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
    }

    .btn-enviar {
        background: linear-gradient(135deg, var(--color-primario, #367789) 0%, #2a5a69 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-enviar:hover {
        background: linear-gradient(135deg, #2a5a69 0%, #1e4450 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(54, 119, 137, 0.3);
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #e7f3ff 0%, #d0ebff 100%);
        border-left: 4px solid #0d6efd;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        color: #004085;
        margin-bottom: 1rem;
    }

    .required-indicator {
        color: #dc3545;
        font-weight: 700;
    }

    .btn-back {
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        transform: translateX(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .hero-welcome h1 {
            font-size: 1.5rem;
        }

        .hero-welcome .lead {
            font-size: 0.95rem;
        }

        .form-section-body {
            padding: 1.25rem;
        }

        .btn-action-group {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-guardar,
        .btn-enviar {
            width: 100%;
        }

        .drop-zone {
            padding: 1.5rem;
        }
    }
</style>

<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <!-- Hero Section -->
        <div class="hero-welcome">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1>🎨 Crear Nueva Obra</h1>
                    <p class="lead">Comparte tu talento con la comunidad cultural de Santiago del Estero</p>
                </div>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-light btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                </a>
            </div>
        </div>

        <form id="form-borrador">
            <!-- Sección: Información Básica -->
            <div class="section-card">
                <div class="section-header">
                    <h4>📝 Información Básica</h4>
                    <p>Los datos esenciales que identifican tu creación artística</p>
                </div>
                <div class="form-section-body">
                    <div class="info-badge mb-4">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Los campos marcados con <span class="required-indicator">*</span> son obligatorios</span>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="titulo" class="form-label">
                                Título de tu Obra <span class="required-indicator">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="titulo" 
                                placeholder="Ej: 'Danza Folklórica del Norte' o 'Retrato del Abuelo'"
                                data-bs-toggle="tooltip"
                                title="Un título descriptivo y memorable que represente tu obra"
                                required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="categoria" class="form-label">
                                Categoría Cultural <span class="required-indicator">*</span>
                            </label>
                            <select 
                                id="categoria" 
                                class="form-select form-select-lg" 
                                data-bs-toggle="tooltip"
                                title="Selecciona la disciplina artística que mejor representa tu obra"
                                required>
                                <option value="" selected disabled>Selecciona una categoría...</option>
                                <option value="musica">🎵 Música</option>
                                <option value="literatura">📚 Literatura</option>
                                <option value="artes_visuales">🎨 Artes Visuales</option>
                                <option value="escultura">🗿 Escultura</option>
                                <option value="artesanias">✂️ Artesanías</option>
                                <option value="danza">💃 Danza</option>
                                <option value="teatro">🎭 Teatro</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            Descripción de tu Obra <span class="required-indicator">*</span>
                        </label>
                        <textarea 
                            class="form-control" 
                            id="descripcion" 
                            rows="6" 
                            placeholder="Cuéntanos sobre tu obra: inspiración, técnica utilizada, significado, historia..."
                            data-bs-toggle="tooltip"
                            title="Una descripción detallada ayuda a que más personas conozcan y aprecien tu trabajo"
                            required></textarea>
                        <div class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>
                            Tip: Una buena descripción incluye el contexto, la técnica y lo que querías transmitir
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Multimedia -->
            <div class="section-card">
                <div class="section-header">
                    <h4>📷 Galería de Imágenes</h4>
                    <p>Añade fotografías que muestren tu obra desde diferentes ángulos</p>
                </div>
                <div class="form-section-body">
                    <!-- Zona de Drag & Drop -->
                    <div class="mb-4">
                        <div id="drop-zone" class="drop-zone text-center">
                            <div>
                                <i class="bi bi-cloud-upload drop-zone-icon"></i>
                                <h5 class="mb-3">Arrastra tus imágenes aquí</h5>
                                <p class="text-muted mb-3">
                                    o haz clic en el botón para seleccionarlas desde tu dispositivo
                                </p>
                                <div class="mb-3">
                                    <span class="badge bg-info me-2">
                                        <i class="bi bi-file-earmark-image me-1"></i>JPG, PNG, WEBP
                                    </span>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hdd me-1"></i>Máx. 5MB c/u
                                    </span>
                                </div>
                                <input type="file" class="d-none" id="multimedia" name="multimedia" accept="image/*" multiple>
                                <button type="button" class="btn btn-primary btn-lg" id="btn-select-images">
                                    <i class="bi bi-images me-2"></i>Seleccionar Imágenes
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview de imágenes -->
                    <div id="preview-container" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                        </div>
                        <div id="image-previews" class="row g-3"></div>
                    </div>
                </div>
            </div>

            <!-- Sección: Detalles de Categoría -->
            <div class="section-card">
                <div class="section-header">
                    <h4>🎯 Detalles Específicos</h4>
                    <p>Información adicional según la categoría de tu obra</p>
                </div>
                <div class="form-section-body">
                    <!-- Campos Condicionales -->
                    <div id="campos-condicionales-container">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-arrow-up-circle display-4 mb-3"></i>
                            <p>Selecciona una categoría arriba para ver los campos específicos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="section-card">
                <div class="btn-action-group">
                    <button type="submit" id="btn-guardar-borrador" class="btn btn-secondary btn-guardar">
                        <i class="bi bi-save me-2"></i>Guardar como Borrador
                    </button>
                    <button type="submit" id="btn-enviar-validacion" class="btn btn-primary btn-enviar">
                        <i class="bi bi-send-check me-2"></i>Enviar para Validación
                    </button>
                </div>
                <div class="px-4 pb-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Guardar como borrador:</strong> Podrás editarlo más tarde antes de enviarlo. 
                        <strong>Enviar para validación:</strong> Tu obra será revisada por nuestro equipo antes de publicarse.
                    </small>
                </div>
            </div>
        </form>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        // Inicializar tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/crear-borrador.js"></script>
</body>
</html>