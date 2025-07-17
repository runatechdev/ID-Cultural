<?php
// 1. Definimos la ruta raíz y cargamos la configuración
define('ROOT_PATH', realpath(__DIR__ . '/../../../../'));
require_once(ROOT_PATH . '/config.php');

// 2. Lógica de seguridad: solo usuarios logueados (ej: admin o editor) pueden ver esto
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'editor'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

// 3. Obtenemos los datos desde el backend
// En un caso real, esto llamaría a un controlador. Por ahora, simulamos los datos.
$solicitudes = [
    ['id' => 101, 'nombre' => 'Artista Ejemplo 1', 'fecha_solicitud' => '2025-07-14', 'estado' => 'Pendiente', 'fecha_validacion' => '-'],
    ['id' => 102, 'nombre' => 'Artista Ejemplo 2', 'fecha_solicitud' => '2025-07-12', 'estado' => 'Aprobado', 'fecha_validacion' => '2025-07-13'],
    ['id' => 103, 'nombre' => 'Artista Ejemplo 3', 'fecha_solicitud' => '2025-07-10', 'estado' => 'Rechazado', 'fecha_validacion' => '2025-07-11'],
];

$page_title = "Estado de Solicitudes - ID Cultural";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css" />
</head>

<body>

    <?php
    // 3. Incluimos el navbar desde su ubicación REAL
    include(ROOT_PATH . '/components/navbar.php');
    ?>

    <main class="container">
        <h1>Estado de Artistas Enviados</h1>
        <p>Aquí puedes seguir el proceso de validación de los perfiles que has registrado.</p>

        <div class="tabla-container">
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Nombre del Artista</th>
                        <th>Fecha de Solicitud</th>
                        <th>Estado</th>
                        <th>Fecha de Validación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-artistas-body"> <?php if (empty($solicitudes)): ?>
                        <tr>
                            <td colspan="5">No hay solicitudes para mostrar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr data-id="<?php echo $solicitud['id']; ?>">
                                <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td class="estado-cell"> <span class="estado-badge estado-<?php echo strtolower(htmlspecialchars($solicitud['estado'])); ?>">
                                        <?php echo htmlspecialchars($solicitud['estado']); ?>
                                    </span>
                                </td>
                                <td class="fecha-cell"><?php echo htmlspecialchars($solicitud['fecha_validacion']); ?></td>
                                <td>
                                    <select data-action="cambiar-estado" data-id="<?php echo $solicitud['id']; ?>">
                                        <option value="">Cambiar...</option>
                                        <option value="Aprobado">Aprobar</option>
                                        <option value="Rechazado">Rechazar</option>
                                        <option value="Pendiente">Marcar como Pendiente</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php
    // 4. Incluimos el footer desde su ubicación REAL
    include(ROOT_PATH . '/components/footer.php');
    ?>

    <script src="<?php echo BASE_URL; ?>static/js/estado_artistas.js"></script>
</body>

</html>