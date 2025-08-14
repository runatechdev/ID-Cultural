<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Inicio - ID Cultural";
$specific_css_files = ['index.css'];
include(__DIR__ . '/../components/header.php');
?>
<body>

  <?php include __DIR__ . '/../components/navbar.php'; ?>

  <main>
    <!-- ===== INICIO: CARRUSEL DENTRO DEL CONTENIDO PRINCIPAL ===== -->
    <div class="container my-5">
      <header class="hero-carousel">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://placehold.co/1200x450/367789/FFFFFF?text=Cultura+Santiagueña" class="d-block w-100" alt="Cultura de Santiago del Estero">
              <div class="carousel-caption d-none d-md-block">
                <h5>Visibilizar y Preservar</h5>
                <p>Un espacio para la identidad artística y cultural de Santiago del Estero.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="https://placehold.co/1200x450/C30135/FFFFFF?text=Nuestros+Artistas" class="d-block w-100" alt="Artistas locales">
              <div class="carousel-caption d-none d-md-block">
                <h5>Conoce a Nuestros Artistas</h5>
                <p>Explora la trayectoria de talentos locales, tanto actuales como históricos.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="https://placehold.co/1200x450/efc892/333333?text=Biblioteca+Digital" class="d-block w-100" alt="Biblioteca digital">
              <div class="carousel-caption d-none d-md-block">
                <h5>Biblioteca Digital</h5>
                <p>Accede a un archivo único con material exclusivo de nuestra región.</p>
              </div>
            </div>
          </div>
        </div>
      </header>
    </div>
    <!-- ===== FIN: CARRUSEL ===== -->

    <!-- ===== INICIO: SECCIÓN DE BIENVENIDA ===== -->
    <section class="container text-center welcome-section">
      <h1 class="display-4">Bienvenidos a ID Cultural</h1>
      <p class="lead col-lg-8 mx-auto">
        <strong>ID Cultural</strong> es una plataforma digital dedicada a visibilizar, preservar y promover la identidad artística y cultural de Santiago del Estero. Te invitamos a explorar, descubrir y formar parte de este espacio pensado para fortalecer nuestras raíces.
      </p>
      <p class="h5"><em>La identidad de un pueblo, en un solo lugar.</em></p>
    </section>
    <!-- ===== FIN: BIENVENIDA ===== -->

    <!-- ===== INICIO: SECCIÓN DE NOTICIAS ===== -->
    <section class="container my-5">
      <h2 class="text-center display-5 mb-4">Últimas Noticias</h2>
      <div id="contenedor-noticias" class="row g-4">
        <!-- Tarjetas de noticias se insertarán aquí -->
      </div>
    </section>
    <!-- ===== FIN: NOTICIAS ===== -->
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
