/**
 * Wiki Profesional - JavaScript para funcionalidad interactiva
 * Archivo: /public/static/js/wiki.js
 */

const WIKI = {
    BASE_URL: document.querySelector('meta[name="base-url"]')?.content || '/',
    currentTab: 'artistas-validados',
    currentFilters: { categoria: '', filter: 'todos' },
    currentPage: 1,
    itemsPerPage: 9,
    data: {
        artists: [],
        works: [],
        stats: { artists: 0, works: 0, categories: 7 }
    }
};

// Inicialización cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    initWiki();
});

/**
 * Inicializar la Wiki
 */
function initWiki() {
    setupTabNavigation();
    setupSearch();
    setupFilters();
    setupCategoryNavigation();
    
    // Verificar si hay un tab específico en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromURL = urlParams.get('tab');
    if (tabFromURL && document.getElementById(tabFromURL)) {
        WIKI.currentTab = tabFromURL;
        // Activar el botón correspondiente
        const targetBtn = document.querySelector(`[data-tab="${tabFromURL}"]`);
        if (targetBtn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            targetBtn.classList.add('active');
            
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            const targetPane = document.getElementById(tabFromURL);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        }
    }
    
    loadInitialData();
    setupResponsive();
}

/**
 * Configurar navegación por pestañas
 */
function setupTabNavigation() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');
            
            // Actualizar botones
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Actualizar contenido
            tabPanes.forEach(pane => pane.classList.remove('active'));
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
            }

            // Actualizar estado
            WIKI.currentTab = targetTab;
            loadTabContent(targetTab);
        });
    });
}

/**
 * Configurar búsqueda
 */
function setupSearch() {
    const searchForm = document.getElementById('form-busqueda');
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('categoria');

    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            performSearch();
        });
    }

    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (searchInput.value.length > 2 || searchInput.value.length === 0) {
                    performSearch();
                }
            }, 500);
        });
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', performSearch);
    }
}

/**
 * Configurar filtros rápidos
 */
function setupFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');
            
            // Actualizar botones
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Actualizar estado y filtrar
            WIKI.currentFilters.filter = filter;
            applyFilters();
        });
    });
}

/**
 * Configurar navegación por categorías
 */
function setupCategoryNavigation() {
    const categoryItems = document.querySelectorAll('.category-item');
    
    categoryItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const category = item.getAttribute('data-category');
            
            console.log('Categoría seleccionada:', category); // Debug
            
            // Actualizar estado
            WIKI.currentFilters.categoria = category;
            
            // Actualizar select de búsqueda
            const categorySelect = document.getElementById('categoria');
            if (categorySelect) {
                categorySelect.value = category;
            }

            // Resetear página y aplicar filtro
            WIKI.currentPage = 1;
            loadTabContent(WIKI.currentTab);
            
            // Resaltar categoría seleccionada
            categoryItems.forEach(cat => cat.classList.remove('active'));
            item.classList.add('active');
        });
    });
}

/**
 * Cargar datos iniciales
 */
async function loadInitialData() {
    // Mostrar loading
    showLoadingState();
    
    try {
        // Cargar datos en paralelo
        await Promise.all([
            loadStats(),
            loadArtists(),
            loadWorks()
        ]);
        
        // Actualizar contadores y mostrar contenido
        updateCategoryCounts();
        loadTabContent(WIKI.currentTab);
        
        // Refrescar estadísticas con datos locales si es necesario
        if (WIKI.data.stats.artists === 0 && WIKI.data.artists.length > 0) {
            WIKI.data.stats.artists = WIKI.data.artists.length;
        }
        if (WIKI.data.stats.works === 0 && WIKI.data.works.length > 0) {
            WIKI.data.stats.works = WIKI.data.works.length;
        }
        updateStatsDisplay();
        
        console.log('Datos cargados:', {
            artistas: WIKI.data.artists.length,
            obras: WIKI.data.works.length
        });
        
    } catch (error) {
        console.error('Error cargando datos iniciales:', error);
    } finally {
        hideLoadingState();
    }
}

/**
 * Mostrar estado de carga
 */
function showLoadingState() {
    const containers = ['validated-artists', 'validated-works'];
    containers.forEach(containerId => {
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = `
                <div class="loading-placeholder text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Cargando contenido...</p>
                </div>
            `;
        }
    });
}

