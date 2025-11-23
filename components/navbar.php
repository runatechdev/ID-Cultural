<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegurar que BASE_URL esté definido
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}
?>

<!-- Material Icons - CDN -->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<header class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100">
      
      <!-- Logo y Nombre -->
      <a href="<?php echo BASE_URL; ?>index.php" class="navbar-brand d-flex align-items-center text-decoration-none">
        <img src="<?php echo BASE_URL; ?>static/img/huella-idcultural.png" alt="ID Cultural Logo" height="40" class="me-2">
        <h4 class="m-0 text-white fw-bold typing-effect notranslate" id="navbar-title"></h4>
      </a>

      <!-- Botón toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <!-- Menú colapsable -->
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

          <?php
          // Determinar si hay sesión y qué rol tiene (mover aquí para usarlo antes)
          $is_logged_in = isset($_SESSION['user_data']);
          $user_role = $is_logged_in ? $_SESSION['user_data']['role'] : null;
          ?>

          <li class="nav-item"><a class="nav-link text-nowrap" href="/wiki.php">Wiki de Artistas</a></li>

          <!-- Notificaciones (solo para artistas logueados) -->
          <?php if ($is_logged_in && ($user_role === 'artista' || $user_role === 'artista_validado' || $user_role === 'usuario')): ?>
          <li class="nav-item dropdown">
            <a class="nav-link position-relative" href="#" id="btn-notificaciones" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell fs-5"></i>
              <span id="notificaciones-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.6rem;">
                0
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end notificaciones-dropdown" aria-labelledby="btn-notificaciones" style="width: 350px; max-height: 500px; overflow-y: auto;">
              <div class="dropdown-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Notificaciones</span>
                <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" id="marcar-todas-leidas">
                  <small>Marcar todas</small>
                </button>
              </div>
              <div class="dropdown-divider"></div>
              <div id="notificaciones-lista">
                <!-- Las notificaciones se cargarán aquí dinámicamente -->
                <div class="text-center py-3">
                  <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <?php endif; ?>

          <!-- Menú dinámico - Siempre visible pero con opciones diferentes -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center notranslate" href="#" id="mainMenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="gap: 8px;">
              <i class="material-icons" style="font-size: 22px;">view_carousel</i>
              <span>Menú</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-with-icons" aria-labelledby="mainMenuDropdown">
              
              <?php if ($is_logged_in && ($user_role === 'artista' || $user_role === 'artista_validado' || $user_role === 'usuario')): ?>
                <!-- Menú para Artistas Logueados -->
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
                <a href="/noticias.php" class="dropdown-item">
                  <i class="material-icons">article</i> Noticias
                </a>
                <a href="/perfil_artista.php?id=<?php echo htmlspecialchars($_SESSION['user_data']['id']); ?>" class="dropdown-item">
                  <i class="material-icons">person</i> Mi Perfil
                </a>
                <a href="/src/views/pages/artista/crear-borrador.php" class="dropdown-item">
                  <i class="material-icons">assignment</i> Agregar obras
                </a>
                <a href="/src/views/pages/artista/dashboard-artista.php" class="dropdown-item">
                  <i class="material-icons">build</i> Editar Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item">
                  <i class="material-icons">exit_to_app</i> Salir
                </a>
              <?php elseif ($is_logged_in && $user_role === 'admin'): ?>
                <!-- Menú para Administradores -->
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
                <a href="/noticias.php" class="dropdown-item">
                  <i class="material-icons">article</i> Noticias
                </a>
                <a href="/src/views/pages/admin/dashboard-adm.php" class="dropdown-item">
                  <i class="material-icons">dashboard</i> Panel de Control
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item">
                  <i class="material-icons">exit_to_app</i> Salir
                </a>
              <?php elseif ($is_logged_in && $user_role === 'editor'): ?>
                <!-- Menú para Editores -->
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
                <a href="/noticias.php" class="dropdown-item">
                  <i class="material-icons">article</i> Noticias
                </a>
                <a href="/src/views/pages/editor/panel_editor.php" class="dropdown-item">
                  <i class="material-icons">edit</i> Panel de Control
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item">
                  <i class="material-icons">exit_to_app</i> Salir
                </a>
              <?php elseif ($is_logged_in && $user_role === 'validador'): ?>
                <!-- Menú para Validadores -->
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
                <a href="/noticias.php" class="dropdown-item">
                  <i class="material-icons">article</i> Noticias
                </a>
                <a href="/src/views/pages/validador/panel_validador.php" class="dropdown-item">
                  <i class="material-icons">verified</i> Panel de Control
                </a>
                <a href="/src/views/pages/validador/log_validaciones.php" class="dropdown-item">
                  <i class="material-icons">history_edu</i> Historial de Validaciones
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item">
                  <i class="material-icons">exit_to_app</i> Salir
                </a>
              <?php else: ?>
                <!-- Menú para usuarios NO logueados -->
                <a href="/index.php" class="dropdown-item">
                  <i class="material-icons">fingerprint</i> Inicio
                </a>
                <a href="/noticias.php" class="dropdown-item">
                  <i class="material-icons">article</i> Noticias
                </a>
                <div class="dropdown-divider"></div>
                <a href="/src/views/pages/auth/login.php" class="dropdown-item">
                  <i class="material-icons">person_add</i> Iniciar Sesión
                </a>
                <a href="/src/views/pages/auth/registro.php" class="dropdown-item">
                  <i class="material-icons">person_add_alt</i> Crear cuenta
                </a>
              <?php endif; ?>
            </div>
          </li>

          <?php if (isset($_SESSION['user_data'])): ?>
            <!-- Usuario logueado - no mostrar botón adicional, todo está en el menú -->
            <!-- El dropdown menu ya contiene todas las opciones para cada rol -->
            <!-- Usuario logueado - no mostrar botón adicional, todo está en el menú -->
            <!-- El dropdown menu ya contiene todas las opciones para cada rol -->
          <?php else: ?>
            <!-- Se muestra si el usuario NO ha iniciado sesión (invitado) -->
            <!-- Las opciones de login/registro ahora están en el menú dinámico -->
            <!-- Las opciones de login/registro ahora están en el menú dinámico -->
          <?php endif; ?>

        </ul>
      </nav>

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
// Función para cambiar el idioma - Mejorada
function changeLanguage(lang) {
  console.log('Cambiando idioma a:', lang);
  console.log('Protocolo:', window.location.protocol);
  console.log('Hostname:', window.location.hostname);
  
  if (lang === 'es') {
    // Volver a español - eliminar traducción
    eraseCookie('googtrans');
    eraseCookie('googtrans', window.location.hostname);
    eraseCookie('googtrans', '.' + window.location.hostname);
    
    // Para ngrok, también limpiar sin el subdominio
    if (window.location.hostname.includes('ngrok')) {
      const baseDomain = window.location.hostname.split('.').slice(-2).join('.');
      eraseCookie('googtrans', '.' + baseDomain);
    }
    
    // Recargar eliminando el hash de Google Translate
    window.location.href = window.location.pathname + window.location.search;
  } else {
    // Cambiar a otro idioma
    const langPair = '/es/' + lang;
    console.log('Estableciendo langPair:', langPair);
    
    // Establecer cookies en múltiples variantes - TODAS a la vez
    const isSecure = window.location.protocol === 'https:';
    const expires = new Date();
    expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000));
    const expiresStr = "; expires=" + expires.toUTCString();
    
    // Variante 1: Sin dominio
    if (isSecure) {
      document.cookie = `googtrans=${langPair}${expiresStr}; path=/; SameSite=None; Secure`;
    } else {
      document.cookie = `googtrans=${langPair}${expiresStr}; path=/; SameSite=Lax`;
    }
    
    // Variante 2: Con hostname completo
    if (isSecure) {
      document.cookie = `googtrans=${langPair}${expiresStr}; path=/; domain=${window.location.hostname}; SameSite=None; Secure`;
    } else {
      document.cookie = `googtrans=${langPair}${expiresStr}; path=/; domain=${window.location.hostname}; SameSite=Lax`;
    }
    
    // Variante 3: Con punto antes del hostname (para subdominios)
    if (window.location.hostname.includes('.')) {
      if (isSecure) {
        document.cookie = `googtrans=${langPair}${expiresStr}; path=/; domain=.${window.location.hostname}; SameSite=None; Secure`;
      } else {
        document.cookie = `googtrans=${langPair}${expiresStr}; path=/; domain=.${window.location.hostname}; SameSite=Lax`;
      }
      
      // Variante 4: Dominio base para ngrok (ej: .ngrok-free.app)
      const parts = window.location.hostname.split('.');
      if (parts.length >= 2) {
        const baseDomain = parts.slice(-2).join('.');
        if (isSecure) {
          document.cookie = `googtrans=${langPair}${expiresStr}; path=/; domain=.${baseDomain}; SameSite=None; Secure`;
        }
      }
    }
    
    console.log('Cookies establecidas, verificando...');
    console.log('document.cookie:', document.cookie);
    
    // Verificar que se estableció
    const check = getCookie('googtrans');
    console.log('Cookie verificada:', check);
    
    if (check === langPair) {
      console.log('✅ Cookie establecida correctamente, recargando página...');
      // Recargar con el hash que Google Translate espera
      window.location.href = window.location.pathname + window.location.search + '#googtrans(' + lang + ')';
      // Forzar recarga completa
      setTimeout(() => {
        window.location.reload(true);
      }, 100);
    } else {
      console.error('❌ Error: Cookie no se estableció correctamente');
      console.log('Esperado:', langPair, 'Obtenido:', check);
    }
  }
}

