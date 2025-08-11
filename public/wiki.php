<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Wiki - ID Cultural";
$specific_css_files = ['wiki.css'];
include(__DIR__ . '/../components/header.php');
?>

<body>

  <?php
  include __DIR__ . '/../components/navbar.php';
  ?>

    <!-- Contenido Principal -->
    <main>
        <img src="/static/img/portada.png" alt="Logo Santiago del Estero">

        <!-- Búsqueda -->
        <div class="search">
            <h2>Buscar en la Biblioteca</h2>
            <form id="form-busqueda" action="#" method="get">
                <input type="text" placeholder="Buscar por nombre o palabra clave..." name="search" id="search" required>

                <select name="categoria" id="categoria">
                    <option value="">Todas las categorías</option>
                    <option value="Artesania">Artesania</option>
                    <option value="Audiovisual">Audiovisual</option>
                    <option value="Danza">Danza</option>
                    <option value="Teatro">Teatro</option>
                    <option value="Musica">Musica</option>
                    <option value="Literatura">Literatura</option>
                    <option value="Escultura">Escultura</option>
                    <!-- Podés agregar más categorías según los datos que tengas -->
                </select>

                <button type="submit">Buscar</button>
            </form>
        </div>


        <div class="container">
            <aside class="sidebar">
                <h2>🎨 Artistas Famosos</h2>

                <div class="famoso">
                    <img src="/static/img/merce.jpg" alt="Mercedes Sosa">
                    <h4>Mercedes Sosa</h4>
                    <p>Cantante y referente del folklore argentino.</p>
                </div>

                <div class="famoso">
                    <img src="/static/img/berni.jpg" alt="Antonio Berni">
                    <h4>Antonio Berni</h4>
                    <p>Pintor y grabador destacado por su arte social.</p>
                </div>
            </aside>

            <!-- Sección Dinámica por Categorías -->
            <section class="main-content" id="biografias">
                <h2>Artistas Registrados</h2>

                <div class="categoria" data-category="Música">
                    <h3>Música</h3>
                    <div class="card">
                        <img src="/static/img/juanperez.jpg" alt="Juan Pérez">
                        <div class="card-info">
                            <h4>Juan Pérez</h4>
                            <p>Guitarrista y compositor de música folclórica.</p>
                            <a href="#" class="btn-biografia">Leer Biografía Completa</a>
                        </div>
                    </div>
                </div> <!-- 👈 Cerrás la categoría Música -->

                <div class="categoria" data-category="Literatura">
                    <h3>Literatura</h3>
                    <div class="card">
                        <img src="/static/img/dem.jpg" alt="María González">
                        <div class="card-info">
                            <h4>María González</h4>
                            <p>Escritora y poeta contemporánea.</p>
                            <a href="#" class="btn-biografia">Leer Biografía Completa</a>
                        </div>
                    </div>
                </div> <!-- 👈 Cerrás la categoría Literatura -->
            </section>

    </main>
    </div>

    <!-- Footer -->
    <?php include("../components/footer.php"); ?>


    <script src="/static/js/main.js"></script>
    <script src="/static/js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>



</body>

</html>