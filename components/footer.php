<div class="footer-zigzag">
  <svg viewBox="0 0 1200 100" preserveAspectRatio="none" class="zigzag-svg">
    <defs>
      <linearGradient id="zigzagGradient" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" stop-color="#00BFFF" />
        <stop offset="20%" stop-color="#32CD32" />
        <stop offset="40%" stop-color="#FFD700" />
        <stop offset="60%" stop-color="#FF8C00" />
        <stop offset="80%" stop-color="#FF1493" />
      </linearGradient>
    </defs>
    <path d="M0,100 L50,0 L100,100 L150,0 L200,100 L250,0 L300,100 L350,0 L400,100 L450,0 L500,100 L550,0 L600,100 L650,0 L700,100 L750,0 L800,100 L850,0 L900,100 L950,0 L1000,100 L1050,0 L1100,100 L1150,0 L1200,100 Z"
      fill="url(#zigzagGradient)" />
  </svg>
</div>

<footer class="footer">
  <div class="footer-content">
    <h2>ID Cultural</h2>
    <p>Subsecretaría de Cultura de Santiago del Estero</p>
  </div>

  <div class="footer-links">
    <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>src/views/pages/public/components/contacto.php" target="_blank">Contacto</a>
    <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>src/views/pages/public/components/privacidad.html" target="_blank">Política de Privacidad</a>
    <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>terminos_condiciones.php" target="_blank">Términos de Uso</a>
  </div>

  <p class="copyright">
    © 2025 ID Cultural | Todos los derechos reservados
  </p>

  <!-- Logos integrados en el footer colorido -->
  <div class="footer-logos">
    <a href="https://itse.gob.ar/" target="_blank" rel="noopener">
      <img src="/static/img/logos/Itse.png" alt="ItseLogo">
    </a>
    <a href="https://www.sde.gob.ar/tag/subsecretaria-de-cultura/" target="_blank" rel="noopener">
      <img src="/static/img/logos/cultura.png" alt="culturaLogo">
    </a>
    <a href="https://www.sde.gob.ar/tag/jefatura-de-gabinete-de-ministros/" target="_blank" rel="noopener">
      <img src="/static/img/logos/jefatura.png" alt="jefaturaLogo">
    </a>
    <a href="https://www.sde.gob.ar/" target="_blank" rel="noopener">
      <img src="/static/img/logos/gobierno.png" alt="sgoLogo">
    </a>
  </div>

  <p style="margin-top: 10px; font-size: 13px; opacity: 0.9;">
    Gobierno de Santiago del Estero
  </p>
</footer>