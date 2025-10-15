  <?php
  // Si tienes alguna lógica de sesión al inicio, déjala aquí.
  ?>

  <header class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="d-flex align-items-center flex-grow-1">
  <img src="<?php echo BASE_URL; ?>static/img/huella-idcultural.png" alt="Logo ID Cultural" class="logo-idcultural me-2" width="40" height="auto">
  <h4 class="m-0 text-white fw-bold">ID Cultural</h4>
</div>


      <div>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <nav class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            
            <li class="nav-item"><a class="nav-link" href="/index.php">Inicio</a></li>

            <!-- Botón de Búsqueda -->
            <li class="nav-item">
              <button class="btn btn-link nav-link" id="open-search-btn" aria-label="Abrir búsqueda">
                <i class="bi bi-search"></i>
              </button>
            </li>

            <li class="nav-item"><a class="nav-link" href="/wiki.php">Wiki de artistas</a></li>

            <?php if (isset($_SESSION['user_data'])): ?>
              <?php
                // --- INICIO: LÓGICA DE ROLES MEJORADA ---
                $user_role = $_SESSION['user_data']['role'];
                $profile_link = '';
                $profile_text = '';

                if ($user_role === 'admin') {
                    $profile_text = 'Panel de Control';
                    $profile_link = '/src/views/pages/admin/dashboard-adm.php';
                } elseif ($user_role === 'editor') {
                    $profile_text = 'Panel de Control';
                    $profile_link = '/src/views/pages/editor/panel_editor.php';
                } elseif ($user_role === 'validador') {
                    // Asumimos que el validador va a la página de solicitudes
                    $profile_text = 'Panel de Control';
                    $profile_link = '/src/views/pages/admin/estado_solicitud.php';
                } else {
                    // Esto se aplica para el rol 'artista' y cualquier otro
                    $profile_text = 'Mi Perfil';
                    $profile_link = '/src/views/pages/artista/dashboard-artista.php'; // Enlace para artistas
                }
              ?>
              <!-- Se muestra si el usuario INICIÓ SESIÓN -->
              <li class="nav-item"><a class="nav-link" href="<?php echo $profile_link; ?>"><?php echo $profile_text; ?></a></li>
              <li class="nav-item"><a class="btn btn-danger rounded-pill btn-sm" href="/logout.php">Cerrar Sesión</a></li>
              <!-- --- FIN: LÓGICA DE ROLES --- -->
            <?php else: ?>
              <!-- Se muestra si el usuario NO ha iniciado sesión (invitado) -->
              <li class="nav-item"><a class="nav-link" href="/src/views/pages/auth/login.php">Iniciar Sesión</a></li>
              <li class="nav-item">
                <a class="btn btn-outline-light rounded-pill btn-sm" href="/src/views/pages/auth/registro.php">Crear cuenta</a>
              </li>
            <?php endif; ?>

          </ul>
        </nav>
      </div>

    </div>
  </header>
  <!--<svg viewBox="0 0 1200 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100px;">
  <path d="
    M0,100 L25,0 L50,100 L75,0 L100,100 L125,0 L150,100 L175,0 L200,100 L225,0 L250,100 L275,0 L300,100 L325,0 L350,100 L375,0 L400,100 L425,0 L450,100 L475,0 L500,100 L525,0 L550,100 L575,0 L600,100 L625,0 L650,100 L675,0 L700,100 L725,0 L750,100 L775,0 L800,100 L825,0 L850,100 L875,0 L900,100 L925,0 L950,100 L975,0 L1000,100 L1025,0 L1050,100 L1075,0 L1100,100 L1125,0 L1150,100 L1175,0 L1200,100 Z"
    fill="url(#zigzagGradient)" />
  <defs>
    <linearGradient id="zigzagGradient" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#00BFFF" />
      <stop offset="20%" stop-color="#32CD32" />
      <stop offset="40%" stop-color="#FFD700" />
      <stop offset="60%" stop-color="#FF8C00" />
      <stop offset="80%" stop-color="#FF1493" />
      <stop offset="100%" stop-color="#00FFFF" />
    </linearGradient>
  </defs>
</svg>-->


<!-- Estructura de la ventana de búsqueda (sin cambios) -->
<div id="search-overlay" class="search-overlay">
  <button id="close-search-btn" class="btn-close-search" aria-label="Cerrar búsqueda">&times;</button>
  <div class="search-overlay-content">
    <form id="search-form" action="/busqueda.php" method="get">
      <input type="search" name="q" class="form-control-search" placeholder="Buscar artistas, obras, eventos..." autofocus>
      <button type="submit" class="btn-search-submit" aria-label="Buscar">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