/**
 * Ocultar estado de carga
 */
function hideLoadingState() {
    // Los contenidos se actualizarán con loadTabContent()
}

/**
 * Cargar estadísticas
 */
async function loadStats() {
    try {
        // Intentar cargar desde la API de estadísticas
        const response = await fetch(WIKI.BASE_URL + 'api/get_estadisticas_inicio.php');
        if (response.ok) {
            const data = await response.json();
            WIKI.data.stats = {
                artists: data.artistas || 0,  // Usar 'artistas' de la API
                works: data.obras || 0,       // Usar 'obras' de la API
                categories: 7
            };
        } else {
            // Si falla, usar los datos cargados
            WIKI.data.stats = {
                artists: WIKI.data.artists.length || 0,
                works: WIKI.data.works.length || 0,
                categories: 7
            };
        }
        updateStatsDisplay();
    } catch (error) {
        console.warn('Error cargando estadísticas:', error);
        // Usar los datos locales como fallback
        WIKI.data.stats = {
            artists: WIKI.data.artists.length || 0,
            works: WIKI.data.works.length || 0,
            categories: 7
        };
        updateStatsDisplay();
    }
}

/**
 * Actualizar visualización de estadísticas
 */
function updateStatsDisplay() {
    const totalArtistas = document.getElementById('total-artistas');
    const totalObras = document.getElementById('total-obras');

    if (totalArtistas) {
        animateNumber(totalArtistas, WIKI.data.stats.artists);
    }
    if (totalObras) {
        animateNumber(totalObras, WIKI.data.stats.works);
    }
}

/**
 * Animar números
 */
function animateNumber(element, target) {
    const currentValue = parseInt(element.textContent) || 0;
    if (currentValue === target) return; // Ya está en el valor correcto
    
    let current = currentValue;
    const difference = target - current;
    const increment = difference / 30; // 30 pasos para la animación
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= target) || (increment < 0 && current <= target)) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 30);
}

/**
 * Cargar artistas
 */
async function loadArtists() {
    try {
        const response = await fetch(WIKI.BASE_URL + 'api/artistas.php?action=get');
        if (response.ok) {
            const data = await response.json();
            // La API devuelve un array directamente, no un objeto con propiedad 'artistas'
            WIKI.data.artists = Array.isArray(data) ? data : [];
        }
    } catch (error) {
        console.warn('Error cargando artistas:', error);
        WIKI.data.artists = [];
    }
}

/**
 * Cargar obras
 */
async function loadWorks() {
    try {
        const response = await fetch(WIKI.BASE_URL + 'api/get_obras_wiki.php');
        if (response.ok) {
            const data = await response.json();
            console.log('Respuesta API obras:', data); // Debug
            WIKI.data.works = data.obras || [];
            console.log('Obras cargadas:', WIKI.data.works.length); // Debug
        } else {
            console.error('Error HTTP al cargar obras:', response.status);
            WIKI.data.works = [];
        }
    } catch (error) {
        console.error('Error cargando obras:', error);
        WIKI.data.works = [];
    }
}

/**
 * Actualizar contadores de categorías
 */
function updateCategoryCounts() {
    const categories = ['musica', 'literatura', 'danza', 'teatro', 'artesania', 'audiovisual', 'escultura'];
    
    categories.forEach(cat => {
        const countElement = document.getElementById(`count-${cat}`);
        if (countElement) {
            // Contar artistas y obras por categoría
            const artistCount = WIKI.data.artists.filter(artist => 
                artist.categoria && artist.categoria.toLowerCase().includes(cat.replace('musica', 'música'))
            ).length;
            
            const workCount = WIKI.data.works.filter(work => 
                work.categoria && work.categoria.toLowerCase().includes(cat.replace('musica', 'música'))
            ).length;
            
            const totalCount = artistCount + workCount;
            countElement.textContent = totalCount;
        }
    });
}

/**
 * Cargar contenido de pestaña
 */
function loadTabContent(tab) {
    switch(tab) {
        case 'artistas-validados':
            renderValidatedArtists();
            break;
        case 'obras-validadas':
            renderValidatedWorks();
            break;
        case 'artistas-famosos':
            // Los artistas famosos ya están en HTML estático
            break;
    }
}

