<!DOCTYPE html>
<html lang="es">
  
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Bootstrap Core -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootswatch Theme -->
<link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

  <title>DNI Cultural</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="/ID-Cultural/static/css/main.css" />

</head>

<body>
  
<?php include __DIR__ . '/ID-Cultural/components/navbar.php'; ?>

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
<!--------------------------------------------------------------NOTICIAS!----->
  <section id="noticias-recientes" class="noticias-home">
  <h2>Últimas Noticias</h2>
  <div id="contenedor-noticias"></div>
</section>

<!---------------------------------------------------------------------------->

      </div>
      <div class="hero-image">
        <picture>
          <source srcset="/ID-Cultural/static/img/logo.jpg" type="image">
          <img src="/ID-Cultural/static/img/logo.jpg" alt="Casa Castro" loading="lazy" />
        </picture>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/ID-Cultural/components/footer.php'; ?>

  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>


</body>

</html>