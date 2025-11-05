document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.getElementById('tabla-borradores-body');

    async function cargarBorradores() {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando tus borradores...</td></tr>';
        try {
            const response = await fetch(`${BASE_URL}api/borradores.php?action=get`);
            if (!response.ok) throw new Error('Error al obtener los datos.');
            const borradores = await response.json();

            tbody.innerHTML = '';
            if (borradores.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No tienes borradores guardados. ¡Crea uno nuevo!</td></tr>';
                return;
            }

            borradores.forEach(borrador => {
                const fecha = new Date(borrador.fecha_creacion).toLocaleDateString('es-AR');
                const fila = `
                    <tr id="borrador-${borrador.id}">
                        <td class="ps-3"><strong>${borrador.titulo}</strong></td>
                        <td>${fecha}</td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="${borrador.id}" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-primary btn-enviar" data-id="${borrador.id}" title="Enviar a Validación"><i class="bi bi-send-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${borrador.id}" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar tus borradores.</td></tr>';
        }
    }

    tbody.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('.btn-delete');
        const sendBtn = e.target.closest('.btn-enviar');

        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el borrador permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('action', 'delete');
                    const response = await fetch(`${BASE_URL}api/borradores.php`, { method: 'POST', body: formData });
                    const res = await response.json();
                    if (response.ok && res.status === 'ok') {
                        Swal.fire('¡Eliminado!', res.message, 'success');
                        cargarBorradores();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        }

        if (sendBtn) {
            const id = sendBtn.dataset.id;
            // Aquí iría la lógica para llamar a una API que cambie el estado a 'pendiente_validacion'
            Swal.fire('Enviado', 'Tu borrador ha sido enviado a validación (simulación).', 'success');
        }
    });

    cargarBorradores();
});
