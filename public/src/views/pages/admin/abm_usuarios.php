<?php

require_once __DIR__ . "../../../../../../config.php";

$page_title = "Panel de Gestión - ID Cultural";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/abm_usuarios.css" />
</head>

<body>

    <?php
    include __DIR__ . "/../../../../../components/navbar.php";
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
                </tbody>
            </table>
        </section>
    </main>

    <?php
    include __DIR__ . "/../../../../../components/footer.php";
    ?>

</body>

</html>