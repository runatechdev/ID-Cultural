<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
require_once __DIR__ . '/../../../../../backend/config/connection.php';

// --- Bloque de seguridad ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

$usuario_id = $_SESSION['user_data']['id'];

// Obtener datos actuales del perfil público
try {
    $stmt = $pdo->prepare("SELECT * FROM artistas WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $artista = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$artista) {
        header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Error al obtener datos del artista: " . $e->getMessage());
    $artista = [];
}

// --- Variables para el header ---
$page_title = "Editar Perfil Público - ID Cultural";
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
            <h1>👤 Editar Perfil Público</h1>
            <p class="lead">Personaliza cómo otros usuarios ven tu perfil en la plataforma. Los cambios requieren validación antes de ser publicados.</p>
        </div>
        <div class="section-card">
            <div class="section-header">
                <h4><i class="bi bi-person-badge"></i> Información Pública del Artista</h4>
                <p class="text-muted mb-0">Edita tu foto, biografía, especialidad y redes sociales para destacar en la comunidad.</p>
            </div>
            <div class="p-4">
                <div class="alert alert-info d-flex align-items-start gap-3" role="alert">
                    <i class="bi bi-info-circle" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                    <div>
                        <strong>Nota:</strong> Los cambios en tu perfil público requieren validación antes de ser publicados.
                    </div>
                </div>
                <form id="form-editar-perfil-publico" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                    <!-- Sección: Foto de Perfil -->
                    <h5 class="mb-3 mt-4">Foto de Perfil</h5>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <img id="preview-foto" src="<?php echo BASE_URL; ?>static/img/perfil_pendiente.webp" alt="Foto Perfil" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                            <div>
                                <label for="foto_perfil" class="form-label">Subir Foto</label>
                                <input 
                                    type="file" 
                                    class="form-control" 
                                    id="foto_perfil" 
                                    name="foto_perfil"
                                    accept="image/*"
                                    data-bs-toggle="tooltip" title="Sube una foto de perfil (JPG, PNG, WEBP, máx. 5MB)"
                                >
                                <small class="text-muted">Formatos: JPG, PNG, WEBP (máx. 5MB)</small>
                            </div>
                        </div>
                    </div>
                    <!-- Sección: Biografía -->
                    <h5 class="mb-3 mt-4">Biografía</h5>
                    <div class="mb-3">
                        <label for="biografia" class="form-label">Cuéntanos sobre ti</label>
                        <textarea 
                            class="form-control" 
                            id="biografia" 
                            name="biografia" 
                            rows="5"
                            placeholder="Describe tu experiencia, especialidades, logros y lo que te hace único como artista..."
                            maxlength="1000"
                            data-bs-toggle="tooltip" title="Comparte tu historia, logros y lo que te inspira como artista"
                        ><?php echo htmlspecialchars($artista['biografia'] ?? ''); ?></textarea>
                        <small class="text-muted"><span id="char-count">0</span>/1000 caracteres</small>
                    </div>
                    <!-- Sección: Especialidades -->
                    <h5 class="mb-3 mt-4">Categoría Artística</h5>
                    <div class="mb-3">
                        <label for="especialidades" class="form-label">¿Cuál es tu disciplina principal? <span class="text-danger">*</span></label>
                        <select 
                            class="form-select" 
                            id="especialidades" 
                            name="especialidades" 
                            required
                            data-bs-toggle="tooltip" title="Selecciona la disciplina artística principal que te representa"
                        >
                            <option value="">Selecciona una categoría</option>
                            <option value="Musica" <?php echo ($artista['especialidades'] ?? '') === 'Musica' ? 'selected' : ''; ?>>Música</option>
                            <option value="Literatura" <?php echo ($artista['especialidades'] ?? '') === 'Literatura' ? 'selected' : ''; ?>>Literatura</option>
                            <option value="Danza" <?php echo ($artista['especialidades'] ?? '') === 'Danza' ? 'selected' : ''; ?>>Danza</option>
                            <option value="Teatro" <?php echo ($artista['especialidades'] ?? '') === 'Teatro' ? 'selected' : ''; ?>>Teatro</option>
                            <option value="Artesania" <?php echo ($artista['especialidades'] ?? '') === 'Artesania' ? 'selected' : ''; ?>>Artesanía</option>
                            <option value="Audiovisual" <?php echo ($artista['especialidades'] ?? '') === 'Audiovisual' ? 'selected' : ''; ?>>Audiovisual</option>
                            <option value="Escultura" <?php echo ($artista['especialidades'] ?? '') === 'Escultura' ? 'selected' : ''; ?>>Escultura</option>
                        </select>
                        <small class="text-muted">Esta categoría se usará para filtrar tu perfil en la Wiki</small>
                    </div>
                    <!-- Sección: Redes Sociales -->
                    <h5 class="mb-3 mt-4">Redes Sociales y Contacto</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp <small class="text-muted">(Público)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                <input 
                                    type="tel" 
                                    class="form-control" 
                                    id="whatsapp" 
                                    name="whatsapp"
                                    placeholder="+54 385 1234567"
                                    value="<?php echo htmlspecialchars($artista['whatsapp'] ?? ''); ?>"
                                    data-bs-toggle="tooltip" title="Número de WhatsApp visible para contacto público"
                                >
                            </div>
                            <small class="text-muted">Este número será visible para que te contacten</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="instagram" 
                                    name="instagram"
                                    placeholder="tu_usuario"
                                    value="<?php echo htmlspecialchars($artista['instagram'] ?? ''); ?>"
                                    data-bs-toggle="tooltip" title="Tu usuario de Instagram"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="facebook" 
                                name="facebook"
                                placeholder="URL de perfil"
                                value="<?php echo htmlspecialchars($artista['facebook'] ?? ''); ?>"
                                data-bs-toggle="tooltip" title="Tu perfil de Facebook (opcional)"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="twitter" class="form-label">Twitter / X</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="twitter" 
                                    name="twitter"
                                    placeholder="tu_usuario"
                                    value="<?php echo htmlspecialchars($artista['twitter'] ?? ''); ?>"
                                    data-bs-toggle="tooltip" title="Tu usuario de Twitter/X (opcional)"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sitio_web" class="form-label">Sitio Web</label>
                            <input 
                                type="url" 
                                class="form-control" 
                                id="sitio_web" 
                                name="sitio_web"
                                placeholder="https://tuportafolio.com"
                                value="<?php echo htmlspecialchars($artista['sitio_web'] ?? ''); ?>"
                                data-bs-toggle="tooltip" title="Tu sitio web o portafolio (opcional)"
                            >
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-5">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar y Enviar a Validación
                        </button>
                        <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inicializar tooltips de Bootstrap
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            // Preview de foto
            const fotoPerfil = document.getElementById('foto_perfil');
            const preview = document.getElementById('preview-foto');
            if (fotoPerfil) {
                fotoPerfil.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            // Contador de caracteres
            const biografia = document.getElementById('biografia');
            const charCount = document.getElementById('char-count');
            if (biografia) {
                biografia.addEventListener('input', () => {
                    charCount.textContent = biografia.value.length;
                });
                charCount.textContent = biografia.value.length;
            }
            // Enviar formulario
            const form = document.getElementById('form-editar-perfil-publico');
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (!form.checkValidity()) {
                        e.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }
                    const formData = new FormData(form);
                    try {
                        const baseUrl = '<?php echo BASE_URL; ?>';
                        const url = baseUrl + 'api/actualizar_perfil_publico.php';
                        const res = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.success) {
                            Swal.fire({
                                title: '✓ Enviado a Validación',
                                text: 'Tu perfil público ha sido enviado para revisión. Te notificaremos cuando sea aprobado.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = baseUrl + 'src/views/pages/artista/dashboard-artista.php';
                            });
                        } else {
                            Swal.fire('Error', data.error || data.mensaje || 'Error al actualizar', 'error');
                        }
                    } catch (err) {
                        console.error(err);
                        Swal.fire('Error', 'Error de conexión', 'error');
                    }
                });
            }
        });
    </script>
</body>
</html>
