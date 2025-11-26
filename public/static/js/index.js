/**
 * INDEX.JS - ID CULTURAL
 * JavaScript para la página de inicio
 */

/**
 * Construir URL de imagen segura (HTTPS)
 */
function construirURLImagen(urlOriginal) {
    if (!urlOriginal) return '';
    
    // Si ya es una URL completa HTTPS, devolverla
    if (urlOriginal.startsWith('https://')) {
        return urlOriginal;
    }
    
    // Si es HTTP de localhost, convertir a ruta relativa
    if (urlOriginal.includes('http://localhost')) {
        urlOriginal = urlOriginal.replace(/^.*\/static\//, '/static/');
    }
    
    // Si es una ruta relativa o comienza con /, usarla con BASE_URL
    if (urlOriginal.startsWith('/')) {
        return BASE_URL + urlOriginal.substring(1);
    }
    
    // Si es una ruta relativa, agregarla a BASE_URL
    return BASE_URL + urlOriginal;
}

/**
 * Cargar y mostrar carrusel de obras destacadas
 */
async function cargarCarruselObras() {
    const loading = document.getElementById('carouselLoading');
    const inner = document.getElementById('carouselInner');
    const indicators = document.getElementById('carouselIndicators');

    try {
        const response = await fetch(`${BASE_URL}api/get_obras_wiki.php`);
        const data = await response.json();

        if (data.status === 'success' && data.obras && data.obras.length > 0) {
            // Tomar las últimas 3 obras
            const obrasDestacadas = data.obras.slice(0, 3);

            // Generar indicadores
            indicators.innerHTML = obrasDestacadas.map((obra, index) => `
                <button type="button" 
                        data-bs-target="#heroCarouselObras" 
                        data-bs-slide-to="${index}" 
                        ${index === 0 ? 'class="active" aria-current="true"' : ''}
                        aria-label="Obra ${index + 1}">
                </button>
            `).join('');

            // Generar slides
            inner.innerHTML = obrasDestacadas.map((obra, index) => {
                const imagenUrl = construirURLImagen(obra.imagen_url);
                const descripcionCorta = truncarTexto(obra.descripcion, 120);
                const categoria = formatearCategoria(obra.categoria);

                return `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <div class="carousel-obra-image" 
                             style="background-image: url('${imagenUrl}');"
                             onerror="this.style.backgroundImage='url(${BASE_URL}static/img/placeholder-obra.png)'">
                        </div>
                        <div class="carousel-obra-overlay"></div>
                        <div class="carousel-obra-content">
                            <div class="container">
                                <span class="carousel-obra-badge" data-aos="fade-down">
                                    <i class="bi bi-palette me-2"></i>${escapeHtml(categoria)}
                                </span>
                                <h1 class="carousel-obra-titulo" data-aos="fade-up" data-aos-delay="100">
                                    ${escapeHtml(obra.titulo)}
                                </h1>
                                <p class="carousel-obra-artista" data-aos="fade-up" data-aos-delay="200">
                                    <i class="bi bi-person-circle me-2"></i>
                                    por ${escapeHtml(obra.artista_nombre)}
                                </p>
                                <p class="carousel-obra-descripcion" data-aos="fade-up" data-aos-delay="300">
                                    ${escapeHtml(descripcionCorta)}
                                </p>
                                <a href="${BASE_URL}perfil_artista.php?id=${obra.artista_id}" 
                                   class="btn carousel-obra-btn" 
                                   data-aos="fade-up" 
                                   data-aos-delay="400">
                                    <i class="bi bi-eye me-2"></i>Ver Perfil del Artista
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Ocultar loading
            loading.style.display = 'none';

            // Inicializar carrusel
            const carouselElement = document.getElementById('heroCarouselObras');
            new bootstrap.Carousel(carouselElement, {
                interval: 6000,
                ride: 'carousel',
                pause: 'hover'
            });

            // Re-inicializar AOS para las animaciones
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }

        } else {
            // Mostrar fallback si no hay obras
            mostrarCarruselFallback();
        }

    } catch (error) {
        console.error('Error al cargar carrusel de obras:', error);
        mostrarCarruselFallback();
    }
}

/**
 * Mostrar carrusel fallback si no hay obras disponibles
 */
function mostrarCarruselFallback() {
    const loading = document.getElementById('carouselLoading');
    const inner = document.getElementById('carouselInner');
    const indicators = document.getElementById('carouselIndicators');

    // Datos de fallback
    const slidesFallback = [
        {
            titulo: 'Visibilizar y Preservar',
            descripcion: 'Un espacio para la identidad artística y cultural de Santiago del Estero',
            imagen: 'https://placehold.co/1920x800/367789/FFFFFF?text=Cultura+Santiagueña',
            btn_texto: 'Explorar Wiki',
            btn_link: '/wiki.php?tab=obras-validadas',
            icon: 'bi-compass'
        },
        {
            titulo: 'Nuestros Artistas',
            descripcion: 'Explora la trayectoria de talentos locales, actuales e históricos',
            imagen: 'https://placehold.co/1920x800/C30135/FFFFFF?text=Nuestros+Artistas',
            btn_texto: 'Ver Artistas',
            btn_link: '/wiki.php?tab=artistas-validados',
            icon: 'bi-people'
        },
        {
            titulo: 'Biblioteca Digital',
            descripcion: 'Accede a un archivo único con material exclusivo de nuestra región',
            imagen: 'https://placehold.co/1920x800/efc892/333333?text=Biblioteca+Digital',
            btn_texto: 'Explorar Biblioteca',
            btn_link: '/busqueda.php?categoria=Arte',
            icon: 'bi-book'
        }
    ];

    // Generar indicadores
    indicators.innerHTML = slidesFallback.map((_, index) => `
        <button type="button" 
                data-bs-target="#heroCarouselObras" 
                data-bs-slide-to="${index}" 
                ${index === 0 ? 'class="active" aria-current="true"' : ''}
                aria-label="Slide ${index + 1}">
        </button>
    `).join('');

    // Generar slides
    inner.innerHTML = slidesFallback.map((slide, index) => `
        <div class="carousel-item ${index === 0 ? 'active' : ''}">
            <div class="carousel-obra-image" style="background-image: url('${slide.imagen}');"></div>
            <div class="carousel-obra-overlay"></div>
            <div class="carousel-obra-content">
                <div class="container">
                    <h1 class="carousel-obra-titulo" data-aos="fade-up">
                        ${slide.titulo}
                    </h1>
                    <p class="carousel-obra-descripcion" data-aos="fade-up" data-aos-delay="100">
                        ${slide.descripcion}
                    </p>
                    <a href="${slide.btn_link}" 
                       class="btn carousel-obra-btn" 
                       data-aos="fade-up" 
                       data-aos-delay="200">
                        <i class="${slide.icon} me-2"></i>${slide.btn_texto}
                    </a>
                </div>
            </div>
        </div>
    `).join('');

    loading.style.display = 'none';

    // Re-inicializar AOS
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }
}

/**
 * Formatear categoría
 */
function formatearCategoria(categoria) {
    const categorias = {
        'musica': 'Música',
        'artes_visuales': 'Artes Visuales',
        'literatura': 'Literatura',
        'teatro': 'Teatro',
        'danza': 'Danza',
        'fotografia': 'Fotografía',
        'artesanias': 'Artesanías',
        'escultura': 'Escultura',
        'otros': 'Otros'
    };
    return categorias[categoria] || categoria;
}

/**
 * Cargar estadísticas del sitio
 */
async function cargarEstadisticas() {
    try {
        const response = await fetch(`${BASE_URL}api/get_estadisticas_inicio.php`);
        const data = await response.json();

        if (data.status === 'ok' || data.artistas !== undefined) {
            animarContador('stat-artistas', data.artistas || 0);
            animarContador('stat-obras', data.obras || 0);
            animarContador('stat-noticias', data.noticias || 0);
        }
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
        // Mostrar valores por defecto
        document.getElementById('stat-artistas').textContent = '0';
        document.getElementById('stat-obras').textContent = '0';
        document.getElementById('stat-noticias').textContent = '0';
    }
}

/**
 * Animar contador de números
 */
function animarContador(elementId, valorFinal) {
    const elemento = document.getElementById(elementId);
    if (!elemento) return;

    const duracion = 2000; // 2 segundos
    const pasos = 60;
    const incremento = valorFinal / pasos;
    const intervalo = duracion / pasos;
    
    let valorActual = 0;
    let paso = 0;

    const timer = setInterval(() => {
        paso++;
        valorActual = Math.min(Math.round(incremento * paso), valorFinal);
        elemento.textContent = valorActual.toLocaleString('es-AR');

        if (paso >= pasos) {
            clearInterval(timer);
            elemento.textContent = valorFinal.toLocaleString('es-AR');
        }
    }, intervalo);
}

/**
 * Observar cuándo las estadísticas entran en viewport
 */
function observarEstadisticas() {
    const statsSection = document.querySelector('.stats-section');
    if (!statsSection) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                cargarEstadisticas();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    observer.observe(statsSection);
}

/**
 * Cargar noticias
 */
async function cargarNoticias() {
    const contenedor = document.getElementById('contenedor-noticias');
    
    try {
        const response = await fetch(`${BASE_URL}api/noticias.php?action=get`);
        const data = await response.json();

        // Verificar si hay error en la respuesta
        if (data.error) {
            console.error('Error en API:', data.error);
            contenedor.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                    <p class="mt-3 text-danger">Error al cargar las noticias</p>
                    <small class="text-muted">${data.error}</small>
                </div>
            `;
            return;
        }

        // Si data es un array vacío o no hay noticias
        const noticias = Array.isArray(data) ? data.slice(0, 6) : (data.noticias || []);
        
        if (noticias.length === 0) {
            contenedor.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">No hay noticias disponibles en este momento</p>
                </div>
            `;
            return;
        }

        contenedor.innerHTML = noticias.map((noticia, index) => `
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="${index * 100}">
                <article class="card noticia-card h-100" onclick="window.location.href='${BASE_URL}noticias.php'" style="cursor: pointer;">
                    ${noticia.imagen_url ? `
                        <img src="${construirURLImagen(noticia.imagen_url)}" 
                             class="card-img-top" 
                             alt="${escapeHtml(noticia.titulo)}"
                             onerror="this.src='https://placehold.co/600x400/0d6efd/FFFFFF?text=Sin+Imagen'">
                    ` : `
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" style="height: 250px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                            <i class="bi bi-newspaper text-white" style="font-size: 4rem; opacity: 0.7;"></i>
                        </div>
                    `}
                    <div class="card-body">
                        <h3 class="noticia-title">${escapeHtml(noticia.titulo)}</h3>
                        <p class="noticia-excerpt">${escapeHtml(truncarTexto(noticia.contenido, 150))}</p>
                        <div class="noticia-meta">
                            <span class="noticia-fecha">
                                <i class="bi bi-calendar3"></i>
                                ${formatearFecha(noticia.fecha_creacion)}
                            </span>
                            <span class="noticia-leer-mas">
                                Leer más <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </div>
        `).join('');

        // Re-inicializar AOS para las noticias nuevas
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }

    } catch (error) {
        console.error('Error al cargar noticias:', error);
        contenedor.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                <p class="mt-3 text-danger">Error al cargar las noticias</p>
            </div>
        `;
    }
}

/**
 * Abrir modal con detalles de la noticia
 */
async function abrirModalNoticia(noticiaId) {
    try {
        const response = await fetch(`${BASE_URL}api/noticias.php?action=get&id=${noticiaId}`);
        const noticia = await response.json();

        if (noticia.error) {
            Swal.fire('Error', 'No se pudo cargar la noticia', 'error');
            return;
        }

        // Crear modal con SweetAlert2
        Swal.fire({
            html: `
                <div class="modal-noticia-content text-start">
                    ${noticia.imagen_url ? `
                        <img src="${escapeHtml(noticia.imagen_url)}" 
                             class="modal-noticia-imagen"
                             alt="${escapeHtml(noticia.titulo)}"
                             onerror="this.style.display='none'">
                    ` : ''}
                    <h2 class="mb-3">${escapeHtml(noticia.titulo)}</h2>
                    <div class="mb-3 text-muted">
                        <i class="bi bi-calendar3 me-2"></i>
                        <small>${formatearFechaCompleta(noticia.fecha_creacion)}</small>
                        ${noticia.editor_nombre ? `
                            <span class="ms-3">
                                <i class="bi bi-person me-2"></i>
                                <small>Por ${escapeHtml(noticia.editor_nombre)}</small>
                            </span>
                        ` : ''}
                    </div>
                    <div class="modal-noticia-contenido">
                        ${noticia.contenido}
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '800px',
            customClass: {
                popup: 'modal-noticia',
                htmlContainer: 'p-0'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            }
        });

    } catch (error) {
        console.error('Error al cargar detalle de noticia:', error);
        Swal.fire('Error', 'No se pudo cargar la noticia', 'error');
    }
}

/**
 * Función auxiliar: Truncar texto
 */
function truncarTexto(texto, maxLength) {
    if (!texto) return '';
    // Eliminar HTML tags
    const textoLimpio = texto.replace(/<[^>]*>/g, '');
    if (textoLimpio.length <= maxLength) return textoLimpio;
    return textoLimpio.substring(0, maxLength) + '...';
}

/**
 * Función auxiliar: Formatear fecha
 */
function formatearFecha(fecha) {
    if (!fecha) return '';
    const date = new Date(fecha);
    const opciones = { day: 'numeric', month: 'short', year: 'numeric' };
    return date.toLocaleDateString('es-AR', opciones);
}

/**
 * Función auxiliar: Formatear fecha completa
 */
function formatearFechaCompleta(fecha) {
    if (!fecha) return '';
    const date = new Date(fecha);
    const opciones = { 
        weekday: 'long',
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('es-AR', opciones);
}

/**
 * Función auxiliar: Escapar HTML
 */
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

/**
 * Precargar próxima imagen del carrusel de obras
 */
const preloadNextCarouselImage = () => {
    const activeItem = document.querySelector('#heroCarouselObras .carousel-item.active');
    const nextItem = activeItem?.nextElementSibling || document.querySelector('#heroCarouselObras .carousel-item:first-child');
    
    if (nextItem) {
        const img = nextItem.querySelector('.carousel-obra-image');
        if (img) {
            const bgImage = img.style.backgroundImage;
            const url = bgImage.match(/url\(['"]?(.*?)['"]?\)/)?.[1];
            if (url) {
                const preloader = new Image();
                preloader.src = url;
            }
        }
    }
};

// ===== INICIALIZAR TODO AL CARGAR LA PÁGINA =====
document.addEventListener('DOMContentLoaded', function() {
    // Cargar carrusel de obras
    cargarCarruselObras();

    // Inicializar animaciones AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true,
            offset: 100
        });
    }

    // Cargar estadísticas
    cargarEstadisticas();

    // Cargar noticias
    cargarNoticias();

    // Animar números de estadísticas cuando entren en viewport
    observarEstadisticas();

    // Pausar carrusel cuando el usuario interactúa
    const carousel = document.querySelector('#heroCarouselObras');
    if (carousel) {
        carousel.addEventListener('mouseenter', function() {
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) bsCarousel.pause();
        });

        carousel.addEventListener('mouseleave', function() {
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) bsCarousel.cycle();
        });
    }
});

/**
 * Mejora de accesibilidad: navegación con teclado en cards
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
        const target = e.target.closest('.noticia-card');
        if (target) {
            e.preventDefault();
            target.click();
        }
    }
});

/**
 * Optimización: Lazy loading para imágenes
 */
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

/**
 * Smooth scroll para enlaces internos
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Ejecutar precarga después de que la página cargue
window.addEventListener('load', preloadNextCarouselImage);