document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.category-btn');
  const biografiasSection = document.getElementById('biografias');

  let artistas = [];

  // Cargar el JSON (puede ser archivo local o API)
  fetch('/static/data/artistas.json') // Cambia ruta si necesario
    .then(response => response.json())
    .then(data => {
      artistas = data;
    });

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const categoria = btn.textContent.trim();

      // Limpiar contenido actual
      biografiasSection.innerHTML = '';

      // Filtrar artistas por categoría
      const filtrados = artistas.filter(a => a.categoria === categoria);

      const container = document.createElement('div');
      container.classList.add('categoria');
      container.dataset.category = categoria;

      const heading = document.createElement('h3');
      heading.textContent = categoria;
      container.appendChild(heading);

      if (filtrados.length === 0) {
        // Mostrar mensaje si no hay artistas
        const msgCard = document.createElement('div');
        msgCard.className = 'card msg-card';
        msgCard.innerHTML = `
          <div class="card-info">
            <h4>¡Atención!</h4>
            <p>Todavía no hay artistas registrados.</p>
          </div>
        `;
        container.appendChild(msgCard);
      } else {
        // Crear tarjetas dinámicas
        filtrados.forEach(a => {
          const card = document.createElement('div');
          card.className = 'card';
          card.innerHTML = `
            <img src="${a.imagen}" alt="${a.nombre}" />
            <div class="card-info">
              <h4>${a.nombre}</h4>
              <p>${a.descripcion}</p>
              <a href="#" class="btn-biografia">Leer Biografía Completa</a>
            </div>
          `;
          container.appendChild(card);
        });
      }

      biografiasSection.appendChild(container);
    });
  });
});