<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Biblioteca Digital - DNI Cultural</title>

  <!-- Bootstrap Core + Quartz Theme -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css" rel="stylesheet" />

  <!-- SweetAlert2 + Animaciones -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="/static/css/main.css" />
  <link rel="stylesheet" href="/static/css/wiki.css" />
</head>

<body>

  <?php include("../components/navbar.php"); ?>

  <main class="container py-4">

    <!-- 🔘 Botones para abrir cada offcanvas -->
    <!-- 🔘 Botones para abrir cada offcanvas con estilo uniforme -->
<div class="d-flex flex-wrap gap-3 mb-4">
  <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarFamosos">
    🎨 Artistas Famosos
  </button>
  <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarMes">
    🌟 Artista del Mes
  </button>
  <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarConsagrados">
    🏆 Artistas Consagrados
  </button>
  <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarRevelacion">
    🚀 Artista Revelación
  </button>
</div>


    <!-- 🎨 Artistas Famosos -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarFamosos">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">🎨 Artistas Famosos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
          <div class="card">
            <img src="/static/img/merce.jpg" class="card-img-top" alt="Mercedes Sosa" />
            <div class="card-body">
              <h5 class="card-title">Mercedes Sosa</h5>
              <p class="card-text">Cantante y referente del folklore argentino.</p>
            </div>
          </div>
          <div class="card">
            <img src="/static/img/berni.jpg" class="card-img-top" alt="Antonio Berni" />
            <div class="card-body">
              <h5 class="card-title">Antonio Berni</h5>
              <p class="card-text">Pintor y grabador destacado por su arte social.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 🌟 Artista del Mes -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMes">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">🌟 Artista del Mes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
          <div class="card">
            <img src="/static/img/arte/artista3.jpeg" class="card-img-top" alt="Artista del Mes" />
            <div class="card-body">
              <h5 class="card-title">FMaxi Padilla</h5>
              <p class="card-text">Reconocimiento especial por su impacto cultural reciente.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 🏆 Artistas Consagrados -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarConsagrados">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">🏆 Artistas Consagrados</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
          <div class="card">
            <img src="/static/img/artesania/10.jpeg" class="card-img-top" alt="Artista Consagrado" />
            <div class="card-body">
              <h5 class="card-title">Marcos Romano</h5>
              <p class="card-text">Trayectoria destacada y legado artístico reconocido.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 🚀 Artista Revelación -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarRevelacion">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">🚀 Artista Revelación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
          <div class="card">
            <img src="/static/img/audiovisual/13.jpeg" class="card-img-top" alt="Artista Revelación" />
            <div class="card-body">
              <h5 class="card-title">Sebastian Ruiz</h5>
              <p class="card-text">Talento emergente que está revolucionando la escena.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 📚 Sección principal -->
    <section id="biografias">
      <h2 class="mb-4">Artistas Registrados</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card h-100">
            <img src="/static/img/juanperez.jpg" class="card-img-top" alt="Juan Pérez" />
            <div class="card-body">
              <h5 class="card-title">Juan Pérez</h5>
              <p class="card-text">Guitarrista y compositor de música folclórica.</p>
              <a href="#" class="btn btn-primary">Leer Biografía Completa</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card h-100">
            <img src="/static/img/dem.jpg" class="card-img-top" alt="María González" />
            <div class="card-body">
              <h5 class="card-title">María González</h5>
              <p class="card-text">Escritora y poeta contemporánea.</p>
              <a href="#" class="btn btn-primary">Leer Biografía Completa</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include("../components/footer.php"); ?>

  <!-- Scripts -->
  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
</body>

</html>
