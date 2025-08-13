document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.getElementById('tabla-solicitudes-body');

    async function cargarSolicitudes() {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Cargando solicitudes...</td></tr>';

        try {
            const response = await fetch(`${BASE_URL}api/get_solicitudes.php`);
            if (!response.ok) throw new Error('Error al obtener los datos.');
            const solicitudes = await response.json();

            tbody.innerHTML = '';
            if (solicitudes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay solicitudes pendientes de validación.</td></tr>';
                return;
            }

            solicitudes.forEach(solicitud => {
                const fila = `
                    <tr id="solicitud-${solicitud.id}">
                        <td class="ps-3"><strong>${solicitud.nombre_artista}</strong></td>
                        <td>${solicitud.titulo}</td>
                        <td>${new Date(solicitud.fecha_envio_validacion).toLocaleDateString()}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success btn-aprobar" data-id="${solicitud.id}">
                                <i class="bi bi-check-circle-fill me-1"></i> Aprobar
                            </button>
                            <button class="btn btn-sm btn-danger btn-rechazar" data-id="${solicitud.id}">
                                <i class="bi bi-x-circle-fill me-1"></i> Rechazar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar las solicitudes.</td></tr>';
        }
    }

    async function actualizarEstado(id, estado) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('estado', estado);

        try {
            const response = await fetch(`${BASE_URL}api/update_solicitud.php`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                Swal.fire('¡Actualizado!', result.message, 'success');
                cargarSolicitudes(); // Recargar la tabla para que desaparezca la solicitud procesada
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    }

    tbody.addEventListener('click', (e) => {
        const aprobarBtn = e.target.closest('.btn-aprobar');
        const rechazarBtn = e.target.closest('.btn-rechazar');

        if (aprobarBtn) {
            const id = aprobarBtn.dataset.id;
            actualizarEstado(id, 'validado');
        }

        if (rechazarBtn) {
            const id = rechazarBtn.dataset.id;
            actualizarEstado(id, 'rechazado');
        }
    });

    // Carga inicial
    cargarSolicitudes();
});
