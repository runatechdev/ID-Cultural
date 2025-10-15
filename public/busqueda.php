<?php
session_start();
require_once __DIR__ . '/../config.php';
// --- 1. CONECTAR A LA BASE DE DATOS ---
require_once __DIR__ . '/../backend/config/connection.php';

// --- 2. OBTENER EL CONTENIDO DEL SITIO DESDE LA BD ---
try {
    $stmt = $pdo->prepare("SELECT content_key, content_value FROM site_content");
    $stmt->execute();
    // Guardamos todo el contenido en un array asociativo
    $site_content = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    // Si hay un error, usamos valores por defecto para que la página no se rompa
    $site_content = [];
    // Opcional: registrar el error
    error_log("Error al cargar el contenido del sitio: " . $e->getMessage());
}

// --- Variables para el header ---
$page_title = "Inicio - ID Cultural";
$specific_css_files = ['busqueda.css'];

include(__DIR__ . '/../components/header.php');
?>

<body>

  <?php include("../components/navbar.php"); ?>

  <!-- 🔘 Botonera de Categorías -->
  <section class="contenedor">
    <h2 class="titulo-seccion">Explorar por Categoría</h2>
    <div class="categorias">
      <?php
      $categorias = ["Danza", "Música", "Arte", "Teatro", "Literatura", "Escultura", "Artesanía", "Audiovisual"];
      foreach ($categorias as $cat) {
        echo '<a href="?categoria=' . urlencode($cat) . '" class="btn-categoria">' . $cat . '</a>';
      }
      ?>
    </div>
  </section>

  <!-- 🎭 Resultados por Categoría -->
  <?php
  $mapCategorias = [
    "Danza" => "danza",
    "Música" => "musica",
    "Arte" => "arte",
    "Teatro" => "teatro",
    "Literatura" => "literatura",
    "Escultura" => "escultura",
    "Artesanía" => "artesania",
    "Audiovisual" => "audiovisual"
  ];

  if (isset($_GET['categoria']) && array_key_exists($_GET['categoria'], $mapCategorias)):
    $categoriaVisible = htmlspecialchars($_GET['categoria']);
    $categoria = $mapCategorias[$_GET['categoria']];
    $imgDir = __DIR__ . "/../public/static/img/" . $categoria;
    $imgUrlBase = "/public/static/img/" . $categoria . "/";
    $defaultImg = "/public/static/img/default.jpg";
  ?>
    <section class="contenedor">
      <h3 class="titulo-resultados">Resultados para: <?= $categoriaVisible ?></h3>
      <div class="resultados">
        <?php
        for ($i = 1; $i <= 3; $i++):
          $nombre = ucfirst($categoriaVisible) . " Artista " . $i;
          $descripcion = "Descripción breve del artista relacionado con " . $categoriaVisible . ".";
          $archivo = "artista{$i}.jpeg";
          $imgPath = file_exists($imgDir . "/" . $archivo) ? $imgUrlBase . $archivo : $defaultImg;
        ?>
          <div class="card animate__animated animate__fadeIn">
            <img src="<?= $imgPath ?>" alt="<?= $nombre ?>" style="width:100%; height:200px; object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title"><?= $nombre ?></h5>
              <p class="card-text"><?= $descripcion ?></p>
              <a href="#" class="btn-biografia">Ver Biografía</a>
            </div>
          </div>
        <?php endfor; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php include("../components/footer.php"); ?>

  <!-- Scripts -->
  <script src="/static/js/main.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
  <script src="/static/js/navbar.js"></script>
</body>
</html>