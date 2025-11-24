<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Términos y Condiciones - ID Cultural";
// Puedes crear un CSS específico si lo necesitas
// $specific_css_files = ['terminos.css']; 
include(__DIR__ . '/../components/header.php');
?>
<body class="dashboard-body">

  <?php include(__DIR__ . '/../components/navbar.php'); ?>

  <main class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4">
                <h1 class="fw-bold mb-2">Términos y Condiciones de Uso</h1>
                <p class="text-muted">ID Cultural - Plataforma de Visibilización Artística y Cultural</p>
                <hr class="my-4">
            </div>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">1. Aceptación de los Términos</h2>
                <p class="text-justify">Al acceder y utilizar la plataforma ID Cultural (en adelante, la "Plataforma"), usted reconoce y acepta estar sujeto a estos Términos y Condiciones de Uso. La Plataforma representa un espacio virtual dedicado a la visibilización, promoción e interconexión de artistas y profesionales de la cultura.</p>
                <p class="text-justify"><strong>Si no está de acuerdo con alguno de estos términos, se le solicita que no acceda ni utilice la Plataforma.</strong> El acceso continuado a la Plataforma constituye una aceptación expresa de estos términos en su totalidad.</p>
                <div class="alert alert-info" role="alert">
                    <strong>⚠️ Declaración de Capacidad Legal:</strong> El usuario declara tener la capacidad legal necesaria para celebrar contratos en su jurisdicción y ser responsable de sus acciones dentro de la Plataforma.
                </div>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">2. Modificaciones de los Términos</h2>
                <p class="text-justify">ID Cultural se reserva el derecho de modificar, actualizar o cambiar estos Términos y Condiciones en cualquier momento sin previo aviso. Las modificaciones serán efectivas desde su publicación en la Plataforma. El uso continuado de la Plataforma tras cualquier cambio constituye la aceptación de los nuevos términos. Se recomienda revisar periódicamente estos términos para mantenerse informado de cualquier cambio.</p>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">3. Descripción de la Plataforma</h2>
                <p class="text-justify">ID Cultural es una plataforma digital gratuita, de acceso público, diseñada específicamente para visibilizar, promocionar y conectar artistas, creadores y profesionales del sector cultural. La Plataforma facilita la creación de perfiles profesionales, la exhibición de proyectos culturales y la generación de conexiones significativas dentro del ecosistema artístico.</p>
                
                <div class="ms-3 mt-3">
                    <h4 class="fw-bold mb-3">3.1 Registro de Usuarios</h4>
                    <p class="text-justify">Para acceder a determinadas funcionalidades de la Plataforma, el usuario debe completar un registro que incluya los siguientes datos:</p>
                    <ul class="ms-3">
                        <li>Nombre completo (nombre y apellido)</li>
                        <li>Correo electrónico válido y verificable</li>
                        <li>Contraseña segura de su elección</li>
                        <li>Fecha de nacimiento</li>
                        <li>Áreas de interés y especialidad cultural</li>
                    </ul>
                    <p class="text-justify mt-3">El usuario es responsable de mantener la confidencialidad de sus credenciales de acceso. Se compromete a proporcionar información verídica, precisa y actualizada. Cualquier información falsa o fraudulenta será causal de eliminación de cuenta.</p>

                    <h4 class="fw-bold mb-3 mt-4">3.2 Perfiles de Artista</h4>
                    <p class="text-justify">Una vez que la cuenta ha sido validada y verificada, el usuario tendrá acceso a crear y personalizar su perfil artístico profesional. El perfil artístico permite:</p>
                    <ul class="ms-3">
                        <li>Describir su proyecto cultural y trayectoria artística</li>
                        <li>Adjuntar material audiovisual, portafolio y documentación</li>
                        <li>Exhibir logros, premios y reconocimientos</li>
                        <li>Construir un portafolio virtual profesional</li>
                        <li>Conectar con otros artistas y profesionales culturales</li>
                    </ul>
                    <p class="text-justify mt-3">El usuario es único responsable del contenido que publique en su perfil. La Plataforma se reserva el derecho de modificar o eliminar contenido que viole estos términos o sea considerado inadecuado.</p>
                </div>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">4. Responsabilidades del Usuario</h2>
                <p class="text-justify">El usuario se compromete a:</p>
                <ul class="ms-3">
                    <li>Usar la Plataforma de manera legal, ética y responsable</li>
                    <li>Respetando los derechos de otros usuarios y terceros</li>
                    <li>No difundir contenido ofensivo, discriminatorio o violento</li>
                    <li>No intentar acceder a áreas restringidas de la Plataforma</li>
                    <li>No utilizar la Plataforma para fines comerciales no autorizados</li>
                    <li>Mantener actualizada la información de su perfil</li>
                </ul>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">5. Derechos de Propiedad Intelectual</h2>
                <p class="text-justify">Todo contenido alojado en la Plataforma, incluyendo textos, imágenes, videos, diseños y materiales audiovisuales, está protegido por las leyes de derechos de autor aplicables. El usuario autoriza a la Plataforma a utilizar el contenido que publica con fines de promoción y visibilización dentro del ecosistema cultural. Sin embargo, el usuario mantiene la propiedad de sus obras originales.</p>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">6. Limitación de Responsabilidad</h2>
                <p class="text-justify">ID Cultural ofrece la Plataforma "tal como está" sin garantías de ningún tipo. La Plataforma no se responsabiliza por:</p>
                <ul class="ms-3">
                    <li>Interrupciones o suspensiones del servicio</li>
                    <li>Pérdida o corrupción de datos</li>
                    <li>Daños directos o indirectos derivados del uso de la Plataforma</li>
                    <li>El contenido generado por otros usuarios</li>
                    <li>Terceros que accedan a la información del usuario</li>
                </ul>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">7. Privacidad y Protección de Datos</h2>
                <p class="text-justify">La privacidad de nuestros usuarios es fundamental. Los datos personales recopilados serán tratados de conformidad con la legislación vigente sobre protección de datos personales. Para información detallada sobre cómo se recopilan, usan y protegen sus datos, consulte nuestra <a href="/privacidad.php" class="link-primary">Política de Privacidad</a>.</p>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">8. Terminación de Cuenta</h2>
                <p class="text-justify">ID Cultural se reserva el derecho de suspender o eliminar cualquier cuenta que viole estos Términos y Condiciones o las políticas de uso de la Plataforma. El usuario también puede solicitar la eliminación de su cuenta en cualquier momento contactando al equipo de soporte.</p>
            </section>

            <section class="mb-4">
                <h2 class="fw-bold text-primary mb-3">9. Contacto y Soporte</h2>
                <p class="text-justify">Para cualquier consulta, aclaración o comunicación relacionada con estos Términos y Condiciones, sírvase contactarnos a través de:</p>
                <div class="alert alert-light border border-secondary mt-3">
                    <p class="mb-0"><strong>📧 Correo Electrónico:</strong> <a href="mailto:dnicultural.contacto@gmail.com" class="link-primary">dnicultural.contacto@gmail.com</a></p>
                    <p class="mb-0 mt-2"><strong>📍 Ubicación:</strong> Santiago del Estero, Argentina</p>
                </div>
            </section>

            <div class="alert alert-warning mt-5" role="alert">
                <strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?><br>
                <small>Estos términos y condiciones son vinculantes y pueden modificarse en cualquier momento. Le recomendamos revisar esta página regularmente.</small>
            </div>
        </div>
    </div>
  </main>

  <?php include(__DIR__ . '/../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>