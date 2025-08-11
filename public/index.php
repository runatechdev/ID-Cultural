<?php
// 1. Inicia la sesión y define el título de la página
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Inicio - ID Cultural";

// 2. Incluye el componente del header (que contiene <!DOCTYPE>, <head>, etc.)
// ¡AQUÍ ESTÁ LA LÍNEA QUE PREGUNTAS!
include(__DIR__ . '/../components/header.php');
?>

<body>

  <?php
  include __DIR__ . '/../components/navbar.php';
  ?>

  <main>
    <section class="hero">
      <div class="hero-text">
        <h1>Bienvenidos a ID Cultural</h1>

        <p><strong>ID Cultural</strong> es una plataforma digital dedicada a visibilizar, preservar y promover la
          identidad artística y cultural de Santiago del Estero. Nuestro objetivo es ofrecer un espacio accesible y
          confiable donde se registre, documente y difunda la trayectoria de artistas locales, tanto actuales como
          históricos.</p>

        <h3>
          <span class="quichua-hover"></span> en ID Cultural?
        </h3>

        <ul>
          <li>Buscar artistas por nombre, disciplina, género o localidad.</li>
          <li>Explorar obras y eventos pasados.</li>
          <li>Acceder a una biblioteca digital con recursos culturales.</li>
          <li>Conocer estadísticas que promueven la igualdad y diversidad cultural.</li>
        </ul>

        <p>Te invitamos a explorar, descubrir y formar parte de este espacio pensado para fortalecer nuestras raíces
          culturales y proyectarlas hacia el futuro.</p>

        <p><em>ID Cultural — La identidad de un pueblo, en un solo lugar.</em></p>
        <section id="noticias-recientes" class="noticias-home">
          <h2>Últimas Noticias</h2>
          <div id="contenedor-noticias"></div>
        </section>

      </div>
      <div class="hero-image">
        <picture>
          <source srcset="/static/img/logo.jpg" type="image">
          <img src="/static/img/logo.jpg" alt="Casa Castro" loading="lazy" />
        </picture>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../components/footer.php'; ?>

  <script src="/static/js/main.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

</body>

</html>