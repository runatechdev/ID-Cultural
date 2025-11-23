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
<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="mb-0">🎨 Editar Perfil Público</h1>
                        <p class="lead">Personaliza cómo otros usuarios ven tu perfil en la plataforma.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> <strong>Nota:</strong> Los cambios en tu perfil público requieren validación antes de ser publicados.
                </div>

                <form id="form-editar-perfil-publico" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                    <!-- Sección: Foto de Perfil -->
                    <h4 class="mb-3 mt-4">Foto de Perfil</h4>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <img id="preview-foto" src="<?php echo BASE_URL; ?>static/img/perfil-del-usuario.png" alt="Foto Perfil" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                            <div>
                                <label for="foto_perfil" class="form-label">Subir Foto</label>
                                <input 
                                    type="file" 
                                    class="form-control" 
                                    id="foto_perfil" 
                                    name="foto_perfil"
                                    accept="image/*"
                                >
                                <small class="text-muted">Formatos: JPG, PNG, WEBP (máx. 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Biografía -->
                    <h4 class="mb-3 mt-4">Biografía</h4>
                    <div class="mb-3">
                        <label for="biografia" class="form-label">Cuéntanos sobre ti</label>
                        <textarea 
                            class="form-control" 
                            id="biografia" 
                            name="biografia" 
                            rows="5"
                            placeholder="Describe tu experiencia, especialidades, logros y lo que te hace único como artista..."
                            maxlength="1000"
                        ><?php echo htmlspecialchars($artista['biografia'] ?? ''); ?></textarea>
                        <small class="text-muted"><span id="char-count">0</span>/1000 caracteres</small>
                    </div>

                    <!-- Sección: Especialidades -->
                    <h4 class="mb-3 mt-4">Categoría Artística</h4>
                    <div class="mb-3">
                        <label for="especialidades" class="form-label">¿Cuál es tu disciplina principal? *</label>
                        <select 
                            class="form-select" 
                            id="especialidades" 
                            name="especialidades" 
                            required
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
                    <h4 class="mb-3 mt-4">Redes Sociales y Contacto</h4>
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
                            >
                        </div>
                    </div>

                    <!-- Botones de acción -->
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                        
                        console.log('Enviando a:', url);
                        console.log('BASE_URL:', baseUrl);
                        
                        const res = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });

                        console.log('Response status:', res.status);
                        
                        const data = await res.json();
                        
                        console.log('Response data:', data);

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
