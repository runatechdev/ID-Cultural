<?php
// 1. Definimos la ruta raíz y cargamos la configuración
define('ROOT_PATH', realpath(__DIR__ . '/../../../../'));
require_once(ROOT_PATH . '/config.php');

// 2. Lógica de seguridad: solo usuarios logueados pueden estar aquí
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'src/views/pages/auth/login.php');
    exit();
}

$page_title = "Cambiar Contraseña - ID Cultural";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css" />
</head>

<body>

    <?php
    // 3. Incluimos el navbar desde su ubicación REAL
    include(ROOT_PATH . '/components/navbar.php');
    ?>

    <main class="blanqueo-container">
        <h2>Cambiar mi Contraseña</h2>
        <p>Para cambiar tu contraseña, ingresa tu clave actual seguida de la nueva.</p>

        <form id="cambiarClaveForm">
            <label for="clave_actual">Contraseña Actual:</label>
            <input type="password" id="clave_actual" name="clave_actual" required>

            <label for="nueva_clave">Nueva Contraseña:</label>
            <input type="password" id="nueva_clave" name="nueva_clave" required>

            <label for="confirmar_clave">Confirmar Nueva Contraseña:</label>
            <input type="password" id="confirmar_clave" name="confirmar_clave" required>

            <input type="submit" value="Actualizar Contraseña">
        </form>
        <div id="mensaje" class="mensaje" hidden></div>
    </main>

    <?php
    // 4. Incluimos el footer desde su ubicación REAL
    include(ROOT_PATH . '/components/footer.php');
    ?>

    <script src="<?php echo BASE_URL; ?>static/js/cambiar_clave.js"></script>
</body>

</html>