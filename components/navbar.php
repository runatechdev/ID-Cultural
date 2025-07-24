<header class="navbar">
  <div class="logo">
    <img src="/static/img/SANTAGO-DEL-ESTERO-2022.svg" alt="Logo">
  </div>
  <h1 class="title">ID Cultural</h1>
  <nav class="animate__animated animate__fadeInDown">
    <ul>
      <li>
        <!-- El enlace de inicio siempre está -->
        <a class="menu" href="/" class="menu"> <i data-lucide="house"></i> Inicio
        </a>
      </li>
      <li>
        <!-- Enlace a la Wiki de Artistas, siempre visible -->
        <a class="menu" href="/wiki.php">Wiki de artistas</a>
      </li>

      <?php
      // Lógica PHP para verificar si el usuario ha iniciado sesión
      if (isset($_SESSION['user_id'])) {
        // Si el usuario está logueado, mostrar el enlace al dashboard/perfil y cerrar sesión
        $dashboard_link = '/'; // Por defecto, volver a inicio
        if (isset($_SESSION['user_role'])) {
          switch ($_SESSION['user_role']) {
            case 'admin':
              $dashboard_link = '/src/views/pages/admin/dashboard-adm.php';
              break;
            case 'editor':
              $dashboard_link = '/src/views/pages/editor/panel_editor.php';
              break;
            case 'validador':
              $dashboard_link = '/src/views/pages/validador/panel_validador.php';
              break;
            case 'artista':
              $dashboard_link = '/src/views/pages/user/dashboard-user.php';
              break;
              // No hay default aquí porque ya se maneja el caso no logueado
          }
        }
      ?>
        <!-- Enlace al Dashboard/Panel del usuario logueado -->
        <li><a class="menu" href="<?php echo $dashboard_link; ?>">Mi Panel</a></li>
        <!-- Enlace para cerrar sesión -->
        <li><a class="btn" href="/logout.php">Cerrar Sesion</a></li>
      <?php
      } else {
        // Si el usuario NO está logueado, mostrar Iniciar Sesión y Crear Cuenta
      ?>
        <li><a class="menu" href="/src/views/pages/auth/login.php">Iniciar Sesion</a></li>
        <li><a class="btn" href="/src/views/pages/auth/registro.php">Crear cuenta</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
</header>