/**
 * Renderizar artistas validados
 */
function renderValidatedArtists() {
    const container = document.getElementById('validated-artists');
    if (!container) return;

    let filteredArtists = WIKI.data.artists.filter(artist => artist.status === 'validado');
    
    // Aplicar filtros
    filteredArtists = applyCurrentFilters(filteredArtists);

    if (filteredArtists.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-info-circle"></i>
                    <p class="mt-3 mb-0">No se encontraron artistas validados con los criterios seleccionados.</p>
                </div>
            </div>
        `;
        return;
    }

    // Paginación
    const startIndex = (WIKI.currentPage - 1) * WIKI.itemsPerPage;
    const endIndex = startIndex + WIKI.itemsPerPage;
    const pageArtists = filteredArtists.slice(startIndex, endIndex);

    let html = '<div class="validated-artists-grid"><div class="row g-4">';
    pageArtists.forEach(artist => {
        html += createArtistCard(artist);
    });
    html += '</div></div>';

    container.innerHTML = html;
    updatePagination(filteredArtists.length);
}

/**
 * Renderizar obras validadas
 */
function renderValidatedWorks() {
    const container = document.getElementById('validated-works');
    if (!container) return;

    console.log('Todas las obras disponibles:', WIKI.data.works.length); // Debug
    
    let filteredWorks = WIKI.data.works.filter(work => {
        // Verificar que la obra esté validada
        return work.estado === 'validado' || work.estado === 'aprobado' || work.estado === 'publicado';
    });
    
    console.log('Obras antes de filtrar:', WIKI.data.works.length); // Debug
    console.log('Obras validadas:', filteredWorks.length); // Debug
    
    // Aplicar filtros adicionales
    filteredWorks = applyCurrentFilters(filteredWorks, 'works');
    
    console.log('Obras después de filtros:', filteredWorks.length); // Debug

    if (filteredWorks.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-info-circle"></i>
                    <p class="mt-3 mb-0">No se encontraron obras validadas con los criterios seleccionados.</p>
                    <small class="text-muted">Total obras en base: ${WIKI.data.works.length}</small>
                </div>
            </div>
        `;
        return;
    }

    // Paginación
    const startIndex = (WIKI.currentPage - 1) * WIKI.itemsPerPage;
    const endIndex = startIndex + WIKI.itemsPerPage;
    const pageWorks = filteredWorks.slice(startIndex, endIndex);

    let html = '<div class="row g-4">';
    pageWorks.forEach(work => {
        html += createWorkCard(work);
    });
    html += '</div>';

    container.innerHTML = html;
    updatePagination(filteredWorks.length);
}

/**
 * Aplicar filtros actuales
 */
