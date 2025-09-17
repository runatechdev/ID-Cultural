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
$specific_css_files = ['index.css'];

include(__DIR__ . '/../components/header.php');
?>

<body>

  <?php include __DIR__ . '/../components/navbar.php'; ?>

  <main>
    <div class="container my-5">
      <header class="hero-carousel">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <!-- --- 3. USAR DATOS DINÁMICOS EN EL CARRUSEL --- -->
            <div class="carousel-item active">
              <img src="<?php echo htmlspecialchars($site_content['carousel_image_1'] ?? ''); ?>" class="d-block w-100" alt="Cultura de Santiago del Estero">
              <div class="carousel-caption d-none d-md-block">
                <h5>Visibilizar y Preservar</h5>
                <p>Un espacio para la identidad artística y cultural de Santiago del Estero.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="<?php echo htmlspecialchars($site_content['carousel_image_2'] ?? ''); ?>" class="d-block w-100" alt="Artistas locales">
              <div class="carousel-caption d-none d-md-block">
                <h5>Conoce a Nuestros Artistas</h5>
                <p>Explora la trayectoria de talentos locales, tanto actuales como históricos.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="<?php echo htmlspecialchars($site_content['carousel_image_3'] ?? ''); ?>" class="d-block w-100" alt="Biblioteca digital">
              <div class="carousel-caption d-none d-md-block">
                <h5>Biblioteca Digital</h5>
                <p>Accede a un archivo único con material exclusivo de nuestra región.</p>
              </div>
            </div>
          </div>
        </div>
      </header>
    </div>

    <!-- --- 4. USAR DATOS DINÁMICOS EN LA BIENVENIDA --- -->
    <section class="container text-center welcome-section">
      <h1 class="display-4"><?php echo htmlspecialchars($site_content['welcome_title'] ?? 'Bienvenidos a ID Cultural'); ?></h1>
      <div class="lead col-lg-8 mx-auto">
        <?php echo $site_content['welcome_paragraph'] ?? ''; // No usamos htmlspecialchars para permitir <strong>, etc. ?>
      </div>
      <p class="h5 mt-3"><em><?php echo htmlspecialchars($site_content['welcome_slogan'] ?? 'La identidad de un pueblo, en un solo lugar.'); ?></em></p>
    </section>
    
    <section class="container my-5">
      <h2 class="text-center display-5 mb-4">Últimas Noticias</h2>
      <div id="contenedor-noticias" class="row g-4">
        <!-- Las noticias se cargan dinámicamente con JS, esto no cambia -->
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
  <script>
      const BASE_URL = '<?php echo BASE_URL; ?>';
  </script>
  <script src="<?php echo BASE_URL; ?>static/js/index.js"></script>

</body>
</html>
