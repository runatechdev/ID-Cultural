<?php
// Si tienes alguna lógica de sesión al inicio, déjala aquí.
?>

<header class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container d-flex align-items-center justify-content-between">

    <!-- Logo y Nombre -->
    <a href="/index.php" class="navbar-brand d-flex align-items-center text-decoration-none">
      <img src="static/img/huella-idcultural.png" alt="ID Cultural Logo" height="40" class="me-2">
      <h4 class="m-0 text-white fw-bold">ID Cultural</h4>
    </a>

    <div>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <nav class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          
          <!-- Botón de traducción con dropdown de Bootstrap -->
          <li class="nav-item dropdown">
            <button class="btn btn-link nav-link" id="translateDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Traducir página">
              <i class="bi bi-globe2 fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="translateDropdown">
              <li><h6 class="dropdown-header"><i class="bi bi-translate"></i> Selecciona un idioma</h6></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('es')">🇪🇸 Español</a></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('en')">🇬🇧 English</a></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('pt')">🇧🇷 Português</a></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('fr')">🇫🇷 Français</a></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('it')">🇮🇹 Italiano</a></li>
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeLanguage('de')">🇩🇪 Deutsch</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="/index.php">Inicio</a></li>

          <!-- Botón de Búsqueda -->
          <li class="nav-item">
            <button class="btn btn-link nav-link" id="open-search-btn" aria-label="Abrir búsqueda">
              <i class="bi bi-search"></i>
            </button>
          </li>

          <li class="nav-item"><a class="nav-link" href="/wiki.php">Wiki de artistas</a></li>

          <?php if (isset($_SESSION['user_data'])): ?>
            <?php
              // --- INICIO: LÓGICA DE ROLES MEJORADA ---
              $user_role = $_SESSION['user_data']['role'];
              $profile_link = '';
              $profile_text = '';

              if ($user_role === 'admin') {
                  $profile_text = 'Panel de Control';
                  $profile_link = '/src/views/pages/admin/dashboard-adm.php';
              } elseif ($user_role === 'editor') {
                  $profile_text = 'Panel de Control';
                  $profile_link = '/src/views/pages/editor/panel_editor.php';
              } elseif ($user_role === 'validador') {
                  $profile_text = 'Panel de Control';
                  $profile_link = '/src/views/pages/validador/panel_validador.php';
              } elseif ($user_role === 'artista_validado' || $user_role === 'usuario') {
                  $profile_text = 'Mi Perfil';
                  $profile_link = '/src/views/pages/artista/dashboard-artista.php';
              } else {
                  // Fallback para roles no definidos
                  $profile_text = 'Mi Perfil';
                  $profile_link = '/src/views/pages/artista/dashboard-artista.php';
              }
            ?>
            <!-- Se muestra si el usuario INICIÓ SESIÓN -->
            <li class="nav-item"><a class="nav-link" href="<?php echo $profile_link; ?>"><?php echo $profile_text; ?></a></li>
            <li class="nav-item"><a class="btn btn-danger rounded-pill btn-sm" href="/logout.php">Cerrar Sesión</a></li>
            <!-- --- FIN: LÓGICA DE ROLES --- -->
          <?php else: ?>
            <!-- Se muestra si el usuario NO ha iniciado sesión (invitado) -->
            <li class="nav-item"><a class="nav-link" href="/src/views/pages/auth/login.php">Iniciar Sesión</a></li>
            <li class="nav-item">
              <a class="btn btn-outline-light rounded-pill btn-sm" href="/src/views/pages/auth/registro.php">Crear cuenta</a>
            </li>
          <?php endif; ?>

        </ul>
      </nav>
    </div>

  </div>
</header>

<!-- Estructura de la ventana de búsqueda -->
<div id="search-overlay" class="search-overlay">
  <button id="close-search-btn" class="btn-close-search" aria-label="Cerrar búsqueda">&times;</button>
  <div class="search-overlay-content">
    <form id="search-form" action="/busqueda.php" method="get">
      <input type="search" name="q" class="form-control-search" placeholder="Buscar artistas, obras, eventos..." autofocus>
      <button type="submit" class="btn-search-submit" aria-label="Buscar">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
