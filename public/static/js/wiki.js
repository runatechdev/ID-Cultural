/**
 * Script para cargar obras en la Wiki
 * Archivo: /public/static/js/wiki.js
 */

const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '/';

// Cargar obras al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    cargarObras();
    configurarFiltros();
});

/**
 * Cargar obras desde la API
 */
async function cargarObras(filtros = {}) {
    try {
        // Construir parámetros de búsqueda
        let url = BASE_URL + 'api/get_obras_wiki.php';
        const params = new URLSearchParams();

        if (filtros.categoria && filtros.categoria !== '') {
            params.append('categoria', filtros.categoria);
        }
        if (filtros.municipio && filtros.municipio !== '') {
            params.append('municipio', filtros.municipio);
        }
        if (filtros.busqueda && filtros.busqueda !== '') {
            params.append('busqueda', filtros.busqueda);
        }

        if (params.toString()) {
            url += '?' + params.toString();
        }

        // Mostrar loading
        const contenedor = document.getElementById('contenedor-obras-wiki');
        if (contenedor) {
            contenedor.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando obras...</span></div><p class="mt-3 text-muted">Cargando obras...</p></div>';
        }

        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();

        if (data.status === 'success') {
            mostrarObras(data.obras);
        } else {
            mostrarError('Error al cargar las obras');
        }

    } catch (error) {
        console.error('Error:', error);
        mostrarError('No se pudieron cargar las obras. Intenta nuevamente.');
    }
}

/**
 * Mostrar obras en el contenedor
 */
function mostrarObras(obras) {
    const contenedor = document.getElementById('contenedor-obras-wiki');
    
    if (!contenedor) {
        console.error('Contenedor de obras no encontrado');
        return;
    }

    if (obras.length === 0) {
        contenedor.innerHTML = `
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-search"></i>
                <p class="mt-3 mb-0">No se encontraron obras con los filtros seleccionados.</p>
            </div>
        `;
        return;
    }

    let html = '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';

    obras.forEach(obra => {
        html += crearTarjetaObra(obra);
    });

    html += '</div>';
    contenedor.innerHTML = html;
}

/**
 * Crear tarjeta de obra
 */
