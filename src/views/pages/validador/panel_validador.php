<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel del Validador - ID Cultural</title>
  <link rel="stylesheet" href="/ID-Cultural/static/css/main.css" />
  <link rel="stylesheet" href="./panel_validador.css" />
</head>
<body>

  <?php include "../../../../components/navbar.php"; ?>
  <header>
 
</header>



  <main class="container">
    <h1>Artistas Pendientes de Aprobación</h1>
    <table id="tabla-artistas">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Disciplina</th>
          <th>Localidad</th>
          <th>Estado</th>
          <th>Acciones</th>
          <th>Motivo del Rechazo</th>
        </tr>
      </thead>
      <tbody>
        <!-- Contenido dinámico con JS -->
      </tbody>
    </table>
  </main>

  <!-- Modal de Rechazo -->
  <div id="modal-rechazo" class="modal hidden">
    <div class="modal-content">
      <h2>Motivo del rechazo</h2>
      <textarea id="input-motivo" placeholder="Escriba el motivo aquí..."></textarea>
      <div class="modal-buttons">
        <button id="btn-cancelar" class="btn cancel">Cancelar</button>
        <button id="btn-confirmar" class="btn confirm">Confirmar</button>
      </div>
    </div>
  </div>

  <!-- <?php include "../../../../components/footer.php"; ?> -->

  <footer>
  <div class="footer-content">
    <h2>ID Cultural</h2>
    <div class="footer-links">
      <a href="index.html">Inicio</a>
      <a href="contacto.html">Contacto</a>
      <a href="creditos.html">Créditos</a>
    </div>
    <p>&copy; 2025 ID Cultural. Todos los derechos reservados.</p>
  </div>
</footer>

  <script src="./panel_validador.js"></script>
</body>
</html>
