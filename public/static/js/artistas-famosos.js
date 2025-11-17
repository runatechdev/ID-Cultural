/**
 * Script para cargar y mostrar artistas famosos en la Wiki
 * Archivo: /public/static/js/artistas-famosos.js
 */

document.addEventListener('DOMContentLoaded', function() {
    cargarArtistasFamosos();
});

/**
 * Cargar artistas famosos de la API
 */
function cargarArtistasFamosos() {
    const container = document.getElementById('famous-artists-container');
    if (!container) return;

    // Mostrar loading
    container.innerHTML = `
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando artistas famosos...</p>
        </div>
    `;

    // Obtener filtro de categoría si existe
    const categoriaFilter = document.getElementById('categoria-filter')?.value || null;

    // Construir URL
    let url = `${BASE_URL}api/artistas_famosos.php?action=get`;
    if (categoriaFilter && categoriaFilter !== 'todos') {
        url += `&categoria=${encodeURIComponent(categoriaFilter)}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.artistas.length > 0) {
                renderArtistasFamosos(data.artistas);
            } else {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-3 text-muted">No hay artistas famosos para mostrar</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error cargando artistas:', error);
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Error al cargar los artistas famosos
                    </div>
                </div>
            `;
        });
}

/**
 * Renderizar tarjetas de artistas famosos
 */
