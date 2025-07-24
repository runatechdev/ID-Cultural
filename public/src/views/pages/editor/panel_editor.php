<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Noticias del Home</title>
  <link rel="stylesheet" href="../../../../static/css/main.css">
  <link rel="stylesheet" href="../../../../static/css/editor_noticias.css">
</head>
<body>
  <?php include("../../../../components/navbar.php"); ?>

  <main class="noticia-main">
    <h1>Noticias del Home</h1>

   <form id="form-noticia">
  <input type="hidden" id="noticia-id" />

  <label for="titulo">Título:</label>
  <input type="text" id="titulo" required>

  <label for="contenido">Contenido:</label>
  <textarea id="contenido" rows="5" required></textarea>

  <label for="imagen">Imagen (opcional):</label>
  <input type="file" id="imagen" accept="image/*">

  <button type="submit">Guardar Noticia</button>
</form>


    <div id="mensaje-confirmacion" hidden>✅ Noticia guardada correctamente.</div>

    <hr>

    <h2>Listado de Noticias</h2>
    <table id="tabla-noticias">
      <thead>
        <tr>
          <th>Título</th>
          <th>Contenido</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Noticias cargadas -->
      </tbody>
    </table>
  </main>

  <?php include("../../../../components/footer.php"); ?>

  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="/ID-Cultural/static/js/noticias_editor.js"></script>
  
</body>
</html>