// Funciones de cookies mejoradas
function setCookie(name, value, days, domain) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  
  const domainStr = domain ? "; domain=" + domain : "";
  const isSecure = window.location.protocol === 'https:';
  
  // Para HTTPS (ngrok), usar SameSite=None; Secure
  // Para HTTP (localhost), usar SameSite=Lax
  let cookie;
  if (isSecure) {
    cookie = name + "=" + (value || "") + expires + domainStr + "; path=/; SameSite=None; Secure";
  } else {
    cookie = name + "=" + (value || "") + expires + domainStr + "; path=/; SameSite=Lax";
  }
  
  document.cookie = cookie;
  console.log('Cookie establecida:', cookie);
  
  // Verificar que se estableció
  setTimeout(() => {
    const check = getCookie(name);
    console.log('Verificación - Cookie "' + name + '":', check);
  }, 50);
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function eraseCookie(name, domain) {
  const domainStr = domain ? "; domain=" + domain : "";
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/' + domainStr;
  console.log('Cookie borrada:', name, domain);
}

function deleteCookie(name) {
  eraseCookie(name);
  eraseCookie(name, window.location.hostname);
  eraseCookie(name, '.' + window.location.hostname);
}

// Inicializar Google Translate
function googleTranslateElementInit() {
  console.log('=== Inicializando Google Translate ===');
  console.log('Cookie googtrans al iniciar:', getCookie('googtrans'));
  console.log('Todas las cookies:', document.cookie);
  
  try {
    new google.translate.TranslateElement({
      pageLanguage: 'es',
      includedLanguages: 'en,fr,it,de,pt',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: true, // Cambiar a true para que Google lo detecte automáticamente
      multilanguagePage: true
    }, 'google_translate_element');
    
    // Solo limpiar elementos visuales, no forzar traducción
    setTimeout(() => {
      console.log('Google Translate inicializado');
      const savedLang = getCookie('googtrans');
      console.log('Idioma en cookie:', savedLang);
      
      // Actualizar indicador visual
      updateLanguageIndicator();
      
      // Limpiar elementos que causan el logo trabado
      cleanupGoogleElements();
    }, 1000);
    
  } catch (error) {
    console.error('Error inicializando Google Translate:', error);
  }
}

