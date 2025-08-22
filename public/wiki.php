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
  <link rel="stylesheet" href="/static/css/carousel.css" /> <!-- Estilo específico de la Biblioteca Digital -->
</head>

<body>

  <?php include("../components/navbar.php"); ?>

  <!-- Carousel de artistas -->
      <div id="carouselArtistas" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">

            <!-- Ejemplo de ítem de carrusel -->
            <div class="carousel-item active">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 p-3"
                    style="min-height: 420px;">
                    <div class="carousel-img-wrapper">
                        <img src="/static/img/famosos/merce.jpg" alt="Mercedes Sosa" class="img-fluid carousel-img">
                    </div>
                    <div class="text-start text-md-left" style="max-width: 500px;">
                        <h3>Mercedes Sosa</h3>
                        <p>Cantante y referente del folklore argentino.</p>
                    </div>
                </div>
            </div>

            <!-- Repetí el mismo bloque para los demás artistas, solo cambiando contenido -->

            <!-- Antonio Berni -->
            <div class="carousel-item">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 p-3"
                    style="min-height: 420px;">
                    <div class="carousel-img-wrapper">

                        <img src="/static/img/famosos/berni.jpg" alt="Antonio Berni" class="img-fluid"
                            style="max-height: 400px; border-radius: 8px;">
                    </div>
                    <div class="text-start text-md-left" style="max-width: 500px;">


                        <h3>Antonio Berni</h3>
                        <p>Pintor y grabador destacado por su arte social.</p>

                    </div>
                </div>
            </div>

            <!-- Leo Dan -->
            <div class="carousel-item ">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 p-3"
                    style="min-height: 420px;">
                    <div class="carousel-img-wrapper">
                        <img src="/static/img/famosos/leo.jpeg" alt="Leo Dan" class="img-fluid"
                            style="max-height: 400px; border-radius: 8px;">

                    </div>
                    <div class="text-start text-md-left" style="max-width: 500px;">
                        <h3>Leo Dan</h3>
                        <p>Cantautor romántico y popular, su voz marcó generaciones con éxitos como “Te he prometido” y
                            “Esa pared”.</p>


                    </div>
                </div>

            </div>

            <!-- Julio Cortázar -->
            <div class="carousel-item ">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 p-3"
                    style="min-height: 420px;">
                    <div class="carousel-img-wrapper">
                        <img src="/static/img/famosos/cortazar.jpeg" alt="Julio Cortázar" class="img-fluid"
                            style="max-height: 400px; border-radius: 8px;">
                    </div>
                    <div class="text-start text-md-left" style="max-width: 500px;">
                        <h3>Julio Cortázar</h3>
                        <p>Escritor de narrativa revolucionaria, maestro del cuento y lo fantástico.</p>
                    </div>
                </div>
            </div>

            <!-- Julio Bocca -->
            <div class="carousel-item ">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 p-3"
                    style="min-height: 420px;">
                    <div class="carousel-img-wrapper">
                        <img src="/static/img/famosos/julio.jpeg" alt="Julio Bocca" class="img-fluid"
                            style="max-height: 400px; border-radius: 8px;">
                    </div>
                    <div class="text-start text-md-left" style="max-width: 500px;">
                        <h3>Julio Bocca</h3>
                        <p>Bailarín de fama internacional, ícono del ballet argentino contemporáneo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- 🔘 Botones para abrir cada offcanvas con estilo uniforme -->
        <!-- <div class="d-flex flex-wrap gap-3 mb-4">  -->
          <div class="d-flex flex-wrap gap-3 mb-4 justify-content-center align-items-center wh-100">
        
          <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarMes">
            Artista del Mes
            </button>
            <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarConsagrados">
              Artistas Consagrados
            </button>
            <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarRevelacion">
              Artista Revelación
            </button>
          </div>

  <main class="container py-4">

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

  </main>

  <h2>Artistas Registrados</h2>
     <main class="wiki-layout">
        <aside class="sidebar">
  <button class="category-btn">
    <i class="bi bi-music-note"></i> Música
  </button>
  <button class="category-btn">
    <i class="bi bi-palette"></i> Arte
  </button>
  <button class="category-btn">
    <i class="bi bi-person-dancing"></i> Danza
  </button>
  <button class="category-btn">
    <i class="bi bi-pen"></i> Escritores
  </button>
  <button class="category-btn">
    <i class="bi bi-brush"></i> Artesanía
  </button>
  <button class="category-btn">
    <i class="bi bi-camera-reels"></i> Audiovisual
  </button>
  <button class="category-btn">
    <i class="bi bi-mic-fill"></i> Teatro
  </button>
  <button class="category-btn">
    <i class="bi bi-book"></i> Literatura
  </button>
  <button class="category-btn">
    <i class="bi bi-bounding-box"></i> Escultura
  </button>
</aside>
     <section class="main-content" id="biografias">

        </section>
</main>

  <?php include("../components/footer.php"); ?>

  <!-- Scripts -->
  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="/static/js/wiki.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
</body>

</html>
