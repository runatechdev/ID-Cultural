<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Login - DNI Cultural</title>
  <link rel="stylesheet" href="/ID-Cultural/static/css/main.css">
  <link rel="stylesheet" href="/ID-Cultural/static/css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>

    <?php include("../../../../components/navbar.php"); ?>

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

<?php include("../../../../components/footer.php"); ?>

  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="/ID-Cultural/static/js/login.js"></script>

</body>

</html>