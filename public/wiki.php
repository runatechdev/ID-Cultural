<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../backend/config/connection.php';

$page_title = "Wiki - ID Cultural";
$specific_css_files = ['wiki.css'];

// Obtener categorías para el selector
try {
    $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM publicaciones WHERE estado = 'validado' ORDER BY categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorias = [];
}

// Obtener municipios para el selector
try {
    $stmt = $pdo->prepare("SELECT DISTINCT municipio FROM artistas WHERE status = 'validado' ORDER BY municipio");
    $stmt->execute();
    $municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $municipios = [];
}

include(__DIR__ . '/../components/header.php');
?>

<body>

    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="container my-5">

        <section class="text-center mb-5">
            <h1 class="display-5 fw-bold">Bienvenidos a la Wiki de Artistas</h1>
            <p class="lead text-muted">La biblioteca digital de los artistas locales de Santiago del Estero.</p>
        </section>
        <!-- Sección de Búsqueda de Obras -->
        <section class="search-section card p-4 mb-5 shadow-sm">
            <h2 class="text-center mb-4">🎨 Buscar Obras</h2>
            <form id="form-busqueda-wiki" action="#" method="get">
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Buscar por título, artista o descripción..." name="busqueda" id="busqueda-wiki">
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <select name="categoria" id="categoria-wiki" class="form-select">
                            <option value="">Todas las Categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['categoria']); ?>">
                                    <?php echo htmlspecialchars($cat['categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="municipio" id="municipio-wiki" class="form-select">
                            <option value="">Todos los Municipios</option>
                            <?php foreach ($municipios as $mun): ?>
                                <option value="<?php echo htmlspecialchars($mun['municipio']); ?>">
                                    <?php echo htmlspecialchars($mun['municipio']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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

        <!-- Sección de Obras Publicadas -->
        <section class="obras-seccion my-5">
            <h2 class="mb-4 section-title">🎨 Galería de Obras</h2>
            <p class="lead text-muted mb-4">Descubre las obras validadas de artistas locales de Santiago del Estero.</p>
            <div id="contenedor-obras-wiki">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando obras...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando obras...</p>
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

    <!-- Meta tag para el base URL en JavaScript -->
    <meta name="base-url" content="<?php echo BASE_URL; ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_URL; ?>static/js/wiki.js"></script>
</body>

</html>