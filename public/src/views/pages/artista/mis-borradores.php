<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';

// --- Bloque de seguridad para Artista ---
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'artista') {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

// --- Variables para el header ---
$page_title = "Mis Borradores - ID Cultural";
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
    .btn-group .btn {
        min-width: 44px;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-group .btn i {
        font-size: 14px;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .table-responsive {
        border-radius: 8px;
    }
    .btn-group {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 6px;
    }
    .loading-row {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
<body class="dashboard-body">
    <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>
    <main class="container my-5">
        <div class="hero-welcome mb-4">
            <h1>📝 Mis Borradores</h1>
            <p class="lead">Aquí puedes continuar editando tus publicaciones guardadas como borrador o enviarlas a validación para que sean revisadas.</p>
        </div>
        <div class="section-card">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h4><i class="bi bi-journal-text"></i> Publicaciones en Borrador</h4>
                    <p class="text-muted mb-0">Gestiona tus publicaciones antes de hacerlas públicas.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo BASE_URL; ?>src/views/pages/artista/crear-borrador.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Borrador
                    </a>
                    <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Título de la Publicación</th>
                                <th>Fecha de Creación</th>
                                <th class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-borradores-body">
                            <!-- Las filas se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/ver_borradores.js"></script>
</body>
</html>
