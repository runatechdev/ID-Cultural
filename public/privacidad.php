<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Política de Privacidad - ID Cultural";
include(__DIR__ . '/../components/header.php');
?>
<body class="dashboard-body">
  <?php include(__DIR__ . '/../components/navbar.php'); ?>
  
  <main class="container my-5">
    <div class="card shadow-sm">
      <div class="card-body p-4 p-md-5">
        <div class="mb-4">
          <h1 class="fw-bold mb-2">Política de Privacidad</h1>
          <p class="text-muted">ID Cultural - Protección y Tratamiento de Datos Personales</p>
          <hr class="my-4">
        </div>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">1. Introducción</h2>
          <p class="text-justify">La presente Política de Privacidad establece los principios, normas y procedimientos que regulan el tratamiento de datos personales proporcionados por los usuarios (en adelante, "Usuarios") durante el registro y utilización de la plataforma digital "ID Cultural". En ID Cultural, consideramos la privacidad y la seguridad de la información de nuestros usuarios como una prioridad fundamental.</p>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">2. Responsable del Tratamiento de Datos</h2>
          <p class="text-justify">Los datos personales recopilados a través de la Plataforma serán administrados por el equipo desarrollador de "ID Cultural", entidad responsable de garantizar su uso responsable, seguro y conforme a la legislación vigente sobre protección de datos personales. Cualquier consulta relativa al tratamiento de sus datos puede dirigirse al correo de contacto indicado en esta política.</p>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">3. Datos Solicitados y Finalidad del Tratamiento</h2>
          <p class="text-justify">Para acceder a todas las funcionalidades de la Plataforma, el usuario debe completar un formulario de registro que incluye la recopilación de los siguientes datos personales:</p>
          
          <div class="ms-3 mt-3">
            <ul class="ms-3">
              <li>Nombre completo (nombre y apellido)</li>
              <li>Correo electrónico verificable</li>
              <li>Contraseña segura</li>
              <li>Fecha de nacimiento</li>
              <li>Áreas de interés y especialidad cultural</li>
            </ul>
          </div>

          <p class="text-justify mt-3">Estos datos se utilizan exclusivamente para los siguientes propósitos:</p>
          
          <div class="ms-3 mt-3">
            <ul class="ms-3">
              <li><strong>Gestión de cuenta:</strong> Permitir el registro, creación y administración de perfiles artísticos personalizados.</li>
              <li><strong>Verificación de identidad:</strong> Validar cuentas a través de correo electrónico y garantizar la seguridad de la Plataforma.</li>
              <li><strong>Promoción de contenidos:</strong> Publicar y promocionar los contenidos artísticos y proyectos culturales cargados por los usuarios.</li>
              <li><strong>Mejora de servicios:</strong> Optimizar la experiencia del usuario, ofrecer funcionalidades personalizadas y adaptadas a sus intereses culturales.</li>
              <li><strong>Comunicaciones:</strong> Enviar notificaciones, actualizaciones y comunicaciones relevantes sobre la Plataforma.</li>
            </ul>
          </div>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">4. Perfil de Usuario y Gestión de Contenidos</h2>
          <p class="text-justify">Una vez que la cuenta ha sido registrada y validada exitosamente, el usuario tendrá acceso completo a las funcionalidades de la Plataforma para crear su perfil artístico profesional. A través de este perfil, el usuario puede:</p>
          
          <div class="ms-3 mt-3">
            <ul class="ms-3">
              <li>Describir su proyecto cultural, trayectoria artística y especialidades</li>
              <li>Adjuntar material audiovisual, imágenes, videos y documentación</li>
              <li>Compartir logros, premios, reconocimientos y certificaciones</li>
              <li>Construir un portafolio virtual profesional</li>
              <li>Interactuar y conectar con otros artistas y profesionales culturales</li>
            </ul>
          </div>

          <p class="text-justify mt-3"><strong>Responsabilidad del usuario:</strong> El usuario es completamente responsable de la veracidad, precisión e integridad de los datos y del contenido que publica en la Plataforma. ID Cultural no se responsabiliza por información falsa, engañosa o que viole derechos de terceros.</p>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">5. Confidencialidad y Seguridad de Almacenamiento</h2>
          <p class="text-justify">Los datos personales de los usuarios serán tratados con estricta confidencialidad y bajo los más altos estándares de seguridad. ID Cultural se compromete a:</p>
          
          <div class="ms-3 mt-3">
            <ul class="ms-3">
              <li>No compartir información personal con terceros sin autorización previa y expresa del usuario</li>
              <li>Almacenar la información de forma segura mediante sistemas de encriptación y protección de datos</li>
              <li>Mantener los datos solamente por el tiempo necesario para cumplir con los fines establecidos</li>
              <li>Implementar medidas técnicas y organizativas para prevenir accesos no autorizados</li>
            </ul>
          </div>

          <p class="text-justify mt-3">Sin embargo, ningún sistema de transmisión por internet es 100% seguro. Aunque implementamos medidas robustas de seguridad, no podemos garantizar seguridad absoluta.</p>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">6. Consentimiento Informado</h2>
          <p class="text-justify">El acceso y uso continuo de la Plataforma implica la aceptación expresa y voluntaria del usuario respecto a los términos de esta Política de Privacidad. En el formulario de registro se incluye una casilla de aceptación de estos términos, la cual es de carácter obligatorio para completar el registro.</p>
          
          <div class="alert alert-info mt-3" role="alert">
            <strong>ℹ️ Importante:</strong> Sin la aceptación de esta Política de Privacidad, no será posible crear una cuenta ni acceder a los servicios de la Plataforma.
          </div>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">7. Derechos del Usuario</h2>
          <p class="text-justify">En conformidad con la Ley 25.326 de Protección de Datos Personales de la República Argentina y la legislación vigente aplicable, todo usuario tiene derecho a ejercer los siguientes derechos sobre sus datos personales:</p>
          
          <div class="ms-3 mt-3">
            <ul class="ms-3">
              <li><strong>Derecho de Acceso:</strong> Acceder a la información personal que tenemos almacenada sobre usted</li>
              <li><strong>Derecho de Rectificación:</strong> Solicitar la corrección, actualización o enmienda de datos inexactos o incompletos</li>
              <li><strong>Derecho de Supresión:</strong> Solicitar la eliminación de sus datos personales y la baja de su cuenta</li>
              <li><strong>Derecho al Olvido:</strong> Solicitar que se eliminen referencias de su información en la Plataforma</li>
            </ul>
          </div>

          <p class="text-justify mt-3">Para ejercer cualquiera de estos derechos, debe enviar una solicitud formal a través del correo electrónico indicado a continuación. La solicitud debe incluir su nombre completo, correo de registro y descripción clara del derecho que desea ejercer.</p>

          <div class="alert alert-light border border-secondary mt-3">
            <p class="mb-0"><strong>📧 Correo de Contacto:</strong> <a href="mailto:dnicultural.contacto@gmail.com" class="link-primary">dnicultural.contacto@gmail.com</a></p>
          </div>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">8. Modificaciones a la Política de Privacidad</h2>
          <p class="text-justify">ID Cultural se reserva el derecho de modificar, actualizar o cambiar esta Política de Privacidad en cualquier momento, a fin de adaptarse a nuevos requisitos legales, cambios tecnológicos o mejoras en nuestros procedimientos de tratamiento de datos. Toda modificación será comunicada de forma clara a través del sitio web y entrará en vigencia desde su publicación. El uso continuado de la Plataforma tras cualquier cambio constituye la aceptación de la nueva política.</p>
        </section>

        <section class="mb-4">
          <h2 class="fw-bold text-primary mb-3">9. Jurisdicción y Ley Aplicable</h2>
          <p class="text-justify">Esta Política de Privacidad se rige exclusivamente por las leyes de la República Argentina, en particular por la Ley 25.326 de Protección de Datos Personales. Cualquier controversia, discrepancia o reclamo derivado de la aplicación de esta Política será resuelta conforme a la jurisdicción competente de los tribunales ordinarios de la Ciudad de Buenos Aires, Argentina.</p>
        </section>

        <div class="alert alert-warning mt-5" role="alert">
          <strong>⏰ Última actualización:</strong> <?php echo date('d/m/Y'); ?><br>
          <small>Esta Política de Privacidad puede modificarse en cualquier momento. Le recomendamos revisar esta página regularmente para mantenerse informado sobre cómo protegemos su información.</small>
        </div>
      </div>
    </div>
  </main>

  <?php include(__DIR__ . '/../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>