function crearTarjetaObra(obra) {
    const imagenUrl = obra.imagen_url || BASE_URL + 'static/img/placeholder-obra.png';
    const artistaUrl = BASE_URL + `api/artistas.php?action=get&id=${obra.artista_id}`;
    
    return `
        <div class="col">
            <div class="card h-100 obra-card shadow-sm border-0 hover-lift">
                <div class="obra-imagen-container position-relative overflow-hidden" style="height: 250px;">
                    <img src="${escaparHTML(imagenUrl)}" 
                         class="card-img-top objeto-cover" 
                         alt="${escaparHTML(obra.titulo)}"
                         loading="lazy">
                    <div class="obra-overlay">
                        <a href="javascript:verDetalleObra(${obra.id})" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i> Ver Detalle
                        </a>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">${escaparHTML(obra.titulo)}</h5>
                    <p class="card-text text-muted small flex-grow-1">${escaparHTML(obra.descripcion.substring(0, 100))}...</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <span class="badge bg-primary">${escaparHTML(obra.categoria)}</span>
                        <small class="text-muted">${formatarFecha(obra.fecha_creacion)}</small>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                                ${escaparHTML(obra.artista_nombre.charAt(0).toUpperCase())}
                            </div>
                            <small>
                                <strong>${escaparHTML(obra.artista_nombre)}</strong><br>
                                <span class="text-muted">${escaparHTML(obra.municipio || 'Santiago del Estero')}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Configurar eventos de filtros
 */
function configurarFiltros() {
    const formBusqueda = document.getElementById('form-busqueda-wiki');
    const inputBusqueda = document.getElementById('busqueda-wiki');
    const selectCategoria = document.getElementById('categoria-wiki');
    const selectMunicipio = document.getElementById('municipio-wiki');

    if (formBusqueda) {
        formBusqueda.addEventListener('submit', (e) => {
            e.preventDefault();
            aplicarFiltros();
        });
    }

    if (selectCategoria) {
        selectCategoria.addEventListener('change', aplicarFiltros);
    }

    if (selectMunicipio) {
        selectMunicipio.addEventListener('change', aplicarFiltros);
    }

    // Buscar mientras escribes (debounce)
    if (inputBusqueda) {
        let debounceTimer;
        inputBusqueda.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (e.target.value.length > 2 || e.target.value.length === 0) {
                    aplicarFiltros();
                }
            }, 500);
        });
    }
}

/**
 * Aplicar filtros
 */
function aplicarFiltros() {
    const busqueda = document.getElementById('busqueda-wiki')?.value || '';
    const categoria = document.getElementById('categoria-wiki')?.value || '';
    const municipio = document.getElementById('municipio-wiki')?.value || '';

    cargarObras({
        busqueda: busqueda,
        categoria: categoria,
        municipio: municipio
    });
}

/**
 * Ver detalle de obra
 */
function verDetalleObra(obraId) {
    fetch(BASE_URL + `api/get_publicacion_detalle.php?id=${obraId}`)
        .then(r => {
            if (!r.ok) throw new Error('No encontrada');
            return r.json();
        })
        .then(data => mostrarModalObra(data))
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cargar la obra');
        });
}

/**
 * Mostrar detalle de obra en modal
 */
function mostrarModalObra(obra) {
    if (!obra.id) {
        alert('Error: Obra inválida');
        return;
    }
    
    // Construir galería de multimedia
    let multimediaHTML = '';
    if (obra.multimedia && obra.multimedia.length > 0) {
        const multimedia = Array.isArray(obra.multimedia) ? obra.multimedia : [obra.multimedia];
        multimediaHTML = '<div class="mt-3"><h6>Galería:</h6>';
        multimedia.forEach(file => {
            if (typeof file === 'string' && /\.(jpg|jpeg|png|gif|webp)$/i.test(file)) {
                multimediaHTML += `<img src="${escaparHTML(file)}" style="max-width: 100%; margin: 5px 0; border-radius: 4px;" alt="Obra">`;
            }
        });
        multimediaHTML += '</div>';
    }
    
    // Construir campos extra
    let camposHTML = '';
    if (obra.campos_extra && Object.keys(obra.campos_extra).length > 0) {
        camposHTML = '<div class="mt-3"><h6>Detalles:</h6><dl class="row">';
        for (const [key, value] of Object.entries(obra.campos_extra)) {
            if (value) {
                const label = key.replace(/_/g, ' ').replace(/^./, c => c.toUpperCase());
                camposHTML += `<dt class="col-sm-4">${escaparHTML(label)}:</dt><dd class="col-sm-8">${escaparHTML(value)}</dd>`;
            }
        }
        camposHTML += '</dl></div>';
    }
    
    Swal.fire({
        title: escaparHTML(obra.titulo),
        html: `
            <div class="text-start">
                <p><strong>Artista:</strong> ${escaparHTML(obra.artista_nombre)}</p>
                <p><strong>Ubicación:</strong> ${escaparHTML(obra.municipio)}, ${escaparHTML(obra.provincia)}</p>
                <p><strong>Categoría:</strong> ${escaparHTML(obra.categoria)}</p>
                <hr>
                <p>${escaparHTML(obra.descripcion)}</p>
                ${camposHTML}
                ${multimediaHTML}
            </div>
        `,
        width: '700px',
        confirmButtonText: 'Cerrar'
    });
}

/**
 * Escapar HTML para prevenir XSS
 */
function escaparHTML(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Formatear fecha
 */
function formatarFecha(fecha) {
    if (!fecha) return '';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-AR', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

/**
 * Mostrar error
 */
function mostrarError(mensaje) {
    const contenedor = document.getElementById('contenedor-obras-wiki');
    if (contenedor) {
        contenedor.innerHTML = `
            <div class="alert alert-danger text-center py-5">
                <i class="bi bi-exclamation-triangle"></i>
                <p class="mt-3 mb-0">${escaparHTML(mensaje)}</p>
            </div>
        `;
    }
}