// Limpiar elementos problemáticos de Google Translate
function cleanupGoogleElements() {
  // Ocultar el widget de selección
  const widget = document.getElementById('google_translate_element');
  if (widget) widget.style.display = 'none';
  
  // Ocultar frames y banners
  const frames = document.querySelectorAll('iframe.goog-te-banner-frame, iframe.skiptranslate');
  frames.forEach(frame => {
    frame.style.display = 'none';
    frame.style.visibility = 'hidden';
  });
  
  // Ajustar body
  document.body.style.top = '0px';
  document.body.style.position = 'static';
  
  // Ocultar divs de Google
  const skiptranslate = document.querySelectorAll('.skiptranslate');
  skiptranslate.forEach(elem => {
    if (elem.tagName === 'DIV' && !elem.querySelector('.goog-te-combo')) {
      elem.style.display = 'none';
    }
  });
  
  // Ocultar logo/spinner de Google
  const googleLogo = document.querySelector('.goog-logo-link');
  if (googleLogo) googleLogo.style.display = 'none';
  
  const toolbar = document.querySelector('.goog-te-banner');
  if (toolbar) toolbar.style.display = 'none';
}

// Actualizar indicador visual del idioma actual
function updateLanguageIndicator() {
  const savedLang = getCookie('googtrans');
  const langCode = savedLang ? savedLang.split('/')[2] : 'es';
  const button = document.getElementById('translateDropdown');
  
  const flags = {
    'es': '🇪🇸',
    'en': '🇺🇸',
    'pt': '🇧🇷',
    'fr': '🇫🇷',
    'it': '🇮🇹',
    'de': '🇩🇪'
  };
  
  if (button && langCode !== 'es') {
    button.innerHTML = `<span style="font-size: 1.2rem;">${flags[langCode] || '🌐'}</span>`;
  }
}

