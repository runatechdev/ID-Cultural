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

// Obtener datos actuales del artista
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
$page_title = "Editar Datos de Contacto - ID Cultural";
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
        .hero-welcome h1 {
            font-size: 1.3rem;
        }
    }
</style>

<body class="dashboard-body">
    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>
    <main class="container my-5">
        <div class="hero-welcome mb-4">
            <h1>📋 Editar Datos de Contacto</h1>
            <p class="lead">Actualiza tu información personal, ubicación y cómo te contactan otros usuarios.</p>
        </div>
        <div class="section-card">
            <div class="section-header">
                <h4><i class="bi bi-person-vcard icon-profile"></i> Información Personal y de Contacto</h4>
                <p class="text-muted mb-0">Completa tus datos para que la comunidad pueda conocerte y contactarte fácilmente.</p>
            </div>
            <div class="p-4">
                <form id="form-editar-datos-contacto" method="POST" class="needs-validation" novalidate>
                    <!-- Sección: Datos Personales -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($artista['nombre']); ?>" required placeholder="Ej: Juan" data-bs-toggle="tooltip" title="Tu nombre real o artístico">
                            <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($artista['apellido']); ?>" required placeholder="Ej: Pérez" data-bs-toggle="tooltip" title="Tu apellido real o artístico">
                            <div class="invalid-feedback">Por favor ingresa tu apellido.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($artista['fecha_nacimiento']); ?>" required data-bs-toggle="tooltip" title="Tu fecha de nacimiento (no se muestra públicamente)">
                            <div class="invalid-feedback">Por favor selecciona tu fecha de nacimiento.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                            <select class="form-select" id="genero" name="genero" required data-bs-toggle="tooltip" title="Selecciona el género con el que te identificas">
                                <option value="">Selecciona un género</option>
                                <option value="Masculino" <?php echo $artista['genero'] === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo $artista['genero'] === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Otro" <?php echo $artista['genero'] === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                <option value="Prefiero no especificar" <?php echo $artista['genero'] === 'Prefiero no especificar' ? 'selected' : ''; ?>>Prefiero no especificar</option>
                            </select>
                            <div class="invalid-feedback">Por favor selecciona un género.</div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <!-- Sección: Ubicación -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pais" class="form-label">País <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo htmlspecialchars($artista['pais'] ?? ''); ?>" required placeholder="Ej: Argentina" data-bs-toggle="tooltip" title="Tu país de residencia">
                            <div class="invalid-feedback">Por favor ingresa tu país.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="provincia" class="form-label">Provincia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo htmlspecialchars($artista['provincia'] ?? ''); ?>" required placeholder="Ej: Santiago del Estero" data-bs-toggle="tooltip" title="Provincia donde resides">
                            <div class="invalid-feedback">Por favor ingresa tu provincia.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="municipio" class="form-label">Municipio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="municipio" name="municipio" value="<?php echo htmlspecialchars($artista['municipio'] ?? ''); ?>" required placeholder="Ej: Capital" data-bs-toggle="tooltip" title="Municipio o ciudad donde resides">
                            <div class="invalid-feedback">Por favor ingresa tu municipio.</div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <!-- Sección: Contacto -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($artista['email']); ?>" disabled data-bs-toggle="tooltip" title="Tu email registrado (no editable)">
                            <small class="text-muted">El email no puede ser modificado. Contacta con soporte si necesitas cambiar tu email.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($artista['telefono'] ?? ''); ?>" placeholder="+54 385 1234567" data-bs-toggle="tooltip" title="Tu número de contacto (opcional)">
                            <small class="text-muted">Ingresa tu número de teléfono personal (opcional)</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-5">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
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
            const form = document.getElementById('form-editar-datos-contacto');
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (!form.checkValidity()) {
                        e.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }
                    const datos = {
                        nombre: document.getElementById('nombre').value.trim(),
                        apellido: document.getElementById('apellido').value.trim(),
                        fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
                        genero: document.getElementById('genero').value,
                        pais: document.getElementById('pais').value.trim(),
                        provincia: document.getElementById('provincia').value.trim(),
                        municipio: document.getElementById('municipio').value.trim(),
                        telefono: document.getElementById('telefono').value.trim()
                    };
                    try {
                        const res = await fetch('/api/artistas.php?action=update_personal', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(datos)
                        });
                        const data = await res.json();
                        if (res.ok && data.success) {
                            Swal.fire({
                                title: '✓ Éxito',
                                text: 'Tus datos de contacto han sido actualizados correctamente.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '/src/views/pages/artista/dashboard-artista.php';
                            });
                        } else {
                            Swal.fire('Error', data.error || 'Error al actualizar', 'error');
                        }
                    } catch (err) {
                        console.error(err);
                        Swal.fire('Error', 'Error de conexión', 'error');
                    }
                });
            }
            // Inicializar tooltips de Bootstrap
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
</body>

</html>