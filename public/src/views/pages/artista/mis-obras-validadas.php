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

// Obtener obras publicadas del artista
try {
    $stmt = $pdo->prepare("
        SELECT * FROM publicaciones 
        WHERE usuario_id = ? AND estado = 'validado'
        ORDER BY fecha_validacion DESC
    ");
    $stmt->execute([$usuario_id]);
    $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error al obtener obras: " . $e->getMessage());
    $obras = [];
}

// --- Variables para el header ---
$page_title = "Mis Obras Publicadas - ID Cultural";
$specific_css_files = ['dashboard.css'];
// Variable de versión para cache busting
$static_version = date('YmdHis'); // Puedes cambiar por un número manual si prefieres
// --- Incluir la cabecera ---
include(__DIR__ . '/../../../../../components/header.php');
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css?v=<?php echo $static_version; ?>">
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
            <h1>✅ Mis Obras Publicadas</h1>
            <p class="lead">Edita tus obras ya publicadas. Los cambios serán enviados a validación y la obra se ocultará temporalmente.</p>
        </div>
        <div class="section-card">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h4><i class="bi bi-easel"></i> Obras Publicadas</h4>
                    <p class="text-muted mb-0">Gestiona y edita tus obras validadas en la plataforma.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/dashboard-artista.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            <div class="p-4">
                <div class="alert alert-info d-flex align-items-start gap-3" role="alert">
                    <i class="bi bi-info-circle" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                    <div>
                        <strong>Importante:</strong> Cualquier edición en tus obras publicadas requiere validación nuevamente.
                    </div>
                </div>
                <?php if (empty($obras)): ?>
                    <div class="alert alert-warning d-flex align-items-start gap-3" role="alert">
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                        <div>
                            No tienes obras publicadas aún. <a href="<?php echo BASE_URL; ?>src/views/pages/artista/crear-borrador.php">Crea tu primera obra</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Categoría</th>
                                    <th>Fecha Publicación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($obras as $obra): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($obra['titulo']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($obra['descripcion'], 0, 60)) . '...'; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($obra['categoria']); ?></span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($obra['fecha_validacion'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Publicada</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>src/views/pages/artista/editar_obra.php?id=<?php echo $obra['id']; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="<?php echo $obra['id']; ?>" title="Eliminar">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include(__DIR__ . '/../../../../../components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Botones eliminar
            const btnsEliminar = document.querySelectorAll('.btn-eliminar');
            btnsEliminar.forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = btn.dataset.id;
                    const result = await Swal.fire({
                        title: '¿Eliminar obra?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#dc3545'
                    });
                    if (result.isConfirmed) {
                        try {
                            const res = await fetch('/api/obras.php?action=delete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id
                                })
                            });
                            const data = await res.json();
                            if (res.ok && data.success) {
                                Swal.fire('Eliminada', 'La obra ha sido eliminada.', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.error || 'Error al eliminar', 'error');
                            }
                        } catch (err) {
                            console.error(err);
                            Swal.fire('Error', 'Error de conexión', 'error');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>