/**
 * Script para cargar y mostrar artistas famosos en la Wiki
 * Carga datos desde la API
 */

// Mapa de emojis por categoría
const emojiPorCategoria = {
    'Música': '🎤',
    'musica': '🎤',
    'Literatura': '📚',
    'literatura': '📚',
    'Artes Plásticas': '🎨',
    'artes_plasticas': '🎨',
    'Danza': '💃',
    'danza': '💃',
    'Teatro': '�',
    'teatro': '🎭'
};

// Cargar al ready
document.addEventListener('DOMContentLoaded', function () {
    cargarArtistasFamosos();
});

async function cargarArtistasFamosos() {
    const container = document.getElementById('famous-artists-container');
    if (!container) {
        console.error('No se encontró el contenedor famous-artists-container');
        return;
    }

    try {
        const response = await fetch(window.BASE_URL + 'api/artistas.php?action=featured');
        const data = await response.json();

        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted"><p>No hay artistas registrados</p></div>';
            return;
        }

        // Limpiar
        container.innerHTML = '';

        // Agregar tarjetas sin parpadeo
        data.data.forEach(artist => {
            container.innerHTML += crearTarjetaArtista(artist);
        });

        console.log('Cargados ' + data.data.length + ' artistas famosos');
    } catch (error) {
        console.error('Error cargando artistas:', error);
        container.innerHTML = '<div class="col-12 text-center text-danger"><p>Error al cargar artistas</p></div>';
    }
}

function crearTarjetaArtista(artist) {
    const categoryMap = {
        "Música": "Musica",
        "musica": "Musica",
        "Literatura": "Literatura",
        "literatura": "Literatura",
        "Artes Plásticas": "Artes Plásticas",
        "artes_plasticas": "Artes Plásticas",
        "Danza": "Danza",
        "danza": "Danza",
        "Teatro": "Teatro",
        "teatro": "Teatro"
    };
    const categoryClass = categoryMap[artist.categoria] || "Musica";

    // Obtener emoji de la API (ya viene configurado) o usar mapa
    const emoji = artist.emoji || emojiPorCategoria[artist.categoria] || '⭐';

    // Procesar logros
    let logrosHTML = '';
    if (artist.logros_premios) {
        const logros = typeof artist.logros_premios === 'string'
            ? artist.logros_premios.split(',').map(l => l.trim()).filter(l => l)
            : artist.logros_premios;
        logrosHTML = logros.map(logro =>
            `<span class="achievement">🏆 ${logro}</span>`
        ).join('');
    }

    return `
        <div class="col-lg-6 col-md-6 famous-artist-item" data-category="${categoryClass}">
            <div class="famous-artist-card">
                <div class="famous-image">
                    <span style="font-size: 5rem; z-index: 10; position: relative;">${emoji}</span>
                    <div class="famous-overlay">
                        <span class="famous-badge">${artist.badge}</span>
                    </div>
                </div>
                <div class="famous-content">
                    <h4>${artist.nombre_completo}</h4>
                    <p class="famous-category">${artist.categoria} - ${artist.subcategoria}</p>
                    <p class="famous-bio">${artist.biografia}</p>
                    <div class="famous-achievements">
                        ${logrosHTML}
                    </div>
                </div>
            </div>
        </div>
    `;
}
