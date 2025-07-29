<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
$page_title = "Login - DNI Cultural";
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Login - DNI Cultural</title>
    
<!-- Bootstrap Core -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootswatch Theme -->
<link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

  <link rel="stylesheet" href="/static/css/main.css">
  <link rel="stylesheet" href="/static/css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>

  <?php
  include(__DIR__ . '/../../../../../components/navbar.php');
  ?>

  <main>
    <section class="login-box">
      <h2>Iniciar sesión</h2>

      <form id="loginForm" novalidate>
        <label for="email">Correo:</label>
        <input type="text" id="email" name="email" placeholder="Ingrese su correo" autocomplete="email" required />

        <label for="clave">Contraseña:</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Ingrese su contraseña"
            autocomplete="current-password" required>
        </div>

        <input type="submit" value="Ingresar">

        <p class="forgot-pass"><a href="#">¿Olvidaste tu contraseña?</a></p>
      </form>

      <p id="mensaje-error" class="error-msg" hidden>Usuario o contraseña incorrectos.</p>
    </section>
  </main>
  
<?php include("../../../../../components/footer.php"); ?>

  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="/ID-Cultural/static/js/login.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

</body>

</html>