function applyCurrentFilters(items, type = 'artists') {
    let filtered = [...items];

    // Filtro de búsqueda por texto
    const searchTerm = document.getElementById('search')?.value?.trim();
    if (searchTerm && searchTerm.length > 0) {
        filtered = filtered.filter(item => {
            const searchText = searchTerm.toLowerCase();
            if (type === 'artists') {
                const nombre = `${item.nombre || ''} ${item.apellido || ''}`.toLowerCase();
                const email = (item.email || '').toLowerCase();
                const categoria = (item.categoria || '').toLowerCase();
                const municipio = (item.municipio || '').toLowerCase();
                const biografia = (item.biografia || '').toLowerCase();
                
                return nombre.includes(searchText) || 
                       email.includes(searchText) || 
                       categoria.includes(searchText) || 
                       municipio.includes(searchText) ||
                       biografia.includes(searchText);
            } else {
                const titulo = (item.titulo || '').toLowerCase();
                const descripcion = (item.descripcion || '').toLowerCase();
                const categoria = (item.categoria || '').toLowerCase();
                const artista = (item.artista_nombre || '').toLowerCase();
                
                return titulo.includes(searchText) || 
                       descripcion.includes(searchText) || 
                       categoria.includes(searchText) ||
                       artista.includes(searchText);
            }
        });
    }

    // Filtro de categoría
    if (WIKI.currentFilters.categoria) {
        filtered = filtered.filter(item => {
            if (!item.categoria) return false;
            
            const itemCategory = item.categoria.toLowerCase();
            const filterCategory = WIKI.currentFilters.categoria.toLowerCase();
            
            // Mapeo de categorías para coincidencias
            const categoryMapping = {
                'musica': ['música', 'musica'],
                'literatura': ['literatura'],
                'danza': ['danza'],
                'teatro': ['teatro'],
                'artesania': ['artesanía', 'artesania'],
                'audiovisual': ['audiovisual'],
                'escultura': ['escultura']
            };
            
            // Verificar coincidencia exacta o por mapeo
            if (itemCategory === filterCategory) {
                return true;
            }
            
            // Verificar mapeo
            for (const [key, values] of Object.entries(categoryMapping)) {
                if (filterCategory === key || values.includes(filterCategory)) {
                    return values.some(v => itemCategory.includes(v));
                }
            }
            
            return itemCategory.includes(filterCategory);
        });
    }

    // Filtro rápido
    switch(WIKI.currentFilters.filter) {
        case 'validados':
            if (type === 'artists') {
                filtered = filtered.filter(item => item.status === 'validado');
            } else {
                filtered = filtered.filter(item => 
                    item.status === 'validado' || 
                    item.estado === 'validado' || 
                    item.estado === 'aprobado' ||
                    item.estado === 'publicado'
                );
            }
            break;
        case 'recientes':
            filtered = filtered.sort((a, b) => {
                const dateA = new Date(a.fecha_registro || a.fecha_creacion || a.created_at || 0);
                const dateB = new Date(b.fecha_registro || b.fecha_creacion || b.created_at || 0);
                return dateB - dateA;
            });
            break;
        case 'famosos':
            if (type === 'artists') {
                // Criterio para artistas famosos (por ejemplo, con más obras)
                filtered = filtered.filter(item => (item.total_obras || 0) > 2);
            }
            break;
    }

    return filtered;
}

/**
 * Crear card de artista
 */
