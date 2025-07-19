<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro del Artista</title>
  <!-- Bootstrap Core -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootswatch Theme -->
<link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

  <link rel="stylesheet" href="/ID-Cultural/static/css/main.css">
  <link rel="stylesheet" href="/ID-Cultural/static/css/registro.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <?php include("../../../../components/navbar.php"); ?>

  <main>
    <section class="registro-wizard">
      <div class="wizard-pasos">
        <div class="paso activo">
          <div class="circulo">1</div>
          <p><strong>Creá tu cuenta</strong></p>
        </div>
        <div class="linea"></div>
        <div class="paso">
          <div class="circulo">2</div>
          <p>Áreas artísticas</p>
        </div>
      </div>
    </section>

    <section id="paso1" class="formulario-paso1 active animate__animated animate__fadeInDown">
      <h2>Creá tu cuenta</h2>
      <form id="registroForm" novalidate>

        <!-- Datos personales -->
        <div class="input-group">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan" autocomplete="given-name" required>
        </div>

        <div class="input-group">
          <label for="apellido">Apellido</label>
          <input type="text" id="apellido" name="apellido" placeholder="Ej: Pérez" autocomplete="family-name" required>
        </div>
        
        <div class="input-group">
          <label for="fechaNacimiento">Fecha de nacimiento</label>
          <input type="date" id="fechaNacimiento" name="fechaNacimiento" autocomplete="bday" required>
        </div>

        <div class="input-group">
          <label for="genero">Género</label>
          <select id="genero" name="genero" required>
            <option value="" disabled selected>Seleccioná una opción</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="otro">Otro</option>
          </select>
        </div>

        <div class="input-group">
          <label for="pais">País</label>
          <select id="pais" name="pais" required>
            <option value="" disabled selected>Seleccioná un país</option>
            <option value="Argentina">Argentina</option>
          </select>
        </div>
        
        <div class="input-group">
          <label for="provincia">Provincia</label>
          <select id="provincia" name="provincia" required disabled>
            <option value="" disabled selected>Seleccioná una provincia</option>
          </select>
        </div>

        <div class="input-group">
          <label for="municipio">Municipio/Departamento</label>
          <select id="municipio" name="municipio" required disabled>
            <option value="" disabled selected>Seleccioná un municipio</option>
          </select>
        </div>
        
        <!-- Credenciales -->
        <div class="input-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" autocomplete="email" required>
        </div>

        <div class="input-group">
          <label for="confirmarEmail">Confirmar correo electrónico</label>
          <input type="email" id="confirmarEmail" name="confirmarEmail" placeholder="Repetí tu correo" required>
        </div>

        <div class="input-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" required>
        </div>

        <div class="input-group">
          <label for="confirmarPassword">Confirmar contraseña</label>
          <input type="password" id="confirmarPassword" name="confirmarPassword" placeholder="Repetí tu contraseña" required>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="terminos" required>
          <label for="terminos">Acepto los <a href="/src/views/pages/public/terminos_condiciones.html" target="_blank">Términos y condiciones</a></label>
        </div>

        <button type="submit" class="btn-principal">Registrarme</button>
        <p class="login-link">¿Ya tenés una cuenta? <a href="#">Ingresá</a></p>
      </form>
    </section>

    <section id="paso2" class="formulario-paso2">
      <h2>Intereses culturales</h2>
      <p>¿En qué categoría artística te desarrollás actualmente? Seleccioná una o más áreas que mejor representen tu producción.</p>

      <form id="interesesForm">
        <input type="hidden" id="email_oculto" name="email">
        <div class="checkbox-grid">
          <div>
            <label><input type="checkbox" name="intereses" value="Artesanía"> Artesanía</label><br>
            <label><input type="checkbox" name="intereses" value="Audiovisual"> Audiovisual</label><br>
            <label><input type="checkbox" name="intereses" value="Danza"> Danza</label><br>
            <label><input type="checkbox" name="intereses" value="Teatro"> Teatro</label><br>
          </div>
          <div>
            <label><input type="checkbox" name="intereses" value="Música"> Música</label><br>
            <label><input type="checkbox" name="intereses" value="Literatura"> Literatura</label><br>
            <label><input type="checkbox" name="intereses" value="Escultura"> Escultura</label><br>
          </div>
        </div>

        <div class="botones-navegacion">
          <button type="button" onclick="volverPaso1()" class="btn-secundario" id="btn-anterior">Anterior</button>
          <button type="submit" class="btn-principal" id="btnSiguiente" disabled>Siguiente</button>
        </div>
      </form>
    </section>
  </main>

  <?php include("../../../../components/footer.php"); ?>

  <!-- Scripts -->
  <script src="/ID-Cultural/static/js/main.js"></script>
  <script src="/ID-Cultural/static/js/navbar.js"></script>
  <script src="/ID-Cultural/static/js/registro.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

  <!-- Modal -->
  <div id="modalTerminos" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal()">&times;</span>
      <iframe src="/src/views/pages/public/terminos_condiciones.html" frameborder="0"></iframe>
    </div>
  </div>
</body>

</html>