</div>

<!-- Google Translate (OCULTO) - Solo para funcionalidad -->
<div id="google_translate_element" style="display: none;"></div>

<script type="text/javascript">
// Función para cambiar el idioma
function changeLanguage(lang) {
  if (lang === 'es') {
    deleteCookie('googtrans');
    window.location.reload();
  } else {
    setCookie('googtrans', '/es/' + lang, 1);
    setCookie('googtrans', '/es/' + lang, 1, window.location.hostname);
    window.location.reload();
  }
}

// Funciones de cookies
function setCookie(name, value, days, domain) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  const domainStr = domain ? "; domain=" + domain : "";
  document.cookie = name + "=" + (value || "") + expires + domainStr + "; path=/";
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
}

function deleteCookie(name) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname;
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + window.location.hostname;
}

// Inicializar Google Translate
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'es',
    includedLanguages: 'en,fr,it,de,pt',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}

// Al cargar la página
window.addEventListener('DOMContentLoaded', function() {
  const currentLang = getCookie('googtrans');
  if (!currentLang || currentLang === '/es/es' || currentLang === '') {
    deleteCookie('googtrans');
  }
  
  const style = document.createElement('style');
  style.textContent = `
    body { top: 0px !important; position: static !important; }
    .skiptranslate { display: none !important; }
    body > .skiptranslate { display: none !important; }
  `;
  document.head.appendChild(style);
});

// Ocultar elementos de Google Translate
window.addEventListener('load', function() {
  setTimeout(() => {
    const frames = document.querySelectorAll('iframe.goog-te-banner-frame');
    frames.forEach(frame => {
      frame.style.display = 'none';
      frame.parentNode && frame.parentNode.removeChild(frame);
    });
    document.body.style.top = '0px';
    document.body.style.position = 'static';
    document.body.classList.remove('translated-ltr', 'translated-rtl');
    const widget = document.getElementById('google_translate_element');
    if (widget) widget.style.display = 'none';
    const skiptranslate = document.querySelectorAll('.skiptranslate');
    skiptranslate.forEach(elem => {
      if (elem.tagName === 'DIV' && !elem.querySelector('.goog-te-combo')) {
        elem.style.display = 'none';
      }
    });
  }, 100);

  setTimeout(() => {
    document.body.style.top = '0px';
    document.body.style.position = 'static';
  }, 500);

  setTimeout(() => {
    document.body.style.top = '0px';
    document.body.style.position = 'static';
  }, 1000);
});
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
.dropdown-menu { min-width: 200px; }
.dropdown-item { padding: 0.5rem 1rem; transition: background-color 0.2s ease; cursor: pointer; }
.dropdown-item:hover { background-color: rgba(0, 123, 255, 0.1); }
.dropdown-item:active { background-color: rgba(0, 123, 255, 0.2); }
.dropdown-header { font-weight: 600; color: #0d6efd; }

#translateDropdown { position: relative; padding: 0.5rem; transition: all 0.3s ease; }
#translateDropdown:hover { transform: scale(1.1); color: rgba(255, 255, 255, 0.9) !important; }
#translateDropdown .bi-globe2 { animation: pulse 2s infinite; }

@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

.navbar-brand img {
  filter: brightness(0) invert(1);
  transition: transform 0.3s ease;
}
.navbar-brand:hover img { transform: scale(1.1); }

body > .skiptranslate { display: none !important; }
.goog-te-banner-frame, .goog-te-banner-frame.skiptranslate {
  display: none !important; visibility: hidden !important;
}
#goog-gt-tt, .goog-te-balloon-frame { display: none !important; }
.goog-text-highlight { background: none !important; box-shadow: none !important; }
body.translated-ltr, body.translated-rtl { top: 0 !important; margin-top: 0 !important; }
.goog-te-gadget, iframe.skiptranslate, .goog-logo-link,
.goog-te-gadget span, #google_translate_element {
  display: none !important;
}
</style>