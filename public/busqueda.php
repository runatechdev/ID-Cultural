<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Búsqueda por Categoría</title>
  
  <!-- Bootstrap Core + Quartz Theme -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css" rel="stylesheet" />
  
  <link rel="stylesheet" href="/static/css/busqueda.css" />
</head>

<body>

  <?php include("../components/navbar.php"); ?>

  <!-- 🔘 Botonera de Categorías -->
  <section class="container mt-4">
    <h2 class="mb-3">Explorar por Categoría</h2>
    <div class="d-flex flex-wrap gap-3 justify-content-start">
      <?php
      $categorias = ["Danza", "Música", "Arte", "Teatro", "Literatura", "Escultura", "Artesanía", "Audiovisual"];
      foreach ($categorias as $cat) {
        echo '<a href="?categoria=' . urlencode($cat) . '" class="btn btn-outline-dark">' . $cat . '</a>';
      }
      ?>
    </div>
  </section>

  <!-- 🎭 Resultados por Categoría -->
<?php if (isset($_GET['categoria']) && in_array($_GET['categoria'], $categorias)): ?>
  <section class="container mt-5">
    <h3 class="mb-4">Resultados para: <?= htmlspecialchars($_GET['categoria']) ?></h3>
    <div class="row g-4">
      <?php
      $categoria = strtolower($_GET['categoria']);
      $cantidad = rand(1, 3); // Al menos 1 resultado
      $imgDir = $_SERVER['DOCUMENT_ROOT'] . "/public/static/img/" . $categoria;
      $imgUrlBase = "/public/static/img/" . $categoria . "/";
      $defaultImg = "/public/static/img/default.jpg";

      // Obtener lista de imágenes disponibles
      $imagenes = [];
      if (is_dir($imgDir)) {
        $imagenes = array_values(array_filter(scandir($imgDir), function($file) use ($imgDir) {
          return is_file($imgDir . "/" . $file) && preg_match('/\.(jpg|jpeg|png|webp)$/i', $file);
        }));
      }

      for ($i = 0; $i < $cantidad; $i++):
        $nombre = ucfirst($categoria) . " Artista " . ($i + 1);
        $descripcion = "Descripción breve del artista relacionado con " . $categoria . ".";

        // Usar imagen si existe, sino fallback
        $imgPath = isset($imagenes[$i]) ? $imgUrlBase . $imagenes[$i] : $defaultImg;
      ?>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= $nombre ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $nombre ?></h5>
              <p class="card-text"><?= $descripcion ?></p>
              <a href="#" class="btn btn-dark">Ver Biografía</a>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </section>
<?php endif; ?>


  <?php include("../components/footer.php"); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
