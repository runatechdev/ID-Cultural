<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
$page_title = "Login - ID Cultural";
$specific_css_files = ['login.css']; // <-- ¡LA MAGIA ESTÁ AQUÍ!
// 2. Incluye el componente del header (que contiene <!DOCTYPE>, <head>, etc.)
// ¡AQUÍ ESTÁ LA LÍNEA QUE PREGUNTAS!
include(__DIR__ . '/../../../../../components/header.php');
?>

<body>

  <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

  <main class="login-container">
    <div class="login-box">
      <h2 class="mb-4 text-center">Iniciar Sesión</h2>

      <form id="loginForm" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="tu@correo.com" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña" required>
        </div>

        <div class="d-grid gap-2 mt-4">
          <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
        </div>

        <p class="text-center mt-3"><a href="#">¿Olvidaste tu contraseña?</a></p>
      </form>
    </div>
  </main>

  <?php include("../../../../../components/footer.php"); ?>

  <!-- Scripts -->
  <script src="/static/js/main.js"></script>
  <script src="/static/js/login.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

</body>

</html>