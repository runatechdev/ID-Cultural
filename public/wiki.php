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
        <!-- Banner -->
        <img src="/static/img/portada.png" alt="Logo Santiago del Estero" class="banner">

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

    <!-- Artistas famosos: Slider con flechas -->
    <section class="famosos-slider-wrapper">
        <div class="famosos-slider">

            <div class="famoso">
                <img src="/static/img/merce.jpg" alt="Mercedes Sosa">
                <h4>Mercedes Sosa</h4>
                <p>Cantante de folklore y referente cultural de Argentina.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/nocheros.jpg" alt="Los Nocheros">
                <h4>Los Nocheros</h4>
                <p>Grupo de folklore muy popular en Argentina.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/chaqueno.jpg" alt="El Chaqueño Palavecino">
                <h4>El Chaqueño Palavecino</h4>
                <p>Cantante de folklore reconocido nacionalmente.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/abel.jpg" alt="Abel Pintos">
                <h4>Abel Pintos</h4>
                <p>Cantautor con fuerte influencia del folklore santiagueño.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/berni.jpg" alt="Antonio Berni">
                <h4>Antonio Berni</h4>
                <p>Pintor y grabador destacado en artes plásticas.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/gorriti.jpg" alt="Juana Manuela Gorriti">
                <h4>Juana Manuela Gorriti</h4>
                <p>Escritora histórica vinculada a Santiago del Estero.</p>
            </div>

            <div class="famoso">
                <img src="/static/img/chango.jpg" alt="Chango Farías Gómez">
                <h4>Chango Farías Gómez</h4>
                <p>Músico y referente del chamamé.</p>
            </div>

        </div>
    </section>

    <!-- Artistas registrados: lista vertical -->
    <section class="main-content" id="biografias">
        <h2>Artistas Registrados</h2>

        <div class="categoria" data-category="Música">
            <h3>Música</h3>
            <div class="artist-vertical">
                <img src="/static/img/juanperez.jpg" alt="Juan Pérez">
                <div class="info">
                    <h4>Juan Pérez</h4>
                    <p>Guitarrista y compositor de música folclórica.</p>
                    <a href="#" class="btn-biografia">Leer Biografía Completa</a>
                </div>
            </div>

            <div class="artist-vertical">
                <img src="/static/img/froilan.jpg" alt="Froilán Gonzales">
                <div class="info">
                    <h4>Froilán Gonzales</h4>
                    <p>Luthier santiagueño reconocido como 'El Indio Froilán', creador de bombos legüeros emblemáticos del folklore.</p>
                    <a href="#" class="btn-biografia">Leer Biografía Completa</a>
                </div>
            </div>
        </div>

        <div class="categoria" data-category="Literatura">
            <h3>Literatura</h3>
            <div class="artist-vertical">
                <img src="/static/img/dem.jpg" alt="María González">
                <div class="info">
                    <h4>María González</h4>
                    <p>Escritora y poeta contemporánea.</p>
                    <a href="#" class="btn-biografia">Leer Biografía Completa</a>
                </div>
            </div>
        </div>
    </section>

    </main>
    </div>

    <!-- Footer -->
    <?php include("../components/footer.php"); ?>


    <script src="/static/js/main.js"></script>
    <script src="/static/js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="/static/js/artist-hover.js"></script>
</body>

</html>