function createArtistCard(artist) {
    const imageSrc = artist.foto_perfil || '/static/img/perfil-del-usuario.png';
    const artistName = [artist.nombre, artist.apellido].filter(Boolean).join(' ');
    const location = [artist.municipio, artist.provincia].filter(Boolean).join(', ');
    const categoria = artist.categoria || artist.especialidades || 'Artista';
    const edad = artist.fecha_nacimiento ? calcularEdad(artist.fecha_nacimiento) : null;
    
    return `
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="artist-card-professional border-0 shadow-sm h-100">
                <div class="artist-image-container">
                    <img src="${escaparHTML(imageSrc)}" class="artist-profile-image" alt="${escaparHTML(artistName)}">
                    <div class="category-overlay">
                        <span class="category-badge">${escaparHTML(categoria)}</span>
                    </div>
                </div>
                <div class="artist-content">
                    <div class="artist-header">
                        <h3 class="artist-name">${escaparHTML(artistName)}</h3>
                    </div>
                    
                    <div class="artist-details">
                        <div class="detail-row">
                            <div class="detail-item">
                                <i class="bi bi-palette"></i>
                                <span class="detail-label">Especialidad:</span>
                                <span class="detail-value">${escaparHTML(categoria)}</span>
                            </div>
                            ${edad ? `
                            <div class="detail-item">
                                <i class="bi bi-calendar-heart"></i>
                                <span class="detail-label">Edad:</span>
                                <span class="detail-value">${edad} años</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="detail-label">Ubicación:</span>
                                <span class="detail-value">${escaparHTML(location || 'Santiago del Estero')}</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-envelope-fill"></i>
                                <span class="detail-label">Contacto:</span>
                                <span class="detail-value">${escaparHTML(artist.email || 'No disponible')}</span>
                            </div>
                        </div>
                        
                        ${artist.biografia ? `
                        <div class="artist-bio">
                            <p><i class="bi bi-quote"></i> ${escaparHTML(artist.biografia)}</p>
                        </div>
                        ` : ''}
                        
                        <div class="artist-social-links">
                            ${artist.instagram ? `<a href="${escaparHTML(artist.instagram)}" target="_blank" class="social-link instagram"><i class="bi bi-instagram"></i></a>` : ''}
                            ${artist.facebook ? `<a href="${escaparHTML(artist.facebook)}" target="_blank" class="social-link facebook"><i class="bi bi-facebook"></i></a>` : ''}
                            ${artist.twitter ? `<a href="${escaparHTML(artist.twitter)}" target="_blank" class="social-link twitter"><i class="bi bi-twitter"></i></a>` : ''}
                            ${artist.sitio_web ? `<a href="${escaparHTML(artist.sitio_web)}" target="_blank" class="social-link website"><i class="bi bi-globe"></i></a>` : ''}
                        </div>
                    </div>
                    
                    <div class="artist-actions">
                        <button class="btn btn-primary btn-view-profile" onclick="goToArtistProfile(${artist.id})">
                            <i class="bi bi-person-circle"></i>
                            Ver Perfil Completo
                        </button>
                        <button class="btn btn-outline-secondary btn-contact" onclick="contactArtist('${escaparHTML(artist.email)}', '${escaparHTML(artistName)}')">
                            <i class="bi bi-chat-dots"></i>
                            Contactar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Crear card de obra
 */
function createWorkCard(work) {
    // Usar el campo imagen_url que viene procesado desde la API
    const imageSrc = work.imagen_url || work.imagen_principal || work.multimedia || '/static/img/placeholder-obra.png';
    
    return `
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm work-card" style="border-radius: 20px; overflow: hidden;">
                <div class="position-relative" style="height: 200px;">
                    <img src="${escaparHTML(imageSrc)}" class="card-img-top" style="height: 100%; object-fit: cover;" alt="${escaparHTML(work.titulo)}">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary">${escaparHTML(work.categoria || 'Obra')}</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h5 class="card-title mb-2">${escaparHTML(work.titulo)}</h5>
                    <p class="card-text text-muted small mb-2">Por: ${escaparHTML(work.artista_nombre || 'Artista Anónimo')}</p>
                    <p class="card-text mb-3" style="font-size: 0.9rem; line-height: 1.4;">
                        ${escaparHTML(work.descripcion ? work.descripcion.substring(0, 100) + '...' : 'Sin descripción disponible')}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> ${formatarFecha(work.fecha_creacion)}
                        </small>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewWorkDetail(${work.id})">
                            Ver Detalle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Actualizar paginación
 */
function updatePagination(totalItems) {
    const paginationContainer = document.getElementById('pagination');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / WIKI.itemsPerPage);
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }

    let html = '';
    
    // Página anterior
    if (WIKI.currentPage > 1) {
        html += `<li><a href="#" onclick="changePage(${WIKI.currentPage - 1})">« Anterior</a></li>`;
    }

    // Páginas numeradas
    for (let i = 1; i <= totalPages; i++) {
        if (i === WIKI.currentPage) {
            html += `<li><span class="active">${i}</span></li>`;
        } else {
            html += `<li><a href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
    }

    // Página siguiente
    if (WIKI.currentPage < totalPages) {
        html += `<li><a href="#" onclick="changePage(${WIKI.currentPage + 1})">Siguiente »</a></li>`;
    }

    paginationContainer.innerHTML = html;
}

/**
 * Cambiar página
 */
function changePage(page) {
    WIKI.currentPage = page;
    loadTabContent(WIKI.currentTab);
}

/**
 * Realizar búsqueda
 */
function performSearch() {
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('categoria');

    const searchTerm = searchInput?.value || '';
    const category = categorySelect?.value || '';

    console.log('Realizando búsqueda:', { searchTerm, category }); // Debug

    // Actualizar filtros
    WIKI.currentFilters.categoria = category;
    
    // Resetear página
    WIKI.currentPage = 1;
    
    // Recargar contenido con filtros aplicados
    loadTabContent(WIKI.currentTab);
}

/**
 * Aplicar filtros
 */
function applyFilters() {
    loadTabContent(WIKI.currentTab);
}

/**
 * Ver detalle de artista
 */
