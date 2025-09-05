<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Wiki - ID Cultural";
$specific_css_files = ['wiki.css'];
include(__DIR__ . '/../components/header.php');
?>

<body>

    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <img src="/static/img/portada.png" alt="Banner ID Cultural" class="banner img-fluid">

    <main class="container my-5">

        <section class="search-section card p-4 mb-5 shadow-sm">
            <h2 class="text-center mb-4">Buscar en la Biblioteca</h2>
            <form id="form-busqueda" action="#" method="get">
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Buscar por nombre o palabra clave..." name="search" id="search" required>

                    <select name="categoria" id="categoria" class="form-select" style="max-width: 200px;">
                        <option value="">Categorías</option>
                        <option value="Artesania">Artesanía</option>
                        <option value="Audiovisual">Audiovisual</option>
                        <option value="Danza">Danza</option>
                        <option value="Teatro">Teatro</option>
                        <option value="Musica">Música</option>
                        <option value="Literatura">Literatura</option>
                        <option value="Escultura">Escultura</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </section>

        <section class="mb-5">
            <h2 class="mb-4 section-title">Artistas Destacados</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card h-100 text-center artist-card border-0">
                        <img src="/static/img/merce.jpg" class="card-img-top" alt="Mercedes Sosa">
                        <div class="card-body">
                            <h5 class="card-title">Mercedes Sosa</h5>
                            <p class="card-text">Cantante de folklore y referente cultural.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center artist-card border-0">
                        <img src="/static/img/nocheros.jpg" class="card-img-top" alt="Los Nocheros">
                        <div class="card-body">
                            <h5 class="card-title">Los Nocheros</h5>
                            <p class="card-text">Grupo de folklore muy popular en Argentina.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center artist-card border-0">
                        <img src="/static/img/chaqueno.jpg" class="card-img-top" alt="El Chaqueño Palavecino">
                        <div class="card-body">
                            <h5 class="card-title">El Chaqueño Palavecino</h5>
                            <p class="card-text">Cantante de folklore reconocido nacionalmente.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center artist-card border-0">
                        <img src="/static/img/abel.jpg" class="card-img-top" alt="Abel Pintos">
                        <div class="card-body">
                            <h5 class="card-title">Abel Pintos</h5>
                            <p class="card-text">Cantautor con fuerte influencia del folklore.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="biografias">
            <h2 class="mb-4 section-title">Artistas Registrados</h2>
            <div class="accordion" id="accordionArtistas">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMusica">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMusica" aria-expanded="true" aria-controls="collapseMusica">
                            Música
                        </button>
                    </h2>
                    <div id="collapseMusica" class="accordion-collapse collapse show" aria-labelledby="headingMusica" data-bs-parent="#accordionArtistas">
                        <div class="accordion-body">

                            <div class="card artist-list-card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <img src="/static/img/juanperez.jpg" class="img-fluid rounded-start" alt="Juan Pérez">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title">Juan Pérez</h5>
                                            <p class="card-text">Guitarrista y compositor de música folclórica.</p>
                                            <a href="#" class="btn btn-outline-primary">Leer Biografía Completa</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card artist-list-card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <img src="/static/img/froilan.jpg" class="img-fluid rounded-start" alt="Froilán Gonzales">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title">Froilán Gonzales</h5>
                                            <p class="card-text">Luthier santiagueño reconocido como 'El Indio Froilán', creador de bombos legüeros emblemáticos.</p>
                                            <a href="#" class="btn btn-outline-primary">Leer Biografía Completa</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingLiteratura">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLiteratura" aria-expanded="false" aria-controls="collapseLiteratura">
                            Literatura
                        </button>
                    </h2>
                    <div id="collapseLiteratura" class="accordion-collapse collapse" aria-labelledby="headingLiteratura" data-bs-parent="#accordionArtistas">
                        <div class="accordion-body">

                            <div class="card artist-list-card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <img src="/static/img/dem.jpg" class="img-fluid rounded-start" alt="María González">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title">María González</h5>
                                            <p class="card-text">Escritora y poeta contemporánea.</p>
                                            <a href="#" class="btn btn-outline-primary">Leer Biografía Completa</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php include("../components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>