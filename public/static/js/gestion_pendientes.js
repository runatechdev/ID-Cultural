/**
 * Gestión de Obras Pendientes de Validación
 * Archivo: /public/static/js/gestion_pendientes.js
 */

// Agregar estilos para el hover de imágenes
const style = document.createElement('style');
style.textContent = `
    .hover-zoom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-zoom:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2) !important;
    }
    .img-thumbnail-preview {
        transition: opacity 0.3s ease;
    }
    .img-thumbnail-preview:hover {
        opacity: 0.8;
    }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', () => {
    // Buscar tbody de manera flexible (puede ser tabla-obras-pendientes-body o tabla-artistas-body con clase tabla-obras-body)
    let tbody = document.getElementById('tabla-obras-pendientes-body');
    //console.log('Buscando tbody con ID tabla-obras-pendientes-body:', tbody);
    
    if (!tbody) {
        tbody = document.querySelector('tbody.tabla-obras-body');
        //console.log('Buscando tbody con selector .tabla-obras-body:', tbody);
    }
    
    if (!tbody) {
        console.error('No se encontró el elemento tbody para cargar las obras');
        //console.log('Elementos tbody disponibles:', document.querySelectorAll('tbody'));
        return;
    }
    //console.log('Tbody encontrado:', tbody.id, tbody.className);
    
    let obrasPendientes = [];
    let perfilesPendientes = [];
    let todosPendientes = []; // Combinación de obras y perfiles
    let filtrados = [];

    // Cargar obras y perfiles pendientes al iniciar
    cargarTodosPendientes();

    // Event listeners para filtros
    document.getElementById('filtro-busqueda')?.addEventListener('input', aplicarFiltros);
    document.getElementById('filtro-categoria')?.addEventListener('change', aplicarFiltros);
    document.getElementById('filtro-municipio')?.addEventListener('change', aplicarFiltros);

async function cargarTodosPendientes() {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando obras pendientes...</p></td></tr>';
    
    try {
        // ✅ SOLO cargar obras, NO perfiles
        const responseObras = await fetch(`${BASE_URL}api/get_publicaciones.php?estado=pendiente`);
        
        if (!responseObras.ok) throw new Error('Error al obtener obras.');
        
        obrasPendientes = await responseObras.json();
        
        // Marcar tipo para consistencia
        obrasPendientes.forEach(obra => obra.tipo_pendiente = 'obra');
        
        // SOLO obras, sin perfiles
        todosPendientes = [...obrasPendientes];
        filtrados = [...todosPendientes];
        
        console.log('Obras pendientes cargadas:', obrasPendientes.length);
        
        // Llenar select de municipios
        llenarSelectMunicipios();
        
        // Mostrar items
        mostrarItems(filtrados);

    } catch (error) {
        console.error('Error completo:', error);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Error al cargar obras pendientes.</p></td></tr>';
    }
}

    function mostrarItems(items) {
        //console.log('mostrarItems llamada con:', items.length, 'items');
        
        if (!items || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5"><i class="bi bi-inbox fs-1 text-muted"></i><p class="mt-3 text-muted">No hay elementos pendientes de validación</p></td></tr>';
            return;
        }
        
        tbody.innerHTML = items.map(item => {
            if (item.tipo_pendiente === 'perfil') {
                return renderizarFilaPerfil(item);
            } else {
                return renderizarFilaObra(item);
            }
        }).join('');
        
        // Agregar event listeners después de renderizar
        agregarEventListeners();
    }
    
    function renderizarFilaPerfil(perfil) {
        // Determinar qué imagen mostrar
        const foto_mostrar = perfil.cambios.foto_perfil 
            ? `${BASE_URL}static/img/perfil_pendiente.webp` 
            : (perfil.valores_actuales.foto_perfil || `${BASE_URL}static/img/default-avatar.png`);
        
        return `
            <tr id="perfil-${perfil.id}" class="perfil-row">
                <td class="ps-3">
                    <div class="d-flex align-items-center">
                        <img src="${foto_mostrar}" 
                             alt="Perfil" 
                             class="rounded-circle me-3" 
                             style="width: 50px; height: 50px; object-fit: cover;"
                             onerror="this.src='${BASE_URL}static/img/default-avatar.png'">
                        <div>
                            <strong class="d-block">Cambios de Perfil</strong>
                            <small class="text-muted">
                                <i class="bi bi-person-circle"></i> ${escapeHtml(perfil.nombre_artista)}
                            </small>
                            ${perfil.cambios.foto_perfil ? '<span class="badge bg-warning ms-2">Nueva Foto</span>' : ''}
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-secondary">Perfil</span>
                </td>
                <td>
                    ${perfil.municipio ? `<i class="bi bi-geo-alt"></i> ${escapeHtml(perfil.municipio)}` : '-'}
                </td>
                <td>
                    <small>${formatearFecha(perfil.fecha_solicitud)}</small>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="verDetallesPerfil(${perfil.id})">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </td>
            </tr>
        `;
    }
    
    function renderizarFilaObra(obra) {
        return `
            <tr id="obra-${obra.id}" class="obra-row">
                <td class="ps-3">
                    <div>
                        <strong class="d-block">${escapeHtml(obra.titulo)}</strong>
                        <small class="text-muted">
                            <i class="bi bi-person-circle"></i> ${escapeHtml(obra.artista_nombre)}
                            ${obra.es_artista_validado ? '<span class="badge bg-success ms-1">Artista Validado</span>' : ''}
                        </small>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info">${formatearCategoria(obra.categoria)}</span>
                </td>
                <td>
                    <small>${escapeHtml(obra.municipio || 'No especificado')}</small><br>
                    <small class="text-muted">${escapeHtml(obra.provincia || '')}</small>
                </td>
                <td>
                    <small>${formatearFecha(obra.fecha_envio_validacion)}</small>
                </td>
                <td class="text-center">
                    <div class="btn-group-vertical btn-group-sm" role="group">
                        <button class="btn btn-sm btn-outline-primary btn-ver" 
                                data-id="${obra.id}"
                                title="Ver detalles de la obra">
                            <i class="bi bi-eye"></i> Ver Obra
                        </button>
                        <button class="btn btn-sm btn-success btn-aprobar" 
                                data-id="${obra.id}"
                                title="Aprobar esta obra">
                            <i class="bi bi-check-circle"></i> Aprobar
                        </button>
                        <button class="btn btn-sm btn-danger btn-rechazar" 
                                data-id="${obra.id}"
                                title="Rechazar esta obra">
                            <i class="bi bi-x-circle"></i> Rechazar
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function agregarEventListeners() {
        // Botón Ver
        document.querySelectorAll('.btn-ver').forEach(btn => {
            btn.addEventListener('click', () => verDetalleObra(btn.dataset.id));
        });

        // Botón Aprobar
        document.querySelectorAll('.btn-aprobar').forEach(btn => {
            btn.addEventListener('click', () => aprobarObra(btn.dataset.id));
        });

        // Botón Rechazar
        document.querySelectorAll('.btn-rechazar').forEach(btn => {
            btn.addEventListener('click', () => mostrarModalRechazo(btn.dataset.id));
        });
    }

    async function verDetalleObra(obraId) {
        try {
            const response = await fetch(`${BASE_URL}api/get_publicacion_detalle.php?id=${obraId}`);
            if (!response.ok) throw new Error('Error al obtener detalles');
            
            const obra = await response.json();
            mostrarModalDetalle(obra);

        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo cargar los detalles de la obra', 'error');
        }
    }

    function mostrarModalDetalle(obra) {
        // Construir contenido multimedia
        let multimediaHTML = '';
        if (obra.multimedia && obra.multimedia.length > 0) {
            const multimedia = typeof obra.multimedia === 'string' ? JSON.parse(obra.multimedia) : obra.multimedia;
            multimediaHTML = `
                <div class="mt-3">
                    <h6><i class="bi bi-images"></i> Imágenes:</h6>
                    <div class="row g-2">
                        ${multimedia.map((file, index) => {
                            // Si el multimedia es solo una string (URL directa)
                            const url = typeof file === 'string' ? file : file.url;
                            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(url) || 
                                          (file.type && file.type === 'image');
                            
                            if (isImage) {
                                return `
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card h-100 shadow-sm hover-zoom" style="cursor: pointer;">
                                            <img src="${BASE_URL}${escapeHtml(url)}" 
                                                 class="card-img-top img-thumbnail-preview" 
                                                 alt="Imagen ${index + 1}"
                                                 style="height: 150px; object-fit: cover;"
                                                 onclick="expandirImagen('${BASE_URL}${escapeHtml(url)}', event)"
                                                 onerror="this.src='${BASE_URL}static/img/no-image.png'">
                                        </div>
                                    </div>
                                `;
                            }
                            return '';
                        }).join('')}
                    </div>
                </div>
            `;
        }

        // Construir campos extra
        let camposExtraHTML = '';
        if (obra.campos_extra) {
            const campos = typeof obra.campos_extra === 'string' ? JSON.parse(obra.campos_extra) : obra.campos_extra;
            camposExtraHTML = '<div class="mt-3"><h6><i class="bi bi-info-circle"></i> Información Adicional:</h6><dl class="row">';
            for (const [key, value] of Object.entries(campos)) {
                camposExtraHTML += `
                    <dt class="col-sm-4">${formatearClave(key)}:</dt>
                    <dd class="col-sm-8">${escapeHtml(value)}</dd>
                `;
            }
            camposExtraHTML += '</dl></div>';
        }

        Swal.fire({
            title: escapeHtml(obra.titulo),
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <span class="badge bg-info">${formatearCategoria(obra.categoria)}</span>
                        ${obra.es_artista_validado ? '<span class="badge bg-success">Artista Validado</span>' : '<span class="badge bg-secondary">Usuario</span>'}
                    </div>
                    <p><strong><i class="bi bi-person"></i> Artista:</strong> ${escapeHtml(obra.artista_nombre)}</p>
                    <p><strong><i class="bi bi-geo-alt"></i> Ubicación:</strong> ${escapeHtml(obra.municipio)}, ${escapeHtml(obra.provincia)}</p>
                    <p><strong><i class="bi bi-envelope"></i> Email:</strong> ${escapeHtml(obra.artista_email)}</p>
                    <hr>
                    <h6><i class="bi bi-file-text"></i> Descripción:</h6>
                    <p>${escapeHtml(obra.descripcion)}</p>
                    ${camposExtraHTML}
                    ${multimediaHTML}
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-clock"></i> Enviado el: ${formatearFecha(obra.fecha_envio_validacion)}
                    </p>
                </div>
            `,
            width: '800px',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-check-circle"></i> Aprobar',
            denyButtonText: '<i class="bi bi-x-circle"></i> Rechazar',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                aprobarObra(obra.id);
            } else if (result.isDenied) {
                mostrarModalRechazo(obra.id);
            }
        });
    }

    async function aprobarObra(obraId) {
        const confirmacion = await Swal.fire({
            title: '¿Aprobar esta obra?',
            text: 'La obra será publicada en la Wiki de Artistas',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, aprobar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmacion.isConfirmed) return;

        try {
            const formData = new FormData();
            formData.append('id', obraId);
            formData.append('accion', 'validar');

            const response = await fetch(`${BASE_URL}api/validar_publicacion.php`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                await Swal.fire({
                    title: '¡Aprobada!',
                    text: result.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                cargarTodosPendientes();
            } else {
                Swal.fire('Error', result.message || 'No se pudo aprobar la obra', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al servidor', 'error');
        }
    }

    function mostrarModalRechazo(obraId) {
        Swal.fire({
            title: '<i class="bi bi-exclamation-triangle text-danger"></i> Rechazar Obra',
            html: `
                <p class="text-start">Por favor, indica el motivo del rechazo. Esta información será enviada al artista.</p>
                <textarea id="swal-motivo" class="form-control" rows="4" placeholder="Ejemplo: La obra no cumple con los criterios de autenticidad regional..."></textarea>
            `,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-send"></i> Confirmar Rechazo',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const motivo = document.getElementById('swal-motivo').value.trim();
                if (!motivo) {
                    Swal.showValidationMessage('Debes proporcionar un motivo de rechazo');
                    return false;
                }
                return motivo;
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                await rechazarObra(obraId, result.value);
            }
        });
    }

    async function rechazarObra(obraId, motivo) {
        try {
            const formData = new FormData();
            formData.append('id', obraId);
            formData.append('accion', 'rechazar');
            formData.append('motivo', motivo);

            const response = await fetch(`${BASE_URL}api/validar_publicacion.php`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                await Swal.fire({
                    title: 'Rechazada',
                    text: result.message,
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
                cargarTodosPendientes();
            } else {
                Swal.fire('Error', result.message || 'No se pudo rechazar la obra', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al servidor', 'error');
        }
    }

    // ============================================
    // FUNCIONES PARA PERFILES PENDIENTES
    // ============================================
    
    window.verDetallesPerfil = async function(perfilId) {
        const perfil = perfilesPendientes.find(p => p.id === perfilId);
        if (!perfil) {
            Swal.fire('Error', 'No se encontró el perfil', 'error');
            return;
        }
        
        // Construir HTML de cambios
        let cambiosHTML = '<div class="text-start">';
        
        if (perfil.cambios.foto_perfil) {
            cambiosHTML += `
                <div class="mb-3">
                    <strong>Nueva Foto de Perfil:</strong><br>
                    <img src="${BASE_URL}static/img/perfil_pendiente.webp" class="img-thumbnail mt-2" style="max-width: 200px;">
                    <p class="text-muted small">La imagen se mostrará una vez aprobada</p>
                </div>
            `;
        }
        
        if (perfil.cambios.biografia) {
            cambiosHTML += `
                <div class="mb-3">
                    <strong>Nueva Biografía:</strong><br>
                    <div class="border p-2 mt-2 bg-light">
                        ${escapeHtml(perfil.cambios.biografia)}
                    </div>
                </div>
            `;
        }
        
        if (perfil.cambios.especialidades) {
            cambiosHTML += `
                <div class="mb-3">
                    <strong>Nuevas Especialidades:</strong><br>
                    <div class="border p-2 mt-2 bg-light">
                        ${escapeHtml(perfil.cambios.especialidades)}
                    </div>
                </div>
            `;
        }
        
        // Redes sociales
        const redesSociales = [];
        if (perfil.cambios.instagram) redesSociales.push(`<i class="bi bi-instagram"></i> ${escapeHtml(perfil.cambios.instagram)}`);
        if (perfil.cambios.facebook) redesSociales.push(`<i class="bi bi-facebook"></i> ${escapeHtml(perfil.cambios.facebook)}`);
        if (perfil.cambios.twitter) redesSociales.push(`<i class="bi bi-twitter"></i> ${escapeHtml(perfil.cambios.twitter)}`);
        if (perfil.cambios.sitio_web) redesSociales.push(`<i class="bi bi-globe"></i> ${escapeHtml(perfil.cambios.sitio_web)}`);
        
        if (redesSociales.length > 0) {
            cambiosHTML += `
                <div class="mb-3">
                    <strong>Redes Sociales:</strong><br>
                    <div class="mt-2">
                        ${redesSociales.join('<br>')}
                    </div>
                </div>
            `;
        }
        
        cambiosHTML += '</div>';
        
        // Mostrar modal con opciones
        Swal.fire({
            title: `Cambios de Perfil - ${perfil.nombre_artista}`,
            html: cambiosHTML,
            icon: 'info',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="bi bi-check-circle"></i> Aprobar',
            denyButtonText: '<i class="bi bi-x-circle"></i> Rechazar',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545',
            width: '600px'
        }).then(async (result) => {
            if (result.isConfirmed) {
                await aprobarPerfil(perfil.artista_id);
            } else if (result.isDenied) {
                solicitarMotivoRechazoPerfil(perfil.artista_id);
            }
        });
    }
    
    async function aprobarPerfil(artistaId) {
        try {
            const formData = new FormData();
            formData.append('id', artistaId);
            formData.append('accion', 'validar');

            const response = await fetch(`${BASE_URL}api/validar_perfil.php`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                await Swal.fire({
                    title: 'Aprobado',
                    text: result.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                cargarTodosPendientes();
            } else {
                Swal.fire('Error', result.message || 'No se pudo aprobar el perfil', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al servidor', 'error');
        }
    }
    
    function solicitarMotivoRechazoPerfil(artistaId) {
        Swal.fire({
            title: 'Rechazar Cambios de Perfil',
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Explica por qué se rechazan los cambios...',
            inputAttributes: {
                'aria-label': 'Motivo del rechazo',
                'rows': 4
            },
            showCancelButton: true,
            confirmButtonText: 'Rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes proporcionar un motivo de rechazo';
                }
                return null;
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                await rechazarPerfil(artistaId, result.value);
            }
        });
    }
    
    async function rechazarPerfil(artistaId, motivo) {
        try {
            const formData = new FormData();
            formData.append('id', artistaId);
            formData.append('accion', 'rechazar');
            formData.append('motivo', motivo);

            const response = await fetch(`${BASE_URL}api/validar_perfil.php`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                await Swal.fire({
                    title: 'Rechazado',
                    text: result.message,
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
                cargarTodosPendientes();
            } else {
                Swal.fire('Error', result.message || 'No se pudo rechazar el perfil', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al servidor', 'error');
        }
    }

    function aplicarFiltros() {
        const busqueda = document.getElementById('filtro-busqueda')?.value.toLowerCase() || '';
        const categoria = document.getElementById('filtro-categoria')?.value || '';
        const municipio = document.getElementById('filtro-municipio')?.value || '';

        filtrados = todosPendientes.filter(item => {
            // Filtro de búsqueda
            const textoBusqueda = item.tipo_pendiente === 'perfil'
                ? `${item.nombre_artista}`.toLowerCase()
                : `${item.titulo} ${item.artista_nombre}`.toLowerCase();
            const coincideBusqueda = textoBusqueda.includes(busqueda);
            
            // Filtro de categoría (solo para obras)
            const coincideCategoria = !categoria || item.tipo_pendiente === 'perfil' || item.categoria === categoria;
            
            // Filtro de municipio
            const coincideMunicipio = !municipio || item.municipio === municipio;

            return coincideBusqueda && coincideCategoria && coincideMunicipio;
        });

        mostrarItems(filtrados);
    }

    function llenarSelectMunicipios() {
        const select = document.getElementById('filtro-municipio');
        if (!select) return;

        const municipios = [...new Set(todosPendientes.map(o => o.municipio).filter(Boolean))].sort();
        
        select.innerHTML = '<option value="">Todos los municipios</option>';
        municipios.forEach(mun => {
            const option = document.createElement('option');
            option.value = mun;
            option.textContent = mun;
            select.appendChild(option);
        });
    }

    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================

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

    function formatearFecha(fecha) {
        if (!fecha) return 'No disponible';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-AR', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric'
        });
    }

    function formatearCategoria(categoria) {
        const categorias = {
            'musica': 'Música',
            'artes_visuales': 'Artes Visuales',
            'literatura': 'Literatura',
            'teatro': 'Teatro',
            'danza': 'Danza',
            'fotografia': 'Fotografía',
            'artesania': 'Artesanía',
            'otros': 'Otros'
        };
        return categorias[categoria] || categoria;
    }

    function formatearClave(clave) {
        return clave.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    // ============================================
    // LIGHTBOX PARA IMÁGENES
    // ============================================
    
    window.expandirImagen = function(url, event) {
        // Evitar que se cierre el modal de validación
        event.stopPropagation();
        
        // Crear overlay para lightbox
        const lightbox = document.createElement('div');
        lightbox.id = 'image-lightbox';
        lightbox.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        `;
        
        // Crear contenedor de imagen
        const imgContainer = document.createElement('div');
        imgContainer.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            position: relative;
        `;
        
        // Crear imagen
        const img = document.createElement('img');
        img.src = url;
        img.style.cssText = `
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
        `;
        
        // Crear botón de cerrar
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
        closeBtn.style.cssText = `
            position: absolute;
            top: -40px;
            right: 0;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        `;
        closeBtn.onmouseover = () => {
            closeBtn.style.background = 'rgba(255, 255, 255, 0.9)';
            closeBtn.style.color = 'black';
        };
        closeBtn.onmouseout = () => {
            closeBtn.style.background = 'rgba(255, 255, 255, 0.2)';
            closeBtn.style.color = 'white';
        };
        
        // Función para cerrar lightbox
        const cerrarLightbox = (e) => {
            if (e) e.stopPropagation();
            lightbox.remove();
        };
        
        // Event listeners
        closeBtn.onclick = cerrarLightbox;
        lightbox.onclick = cerrarLightbox;
        img.onclick = (e) => e.stopPropagation(); // Evitar cerrar al hacer click en la imagen
        
        // Cerrar con tecla ESC
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                cerrarLightbox();
                document.removeEventListener('keydown', handleEsc);
            }
        };
        document.addEventListener('keydown', handleEsc);
        
        // Ensamblar elementos
        imgContainer.appendChild(img);
        imgContainer.appendChild(closeBtn);
        lightbox.appendChild(imgContainer);
        document.body.appendChild(lightbox);
        
        // Animación de entrada
        lightbox.style.opacity = '0';
        setTimeout(() => {
            lightbox.style.transition = 'opacity 0.3s';
            lightbox.style.opacity = '1';
        }, 10);
    };
});