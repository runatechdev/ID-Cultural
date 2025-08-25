<?php
// Si tienes alguna lógica de sesión al inicio, déjala aquí.
?>

<header class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container d-flex align-items-center justify-content-between">

    <h4 class="m-0 text-white fw-bold text-left flex-grow-1">ID Cultural</h4>

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

<!-- Estructura de la ventana de búsqueda (sin cambios) -->
<div id="search-overlay" class="search-overlay">
  <button id="close-search-btn" class="btn-close-search" aria-label="Cerrar búsqueda">&times;</button>
  <div class="search-overlay-content">
    <form id="search-form" action="/search.php" method="get">
      <input type="search" name="q" class="form-control-search" placeholder="Buscar artistas, obras, eventos..." autofocus>
      <button type="submit" class="btn-search-submit" aria-label="Buscar">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
</div>
