document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.getElementById('tabla-noticias-body');
    const form = document.getElementById('form-add-noticia');

    async function cargarNoticias() {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando noticias...</td></tr>';
        try {
            const response = await fetch(`${BASE_URL}api/get_noticias.php`);
            const noticias = await response.json();

            tbody.innerHTML = '';
            if (noticias.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No hay noticias publicadas.</td></tr>';
                return;
            }

            noticias.forEach(noticia => {
                const fecha = new Date(noticia.fecha_creacion).toLocaleDateString();
                const fila = `
                    <tr>
                        <td class="ps-3"><strong>${noticia.titulo}</strong></td>
                        <td>${fecha}</td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar las noticias.</td></tr>';
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('titulo', document.getElementById('titulo').value);
        formData.append('contenido', document.getElementById('contenido').value);
        formData.append('imagen', document.getElementById('imagen').files[0]);

        try {
            const response = await fetch(`${BASE_URL}api/add_noticia.php`, {
                method: 'POST',
                body: formData
            });
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

    // Carga inicial
    cargarNoticias();
});
