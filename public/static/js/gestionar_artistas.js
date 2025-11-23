/**
 * JavaScript para Gestión de Artistas
 * Archivo: /static/js/gestionar_artistas.js
 */

let allArtistas = [];
let filteredArtistas = [];

document.addEventListener('DOMContentLoaded', function() {
    cargarArtistas();
    
    // Event listeners para filtros
    document.getElementById('filter-status').addEventListener('change', aplicarFiltros);
    document.getElementById('filter-search').addEventListener('keyup', aplicarFiltros);
    document.getElementById('filter-disciplina').addEventListener('keyup', aplicarFiltros);
});

async function cargarArtistas() {
    try {
        const response = await fetch(`${BASE_URL}api/artistas.php?action=get`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            allArtistas = data;
            filteredArtistas = [...allArtistas];
            actualizarEstadisticas();
            mostrarArtistas(filteredArtistas);
        } else {
            Swal.fire('Error', 'No se pudieron cargar los artistas', 'error');
        }
    } catch (error) {
        console.error('Error al cargar artistas:', error);
        Swal.fire('Error', 'Error de conexión al cargar los artistas', 'error');
    }
}

function aplicarFiltros() {
    const filterStatus = document.getElementById('filter-status').value.toLowerCase();
    const filterSearch = document.getElementById('filter-search').value.toLowerCase();
    const filterCategoria = document.getElementById('filter-disciplina').value.toLowerCase();
    
    filteredArtistas = allArtistas.filter(artista => {
        // Filtro por estado
        if (filterStatus && artista.status.toLowerCase() !== filterStatus) {
            return false;
        }
        
        // Filtro por búsqueda en nombre
        if (filterSearch) {
            const nombre = (artista.nombre || '').toLowerCase();
            const apellido = (artista.apellido || '').toLowerCase();
            const nombreCompleto = nombre + ' ' + apellido;
            if (!nombreCompleto.includes(filterSearch)) {
                return false;
            }
        }
        
        // Filtro por categoría
        if (filterCategoria && artista.categoria) {
            if (!artista.categoria.toLowerCase().includes(filterCategoria)) {
                return false;
            }
        }
        
        return true;
    });
    
    mostrarArtistas(filteredArtistas);
}

function actualizarEstadisticas() {
    const total = allArtistas.length;
    const validados = allArtistas.filter(a => a.status === 'validado').length;
    const pendientes = allArtistas.filter(a => a.status === 'pendiente').length;
    
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-validados').textContent = validados;
    document.getElementById('stat-pendientes').textContent = pendientes;
}

function mostrarArtistas(artistas) {
    const tbody = document.getElementById('tabla-artistas-body');
    
    if (artistas.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    <i class="bi bi-info-circle"></i> No hay artistas para mostrar
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = artistas.map(artista => {
        const badgeClass = getStatusBadge(artista.status);
        const nombre = (artista.nombre || '') + ' ' + (artista.apellido || '');
        const email = artista.email || 'No disponible';
        const categoria = artista.categoria || 'No especificada';
        const totalObras = artista.total_obras || 0;
        
        return `
            <tr>
                <td>${artista.id}</td>
                <td>
                    <strong>${escapeHtml(nombre.trim())}</strong>
                </td>
                <td>${escapeHtml(email)}</td>
                <td>${escapeHtml(categoria)}</td>
                <td>
                    <span class="badge ${badgeClass}">
                        ${capitalizar(artista.status)}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge bg-info">${totalObras}</span>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-info" onclick="verObras(${artista.id})" title="Ver Obras">
                            <i class="bi bi-image"></i>
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="verPerfil(${artista.id})" title="Ver Perfil">
                            <i class="bi bi-person"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarArtista(${artista.id}, '${escapeHtml(nombre.trim())}')" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusBadge(status) {
    const badges = {
        'pendiente': 'bg-warning',
        'validado': 'bg-success',
        'rechazado': 'bg-danger'
    };
    return badges[status] || 'bg-secondary';
}

function capitalizar(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function verObras(artistaId) {
    window.location.href = `${BASE_URL}src/views/pages/shared/gestion_artistas_obras.php?artista_id=${artistaId}`;
}

function verPerfil(artistaId) {
    window.location.href = `${BASE_URL}perfil_artista.php?id=${artistaId}`;
}

async function eliminarArtista(artistaId, nombreArtista) {
    const result = await Swal.fire({
        title: '¿Eliminar artista?',
        html: `Se eliminará el artista <strong>${nombreArtista}</strong> y <strong>todas sus obras</strong>.<br><br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', artistaId);
            
            const response = await fetch(`${BASE_URL}api/artistas.php`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.status === 'success') {
                Swal.fire('Eliminado', 'El artista y sus obras han sido eliminados', 'success');
                cargarArtistas(); // Recargar la lista
            } else {
                Swal.fire('Error', data.message || 'No se pudo eliminar el artista', 'error');
            }
        } catch (error) {
            console.error('Error al eliminar artista:', error);
            Swal.fire('Error', 'Error de conexión al eliminar el artista', 'error');
        }
    }
}
