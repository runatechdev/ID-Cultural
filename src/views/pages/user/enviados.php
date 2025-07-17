<?php
// 1. Incluimos la configuración principal (para BASE_URL y sesiones)
require_once __DIR__ . '/../../../../../config.php';

// 2. Lógica para verificar que el usuario sea un editor y esté logueado
// require_once(APPROOT . '/backend/controllers/verificar_sesion_editor.php');

// 3. Obtenemos los datos desde el backend
// Esta línea sería la que trae los datos reales. Por ahora, simulamos los datos.
// $enviados = require_once(APPROOT . '/backend/controllers/obtener_enviados.php');

// --- SIMULACIÓN DE DATOS DEL BACKEND ---
$enviados = [
    ['id' => 101, 'nombre_artista' => 'Artista Ejemplo 1', 'fecha_envio' => '2025-07-14', 'estado' => 'Pendiente'],
    ['id' => 102, 'nombre_artista' => 'Artista Ejemplo 2', 'fecha_envio' => '2025-07-12', 'estado' => 'Aprobado'],
    ['id' => 103, 'nombre_artista' => 'Artista Ejemplo 3', 'fecha_envio' => '2025-07-10', 'estado' => 'Rechazado'],
    ['id' => 104, 'nombre_artista' => 'Otro Artista Más', 'fecha_envio' => '2025-07-09', 'estado' => 'Aprobado'],
];
// --- FIN DE LA SIMULACIÓN ---

$page_title = "Enviados a Validación - ID Cultural";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css">
</head>
<body>

    <?php include __DIR__ . '/../../../components/navbar.php'; // Incluimos el navbar inteligente ?>

    <main class="container">
        <h1>Perfiles Enviados a Validación</h1>
        <p>Aquí puedes ver el estado de los perfiles que has enviado para su revisión.</p>

        <div class="tabla-container">
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Nombre del Artista</th>
                        <th>Fecha de Envío</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enviados)): ?>
                        <tr>
                            <td colspan="4">No has enviado ningún perfil a validación.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enviados as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nombre_artista']); ?></td>
                                <td><?php echo htmlspecialchars($item['fecha_envio']); ?></td>
                                <td>
                                    <span class="estado-badge estado-<?php echo strtolower(htmlspecialchars($item['estado'])); ?>">
                                        <?php echo htmlspecialchars($item['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>src/views/pages/public/artista.php?id=<?php echo $item['id']; ?>" class="btn-accion">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include __DIR__ . '/../../../components/footer.php'; ?>

</body>
</html>