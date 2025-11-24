<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad: solo usuarios con rol 'artista' pueden ver esto ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Panel de Artista";
$specific_css_files = ['dashboard.css', 'dashboard-adm.css'];
$nombre_artista = $_SESSION['user_data']['nombre'] ?? 'Artista';

// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>

<style>
    /* Estilos específicos para el panel de artista */
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
        font-size: 2.25rem;
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

    .dashboard-menu {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
    }

    .dashboard-item {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 0.75rem;
        text-decoration: none;
        color: #495057;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
        border-left: 5px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .dashboard-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(54, 119, 137, 0.05) 0%, rgba(42, 90, 105, 0.08) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dashboard-item:hover {
        transform: translateX(8px);
        color: var(--color-primario, #367789);
        border-left-color: var(--color-primario, #367789);
        box-shadow: 0 6px 20px rgba(54, 119, 137, 0.15);
    }

    .dashboard-item:hover::before {
        opacity: 1;
    }

    .dashboard-item i {
        font-size: 1.5rem;
        margin-right: 1rem;
        min-width: 30px;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .dashboard-item:hover i {
        transform: scale(1.15);
    }

    .dashboard-item span {
        position: relative;
        z-index: 1;
    }

    /* Iconos con colores distintivos */
    .icon-profile { color: #0d6efd; }
    .icon-public { color: #198754; }
    .icon-create { color: #fd7e14; }
    .icon-drafts { color: #6f42c1; }
    .icon-published { color: #20c997; }
    .icon-requests { color: #dc3545; }

    /* Badge contador (opcional para futuras versiones) */
    .badge-counter {
        margin-left: auto;
        background: linear-gradient(135deg, var(--color-primario, #367789) 0%, #2a5a69 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-welcome h1 {
            font-size: 1.75rem;
        }

        .dashboard-menu {
            grid-template-columns: 1fr;
        }

        .section-header h4 {
            font-size: 1.1rem;
        }
    }
</style>

<body class="dashboard-body">

    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

    <main class="container my-5">
        <!-- Hero Welcome Section -->
        <div class="hero-welcome">
            <h1>👋 ¡Hola, <strong><?php echo htmlspecialchars($nombre_artista); ?></strong>!</h1>
            <p class="lead">Bienvenido a tu espacio creativo. Aquí puedes gestionar tu perfil artístico, crear nuevas obras y compartir tu talento con la comunidad cultural de Santiago del Estero.</p>
        </div>

        <!-- Sección: Gestión de Perfil -->
        <div class="section-card">
            <div class="section-header">
                <h4>👤 Gestión de Perfil</h4>
                <p class="text-muted mb-0">Mantén actualizada tu información personal y cómo te ve el público</p>
            </div>
            <div class="dashboard-menu">
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/editar_datos_contacto.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Actualiza tu nombre, email, ubicación y otros datos de contacto">
                    <i class="bi bi-person-vcard icon-profile"></i>
                    <span>Editar Datos de Contacto</span>
                </a>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/editar_perfil_publico.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Personaliza tu biografía, foto de perfil y enlaces a redes sociales">
                    <i class="bi bi-image icon-public"></i>
                    <span>Editar Perfil Público</span>
                </a>
            </div>
        </div>

        <!-- Sección: Gestión de Obras -->
        <div class="section-card">
            <div class="section-header">
                <h4>🎨 Gestión de Obras</h4>
                <p class="text-muted mb-0">Crea, edita y administra todas tus creaciones artísticas</p>
            </div>
            <div class="dashboard-menu">
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/crear-borrador.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Comienza una nueva obra, evento o publicación artística">
                    <i class="bi bi-plus-circle-fill icon-create"></i>
                    <span>Crear Nueva Obra</span>
                </a>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/mis-borradores.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Accede a tus obras guardadas que aún no has enviado a validación">
                    <i class="bi bi-journal-richtext icon-drafts"></i>
                    <span>Mis Borradores</span>
                </a>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/mis-obras-validadas.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Visualiza y edita tus obras ya publicadas en la plataforma">
                    <i class="bi bi-check2-circle icon-published"></i>
                    <span>Mis Obras Publicadas</span>
                </a>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/solicitudes-enviadas.php" 
                   class="dashboard-item" 
                   data-bs-toggle="tooltip" 
                   title="Revisa el estado de tus obras pendientes de validación">
                    <i class="bi bi-send-check-fill icon-requests"></i>
                    <span>Solicitudes de Validación</span>
                </a>
            </div>
        </div>
    </main>

    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Inicializar tooltips de Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
</body>

</html>