function viewArtistDetail(artistId) {
    const artist = WIKI.data.artists.find(a => a.id == artistId);
    if (!artist) {
        alert('Artista no encontrado');
        return;
    }

    const artistName = [artist.nombre, artist.apellido].filter(Boolean).join(' ');
    const location = [artist.municipio, artist.provincia].filter(Boolean).join(', ');

    Swal.fire({
        title: artistName,
        html: `
            <div class="text-start">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img src="${artist.foto_perfil || '/static/img/perfil-del-usuario.png'}" 
                             class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Email:</strong> ${escaparHTML(artist.email || 'No disponible')}</p>
                        <p><strong>Ubicación:</strong> ${escaparHTML(location || 'No especificada')}</p>
                        <p><strong>Fecha de nacimiento:</strong> ${escaparHTML(artist.fecha_nacimiento || 'No especificada')}</p>
                        <p><strong>Género:</strong> ${escaparHTML(artist.genero || 'No especificado')}</p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">${escaparHTML(artist.status || 'No definido')}</span></p>
                    </div>
                </div>
                ${artist.biografia ? `<hr><h6>Biografía:</h6><p>${escaparHTML(artist.biografia)}</p>` : ''}
            </div>
        `,
        width: '600px',
        confirmButtonText: 'Cerrar'
    });
}

/**
 * Ver detalle de obra
 */
function viewWorkDetail(workId) {
    const work = WIKI.data.works.find(w => w.id == workId);
    if (!work) {
        alert('Obra no encontrada');
        return;
    }

    Swal.fire({
        title: work.titulo,
        html: `
            <div class="text-start">
                ${work.imagen_principal ? `<img src="${work.imagen_principal}" class="img-fluid rounded mb-3" style="max-height: 300px;">` : ''}
                <p><strong>Artista:</strong> ${escaparHTML(work.artista_nombre || 'Desconocido')}</p>
                <p><strong>Categoría:</strong> ${escaparHTML(work.categoria || 'No especificada')}</p>
                <p><strong>Fecha:</strong> ${formatarFecha(work.fecha_creacion)}</p>
                ${work.descripcion ? `<hr><p>${escaparHTML(work.descripcion)}</p>` : ''}
            </div>
        `,
        width: '600px',
        confirmButtonText: 'Cerrar'
    });
}

/**
 * Configurar responsividad
 */
function setupResponsive() {
    window.addEventListener('resize', () => {
        // Ajustes responsivos si es necesario
    });
}

/**
 * Calcular edad a partir de fecha de nacimiento
 */
function calcularEdad(fechaNacimiento) {
    if (!fechaNacimiento) return null;
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const diferenciaMeses = hoy.getMonth() - nacimiento.getMonth();
    
    if (diferenciaMeses < 0 || (diferenciaMeses === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}

/**
 * Ir al perfil público del artista
 */
function goToArtistProfile(artistId) {
    // Redirigir al perfil público del artista
    window.location.href = WIKI.BASE_URL + `perfil_artista.php?id=${artistId}`;
}

/**
 * Contactar artista
 */
function contactArtist(email, artistName) {
    if (!email || email === 'No disponible') {
        Swal.fire({
            title: 'Información de contacto no disponible',
            text: 'Este artista no ha proporcionado información de contacto.',
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    const subject = encodeURIComponent(`Consulta sobre tu perfil en ID-Cultural`);
    const body = encodeURIComponent(`Hola ${artistName},\n\nHe visto tu perfil en ID-Cultural y me gustaría conocer más sobre tu trabajo.\n\nSaludos!`);
    
    window.open(`mailto:${email}?subject=${subject}&body=${body}`, '_blank');
}

/**
 * Formatear fecha
 */
function formatarFecha(fecha) {
    if (!fecha) return 'Sin fecha';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-AR', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

/**
 * Escapar HTML
 */
function escaparHTML(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Limpiar todos los filtros
 */
function clearFilters() {
    // Limpiar campos de búsqueda
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('categoria');
    
    if (searchInput) searchInput.value = '';
    if (categorySelect) categorySelect.value = '';
    
    // Resetear filtros internos
    WIKI.currentFilters.categoria = '';
    WIKI.currentFilters.filter = 'todos';
    WIKI.currentPage = 1;
    
    // Activar botón "Todos" en filtros rápidos
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        if (btn.getAttribute('data-filter') === 'todos') {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    // Limpiar categorías seleccionadas
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => item.classList.remove('active'));
    
    // Recargar contenido
    loadTabContent(WIKI.currentTab);
}