// Al cargar la página
window.addEventListener('DOMContentLoaded', function() {
  console.log('=== DOM Cargado ===');
  console.log('Protocolo:', window.location.protocol);
  console.log('Hostname:', window.location.hostname);
  console.log('Cookie googtrans:', getCookie('googtrans'));
  console.log('Todas las cookies:', document.cookie);
  
  // Verificar y limpiar cookies inválidas
  const currentLang = getCookie('googtrans');
  
  if (!currentLang || currentLang === '/es/es' || currentLang === '') {
    console.log('No hay traducción activa, limpiando cookies...');
    deleteCookie('googtrans');
    sessionStorage.removeItem('translate_retry');
  } else {
    console.log('Traducción detectada:', currentLang);
  }
  
  // Actualizar indicador visual
  updateLanguageIndicator();
  
  // Inyectar CSS adicional para eliminar barra blanca
  const style = document.createElement('style');
  style.textContent = `
    body {
      top: 0px !important;
      position: static !important;
    }
    .skiptranslate {
      display: none !important;
    }
    body > .skiptranslate {
      display: none !important;
    }
    .goog-te-banner-frame {
      display: none !important;
    }
  `;
  document.head.appendChild(style);
});

// Ocultar elementos de Google Translate
window.addEventListener('load', function() {
  // Limpiar múltiples veces para asegurar que se oculte todo
  const cleanupIntervals = [100, 300, 500, 800, 1200, 2000, 3000];
  
  cleanupIntervals.forEach(delay => {
    setTimeout(() => {
      cleanupGoogleElements();
    }, delay);
  });
  
  // También observar cambios en el DOM
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.addedNodes.length) {
        mutation.addedNodes.forEach(node => {
          if (node.nodeType === 1) { // Element node
            // Ocultar frames de Google
            if (node.tagName === 'IFRAME' && 
                (node.classList.contains('goog-te-banner-frame') || 
                 node.classList.contains('skiptranslate'))) {
              node.style.display = 'none';
              node.style.visibility = 'hidden';
            }
            
            // Ocultar divs de Google
            if (node.classList && node.classList.contains('skiptranslate')) {
              if (!node.querySelector('.goog-te-combo')) {
                node.style.display = 'none';
              }
            }
          }
        });
      }
    });
    
    // Asegurar que el body esté correcto
    document.body.style.top = '0px';
    document.body.style.position = 'static';
  });
  
  // Observar cambios en el body
  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
});

