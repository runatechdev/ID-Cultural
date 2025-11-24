<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
require_once __DIR__ . '/../../../../../backend/config/connection.php';

// --- Función auxiliar para resolver la ruta física y la URL web de una imagen ---
function resolver_url_imagen($img, $base_url)
{
    // Si no hay imagen, intentar cargar placeholder (si existe) o dejar vacío
    if (empty($img)) return $base_url . 'static/img/placeholder.webp';

    // 1. Si ya es una URL completa (http...), devolverla tal cual
    if (preg_match('/^https?:\/\//i', $img)) return $img;

    // 2. Limpieza agresiva: Quitar 'public' y 'static' si vienen en la ruta de la BD
    $img = str_replace(['public/', 'static/'], '', $img);
    $img = ltrim($img, '/'); // Quitar barras iniciales sobrantes

    // 3. Construcción de la URL Correcta

    // CASO A: Si la ruta limpia ya empieza con "uploads/"
    // Ejemplo BD: "uploads/imagenes/foto.jpg" -> URL: BASE_URL + "uploads/imagenes/foto.jpg"
    if (strpos($img, 'uploads/') === 0) {
        return $base_url . $img;
    }

    // CASO B: Si solo tenemos el nombre del archivo
    // Ejemplo BD: "media_123.jpg" -> URL: BASE_URL + "uploads/imagenes/" + "media_123.jpg"
    return $base_url . 'uploads/imagenes/' . $img;
}

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

    .drop-zone {
        border: 3px dashed #dee2e6;
        border-radius: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 200px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 2rem;
        text-align: center;
    }

    .drop-zone.dragover {
        border-color: #0d6efd;
        background: #e7f3ff;
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
                        <div id="drop-zone" class="drop-zone">
                            <div class="py-4">
                                <i class="bi bi-cloud-upload display-4 text-primary mb-3"></i>
                                <h5>Arrastra imágenes aquí o haz clic para seleccionar</h5>
                                <p class="text-muted mb-3">
                                    Formatos: JPG, PNG, WEBP (máx. 5MB cada una)<br>
                                    Puedes subir múltiples imágenes
                                </p>
                                <input type="file" class="d-none" id="multimedia" name="multimedia[]" accept="image/*" multiple>
                                <button type="button" class="btn btn-primary" id="btn-select-images">
                                    <i class="bi bi-image me-2"></i>Seleccionar Imágenes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="preview-container" class="mb-3" style="<?php echo !empty($obra['multimedia']) ? 'display: block;' : 'display: none;'; ?>">
                        <h6 class="mb-3">Imágenes seleccionadas:</h6>
                        <div class="row g-3">
                            <div id="existing-images-container" class="col-12 row g-3">
                                <?php
                                $multimedia = $obra['multimedia'] ?? '';
                                $imagenes = [];
                                if (!empty($multimedia)) {
                                    // Detectar si es JSON (nuevo formato)
                                    if (is_string($multimedia) && ($arr = json_decode($multimedia, true)) && json_last_error() === JSON_ERROR_NONE) {
                                        $imagenes = $arr;
                                    } else {
                                        $imagenes = [$multimedia];
                                    }
                                }
                                foreach ($imagenes as $img) {
                                    $src = resolver_url_imagen($img, BASE_URL);
                                ?>
                                    <div class="col-md-3 col-sm-4 col-6" data-existing="true" data-src="<?php echo htmlspecialchars($src); ?>">
                                        <div class="card h-100 border-0 shadow-sm position-relative">
                                            <img src="<?php echo htmlspecialchars($src); ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Imagen actual">
                                            <div class="card-footer bg-light small">
                                                <span class="text-muted">Imagen actual</span>
                                                <button type="button" class="btn btn-sm btn-danger float-end p-0 btn-remove-existing">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="new-images-container" class="col-12 row g-3"></div>
                        </div>
                    </div>
                    <input type="hidden" id="imagenes_a_borrar" name="imagenes_a_borrar" value="[]">
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
    <script src="<?php echo BASE_URL; ?>static/js/editar-obra.js?v=<?php echo time(); ?>"></script>
</body>

</html>