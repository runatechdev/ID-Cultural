/**
 * Wiki Profesional - JavaScript para funcionalidad interactiva
 * Archivo: /public/static/js/wiki.js
 */

const WIKI = {
    BASE_URL: document.querySelector('meta[name="base-url"]')?.content || '/',
    currentTab: 'artistas-validados',
    currentFilters: { categoria: '', filter: 'todos' },
    currentPage: 1,
    itemsPerPage: 12,
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
    
    // Aplicar filtros iniciales si es necesario
    updateTabFilterIndicators();
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
            
            // Actualizar indicadores de filtro en pestañas
            updateTabFilterIndicators();
        });
    });
}

/**
 * Actualizar indicadores de filtro en pestañas
 */
function updateTabFilterIndicators() {
    const hasFilters = WIKI.currentFilters.categoria !== '' || WIKI.currentFilters.filter !== 'todos';
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    tabBtns.forEach(btn => {
        // Remover indicador anterior si existe
        const existingIndicator = btn.querySelector('.filter-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
        
        // Agregar nuevo indicador si hay filtros activos
        if (hasFilters) {
            const indicator = document.createElement('span');
            indicator.className = 'filter-indicator';
            indicator.innerHTML = '<i class="bi bi-funnel-fill"></i>';
            btn.appendChild(indicator);
        }
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
            const isCurrentlyActive = item.classList.contains('active');
            
            //console.log('Categoría clickeada:', category, 'Activa:', isCurrentlyActive); // Debug
            
            // Si ya está activa, deseleccionar
            if (isCurrentlyActive) {
                // Deseleccionar categoría
                WIKI.currentFilters.categoria = '';
                
                // Limpiar select de búsqueda
                const categorySelect = document.getElementById('categoria');
                if (categorySelect) {
                    categorySelect.value = '';
                }
                
                // Remover clase activa
                item.classList.remove('active');
                
                //console.log('Categoría deseleccionada'); // Debug
            } else {
                // Seleccionar nueva categoría
                WIKI.currentFilters.categoria = category;
                
                // Actualizar select de búsqueda
                const categorySelect = document.getElementById('categoria');
                if (categorySelect) {
                    categorySelect.value = category;
                }
                
                // Resaltar categoría seleccionada
                categoryItems.forEach(cat => cat.classList.remove('active'));
                item.classList.add('active');
                
                //console.log('Categoría seleccionada:', category); // Debug
            }

            // Resetear página y aplicar filtro en TODAS las pestañas
            WIKI.currentPage = 1;
            refreshAllTabs();
            
            // Actualizar indicadores de filtro
            updateTabFilterIndicators();
        });
    });
}

/**
 * Refrescar todas las pestañas con los filtros actuales
 */
function refreshAllTabs() {
    // Forzar recarga de contenido para todas las pestañas
    loadTabContent('artistas-validados');
    loadTabContent('obras-validadas');
    filterFamousArtists(); // Filtrar artistas famosos también
    
    // Recargar la pestaña actual
    loadTabContent(WIKI.currentTab);
}

/**
 * Filtrar artistas famosos por categoría
 */
