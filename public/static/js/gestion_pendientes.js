document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.getElementById('tabla-artistas-pendientes-body');

    async function cargarArtistasPendientes() {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando artistas pendientes...</td></tr>';
        try {
            // ===== CORRECCIÓN CLAVE AQUÍ =====
            // Llamamos a la API que busca ARTISTAS por su estado, no publicaciones.
            const response = await fetch(`${BASE_URL}api/get_artistas.php?status=pendiente`);
            if (!response.ok) throw new Error('Error al obtener los datos.');
            const artistas = await response.json();

            tbody.innerHTML = '';
            if (artistas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">¡Buen trabajo! No hay artistas pendientes de validación.</td></tr>';
                return;
            }

            artistas.forEach(artista => {
                // Adaptamos la fila a la estructura de la tabla en tu PHP (Nombre, Correo, Acciones)
                const fila = `
                    <tr id="artista-${artista.id}">
                        <td class="ps-3"><strong>${artista.nombre} ${artista.apellido}</strong></td>
                        <td>${artista.email}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success btn-aprobar" data-id="${artista.id}">Aprobar</button>
                            <button class="btn btn-sm btn-danger btn-rechazar" data-id="${artista.id}">Rechazar</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar los artistas.</td></tr>';
        }
    }

    async function procesarValidacion(artistaId, estado, motivo) {
        const formData = new FormData();
        formData.append('id', artistaId); // La API actualiza el status del ARTISTA
        formData.append('status', estado);
        formData.append('motivo', motivo);

        try {
            const response = await fetch(`${BASE_URL}api/update_artista_status.php`, { method: 'POST', body: formData });
            const result = await response.json();
            if (response.ok && result.status === 'ok') {
                Swal.fire('¡Actualizado!', result.message, 'success');
                cargarArtistasPendientes();
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
            const artistaId = aprobarBtn.dataset.id;
            Swal.fire({
                title: 'Aprobar Artista',
                input: 'textarea',
                inputLabel: 'Comentario (opcional)',
                inputPlaceholder: 'Añade un comentario para el registro interno...',
                showCancelButton: true,
                confirmButtonText: 'Aprobar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    procesarValidacion(artistaId, 'validado', result.value || '');
                }
            });
        }

        if (rechazarBtn) {
            const artistaId = rechazarBtn.dataset.id;
            Swal.fire({
                title: 'Rechazar Artista',
                input: 'textarea',
                inputLabel: 'Motivo del rechazo',
                inputPlaceholder: 'Explica por qué se rechaza la cuenta...',
                showCancelButton: true,
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return '¡Necesitas escribir un motivo para el rechazo!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    procesarValidacion(artistaId, 'rechazado', result.value);
                }
            });
        }
    });

    cargarArtistasPendientes();
});
