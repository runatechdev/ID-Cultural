document.addEventListener('DOMContentLoaded', () => {
    const categoriaSelect = document.getElementById('categoria');
    const camposContainer = document.getElementById('campos-condicionales-container');
    const form = document.getElementById('form-borrador');

    const camposPorCategoria = {
        musica: `
            <div class="row">
                <div class="col-md-6 mb-3"><label for="plataformas" class="form-label">Plataformas Digitales</label><input type="text" id="plataformas" class="form-control"></div>
                <div class="col-md-6 mb-3"><label for="sello" class="form-label">Sello Discográfico</label><input type="text" id="sello" class="form-control"></div>
            </div>`,
        literatura: `
            <div class="row">
                <div class="col-md-6 mb-3"><label for="genero-lit" class="form-label">Género Literario</label><input type="text" id="genero-lit" class="form-control"></div>
                <div class="col-md-6 mb-3"><label for="editorial" class="form-label">Editorial</label><input type="text" id="editorial" class="form-control"></div>
            </div>`,
        artes_visuales: `
            <div class="row">
                <div class="col-md-4 mb-3"><label for="tecnica" class="form-label">Técnica/Soporte</label><input type="text" id="tecnica" class="form-control"></div>
                <div class="col-md-4 mb-3"><label for="dimensiones_av" class="form-label">Dimensiones</label><input type="text" id="dimensiones_av" class="form-control"></div>
                <div class="col-md-4 mb-3"><label for="ano_creacion" class="form-label">Año de Creación</label><input type="number" id="ano_creacion" class="form-control"></div>
            </div>`,
        // Añade aquí las plantillas para las otras categorías (escultura, danza, etc.)
    };

    categoriaSelect.addEventListener('change', () => {
        const categoria = categoriaSelect.value;
        camposContainer.innerHTML = camposPorCategoria[categoria] || '';
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const estado = e.submitter.id === 'btn-enviar-validacion' ? 'pendiente' : 'borrador';
        
        const formData = new FormData();
        formData.append('action', 'save');
        formData.append('titulo', document.getElementById('titulo').value);
        formData.append('descripcion', document.getElementById('descripcion').value);
        formData.append('categoria', document.getElementById('categoria').value);
        formData.append('estado', estado);

        // Agregar archivos multimedia
        const multimediaFiles = document.getElementById('multimedia').files;
        for (let i = 0; i < multimediaFiles.length; i++) {
            formData.append('multimedia[]', multimediaFiles[i]);
        }

        // Recolectar campos extra
        const extraFields = camposContainer.querySelectorAll('input, select, textarea');
        extraFields.forEach(field => {
            formData.append(field.id, field.value);
        });

        try {
            const response = await fetch(`${BASE_URL}api/borradores.php`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (response.ok && result.status === 'ok') {
                Swal.fire({
                    title: '¡Éxito!',
                    text: result.message,
                    icon: 'success',
                }).then(() => {
                    window.location.href = `${BASE_URL}src/views/pages/artista/dashboard-artista.php`;
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    });
});
