<?php
?>

<header class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container d-flex align-items-center justify-content-between">

    <a class="navbar-brand me-auto" href="/index.php">
      <img src="/static/img/SANTAGO-DEL-ESTERO-2022.svg" alt="Logo" height="40">
    </a>

    <h4 class="m-0 text-white fw-bold text-center flex-grow-1">ID Cultural</h4>

    <div>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <nav class="collapse navbar-collapse" id="navbarNav">
        <!--  Añadimos 'align-items-center' para asegurar la alineación vertical de todos los elementos -->
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="/index.php"><i data-lucide="house"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="/wiki.php">Wiki de artistas</a></li>

          <?php if (isset($_SESSION['user_data'])): ?>
            <!-- Se muestra si el usuario INICIÓ SESIÓN -->
            <li class="nav-item"><a class="nav-link" href="/dashboard.php">Mi Perfil</a></li>
            <li class="nav-item"><a class="btn btn-danger rounded-pill btn-sm" href="/logout.php">Cerrar Sesión</a></li>
          <?php else: ?>
            <!-- Se muestra si el usuario NO ha iniciado sesión (invitado) -->
            <li class="nav-item"><a class="nav-link" href="/src/views/pages/auth/login.php">Iniciar Sesión</a></li>
            <!--  Añadimos 'rounded-pill' para que el botón sea una píldora -->
            <li class="nav-item">
              <a class="btn btn-outline-light rounded-pill btn-sm" href="/src/views/pages/auth/registro.php">Crear cuenta</a>
            </li>
          <?php endif; ?>

        </ul>
      </nav>
    </div>

  </div>
</header>