<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../static/img/huella-idcultural.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Perfil Artista
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/material-kit.css?v=2.2.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <link href="../assets/demo/vertical-nav.css" rel="stylesheet" />
</head>

<body class="profile-page sidebar-collapse">
    <nav class="navbar navbar-color-on-scroll navbar-transparent    fixed-top  navbar-expand-lg " color-on-scroll="100" id="sectionsNav">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="#">
          Perfil Artista </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="dropdown nav-item">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
              <i class="material-icons">view_carousel</i> Menu
            </a>
            <div class="dropdown-menu dropdown-with-icons">
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
              <a href="/src/views/pages/artista/crear-borrador.php" class="dropdown-item">
                <i class="material-icons">assignment</i> Agregar obras
              </a>
              <a href="/src/views/pages/artista/dashboard-artista.php" class="dropdown-item">
                <i class="material-icons">build</i> Editar Perfil
              </a>
              <a href="/src/views/pages/auth/login.php" class="dropdown-item">
                <i class="material-icons">person_add</i> Salir
              </a>
            </div>
          </li>
           <li class="button-container nav-item iframe-extern">
                        <a href="https://www.whatsapp.com/?lang=es" target="_blank"
                            class="btn  btn-rose   btn-round btn-block">
                            <i class="material-icons">call</i> Contactar
                        </a>
                    </li>
        </ul>
      </div>
    </div>
  </nav>
                   
 <div class="page-header header-filter" data-parallax="true"
        style="background-image: url('../assets/img/sgo.jpg');"></div>
    <div class="main main-raised">
        <div class="profile-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ml-auto mr-auto">
                        <div class="profile">
                            <div class="avatar">
                                <img src="../assets/img/faces/rusher.jpeg" alt="Circle Image"
                                    class="img-raised rounded-circle img-fluid">
                            </div>
                            <div class="name">
                                <h3 class="title">Rusher King</h3>
                                <h6>Cantante</h6>
                                <a href="#pablo" class="btn btn-just-icon btn-link btn-dribbble"><i
                                        class="fa fa-dribbble"></i></a>
                                <a href="#pablo" class="btn btn-just-icon btn-link btn-twitter"><i
                                        class="fa fa-twitter"></i></a>
                                <a href="#pablo" class="btn btn-just-icon btn-link btn-pinterest"><i
                                        class="fa fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="description text-center">
                    <p>
                        <strong>Rusherking</strong>, cuyo nombre real es <strong>Thomas Nicolás Tobar</strong>, es un
                        rapero y artista de música urbana argentino que se hizo conocido en <strong>2021</strong> con el
                        remix de su sencillo <em>«Además de mí»</em>. Nacido en <strong>Santiago del Estero</strong>, el
                        artista se mudó a <strong>Buenos Aires</strong> para seguir su carrera musical, la cual se basa
                        en el género <strong>trap</strong>, a menudo mezclado con otros estilos. Recientemente ha
                        decidido cambiar su nombre artístico a solo <strong>"Rusher"</strong>.
                    </p>
                </div>


                <div class="row">
                    <div class="col-md-6 ml-auto mr-auto">
                        <div class="profile-tabs">
                            <ul class="nav nav-pills nav-pills-icons justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#studio" role="tab" data-toggle="tab">
                                        <i class="material-icons">camera</i>
                                        Obras
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#works" role="tab" data-toggle="tab">
                                        <i class="material-icons">music_note</i>
                                        Colaboraciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#favorite" role="tab" data-toggle="tab">
                                        <i class="material-icons">favorite</i>
                                        Favoritos
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-content tab-space">
                    <div class="tab-pane active text-center gallery" id="studio">
                        <div class="row">
                            <div class="col-md-3 ml-auto">
                                <img src="../assets/img/examples/studio-1.jpg" class="rounded">
                                <img src="../assets/img/examples/studio-2.jpg" class="rounded">
                            </div>
                            <div class="col-md-3 mr-auto">
                                <img src="../assets/img/examples/studio-5.jpg" class="rounded">
                                <img src="../assets/img/examples/studio-4.jpg" class="rounded">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane text-center gallery" id="works">
                        <div class="row">
                            <div class="col-md-3 ml-auto">
                                <img src="../assets/img/examples/olu-eletu.jpg" class="rounded">
                                <img src="../assets/img/examples/clem-onojeghuo.jpg" class="rounded">
                                <img src="../assets/img/examples/cynthia-del-rio.jpg" class="rounded">
                            </div>
                            <div class="col-md-3 mr-auto">
                                <img src="../assets/img/examples/mariya-georgieva.jpg" class="rounded">
                                <img src="../assets/img/examples/clem-onojegaw.jpg" class="rounded">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane text-center gallery" id="favorite">
                        <div class="row">
                            <div class="col-md-3 ml-auto">
                                <img src="../assets/img/examples/mariya-georgieva.jpg" class="rounded">
                                <img src="../assets/img/examples/studio-3.jpg" class="rounded">
                            </div>
                            <div class="col-md-3 mr-auto">
                                <img src="../assets/img/examples/clem-onojeghuo.jpg" class="rounded">
                                <img src="../assets/img/examples/olu-eletu.jpg" class="rounded">
                                <img src="../assets/img/examples/studio-1.jpg" class="rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-default">
        <div class="container">
            <nav class="float-left">
                <ul>
                    <li>
                        <a href="#">
                            Facebook
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Youtube
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Instgram
                        </a>
                    </li>
                    <li>
                        <a href="
                        #">
                            X
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright float-right">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>, hecha <i class="material-icons">favorite</i> por
                <a href="#" target="_blank">RunaTech</a>.
            </div>
        </div>
    </footer>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="../assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
    <script src="../assets/js/plugins/moment.min.js"></script>
    <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
    <script src="../assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="../assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
    <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="../assets/js/plugins/bootstrap-selectpicker.js" type="text/javascript"></script>
    <!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="../assets/js/plugins/jasny-bootstrap.min.js" type="text/javascript"></script>
    <!--	Plugin for Small Gallery in Product Page -->
    <script src="../assets/js/plugins/jquery.flexisel.js" type="text/javascript"></script>
    <!-- Plugins for presentation and navigation  -->
    <script src="../assets/demo/modernizr.js" type="text/javascript"></script>
    <script src="../assets/demo/vertical-nav.js" type="text/javascript"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Js With initialisations For Demo Purpose, Don't Include it in Your Project -->
    <script src="../assets/demo/demo.js" type="text/javascript"></script>
    <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/material-kit.js?v=2.2.1" type="text/javascript"></script>
</body>

</html>