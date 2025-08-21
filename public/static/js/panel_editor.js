document.addEventListener('DOMContentLoaded', () => {

    let listaNoticias = []; // Guardamos las noticias para la edición
    const tbody = document.getElementById('tabla-noticias-body');
    const form = document.getElementById('form-add-noticia');

    async function cargarNoticias() {
        // Asegurarse de que el elemento tbody exista antes de continuar
        if (!tbody) return; 

        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando noticias...</td></tr>';
        try {
            const response = await fetch(`${BASE_URL}api/get_noticias.php`);
            listaNoticias = await response.json();

            tbody.innerHTML = '';
            if (listaNoticias.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No hay noticias publicadas.</td></tr>';
                return;
            }

            listaNoticias.forEach(noticia => {
                const fecha = new Date(noticia.fecha_creacion).toLocaleDateString();
                const fila = `
                    <tr id="noticia-${noticia.id}">
                        <td class="ps-3"><strong>${noticia.titulo}</strong></td>
                        <td>${fecha}</td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="${noticia.id}" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${noticia.id}" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar las noticias.</td></tr>';
        }
    }

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('titulo', document.getElementById('titulo').value);
            formData.append('contenido', document.getElementById('contenido').value);
            formData.append('imagen', document.getElementById('imagen').files[0]);

            try {
                const response = await fetch(`${BASE_URL}api/add_noticia.php`, { method: 'POST', body: formData });
                const result = await response.json();

                if (response.ok && result.status === 'ok') {
                    Swal.fire('¡Éxito!', result.message, 'success');
                    form.reset();
                    const accordion = bootstrap.Collapse.getInstance(document.getElementById('collapseAdd'));
                    if (accordion) accordion.hide();
                    cargarNoticias();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
            }
        });
    }

    if (tbody) {
        tbody.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.btn-delete');
            const editBtn = e.target.closest('.btn-edit');

            if (deleteBtn) {
                const noticiaId = deleteBtn.dataset.id;
                Swal.fire({
                    title: '¿Estás seguro?', text: "¡No podrás revertir esta acción!", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, ¡eliminar!', cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('id', noticiaId);
                        const response = await fetch(`${BASE_URL}api/delete_noticia.php`, { method: 'POST', body: formData });
                        const res = await response.json();
                        if (response.ok && res.status === 'ok') {
                            Swal.fire('¡Eliminada!', res.message, 'success');
                            cargarNoticias();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    }
                });
            }

            if (editBtn) {
                const noticiaId = editBtn.dataset.id;
                const noticia = listaNoticias.find(n => n.id == noticiaId);
                if (noticia) {
                    document.getElementById('edit-id').value = noticia.id;
                    document.getElementById('edit-titulo').value = noticia.titulo;
                    document.getElementById('edit-contenido').value = noticia.contenido;
                    
                    const editModal = new bootstrap.Modal(document.getElementById('editNoticiaModal'));
                    editModal.show();
                }
            }
        });
    }
    
    const saveEditButton = document.getElementById('save-edit-button');
    if (saveEditButton) {
        saveEditButton.addEventListener('click', async () => {
            const formData = new FormData();
            formData.append('id', document.getElementById('edit-id').value);
            formData.append('titulo', document.getElementById('edit-titulo').value);
            formData.append('contenido', document.getElementById('edit-contenido').value);
            
            const imagenInput = document.getElementById('edit-imagen');
            if (imagenInput.files.length > 0) {
                formData.append('imagen', imagenInput.files[0]);
            }

            try {
                const response = await fetch(`${BASE_URL}api/update_noticia.php`, { method: 'POST', body: formData });
                const result = await response.json();
                
                if (response.ok && result.status === 'ok') {
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editNoticiaModal'));
                    editModal.hide();
                    Swal.fire('¡Guardado!', result.message, 'success');
                    cargarNoticias();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
            }
        });
    }

    // Carga inicial de datos (solo si estamos en la página de gestión de noticias)
    if (tbody) {
        cargarNoticias();
    }
});