// Funcionalidad del botón de búsqueda
document.addEventListener('DOMContentLoaded', function() {
const openSearchBtn = document.getElementById('open-search-btn');
const closeSearchBtn = document.getElementById('close-search-btn');
const searchOverlay = document.getElementById('search-overlay');
const searchForm = document.getElementById('search-form');
const searchInput = document.querySelector('.form-control-search');

if (openSearchBtn) {
  openSearchBtn.addEventListener('click', function() {
    searchOverlay.classList.add('active');
    if (searchInput) searchInput.focus();
  });
}

if (closeSearchBtn) {
  closeSearchBtn.addEventListener('click', function() {
    searchOverlay.classList.remove('active');
  });
}

if (searchOverlay) {
  searchOverlay.addEventListener('click', function(e) {
    if (e.target === searchOverlay) {
      searchOverlay.classList.remove('active');
    }
  });
}

if (searchInput) {
  searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Escape') {
      searchOverlay.classList.remove('active');
    }
  });
}

const titleElement = document.getElementById('navbar-title');
const phrases = ['ID Cultural', 'Sgo del Estero'];
let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
  const currentPhrase = phrases[phraseIndex];
  
  // Definimos el cursor: si estamos escribiendo/borrando siempre se ve.
  // El parpadeo solo ocurrirá en la pausa (manejado más abajo).
  const cursor = '|'; 

  if (isDeleting) {
    // Borrando
    titleElement.textContent = currentPhrase.substring(0, charIndex - 1) + cursor;
    charIndex--;
  } else {
    // Escribiendo
    titleElement.textContent = currentPhrase.substring(0, charIndex + 1) + cursor;
    charIndex++;
  }

  // --- CONTROL DE VELOCIDAD NATURAL ---
  // Base más lenta (150ms) + Variación aleatoria (0 a 100ms extra)
  // Esto hace que cada letra tarde un tiempo distinto, como una persona real.
  let typeSpeed = 150 + Math.random() * 100;

  if (isDeleting) {
    typeSpeed /= 2; // Borrar sigue siendo un poco más rápido
  }

  // --- CONTROL DE FLUJO ---
  if (!isDeleting && charIndex === currentPhrase.length) {
    // CASO: FRASE COMPLETA (Pausa con parpadeo)
    
    let blinkCount = 0;
    const maxBlinks = 6; // Cantidad de veces que parpadea (aprox 2-3 segs)
    
    // Iniciamos un intervalo exclusivo para el parpadeo
    const blinkInterval = setInterval(() => {
      // Alternamos entre mostrar el cursor y no mostrarlo
      if (titleElement.textContent.endsWith('|')) {
         titleElement.textContent = currentPhrase; // Sin cursor
      } else {
         titleElement.textContent = currentPhrase + '|'; // Con cursor
      }
      
      blinkCount++;
      
      // Cuando termine de parpadear, limpiamos y seguimos borrando
      if (blinkCount >= maxBlinks) {
        clearInterval(blinkInterval);
        isDeleting = true; // Cambiamos estado a borrar
        setTimeout(typeEffect, 1000); // Pequeña pausa antes de empezar a borrar
      }
    }, 400); // Velocidad del parpadeo (400ms)
    
    return; // IMPORTANTE: Detenemos la recursión aquí para que el intervalo tome el control

  } else if (isDeleting && charIndex === 0) {
    // CASO: FRASE BORRADA COMPLETAMENTE
    isDeleting = false;
    phraseIndex = (phraseIndex + 1) % phrases.length; // Siguiente frase
    typeSpeed = 500; // Pausa antes de empezar a escribir la nueva
  }

  setTimeout(typeEffect, typeSpeed);
}

// Iniciar
document.addEventListener('DOMContentLoaded', typeEffect);

// Iniciar efecto cuando se carga la página
window.addEventListener('load', typeEffect);
});
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
/* Estilos para Material Icons en el navbar */
.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-flex;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  font-feature-settings: 'liga';
  -moz-font-feature-settings: 'liga';
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
  vertical-align: middle;
}

