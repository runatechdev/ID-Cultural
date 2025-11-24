<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
require_once __DIR__ . '/../../../../../backend/config/connection.php';

// --- Bloque de seguridad para Artista ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

$usuario_id = $_SESSION['user_data']['id'];

// Obtener ID de la obra
$obra_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$obra_id) {
    header('Location: ' . BASE_URL . 'src/views/pages/artista/mis-obras-validadas.php');
    exit();
}

// Obtener datos de la obra
try {
    $stmt = $pdo->prepare("
        SELECT * FROM publicaciones 
        WHERE id = ? AND usuario_id = ?
    ");
    $stmt->execute([$obra_id, $usuario_id]);
    $obra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$obra) {
        header('Location: ' . BASE_URL . 'src/views/pages/artista/mis-obras-validadas.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Error al obtener obra: " . $e->getMessage());
    header('Location: ' . BASE_URL . 'src/views/pages/artista/mis-obras-validadas.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Editar Obra - ID Cultural";
$specific_css_files = ['dashboard.css'];
// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<style>
    .dashboard-body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        min-height: 100vh;
    }
    .hero-welcome {
        background: linear-gradient(135deg, var(--color-primario, #367789) 0%, #2a5a69 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(54, 119, 137, 0.2);
        position: relative;
        overflow: hidden;
    }
    .hero-welcome h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    .hero-welcome .lead {
        font-size: 1.1rem;
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .section-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
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
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-header p {
        margin: 0;
        font-size: 0.9rem;
    }
    @media (max-width: 768px) {
        .hero-welcome h1 { font-size: 1.3rem; }
    }
</style>
<body class="dashboard-body">
    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>
    <main class="container my-5">
        <div class="hero-welcome mb-4">
            <h1>🎨 Editar Obra</h1>
            <p class="lead">Modifica los detalles de tu obra. Los cambios serán enviados a validación y la obra se ocultará temporalmente.</p>
        </div>
        <div class="section-card">
            <div class="section-header">
                <h4><i class="bi bi-brush"></i> Detalles de la Obra</h4>
                <p class="text-muted mb-0">Actualiza la información, imágenes y detalles específicos de tu obra artística.</p>
            </div>
            <div class="p-4">
                <div class="alert alert-warning d-flex align-items-start gap-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                    <div>
                        <strong>⚠️ Importante:</strong> Al editar esta obra, se enviará automáticamente a validación. Durante el proceso de validación, la obra se ocultará de la plataforma y no será visible para otros usuarios.
                    </div>
                </div>
                <form id="form-editar-obra">
                    <input type="hidden" id="obra-id" value="<?php echo $obra['id']; ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="titulo" class="form-label">Título de la Obra <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" value="<?php echo htmlspecialchars($obra['titulo']); ?>" required placeholder="Ej: Mi Obra" data-bs-toggle="tooltip" title="El título principal de tu obra">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="categoria" class="form-label">Categoría Cultural <span class="text-danger">*</span></label>
                            <select id="categoria" class="form-select" required data-bs-toggle="tooltip" title="Selecciona la categoría principal de tu obra">
                                <option value="" selected disabled>Seleccionar...</option>
                                <option value="musica" <?php echo $obra['categoria'] === 'musica' ? 'selected' : ''; ?>>Música</option>
                                <option value="literatura" <?php echo $obra['categoria'] === 'literatura' ? 'selected' : ''; ?>>Literatura</option>
                                <option value="artes_visuales" <?php echo $obra['categoria'] === 'artes_visuales' ? 'selected' : ''; ?>>Artes Visuales</option>
                                <option value="escultura" <?php echo $obra['categoria'] === 'escultura' ? 'selected' : ''; ?>>Escultura</option>
                                <option value="artesanias" <?php echo $obra['categoria'] === 'artesanias' ? 'selected' : ''; ?>>Artesanías</option>
                                <option value="danza" <?php echo $obra['categoria'] === 'danza' ? 'selected' : ''; ?>>Danza</option>
                                <option value="teatro" <?php echo $obra['categoria'] === 'teatro' ? 'selected' : ''; ?>>Teatro</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" rows="5" required placeholder="Describe tu obra" data-bs-toggle="tooltip" title="Describe brevemente tu obra, inspiración, técnica, etc."><?php echo htmlspecialchars($obra['descripcion']); ?></textarea>
                    </div>
                    <hr class="my-4">
                    <h5 class="mb-3">📷 Multimedia (Imágenes)</h5>
                    <div class="mb-3">
                        <label for="multimedia" class="form-label">Cambiar Imágenes (Opcional)</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="multimedia" name="multimedia[]" accept="image/*" multiple data-bs-toggle="tooltip" title="Puedes subir nuevas imágenes para tu obra (máx. 5MB cada una)">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Formatos: JPG, PNG, WEBP (máx. 5MB cada una)<br>
                            Si no seleccionas nuevas imágenes, se mantendrán las actuales
                        </small>
                    </div>
                    <?php if (!empty($obra['multimedia'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Imagen Actual</label>
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-4 col-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <img src="<?php echo BASE_URL . ltrim($obra['multimedia'], '/'); ?>" 
                                             class="card-img-top" 
                                             style="height: 150px; object-fit: cover;" 
                                             alt="Imagen actual">
                                        <div class="card-footer bg-light small">
                                            <span class="text-muted">Imagen actual</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div id="preview-container" class="mb-3" style="display: none;">
                        <label class="form-label">Nuevas Imágenes</label>
                        <div id="image-previews" class="row g-3"></div>
                    </div>
                    <hr class="my-4">
                    <h5 class="mb-3">Detalles Específicos de la Categoría</h5>
                    <div id="campos-condicionales-container">
                        <!-- Los campos para cada categoría se insertarán aquí con JS -->
                    </div>
                    <div class="mt-4 d-flex gap-3">
                        <a href="<?php echo BASE_URL; ?>src/views/pages/artista/mis-obras-validadas.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" id="btn-guardar-cambios" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar y Enviar a Validación
                        </button>
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
        const obraData = <?php echo json_encode($obra); ?>;
        document.addEventListener('DOMContentLoaded', () => {
            // Inicializar tooltips de Bootstrap
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/editar-obra.js"></script>
</body>
</html>
