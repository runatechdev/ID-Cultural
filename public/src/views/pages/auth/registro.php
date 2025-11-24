<?php
session_start();
require_once __DIR__ . '/../../../../../config.php';
$page_title = "Registro de Artista - ID Cultural";
// Cargamos un CSS específico para el registro
$specific_css_files = ['registro.css'];
include(__DIR__ . '/../../../../../components/header.php');
?>
<body class="dashboard-body">

  <?php include(__DIR__ . '/../../../../../components/navbar.php'); ?>

  <main class="container my-5">
    <div class="card col-lg-7 mx-auto shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-5">
                <i class="bi bi-person-plus-fill display-3 text-primary mb-3"></i>
                <h1 class="mb-2 fw-bold">Crear Cuenta de Artista</h1>
                <p class="text-muted">Únete a nuestra comunidad cultural y comparte tu talento con el mundo</p>
            </div>

            <form id="registroForm" novalidate>
                <!-- Datos Personales -->
                <div class="form-section mb-4">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="bi bi-person-fill me-2"></i> Información Personal
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" placeholder="Tu nombre" required>
                            <small class="form-text text-muted">Como deseas que aparezca en tu perfil</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellido" placeholder="Tu apellido" required>
                            <small class="form-text text-muted">Completa tu identidad artística</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento">
                            <small class="form-text text-muted">Opcional. Para verificación de edad</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select id="genero" class="form-select">
                                <option value="" selected>Prefiero no especificar</option>
                                <option value="femenino">Femenino</option>
                                <option value="masculino">Masculino</option>
                                <option value="otro">Otro</option>
                            </select>
                            <small class="form-text text-muted">Opcional. Nos ayuda a conocerte mejor</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Datos de Ubicación -->
                <div class="form-section mb-4">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="bi bi-geo-alt-fill me-2"></i> Ubicación
                    </h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pais" class="form-label">País <span class="text-danger">*</span></label>
                            <select id="pais" class="form-select" required>
                                <option value="Argentina" selected>Argentina</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="provincia" class="form-label">Provincia <span class="text-danger">*</span></label>
                            <select id="provincia" class="form-select" required>
                                <option value="Santiago del Estero" selected>Santiago del Estero</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="municipio" class="form-label">Municipio</label>
                            <select id="municipio" class="form-select">
                                <option value="" selected disabled>Seleccionar tu municipio...</option>
                                <option value="Añatuya">Añatuya</option>
                                <option value="Campo Gallo">Campo Gallo</option>
                                <option value="Clodomira">Clodomira</option>
                                <option value="Colonia Dora">Colonia Dora</option>
                                <option value="Fernández">Fernández</option>
                                <option value="Frías">Frías</option>
                                <option value="La Banda">La Banda</option>
                                <option value="Loreto">Loreto</option>
                                <option value="Monte Quemado">Monte Quemado</option>
                                <option value="Pampa de los Guanacos">Pampa de los Guanacos</option>
                                <option value="Quimilí">Quimilí</option>
                                <option value="Santiago del Estero">Santiago del Estero (Capital)</option>
                                <option value="Sumampa">Sumampa</option>
                                <option value="Suncho Corral">Suncho Corral</option>
                                <option value="Termas de Río Hondo">Termas de Río Hondo</option>
                                <option value="Tintina">Tintina</option>
                                <option value="Villa Ojo de Agua">Villa Ojo de Agua</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <small class="form-text text-muted">Opcional. Ayuda a conectarte con artistas locales</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Datos de Cuenta -->
                <div class="form-section mb-4">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="bi bi-shield-lock me-2"></i> Datos de la Cuenta
                    </h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" placeholder="tu@email.com" required>
                        <small class="form-text text-muted">Usaremos este correo para verificar tu cuenta</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" placeholder="Mínimo 8 caracteres" required>
                            <small class="form-text text-muted">Usa mayúsculas, números y símbolos</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" placeholder="Confirma tu contraseña" required>
                            <small class="form-text text-muted">Deben coincidir</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Información Importante -->
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Próximo paso:</strong> Después de crear tu cuenta, podrás personalizar tu perfil, agregar una foto, describir tu trayectoria artística y subir tus obras.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Checkbox de Términos y Condiciones -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="acceptTerms" required>
                    <label class="form-check-label" for="acceptTerms">
                        Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="link-primary">Términos y Condiciones</a> de ID Cultural <span class="text-danger">*</span>
                    </label>
                    <small class="d-block form-text text-muted mt-1">Lee atentamente nuestros términos antes de continuar</small>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" id="submit-button" class="btn btn-primary btn-lg fw-bold" disabled>
                        <i class="bi bi-person-plus me-2"></i> Crear Mi Cuenta
                    </button>
                </div>
                
                <p class="text-center mt-4 text-muted">
                    ¿Ya tienes una cuenta? 
                    <a href="/src/views/pages/auth/login.php" class="link-primary fw-bold">Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </div>
  </main>

<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="termsModalLabel">
          <i class="bi bi-file-text me-2"></i> Términos y Condiciones de Uso
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
          // Esta línea incluye el contenido del archivo que ya tienes abierto.
          // Asegúrate de que la ruta sea correcta desde tu archivo registro.php
          include(__DIR__ . '/../terminos_condiciones_content.php'); 
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>

  <?php include("../../../../../components/footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
  <script>
      const BASE_URL = '<?php echo BASE_URL; ?>';
  </script>
  <script src="<?php echo BASE_URL; ?>static/js/registro.js"></script>

  <style>
    /* Estilos para mejorar la experiencia visual */
    .form-section {
      background: linear-gradient(135deg, rgba(13, 110, 253, 0.02) 0%, rgba(13, 110, 253, 0.05) 100%);
      padding: 1.5rem;
      border-radius: 8px;
      border-left: 4px solid #0d6efd;
    }

    .form-section h5 {
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      color: #333;
    }

    .form-control, .form-select {
      border-radius: 6px;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .form-check-input {
      width: 1.25rem;
      height: 1.25rem;
      margin-top: 0.3rem;
    }

    .form-check-input:checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .btn-lg {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
    }

    .btn-primary:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .btn-primary:not(:disabled):hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .card {
      border: none;
      border-radius: 12px;
      overflow: hidden;
    }

    hr {
      border-top: 2px solid #e9ecef;
      margin: 2rem 0;
    }

    .alert {
      border-radius: 8px;
      border: none;
    }

    small.form-text {
      display: block;
      margin-top: 0.35rem;
    }

    @media (max-width: 768px) {
      .card {
        margin: 0 -0.5rem;
      }

      .card-body {
        padding: 1.5rem !important;
      }

      .display-3 {
        font-size: 2rem;
      }

      .form-section {
        padding: 1rem;
      }
    }
  </style>

</body>
</html>