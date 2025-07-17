<?php
// 1. Definimos la ruta raíz y cargamos la configuración
define('ROOT_PATH', realpath(__DIR__ . '/../../../../'));
require_once(ROOT_PATH . '/config.php');

// 2. Lógica de seguridad: verificar que solo un admin pueda ver esta página
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

// 3. Obtenemos los usuarios de la base de datos para la carga inicial
// En un caso real, esto vendría de un controlador:
// $usuarios = require_once(ROOT_PATH . '/backend/controllers/obtener_todos_los_usuarios.php');
// Por ahora, simulamos los datos:
$usuarios = [
    ['id' => 1, 'nombre' => 'Maximiliano Padilla', 'email' => 'maxi@test.com', 'role' => 'admin'],
    ['id' => 2, 'nombre' => 'Sandra Sanchez', 'email' => 'sandra@test.com', 'role' => 'editor'],
    ['id' => 3, 'nombre' => 'Sebastián Itse', 'email' => 'seba@test.com', 'role' => 'validador'],
    ['id' => 4, 'nombre' => 'Marcos Juarez', 'email' => 'marcos@test.com', 'role' => 'editor'],
];

$page_title = "Gestión de Usuarios - ID Cultural";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/abm_usuarios.css" />
</head>
<body>

<?php
  // 3. Incluimos el navbar desde su ubicación REAL
  include(ROOT_PATH . '/components/navbar.php');
  ?>

    <main>
        <section class="form-section">
            <h2>Gestión de Usuarios</h2>
            <form id="form-usuario" class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required />
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="email" required />
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="editor">Editor</option>
                        <option value="validador">Validador</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <button type="submit">➕ Agregar Usuario</button>
                </div>
            </form>
        </section>

        <section class="tabla-section">
            <h3>Usuarios Registrados</h3>
            <input type="text" id="buscador" placeholder="🔍 Buscar por nombre o correo...">
            <table class="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios-body">
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr data-id="<?php echo $usuario['id']; ?>">
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['role']); ?></td>
                            <td>
                                <button class="btn-editar">Editar</button>
                                <button class="btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php
  // 4. Incluimos el footer desde su ubicación REAL
  include(ROOT_PATH . '/components/footer.php');
  ?>

    <script>
        const initialUsers = <?php echo json_encode($usuarios); ?>;
    </script>
    <script src="<?php echo BASE_URL; ?>static/js/abm_usuarios.js"></script>
</body>
</html>