/* Navbar responsive - evitar que el menú empuje el logo */
@media (max-width: 991.98px) {
  /* Forzar navbar sticky en móvil */
  .navbar.sticky-top {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    z-index: 1030 !important;
  }
  
  .navbar .container {
    flex-wrap: wrap;
  }
  
  .navbar-collapse {
    width: 100%;
    margin-top: 1rem;
    overflow: visible !important;
  }
  
  /* Mantener el logo y título fijos en su línea */
  .navbar-brand {
    flex: 1;
  }
  
  .navbar-toggler {
    order: 1;
  }
  
  /* Mantener los items del navbar en horizontal */
  .navbar-nav {
    flex-direction: row !important;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: visible !important;
    -webkit-overflow-scrolling: touch;
  }
  
  .navbar-nav .nav-item {
    white-space: nowrap;
    position: static;
  }
  
  /* Asegurar que los dropdowns se vean completos */
  .navbar-nav .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: auto !important;
    right: 0 !important;
    margin-top: 0.5rem;
  }
  
  /* Ajustar padding de los nav-links en móvil */
  .navbar-nav .nav-link {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
  }
}

/* Efecto de escritura en el título */
.typing-effect {
  font-family: 'Courier New', monospace;
  letter-spacing: 0.1em;
  min-width: 150px;
  position: relative;
}

.typing-effect::after {
  content: '';
  display: inline;
  animation: blink 0.7s infinite;
  color: white;
}

@keyframes blink {
  0%, 49% {
    opacity: 1;
  }
  50%, 100% {
    opacity: 0;
  }
}

/* Estilos para el dropdown con iconos (Material Icons) */
.dropdown-with-icons {
  min-width: 250px;
  padding: 5px 0;
  border-radius: 4px;
}

.dropdown-with-icons .dropdown-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  transition: all 0.2s ease;
  color: #333;
  text-decoration: none;
  font-size: 14px;
  position: relative;
}

.dropdown-with-icons .dropdown-item:hover {
  background-color: rgba(0, 0, 0, 0.04);
  color: #0d6efd;
}

.dropdown-with-icons .dropdown-item i {
  margin-right: 16px;
  font-size: 20px;
  color: #666;
  width: 24px;
  text-align: center;
}

.dropdown-with-icons .dropdown-item:hover i {
  color: #0d6efd;
}

.dropdown-with-icons .dropdown-divider {
  margin: 5px 0;
  background-color: #e9ecef;
}

/* Estilos para Material Icons en el navbar */
.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-flex;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  font-feature-settings: 'liga';
  -moz-font-feature-settings: 'liga';
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
  vertical-align: middle;
}

/* Estilos para el dropdown con iconos (Material Icons) */
.dropdown-with-icons {
  min-width: 250px;
  padding: 5px 0;
  border-radius: 4px;
}

.dropdown-with-icons .dropdown-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  transition: all 0.2s ease;
  color: #333;
  text-decoration: none;
  font-size: 14px;
  position: relative;
}

.dropdown-with-icons .dropdown-item:hover {
  background-color: rgba(0, 0, 0, 0.04);
  color: #0d6efd;
}

.dropdown-with-icons .dropdown-item i {
  margin-right: 16px;
  font-size: 20px;
  color: #666;
  width: 24px;
  text-align: center;
}

.dropdown-with-icons .dropdown-item:hover i {
  color: #0d6efd;
}

.dropdown-with-icons .dropdown-divider {
  margin: 5px 0;
  background-color: #e9ecef;
}

/* Estilos adicionales para el dropdown de traducción */
.dropdown-menu {
  min-width: 200px;
  z-index: 1100 !important; /* Mayor que search-overlay (1050) */
}

.dropdown-item {
  padding: 0.5rem 1rem;
  transition: background-color 0.2s ease;
  cursor: pointer;
}

.dropdown-item:hover {
  background-color: rgba(0, 123, 255, 0.1);
}

.dropdown-item:active {
  background-color: rgba(0, 123, 255, 0.2);
}

.dropdown-header {
  font-weight: 600;
  color: #0d6efd;
}

/* Estilo del botón de idiomas */
#translateDropdown {
  position: relative;
  padding: 0.5rem;
  transition: all 0.3s ease;
}

#translateDropdown:hover {
  transform: scale(1.1);
  color: rgba(255, 255, 255, 0.9) !important;
}

