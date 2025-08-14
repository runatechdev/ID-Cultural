document.addEventListener('DOMContentLoaded', () => {
  const openBtn = document.getElementById('open-search-btn');
  const closeBtn = document.getElementById('close-search-btn');
  const searchOverlay = document.getElementById('search-overlay');
  const searchInput = document.querySelector('.form-control-search');
  const searchForm = document.getElementById('search-form');

  if (openBtn && closeBtn && searchOverlay) {
    
    // Abrir la ventana de búsqueda
    openBtn.addEventListener('click', () => {
      searchOverlay.classList.add('active');
      // Poner el foco en el campo de texto para que el usuario pueda escribir de inmediato
      searchInput.focus();
    });

    // Cerrar la ventana de búsqueda
    closeBtn.addEventListener('click', () => {
      searchOverlay.classList.remove('active');
    });
    
    // Cerrar también si se presiona la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            searchOverlay.classList.remove('active');
        }
    });

    // Manejar el envío del formulario
    searchForm.addEventListener('submit', (e) => {
        if (searchInput.value.trim() === '') {
            e.preventDefault(); // Evita enviar una búsqueda vacía
        }
        // La acción del formulario ya redirige a /search.php?q=...
    });
  }
});
