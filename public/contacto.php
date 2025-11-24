<?php
session_start();
require_once __DIR__ . '/../config.php';
$page_title = "Contacto - ID Cultural";
include(__DIR__ . '/../components/header.php');
?>
<body class="dashboard-body">

  <?php include(__DIR__ . '/../components/navbar.php'); ?>

  <main class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- Encabezado -->
        <div class="text-center mb-5">
          <i class="bi bi-envelope-heart display-3 text-primary mb-3"></i>
          <h1 class="fw-bold mb-2">Ponte en Contacto</h1>
          <p class="lead text-muted">¿Tienes preguntas o sugerencias? Nos encantaría escucharte. Completa el formulario y nos comunicaremos pronto.</p>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-body p-4 p-md-5">
            
            <!-- Información de contacto rápida -->
            <div class="row mb-5 g-3">
              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-envelope-at text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Correo Electrónico</h6>
                    <a href="mailto:dnicultural.contacto@gmail.com" class="text-primary text-decoration-none">dnicultural.contacto@gmail.com</a>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-geo-alt text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Ubicación</h6>
                    <p class="text-muted mb-0">Santiago del Estero, Argentina</p>
                  </div>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Formulario de contacto -->
            <h5 class="fw-bold mb-4">
              <i class="bi bi-chat-dots me-2"></i> Envíanos un Mensaje
            </h5>

            <form action="https://formspree.io/f/mrgwpnnn" method="POST" novalidate id="contactForm">
              
              <!-- Nombre -->
              <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       placeholder="Tu nombre completo" required>
                <small class="form-text text-muted">¿Cómo podemos llamarte?</small>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="tu@email.com" required>
                <small class="form-text text-muted">Usaremos este correo para responderte</small>
              </div>

              <!-- Asunto -->
              <div class="mb-3">
                <label for="asunto" class="form-label fw-bold">Asunto <span class="text-danger">*</span></label>
                <select class="form-select" id="asunto" name="asunto" required>
                  <option value="">Selecciona un asunto...</option>
                  <option value="consulta-general">Consulta General</option>
                  <option value="soporte-tecnico">Soporte Técnico</option>
                  <option value="sugerencia">Sugerencia o Mejora</option>
                  <option value="colaboracion">Colaboración</option>
                  <option value="reporte-problema">Reporte de Problema</option>
                  <option value="otro">Otro</option>
                </select>
              </div>

              <!-- Mensaje -->
              <div class="mb-4">
                <label for="mensaje" class="form-label fw-bold">Mensaje <span class="text-danger">*</span></label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="6" 
                          placeholder="Cuéntanos con detalle qué necesitas. Nos ayuda para atenderte mejor..." required></textarea>
                <small class="form-text text-muted">Mínimo 10 caracteres</small>
              </div>

              <!-- Checkbox de privacidad -->
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="privacidad" required>
                <label class="form-check-label" for="privacidad">
                  He leído y acepto la <a href="/privacidad.php" class="link-primary">Política de Privacidad</a> 
                  <span class="text-danger">*</span>
                </label>
                <small class="d-block form-text text-muted mt-1">Tu información será tratada confidencialmente</small>
              </div>

              <!-- Botón enviar -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                  <i class="bi bi-send me-2"></i> Enviar Mensaje
                </button>
              </div>

              <!-- Mensaje de ayuda -->
              <div class="alert alert-info mt-4" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Tiempo de respuesta:</strong> Nos esforzamos por responder todos los mensajes en menos de 48 horas hábiles.
              </div>

            </form>

          </div>
        </div>

        <!-- Sección adicional: FAQs rápidas -->
        <div class="mt-5">
          <h5 class="fw-bold mb-4 text-center">
            <i class="bi bi-question-circle me-2"></i> Preguntas Frecuentes
          </h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card h-100 border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title fw-bold text-primary">
                    <i class="bi bi-clock me-2"></i> ¿Cuánto tiempo tardan en responder?
                  </h6>
                  <p class="card-text small">Respondemos en un plazo de 24 a 48 horas hábiles.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title fw-bold text-primary">
                    <i class="bi bi-shield-check me-2"></i> ¿Es seguro compartir mis datos?
                  </h6>
                  <p class="card-text small">Sí, todos los datos se tratan según nuestra política de privacidad.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title fw-bold text-primary">
                    <i class="bi bi-search me-2"></i> ¿Dónde puedo encontrar ayuda técnica?
                  </h6>
                  <p class="card-text small">Selecciona "Soporte Técnico" en el formulario para asuntos técnicos.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title fw-bold text-primary">
                    <i class="bi bi-hand-thumbs-up me-2"></i> ¿Puedo sugerir mejoras?
                  </h6>
                  <p class="card-text small">¡Por supuesto! Usa la opción "Sugerencia o Mejora" en el formulario.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <?php include(__DIR__ . '/../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validación del formulario
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('contactForm');
      const mensajeInput = document.getElementById('mensaje');

      if (form) {
        form.addEventListener('submit', function(e) {
          // Validar longitud mínima del mensaje
          if (mensajeInput.value.trim().length < 10) {
            e.preventDefault();
            alert('El mensaje debe tener al menos 10 caracteres.');
            return;
          }

          // Mostrar mensaje de confirmación visual
          const btn = form.querySelector('button[type="submit"]');
          const originalText = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Enviando...';

          // Restaurar después de 3 segundos (Formspree maneja el envío)
          setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
          }, 3000);
        });
      }
    });
  </script>

  <style>
    .card {
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }

    .form-control, .form-select {
      border-radius: 8px;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn-primary {
      border-radius: 8px;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .btn-primary:hover:not(:disabled) {
      background-color: #0b5ed7;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .btn-primary:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }

    .bg-opacity-10 {
      background-color: rgba(13, 110, 253, 0.1);
    }

    .rounded-3 {
      border-radius: 12px;
    }

    @media (max-width: 768px) {
      .card-body {
        padding: 1.5rem !important;
      }

      .display-3 {
        font-size: 2rem;
      }

      label {
        font-size: 0.95rem;
      }
    }
  </style>

</body>
</html>