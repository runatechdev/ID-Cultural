<?php
session_start();
require_once __DIR__ . "../../../../../../config.php";  
$page_title = "Panel de Gestión - ID Cultural";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard-adm.css">
</head>
<body>

<?php 
include __DIR__ . "/../../../../../components/navbar.php"; 
?>

    <main class="panel-gestion-container">
        <h1>Panel de Gestión</h1>

        <ul class="dashboard-menu">
            <li class="dashboard-item">
                <a href="<?php echo BASE_URL; ?>src/views/pages/admin/abm_usuarios.php">
                    <img src="<?php echo BASE_URL; ?>static/img/perfil-del-usuario.png" alt="Icono Usuarios" class="dashboard-icon">
                    Gestionar Usuarios
                </a>
            </li>
            <li class="dashboard-item">
                <a href="<?php echo BASE_URL; ?>src/views/pages/admin/abm_artistas.php">
                    <img src="<?php echo BASE_URL; ?>static/img/paleta-de-pintura.png" alt="Icono Artistas" class="dashboard-icon">
                 Gestionar Artistas
                </a>
            </li>
            <li class="dashboard-item">
                <a href="<?php echo BASE_URL; ?>src/views/pages/admin/blanqueo_clave_admin.php">
                    <img src="<?php echo BASE_URL; ?>static/img/candado.png" alt="Icono Blanqueo" class="dashboard-icon">
                    Reiniciar Clave 
                </a>
            </li>
            <li class="dashboard-item">
                <a href="<?php echo BASE_URL; ?>src/views/pages/admin/cambiar_clave.php">
                    <img src="<?php echo BASE_URL; ?>static/img/correo-electronico.png" alt="Icono Cambiar Clave" class="dashboard-icon">
                    Cambiar Clave por Correo
                </a>
            </li>
            <li class="dashboard-item">
                <a href="<?php echo BASE_URL; ?>src/views/pages/admin/estado_solicitud.php">
                    <img src="<?php echo BASE_URL; ?>static/img/lectura.png" alt="Icono Estado Solicitud" class="dashboard-icon">
                    Ver  Solicitud 
                </a>
            </li>
        </ul>
    </main>

<?php 
include __DIR__ . "/../../../../../components/footer.php"; 
?>

</body>
</html>