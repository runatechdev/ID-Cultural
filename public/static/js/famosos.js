/**
 * Script para cargar Artistas Famosos de Santiago del Estero
 * Sin parpadeo usando loading skeleton
 */

// Array de artistas famosos (misma data que está en la BD)
const artistasFamosos = [
    {
        nombre: "Mercedes Sosa",
        categoria: "Música",
        subcategoria: "Folklore/Nuevo Cancionero",
        bio: "Cantante argentina considerada una de las máximas exponentes del folklore latinoamericano.",
        badge: "Leyenda",
        emoji: "🎤",
        logros: ["Grammy Latino", "UNESCO"]
    },
    {
        nombre: "Andrés Chazarreta",
        categoria: "Música",
        subcategoria: "Folklore",
        bio: "Músico, compositor y folclorista argentino. Considerado el 'Patriarca del Folklore Argentino'.",
        badge: "Leyenda",
        emoji: "🎸",
        logros: ["Patriarca del Folklore", "Pionero Nacional"]
    },
    {
        nombre: "Jacinto Piedra",
        categoria: "Música",
        subcategoria: "Chacarera",
        bio: "Músico y compositor santiagueño, especializado en chacarera. Uno de los grandes exponentes del folklore.",
        badge: "Regional",
        emoji: "🎵",
        logros: ["Chacarera Maestra", "Virtuosismo"]
    },
    {
        nombre: "Raly Barrionuevo",
        categoria: "Música",
        subcategoria: "Folklore Contemporáneo",
        bio: "Cantautor argentino, exponente del folklore contemporáneo. Combina tradición santiagueña con elementos modernos.",
        badge: "Actual",
        emoji: "🎤",
        logros: ["Premios Gardel", "Internacional"]
    },
    {
        nombre: "Juan Carlos Dávalos",
        categoria: "Literatura",
        subcategoria: "Narrativa/Poesía",
        bio: "Escritor y poeta argentino. Sus obras retratan la vida rural del norte argentino.",
        badge: "Clásico",
        emoji: "📚",
        logros: ["Literatura Nacional", "Regionalista"]
    },
    {
        nombre: "Bernardo Canal Feijóo",
        categoria: "Literatura",
        subcategoria: "Ensayo/Historia",
        bio: "Escritor, ensayista, historiador y pensador argentino. Estudioso profundo de la cultura santiagueña.",
        badge: "Intelectual",
        emoji: "📖",
        logros: ["Doctor Honoris Causa", "Konex"]
    },
    {
        nombre: "Los Manseros Santiagueños",
        categoria: "Música",
        subcategoria: "Folklore/Chacarera",
        bio: "Conjunto folklórico emblemático de Santiago del Estero. Llevan décadas difundiendo la cultura santiagueña.",
        badge: "Legendario",
        emoji: "🎶",
        logros: ["Discos de Oro", "Internacional"]
    },
    {
        nombre: "Horacio Banegas",
        categoria: "Música",
        subcategoria: "Folklore/Chacarera",
        bio: "Músico, compositor y poeta santiagueño. Figura fundamental del folklore argentino.",
        badge: "Poeta",
        emoji: "✍️",
        logros: ["Letras Magistrales", "Premios"]
    },
    {
        nombre: "Alfredo Gogna",
        categoria: "Artes Plásticas",
        subcategoria: "Pintura",
        bio: "Pintor argentino. Sus obras reflejan paisajes y costumbres de Santiago del Estero.",
        badge: "Pintor",
        emoji: "🎨",
        logros: ["Pintura Regional", "Maestro"]
    },
    {
        nombre: "Ricardo y Francisco Sola",
        categoria: "Artes Plásticas",
        subcategoria: "Escultura",
        bio: "Hermanos escultores reconocidos por sus obras monumentales en Santiago del Estero.",
        badge: "Escultores",
        emoji: "🗿",
        logros: ["Monumentos Públicos", "Urbanos"]
    }
];

// Función para obtener color según categoría
function getColorByCategory(categoria) {
    const colors = {
        "Música": "Musica",
        "Literatura": "Literatura",
        "Artes Plásticas": "Artes Plásticas",
        "Danza": "Danza",
        "Teatro": "Teatro"
    };
    return colors[categoria] || "Musica";
}

// Función para crear tarjeta de artista
function createArtistCard(artist) {
    const categoryClass = getColorByCategory(artist.categoria);
    
    return `
        <div class="col-lg-6 col-md-6 famous-artist-item" data-category="${categoryClass}">
            <div class="famous-artist-card">
                <div class="famous-image">
                    <span style="font-size: 5rem; z-index: 10;">${artist.emoji}</span>
                    <div class="famous-overlay">
                        <span class="famous-badge">${artist.badge}</span>
                    </div>
                </div>
                <div class="famous-content">
                    <h4>${artist.nombre}</h4>
                    <p class="famous-category">${artist.categoria} - ${artist.subcategoria}</p>
                    <p class="famous-bio">${artist.bio}</p>
                    <div class="famous-achievements">
                        ${artist.logros.map(logro => `<span class="achievement">🏆 ${logro}</span>`).join('')}
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Cargar artistas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('famous-artists-container');
    
    if (container) {
        // Limpiar contenido anterior
        container.innerHTML = '';
        
        // Agregar todas las tarjetas sin parpadeo
        artistasFamosos.forEach(artist => {
            container.innerHTML += createArtistCard(artist);
        });
    }
});