function renderArtistasFamosos(artistas) {
    const container = document.getElementById('famous-artists-container');
    
    if (!artistas || artistas.length === 0) {
        container.innerHTML = '<p class="text-muted">No hay artistas para mostrar</p>';
        return;
    }

    container.innerHTML = artistas.map(artista => {
        const foto = artista.foto_perfil || '/static/img/placeholder-artista.png';
        const estado = artista.esta_vivo ? 'Vivo' : 'Fallecido';
        const estadoClass = artista.esta_vivo ? 'badge-success' : 'badge-secondary';
        const year = artista.fecha_nacimiento ? new Date(artista.fecha_nacimiento).getFullYear() : '';
        const yearFin = artista.fecha_fallecimiento ? new Date(artista.fecha_fallecimiento).getFullYear() : '';
        const periodo = artista.fecha_fallecimiento ? `${year} - ${yearFin}` : `Nac. ${year}`;
        
        return `
            <div class="col-lg-6 col-md-6 famous-artist-item">
                <div class="famous-artist-card h-100">
                    <div class="famous-image">
                        <img src="${foto}" alt="${artista.nombre_completo}" 
                             onerror="this.src='/static/img/placeholder-artista.png'">
                        <div class="famous-overlay">
                            <span class="famous-badge badge ${estadoClass}">${estado}</span>
                            <span class="categoria-badge badge bg-primary">${artista.categoria}</span>
                        </div>
                    </div>
                    <div class="famous-content">
                        <h5 class="famous-title">${artista.nombre_completo}</h5>
                        ${artista.nombre_artistico ? `<p class="famous-subtitle">"${artista.nombre_artistico}"</p>` : ''}
                        <p class="famous-dates">
                            <i class="bi bi-calendar"></i> ${periodo}
                        </p>
                        ${artista.lugar_nacimiento ? `
                        <p class="famous-location">
                            <i class="bi bi-geo-alt"></i> ${artista.lugar_nacimiento}
                        </p>
                        ` : ''}
                        <p class="famous-bio text-muted small">${truncarTexto(artista.biografia, 150)}</p>
                        
                        <div class="famous-actions">
                            <button class="btn btn-sm btn-primary" 
                                    onclick="mostrarDetalleArtista(${artista.id})">
                                <i class="bi bi-eye"></i> Ver Perfil
                            </button>
                            ${artista.wikipedia_url ? `
                            <a href="${artista.wikipedia_url}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-wikipedia"></i> Wikipedia
                            </a>
                            ` : ''}
                        </div>
                        
                        <div class="famous-social mt-3">
                            ${artista.instagram ? `<a href="https://instagram.com/${artista.instagram.replace('@', '')}" target="_blank" class="social-link"><i class="bi bi-instagram"></i></a>` : ''}
                            ${artista.facebook ? `<a href="${artista.facebook}" target="_blank" class="social-link"><i class="bi bi-facebook"></i></a>` : ''}
                            ${artista.twitter ? `<a href="https://twitter.com/${artista.twitter.replace('@', '')}" target="_blank" class="social-link"><i class="bi bi-twitter"></i></a>` : ''}
                            ${artista.sitio_web ? `<a href="${artista.sitio_web}" target="_blank" class="social-link"><i class="bi bi-globe"></i></a>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

/**
 * Mostrar modal con detalles del artista
 */
function mostrarDetalleArtista(id) {
    const url = `${BASE_URL}api/artistas_famosos.php?action=get_id&id=${id}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const artista = data.artista;
                const html = generarDetalleArtista(artista);
                
                // Crear modal dinámicamente
                let modal = document.getElementById('artist-detail-modal');
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'artist-detail-modal';
                    modal.className = 'modal fade';
                    modal.tabIndex = -1;
                    document.body.appendChild(modal);
                }
                
                modal.innerHTML = html;
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        })
        .catch(error => console.error('Error:', error));
}

/**
 * Generar HTML del detalle del artista
 */
function generarDetalleArtista(artista) {
    const estado = artista.esta_vivo ? 'Vivo' : 'Fallecido';
    const year = artista.fecha_nacimiento ? new Date(artista.fecha_nacimiento).getFullYear() : '';
    const yearFin = artista.fecha_fallecimiento ? new Date(artista.fecha_fallecimiento).getFullYear() : '';
    const periodo = artista.fecha_fallecimiento ? `${year} - ${yearFin}` : `Nacido en ${year}`;
    
    return `
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">${artista.nombre_completo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${artista.foto_perfil || '/static/img/placeholder-artista.png'}" 
                                 alt="${artista.nombre_completo}" 
                                 class="img-fluid rounded-3 mb-3"
                                 style="max-height: 400px; object-fit: cover;">
                            <div class="mb-3">
                                <span class="badge bg-primary me-2">${artista.categoria}</span>
                                ${artista.subcategoria ? `<span class="badge bg-secondary">${artista.subcategoria}</span>` : ''}
                            </div>
                        </div>
                        <div class="col-md-8">
                            ${artista.nombre_artistico ? `<p class="text-muted fst-italic">"${artista.nombre_artistico}"</p>` : ''}
                            
                            <p class="mb-3">
                                <strong>${periodo}</strong><br>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> ${artista.lugar_nacimiento || 'Santiago del Estero'}, 
                                    ${artista.provincia}
                                </small>
                            </p>
                            
                            <h6>Biografía</h6>
                            <p>${artista.biografia}</p>
                            
                            ${artista.logros_premios ? `
                            <h6 class="mt-4">Premios y Reconocimientos</h6>
                            <p>${artista.logros_premios}</p>
                            ` : ''}
                            
                            ${artista.obras_destacadas ? `
                            <h6 class="mt-4">Obras Destacadas</h6>
                            <p>${artista.obras_destacadas}</p>
                            ` : ''}
                            
                            <div class="mt-4">
                                ${artista.instagram ? `<a href="https://instagram.com/${artista.instagram.replace('@', '')}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-instagram"></i></a>` : ''}
                                ${artista.facebook ? `<a href="${artista.facebook}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-facebook"></i></a>` : ''}
                                ${artista.twitter ? `<a href="https://twitter.com/${artista.twitter.replace('@', '')}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-twitter"></i></a>` : ''}
                                ${artista.sitio_web ? `<a href="${artista.sitio_web}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-globe"></i></a>` : ''}
                                ${artista.wikipedia_url ? `<a href="${artista.wikipedia_url}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-wikipedia"></i></a>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Truncar texto a cierta longitud
 */
function truncarTexto(texto, longitud = 150) {
    if (!texto) return '';
    if (texto.length <= longitud) return texto;
    return texto.substring(0, longitud) + '...';
}