function filterFamousArtists() {
    const famousArtistItems = document.querySelectorAll('.famous-artist-item');
    const currentCategory = WIKI.currentFilters.categoria;
    
    //console.log('Filtrando artistas famosos por categoría:', currentCategory); // Debug
    
    famousArtistItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        
        if (!currentCategory || currentCategory === '') {
            // Mostrar todos si no hay filtro
            item.style.display = 'block';
        } else {
            // Aplicar mismo mapeo que en otros filtros
            const categoryMapping = {
                'Musica': ['música', 'musica'],
                'Literatura': ['literatura'],
                'Danza': ['danza'],
                'Teatro': ['teatro'],
                'Artesania': ['artesanía', 'artesania'],
                'Audiovisual': ['audiovisual'],
                'Escultura': ['escultura'],
                'Otros': ['otros', 'other']
            };
            
            let shouldShow = false;
            
            // Verificar coincidencia exacta
            if (itemCategory === currentCategory) {
                shouldShow = true;
            } else {
                // Verificar mapeo
                for (const [key, values] of Object.entries(categoryMapping)) {
                    if ((currentCategory === key || values.includes(currentCategory.toLowerCase())) &&
                        (itemCategory === key || values.includes(itemCategory.toLowerCase()))) {
                        shouldShow = true;
                        break;
                    }
                }
            }
            
            item.style.display = shouldShow ? 'block' : 'none';
        }
    });
    
    // Mostrar mensaje si no hay artistas famosos visibles
    const visibleArtists = Array.from(famousArtistItems).filter(item => item.style.display !== 'none');
    const container = document.getElementById('famous-artists-container');
    
    if (container) {
        // Remover mensaje anterior si existe
        const existingMessage = container.querySelector('.no-famous-artists-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        if (visibleArtists.length === 0 && currentCategory) {
            const message = document.createElement('div');
            message.className = 'col-12 no-famous-artists-message';
            message.innerHTML = `
                <div class="alert alert-info text-center py-4">
                    <i class="bi bi-info-circle"></i>
                    <p class="mt-2 mb-0">No hay artistas famosos registrados en la categoría "${currentCategory}".</p>
                </div>
            `;
            container.appendChild(message);
        }
    }
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
        
        //console.log('Datos cargados:', {
        //    artistas: WIKI.data.artists.length,
        //    obras: WIKI.data.works.length
        //});
        
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
            //console.log('Respuesta API obras:', data); // Debug
            WIKI.data.works = data.obras || [];
            //console.log('Obras cargadas:', WIKI.data.works.length); // Debug
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
    const categories = {
        'musica': 'Musica',
        'literatura': 'Literatura',
        'danza': 'Danza',
        'teatro': 'Teatro',
        'artesania': 'Artesania',
        'audiovisual': 'Audiovisual',
        'escultura': 'Escultura'
    };
    
    Object.keys(categories).forEach(catKey => {
        const catValue = categories[catKey];
        const countElement = document.getElementById(`count-${catKey}`);
        
        if (countElement) {
            // Contar artistas por categoría (comparación case-insensitive)
            const artistCount = WIKI.data.artists.filter(artist => 
                artist.categoria && artist.categoria.toLowerCase() === catValue.toLowerCase()
            ).length;
            
            // Contar obras por categoría (comparación case-insensitive)
            const workCount = WIKI.data.works.filter(work => 
                work.categoria && work.categoria.toLowerCase() === catValue.toLowerCase()
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

    //console.log('Todas las obras disponibles:', WIKI.data.works.length); // Debug
    
    let filteredWorks = WIKI.data.works.filter(work => {
        // Verificar que la obra esté validada
        return work.estado === 'validado' || work.estado === 'aprobado' || work.estado === 'publicado';
    });
    
    //console.log('Obras antes de filtrar:', WIKI.data.works.length); // Debug
    //console.log('Obras validadas:', filteredWorks.length); // Debug
    
    // Aplicar filtros adicionales
    filteredWorks = applyCurrentFilters(filteredWorks, 'works');
    
    //console.log('Obras después de filtros:', filteredWorks.length); // Debug

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
                'escultura': ['escultura'],
                'otros': ['otros', 'other']
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
    const totalObras = artist.total_obras || 0;
    const whatsapp = artist.whatsapp || '';
    
    return `
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="artist-card-professional border-0 shadow-sm h-100">
                <div class="artist-image-container">
                    <img src="${escaparHTML(imageSrc)}" class="artist-profile-image" alt="${escaparHTML(artistName)}">
                    <div class="category-overlay">
                        <span class="category-badge">${escaparHTML(categoria)}</span>
                    </div>
                    <div class="verified-badge">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
                <div class="artist-content">
                    <div class="artist-header">
                        <h3 class="artist-name">${escaparHTML(artistName)}</h3>
                        <p class="artist-location">
                            <i class="bi bi-geo-alt-fill"></i>
                            ${escaparHTML(location || 'Santiago del Estero')}
                        </p>
                    </div>
                    
                    <div class="artist-stats">
                        ${edad ? `
                        <div class="stat-item">
                            <i class="bi bi-calendar-heart"></i>
                            <span>${edad} años</span>
                        </div>
                        ` : ''}
                        ${totalObras > 0 ? `
                        <div class="stat-item">
                            <i class="bi bi-collection"></i>
                            <span>${totalObras} obra${totalObras !== 1 ? 's' : ''}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="artist-actions">
                        <button class="btn btn-primary btn-view-profile" onclick="goToArtistProfile(${artist.id})">
                            <i class="bi bi-person-circle"></i>
                            Ver Perfil
                        </button>
                        ${whatsapp ? `
                        <button class="btn btn-success btn-contact" onclick="contactArtistWhatsApp('${escaparHTML(whatsapp)}', '${escaparHTML(artistName)}')">
                            <i class="bi bi-whatsapp"></i>
                            Contactar
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Crear card profesional de obra
 */
function createWorkCard(work) {
    // Usar el campo imagen_url que viene procesado desde la API
    const imageSrc = work.imagen_url || work.imagen_principal || work.multimedia || '/static/img/placeholder-obra.png';
    const artistName = work.artista_nombre || 'Artista Anónimo';
    const categoria = work.categoria || 'Obra Cultural';
    const descripcionCorta = work.descripcion ? 
        (work.descripcion.length > 120 ? work.descripcion.substring(0, 120) + '...' : work.descripcion) : 
        'Sin descripción disponible';
    
    // Determinar el estado visual
    const estadoBadge = work.estado === 'validado' ? 
        '<span class="status-badge status-validated"><i class="bi bi-check-circle-fill"></i> Validado</span>' :
        '<span class="status-badge status-pending"><i class="bi bi-clock-fill"></i> En Proceso</span>';
    
    return `
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="work-card-professional border-0 shadow-sm h-100">
                <div class="work-image-container">
                    <img src="${escaparHTML(imageSrc)}" class="work-main-image" alt="${escaparHTML(work.titulo)}">
                    <div class="work-overlay">
                        <div class="work-category-badge">
                            <i class="bi bi-palette-fill"></i>
                            ${escaparHTML(categoria)}
                        </div>
                        ${estadoBadge}
                    </div>
                    <div class="work-hover-actions">
                        <button class="btn btn-light btn-sm work-action-btn" onclick="viewWorkDetail(${work.id})" title="Ver detalles completos">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-light btn-sm work-action-btn" onclick="contactWorkArtist('${escaparHTML(work.artista_email || '')}', '${escaparHTML(artistName)}', '${escaparHTML(work.titulo)}')" title="Contactar artista">
                            <i class="bi bi-chat-dots-fill"></i>
                        </button>
                    </div>
                </div>
                
                <div class="work-content">
                    <div class="work-header">
                        <h3 class="work-title">${escaparHTML(work.titulo)}</h3>
                        <div class="work-artist-info">
                            <i class="bi bi-person-circle"></i>
                            <span class="artist-name">${escaparHTML(artistName)}</span>
                        </div>
                    </div>
                    
                    <div class="work-description">
                        <p>${escaparHTML(descripcionCorta)}</p>
                    </div>
                    
                    <div class="work-metadata">
                        <div class="metadata-row">
                            <div class="metadata-item">
                                <i class="bi bi-calendar-event"></i>
                                <span class="metadata-label">Creada:</span>
                                <span class="metadata-value">${formatarFecha(work.fecha_creacion)}</span>
                            </div>
                            <div class="metadata-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="metadata-label">Origen:</span>
                                <span class="metadata-value">${escaparHTML(work.municipio || 'Santiago del Estero')}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="work-actions">
                        <button class="btn btn-primary btn-view-work" onclick="viewWorkDetail(${work.id})">
                            <i class="bi bi-collection-fill"></i>
                            Ver Obra Completa
                        </button>
                        <div class="work-secondary-actions">
                            <button class="btn btn-outline-secondary btn-sm" onclick="shareWork(${work.id})" title="Compartir obra">
                                <i class="bi bi-share-fill"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="favoriteWork(${work.id})" title="Agregar a favoritos">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
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

    //console.log('Realizando búsqueda:', { searchTerm, category }); // Debug

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
 * Sistema de Galería Integrado en Modal de Obra - VERSIÓN PROFESIONAL FINAL
 */

// Variable global para mantener estado de la galería
let currentGalleryState = {
    images: [],
    currentIndex: 0,
    isGalleryOpen: false,
    container: null
};

/**
 * Ver detalle profesional de obra
 */
function viewWorkDetail(workId) {
    const work = WIKI.data.works.find(w => w.id == workId);
    if (!work) {
        Swal.fire({
            title: 'Error',
            text: 'Obra no encontrada',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    const imageSrc = work.imagen_url || work.imagen_principal || work.multimedia || '/static/img/placeholder-obra.png';
    const artistName = work.artista_nombre || 'Artista Anónimo';
    
    // Procesar multimedia para la galería
    let images = [imageSrc];
    try {
        if (work.multimedia) {
            const multimediaArray = typeof work.multimedia === 'string' ? JSON.parse(work.multimedia) : work.multimedia;
            if (Array.isArray(multimediaArray)) {
                images = multimediaArray.filter(media => 
                    /\.(jpg|jpeg|png|gif|webp)$/i.test(media)
                );
                if (images.length === 0) images = [imageSrc];
            }
        }
    } catch (e) {
        images = [imageSrc];
    }
    
    // Guardar imágenes en estado global
    currentGalleryState.images = images;
    currentGalleryState.currentIndex = 0;
    currentGalleryState.isGalleryOpen = false;

    // Crear HTML de galería mejorada
    let mediaGallery = '';
    if (images.length > 1) {
        mediaGallery = `
            <div class="multimedia-gallery mt-4 mb-3">
                <div class="gallery-info">
                    <span class="gallery-counter"><span id="current-image">1</span> / ${images.length}</span>
                    <small class="text-muted">Haz clic en una imagen para expandirla</small>
                </div>
                <div class="gallery-grid">
                    ${images.map((media, index) => `
                        <div class="gallery-thumb-wrapper" data-index="${index}">
                            <img src="${escaparHTML(media)}" 
                                 class="gallery-thumb" 
                                 onclick="openLightboxGallery(${index}, event)" 
                                 alt="Imagen ${index + 1}"
                                 title="Click para ver en grande">
                            <span class="thumb-number">${index + 1}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    const modalHTML = `
        <div class="work-detail-modal">
            <div class="modal-work-header">
                <div class="work-main-image-container">
                    <img src="${escaparHTML(imageSrc)}" class="work-modal-image" alt="${escaparHTML(work.titulo)}" onclick="openLightboxGallery(0, event)">
                    <div class="image-overlay">
                        <button class="btn btn-light btn-sm expand-image-btn" onclick="openLightboxGallery(0, event)" title="Ver imagen completa">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                    </div>
                    ${images.length > 1 ? `<div class="gallery-navigation"><span class="image-counter">${images.length} imágenes</span></div>` : ''}
                </div>
            </div>
            
            <div class="modal-work-content">
                <div class="work-title-section">
                    <h3 class="modal-work-title">${escaparHTML(work.titulo)}</h3>
                    <div class="work-status-badges">
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Validado</span>
                        <span class="badge bg-primary"><i class="bi bi-palette"></i> ${escaparHTML(work.categoria || 'Obra Cultural')}</span>
                    </div>
                </div>
                
                <div class="artist-info-card">
                    <div class="artist-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="artist-details">
                        <h5 class="artist-name">${escaparHTML(artistName)}</h5>
                        <p class="artist-location">
                            <i class="bi bi-geo-alt"></i> 
                            ${escaparHTML([work.municipio, work.provincia].filter(Boolean).join(', ') || 'Santiago del Estero')}
                        </p>
                        ${work.artista_email ? `
                            <button class="btn btn-outline-primary btn-sm contact-artist-btn" onclick="contactWorkArtist('${escaparHTML(work.artista_email)}', '${escaparHTML(artistName)}', '${escaparHTML(work.titulo)}')">
                                <i class="bi bi-envelope"></i> Contactar Artista
                            </button>
                        ` : ''}
                    </div>
                </div>
                
                ${work.descripcion ? `
                    <div class="work-description-section">
                        <h6><i class="bi bi-journal-text"></i> Sobre esta Obra</h6>
                        <p class="work-description-full">${escaparHTML(work.descripcion)}</p>
                    </div>
                ` : ''}
                
                <div class="work-metadata-section">
                    <div class="metadata-grid">
                        <div class="metadata-card">
                            <i class="bi bi-calendar-event text-primary"></i>
                            <div>
                                <span class="metadata-label">Fecha de Creación</span>
                                <span class="metadata-value">${formatarFecha(work.fecha_creacion)}</span>
                            </div>
                        </div>
                        
                        <div class="metadata-card">
                            <i class="bi bi-eye text-success"></i>
                            <div>
                                <span class="metadata-label">Estado</span>
                                <span class="metadata-value">Obra Validada</span>
                            </div>
                        </div>
                        
                        <div class="metadata-card">
                            <i class="bi bi-hash text-info"></i>
                            <div>
                                <span class="metadata-label">ID de Obra</span>
                                <span class="metadata-value">#${work.id}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${mediaGallery}
                
                <div class="modal-actions">
                    <button class="btn btn-primary" onclick="shareWork(${work.id})">
                        <i class="bi bi-share"></i> Compartir Obra
                    </button>
                    <button class="btn btn-outline-info" onclick="goToArtistProfile(${work.artista_id || 0})">
                        <i class="bi bi-person"></i> Ver Perfil del Artista
                    </button>
                </div>
            </div>

            <div id="gallery-lightbox" class="gallery-lightbox" style="display: none;">
                <div class="gallery-lightbox-content">
                    <button class="gallery-lightbox-close" onclick="closeLightboxGallery()" title="Cerrar galería">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    
                    ${currentGalleryState.images.length > 1 ? `
                        <button class="gallery-lightbox-btn gallery-lightbox-prev" onclick="navigateLightboxGallery(-1)" title="Anterior (←)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    ` : ''}
                    
                    <div class="gallery-lightbox-image-wrapper">
                        <img src="${escaparHTML(images[0])}" class="gallery-lightbox-image" id="gallery-lightbox-image" alt="Imagen">
                    </div>
                    
                    ${currentGalleryState.images.length > 1 ? `
                        <button class="gallery-lightbox-btn gallery-lightbox-next" onclick="navigateLightboxGallery(1)" title="Siguiente (→)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    ` : ''}
                    
                    <div class="gallery-lightbox-counter">
                        <span id="lightbox-counter">1 / ${images.length}</span>
                    </div>
                </div>
                
                ${images.length > 1 ? `
                    <div class="gallery-lightbox-thumbnails">
                        ${images.map((img, idx) => `
                            <img src="${escaparHTML(img)}" 
                                 class="gallery-lightbox-thumb ${idx === 0 ? 'active' : ''}" 
                                 onclick="jumpToLightboxImage(${idx})"
                                 alt="Thumb ${idx + 1}">
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    Swal.fire({
        title: '',
        html: modalHTML,
        width: '900px',
        maxHeight: '90vh',
        padding: '0',
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'work-detail-popup',
            closeButton: 'work-modal-close'
        },
        didOpen: () => {
            document.querySelector('.swal2-popup').classList.add('work-modal');
            currentGalleryState.container = document.getElementById('gallery-lightbox');
            document.addEventListener('keydown', handleLightboxKeyPress);
        },
        willClose: () => {
            document.removeEventListener('keydown', handleLightboxKeyPress);
            currentGalleryState.isGalleryOpen = false;
        }
    });
}

/**
 * Abrir galería lightbox
 */
function openLightboxGallery(index, event) {
    if (event) event.stopPropagation();
    
    if (!currentGalleryState.images || currentGalleryState.images.length === 0) return;
    
    currentGalleryState.currentIndex = index;
    currentGalleryState.isGalleryOpen = true;
    
    if (currentGalleryState.container) {
        currentGalleryState.container.style.display = 'flex';
        
        // Ocultar botón X del modal
        const closeBtn = document.querySelector('.swal2-close');
        if (closeBtn) {
            closeBtn.style.zIndex = '-1';
            closeBtn.style.pointerEvents = 'none';
        }
        
        updateLightboxUI();
    }
}

/**
 * Cerrar galería lightbox
 */
function closeLightboxGallery() {
    if (currentGalleryState.container) {
        currentGalleryState.container.style.display = 'none';
        currentGalleryState.isGalleryOpen = false;
        
        // Restaurar botón X del modal
        const closeBtn = document.querySelector('.swal2-close');
        if (closeBtn) {
            closeBtn.style.zIndex = '1000';
            closeBtn.style.pointerEvents = 'auto';
        }
    }
}

/**
 * Navegar en lightbox
 */
function navigateLightboxGallery(direction) {
    if (!currentGalleryState.images || currentGalleryState.images.length <= 1) return;
    
    currentGalleryState.currentIndex += direction;
    
    if (currentGalleryState.currentIndex < 0) {
        currentGalleryState.currentIndex = currentGalleryState.images.length - 1;
    } else if (currentGalleryState.currentIndex >= currentGalleryState.images.length) {
        currentGalleryState.currentIndex = 0;
    }
    
    updateLightboxUI();
}

/**
 * Saltar a imagen específica
 */
function jumpToLightboxImage(index) {
    currentGalleryState.currentIndex = index;
    updateLightboxUI();
}

/**
 * Actualizar UI del lightbox
 */
function updateLightboxUI() {
    const image = document.getElementById('gallery-lightbox-image');
    const counter = document.getElementById('lightbox-counter');
    
    if (image) {
        image.src = escaparHTML(currentGalleryState.images[currentGalleryState.currentIndex]);
    }
    if (counter) {
        counter.textContent = `${currentGalleryState.currentIndex + 1} / ${currentGalleryState.images.length}`;
    }
    
    // Actualizar miniaturas activas
    const thumbs = document.querySelectorAll('.gallery-lightbox-thumb');
    thumbs.forEach((thumb, idx) => {
        if (idx === currentGalleryState.currentIndex) {
            thumb.classList.add('active');
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
            thumb.classList.remove('active');
        }
    });
}

/**
 * Manejador de teclas
 */
function handleLightboxKeyPress(e) {
    if (!currentGalleryState.isGalleryOpen) return;
    
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        navigateLightboxGallery(1);
    } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        navigateLightboxGallery(-1);
    } else if (e.key === 'Escape') {
        e.preventDefault();
        closeLightboxGallery();
    }
}

/**
 * Estilos CSS para la galería
 */
const galleryCss = `
/* ========== GALERÍA LIGHTBOX INTEGRADA ========== */

.gallery-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 2rem 1rem;
    overflow: hidden;
}

.gallery-lightbox-content {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    width: 100%;
    height: 100%;
    max-height: calc(100vh - 150px);
}

.gallery-lightbox-image-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    width: 100%;
    height: 100%;
    max-height: 100%;
}

.gallery-lightbox-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
    animation: fadeInImage 0.3s ease-in-out;
}

@keyframes fadeInImage {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.gallery-lightbox-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    z-index: 10001;
    padding: 0;
}

.gallery-lightbox-close:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.6);
    transform: scale(1.1);
}

.gallery-lightbox-btn {
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10001;
    padding: 0;
}

.gallery-lightbox-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-50%) scale(1.1);
}

.gallery-lightbox-btn:active {
    transform: translateY(-50%) scale(0.95);
}

.gallery-lightbox-prev {
    left: 1rem;
}

.gallery-lightbox-next {
    right: 1rem;
}

.gallery-lightbox-counter {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    z-index: 10001;
}

.gallery-lightbox-thumbnails {
    position: absolute;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    max-width: 90%;
    padding: 0.75rem;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    z-index: 10001;
}

.gallery-lightbox-thumb {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.gallery-lightbox-thumb:hover {
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
}

.gallery-lightbox-thumb.active {
    border-color: #0d6efd;
    box-shadow: 0 0 15px rgba(13, 110, 253, 0.6);
}

.multimedia-gallery {
    background: rgba(0, 0, 0, 0.03);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid #0d6efd;
}

.gallery-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.gallery-counter {
    font-weight: bold;
    color: #0d6efd;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.75rem;
}

.gallery-thumb-wrapper {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.gallery-thumb-wrapper:hover {
    transform: scale(1.05);
    border: 2px solid #0d6efd;
}

.gallery-thumb {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
}

.thumb-number {
    position: absolute;
    bottom: 4px;
    right: 4px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: bold;
}

@media (max-width: 768px) {
    .gallery-lightbox-btn {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .gallery-lightbox-prev {
        left: 0.5rem;
    }
    
    .gallery-lightbox-next {
        right: 0.5rem;
    }
    
    .gallery-lightbox-thumbnail {
        width: 50px;
        height: 50px;
    }
}
`;

// Inyectar estilos
if (!document.getElementById('gallery-styles')) {
    const styleTag = document.createElement('style');
    styleTag.id = 'gallery-styles';
    styleTag.textContent = galleryCss;
    document.head.appendChild(styleTag);
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
    
    // Mostrar todos los artistas famosos
    filterFamousArtists();
    
    // Recargar contenido
    loadTabContent(WIKI.currentTab);
    
    // Actualizar indicadores de filtro
    updateTabFilterIndicators();
}

/**
 * Contactar artista de una obra específica
 */
function contactWorkArtist(email, artistName, workTitle) {
    if (!email || email === 'No disponible') {
        Swal.fire({
            title: 'Información de contacto no disponible',
            text: 'Este artista no ha proporcionado información de contacto.',
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    const subject = encodeURIComponent(`Consulta sobre "${workTitle}" en ID-Cultural`);
    const body = encodeURIComponent(`Hola ${artistName},\n\nHe visto tu obra "${workTitle}" en ID-Cultural y me gustaría conocer más detalles sobre ella.\n\n¿Podrías contarme más sobre tu proceso creativo?\n\nSaludos!`);
    
    window.open(`mailto:${email}?subject=${subject}&body=${body}`, '_blank');
}

/**
 * Mostrar imagen en tamaño completo
 */
function showFullImage(imageSrc) {
    Swal.fire({
        html: `<img src="${imageSrc}" class="img-fluid" style="max-width: 100%; max-height: 80vh; border-radius: 8px;">`,
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto',
        padding: '1rem',
        background: 'rgba(0,0,0,0.9)',
        customClass: {
            popup: 'image-viewer-popup'
        }
    });
}

/**
 * Compartir obra
 */
function shareWork(workId) {
    const work = WIKI.data.works.find(w => w.id == workId);
    if (!work) return;
    
    const shareUrl = `${window.location.origin}${WIKI.BASE_URL}wiki.php?obra=${workId}`;
    const shareText = `Mira esta increíble obra: "${work.titulo}" por ${work.artista_nombre} en ID-Cultural`;
    
    if (navigator.share) {
        navigator.share({
            title: work.titulo,
            text: shareText,
            url: shareUrl
        });
    } else {
        // Fallback: copiar al portapapeles
        navigator.clipboard.writeText(`${shareText} - ${shareUrl}`).then(() => {
            Swal.fire({
                title: '¡Enlace copiado!',
                text: 'El enlace de la obra ha sido copiado al portapapeles.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
}

/**
 * Agregar a favoritos
 */
function favoriteWork(workId) {
    // Aquí podrías implementar la lógica de favoritos
    // Por ahora, mostrar mensaje
    Swal.fire({
        title: 'Favoritos',
        text: 'Función de favoritos próximamente disponible.',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

/**
 * Contactar artista por WhatsApp
 */
function contactArtistWhatsApp(telefono, artistName) {
    if (!telefono || telefono === 'No disponible' || telefono === '') {
        Swal.fire({
            title: 'WhatsApp no disponible',
            text: 'Este artista no ha proporcionado su número de WhatsApp.',
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    // Limpiar el número de teléfono (quitar espacios, guiones, etc.)
    const numeroLimpio = telefono.replace(/\D/g, '');
    
    // Si no tiene código de país, agregar +54 (Argentina)
    const numeroCompleto = numeroLimpio.startsWith('54') ? numeroLimpio : '54' + numeroLimpio;
    
    const mensaje = encodeURIComponent(`Hola ${artistName}, vi tu perfil en ID-Cultural y me gustaría conocer más sobre tu trabajo.`);
    
    window.open(`https://wa.me/${numeroCompleto}?text=${mensaje}`, '_blank');
}
