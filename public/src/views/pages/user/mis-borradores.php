<?php require_once __DIR__ . '/../../../../../config.php'; ?>
<?php
// Lógica de PHP para esta página (ej: generar un token de seguridad)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Borrador - ID Cultural</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/main.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body>

    <?php include('../../../components/navbar.php'); ?>

    <main class="dashboard-container">
        <div class="info-proceso">
            <h2>Crear Borrador de Perfil Cultural</h2>
            <p>Completa el formulario para dar de alta un nuevo perfil de artista. Puedes guardar tu progreso o enviarlo a validación cuando esté listo.</p>
        </div>

        <form id="form-borrador" class="borrador-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="titulo">Título de la Obra:</label>
            <input type="text" id="titulo" name="titulo" required>

            <button type="submit" id="btn-guardar" class="btn-guardar">
                <i class="fa-solid fa-save"></i> Guardar Borrador
            </button>
            <button type="button" id="btn-enviar" class="btn-enviar">
                <i class="fa-solid fa-paper-plane"></i> Enviar para Validación
            </button>
        </form>
        <div id="form-message" class="form-message" hidden></div>
    </main>

    <?php include('../../../components/footer.php'); ?>

    <script src="<?php echo BASE_URL; ?>static/js/borradores.js"></script>
</body>

</html>