#translateDropdown .bi-globe2 {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

/* Logo del navbar */
.navbar-brand img {
  filter: brightness(1) contrast(1.0) drop-shadow(0 0 2px rgba(255, 255, 255, 0.8));
  transition: transform 0.3s ease, filter 0.3s ease;
  opacity: 1;
}

.navbar-brand:hover img {
  transform: scale(1.1);
  filter: brightness(1.5) contrast(1.5) drop-shadow(0 0 4px rgba(255, 255, 255, 1));
  opacity: 1;
}

/* Asegurar que NO aparezca la barra de Google */
body > .skiptranslate {
  display: none !important;
  visibility: hidden !important;
}

.goog-te-banner-frame,
.goog-te-banner-frame.skiptranslate {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
  opacity: 0 !important;
}

#goog-gt-tt, .goog-te-balloon-frame {
  display: none !important;
}

.goog-text-highlight {
  background: none !important;
  box-shadow: none !important;
}

/* Ocultar completamente cualquier elemento de Google Translate */
body.translated-ltr,
body.translated-rtl {
  top: 0 !important;
  margin-top: 0 !important;
}

.goog-te-gadget {
  display: none !important;
}

iframe.skiptranslate {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
  position: absolute !important;
  left: -9999px !important;
}

.goog-logo-link {
  display: none !important;
}

.goog-te-gadget span {
  display: none !important;
}

#google_translate_element {
  display: none !important;
}

/* Ocultar el spinner/logo de carga de Google */
.goog-te-spinner-pos {
  display: none !important;
  visibility: hidden !important;
}

.goog-te-spinner {
  display: none !important;
  visibility: hidden !important;
}

/* Evitar que Google Translate modifique elementos con notranslate */
.notranslate {
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
}

/* Mantener ancho fijo del botón de menú para evitar cambios al traducir */
#mainMenuDropdown {
  min-width: 90px !important;
  white-space: nowrap !important;
}

#mainMenuDropdown .material-icons {
  flex-shrink: 0 !important;
}

/* Asegurar que el navbar-title no se traduzca */
#navbar-title {
  pointer-events: none;
  user-select: none;
}

/* Ocultar cualquier iframe de Google Translate */
iframe[name^="goog"] {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
  width: 0 !important;
  position: absolute !important;
  left: -9999px !important;
  top: -9999px !important;
}

/* Asegurar que el body no tenga margen superior */
body {
  top: 0 !important;
  position: static !important;
  margin-top: 0 !important;
}

html.translated-ltr,
html.translated-rtl {
  margin-top: 0 !important;
}

body.translated-ltr,
body.translated-rtl {
  top: 0 !important;
}

/* Ocultar el toolbar de Google Translate */
.goog-te-banner {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
}

/* Prevenir que elementos dinámicos de Google interfieran */
[style*="z-index: 1000000"] {
  display: none !important;
}

/* ========================================
   ESTILOS PARA NOTIFICACIONES
   ======================================== */

.notificaciones-dropdown {
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.notificacion-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  transition: background-color 0.2s;
}

.notificacion-item:hover {
  background-color: #f8f9fa !important;
}

.notificacion-item.notif-no-leida {
  background-color: #e7f3ff;
}

.notificacion-item.notif-leida {
  opacity: 0.8;
}

.notificacion-item h6 {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 4px;
}

.notificacion-item p {
  font-size: 0.85rem;
  color: #6c757d;
  margin-bottom: 4px;
}

.notificacion-item small {
  font-size: 0.75rem;
  color: #999;
}

#notificaciones-badge {
  font-size: 0.6rem;
  padding: 0.25em 0.5em;
}

.dropdown-header button {
  font-size: 0.8rem;
  color: #007bff;
}

.dropdown-header button:hover {
  text-decoration: underline !important;
}

</style>

<!-- Script de notificaciones para artistas -->
<?php if ($is_logged_in && ($user_role === 'artista' || $user_role === 'artista_validado' || $user_role === 'usuario')): ?>
<script src="<?php echo BASE_URL; ?>static/js/notificaciones.js"></script>
<?php endif; ?>