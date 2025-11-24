document.addEventListener('DOMContentLoaded', () => {
    // --- Referencias al DOM ---
    const form = document.getElementById('form-editar-obra');
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('multimedia');
    const btnSelectImages = document.getElementById('btn-select-images');
    
    // Contenedores separados (Clave para que no desaparezcan las viejas)
    const newImagesContainer = document.getElementById('new-images-container');
    const existingImagesContainer = document.getElementById('existing-images-container');
    const previewContainer = document.getElementById('preview-container');
    const inputBorrar = document.getElementById('imagenes_a_borrar');
    
    // Contenedor de campos condicionales
    const categoriaSelect = document.getElementById('categoria');
    const camposContainer = document.getElementById('campos-condicionales-container');

    // Estado local
    let selectedFiles = []; // Solo archivos NUEVOS
    let imagenesBorrar = []; // Rutas de imágenes VIEJAS a borrar

    console.log('DOM Cargado. DropZone:', dropZone);

    // --- 1. Lógica de Categorías y Campos Extra ---
    const camposPorCategoria = {
        musica: `
            <div class="row">
                <div class="col-md-6 mb-3"><label for="plataformas" class="form-label">Plataformas Digitales</label><input type="text" id="plataformas" class="form-control" placeholder="Spotify, YouTube..."></div>
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
                <div class="col-md-4 mb-3"><label for="ano_creacion" class="form-label">Año</label><input type="number" id="ano_creacion" class="form-control"></div>
            </div>`,
        escultura: `
            <div class="row">
                <div class="col-md-4 mb-3"><label for="material" class="form-label">Material</label><input type="text" id="material" class="form-control"></div>
                <div class="col-md-4 mb-3"><label for="dimensiones_esc" class="form-label">Dimensiones</label><input type="text" id="dimensiones_esc" class="form-control"></div>
                <div class="col-md-4 mb-3"><label for="peso" class="form-label">Peso aprox.</label><input type="text" id="peso" class="form-control"></div>
            </div>`,
        // Agrega las demás categorías si faltan
    };

    // Renderizar campos al iniciar
    if (obraData && obraData.categoria) {
        categoriaSelect.value = obraData.categoria;
        renderCamposExtras(obraData.categoria);
    }

    // Evento cambio de categoría
    categoriaSelect.addEventListener('change', () => {
        renderCamposExtras(categoriaSelect.value);
    });

    function renderCamposExtras(cat) {
        camposContainer.innerHTML = camposPorCategoria[cat] || '<div class="text-center text-muted p-3">No hay campos específicos para esta categoría</div>';
        
        // Rellenar valores si existen en obraData
        if (obraData && obraData.campos_extra) {
            let extras = obraData.campos_extra;
            if (typeof extras === 'string') {
                try { extras = JSON.parse(extras); } catch (e) {}
            }
            
            Object.keys(extras).forEach(key => {
                const input = document.getElementById(key);
                if (input) input.value = extras[key];
            });
        }
    }

    // --- 2. Lógica de Imágenes Existentes ---
    // Manejar botones de borrar imágenes ya existentes
    existingImagesContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-existing');
        if (!btn) return;

        const col = btn.closest('.col-md-3');
        const src = col.dataset.src; // Asegúrate de que PHP ponga data-src
        
        // Agregar a la lista de borrado (usando la URL o el identificador que tengas)
        // Nota: Idealmente deberíamos guardar la ruta relativa tal como está en la BD.
        // Aquí asumimos que obraData.multimedia tiene las rutas originales.
        
        // Vamos a intentar sacar la ruta relativa limpia
        // Un truco es usar el índice si fuera necesario, pero por ahora usaremos el src limpio
        // O mejor: obtenemos la ruta original del array de JS si es posible
        
        // Estrategia simple: Agregar al array visualmente y que el backend decida
        // Para ser más precisos, enviaremos el src del tag img, pero el backend filtrará
        
        let pathToDelete = col.querySelector('img').getAttribute('src');
        // Limpiamos la URL base si está presente para enviar solo path relativo si es posible,
        // pero el backend que te pasé ya maneja comparación inteligente.
        
        imagenesBorrar.push(pathToDelete);
        inputBorrar.value = JSON.stringify(imagenesBorrar);
        
        // Eliminar visualmente
        col.remove();
        checkVisibility();
    });


    // --- 3. Drag & Drop para Nuevas Imágenes ---
    
    // Prevenir defaults
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Efectos visuales
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.classList.add('dragover');
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.background = '#e7f3ff';
    }

    function unhighlight() {
        dropZone.classList.remove('dragover');
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.background = '#f8f9fa';
    }

    // Drop
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        handleFiles(dt.files);
    }

    // Click en botón
    btnSelectImages.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.click();
    });
    
    // Click en zona (fuera del botón)
    dropZone.addEventListener('click', (e) => {
        if (!e.target.closest('#btn-select-images')) {
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        files = [...files];
        
        const validFiles = files.filter(file => {
            if (!file.type.startsWith('image/')) {
                Swal.fire('Error', `El archivo ${file.name} no es una imagen`, 'error');
                return false;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', `El archivo ${file.name} pesa más de 5MB`, 'error');
                return false;
            }
            return true;
        });

        // Agregar a nuestro array local
        validFiles.forEach(file => {
            // Evitar duplicados por nombre y tamaño
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });

        updateNewPreviews();
        checkVisibility();
    }

    function updateNewPreviews() {
        newImagesContainer.innerHTML = ''; // SOLO limpiamos el contenedor de nuevas

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6';
                col.innerHTML = `
                    <div class="card h-100 border-0 shadow-sm position-relative">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-footer bg-light small">
                            <span class="text-muted text-truncate d-block">${file.name}</span>
                            <button type="button" class="btn btn-sm btn-danger float-end p-0 mt-1" onclick="removeNewFile(${index})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                newImagesContainer.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    // Función global para borrar nuevas (necesaria para el onclick inline)
    window.removeNewFile = function(index) {
        selectedFiles.splice(index, 1);
        updateNewPreviews();
        checkVisibility();
    };

    function checkVisibility() {
        const totalImages = existingImagesContainer.children.length + selectedFiles.length;
        if (totalImages > 0) {
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    }

    // --- 4. Enviar Formulario ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validación básica
        if (!document.getElementById('titulo').value || !categoriaSelect.value) {
            Swal.fire('Atención', 'Título y Categoría son obligatorios', 'warning');
            return;
        }

        const confirmacion = await Swal.fire({
            title: '¿Guardar cambios?',
            text: "La obra pasará a estado 'Pendiente' y será revisada nuevamente.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmacion.isConfirmed) return;

        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('id', document.getElementById('obra-id').value);
        formData.append('titulo', document.getElementById('titulo').value);
        formData.append('descripcion', document.getElementById('descripcion').value);
        formData.append('categoria', categoriaSelect.value);
        
        // Array de imagenes a borrar
        formData.append('imagenes_a_borrar', JSON.stringify(imagenesBorrar));

        // Imágenes nuevas
        selectedFiles.forEach(file => {
            formData.append('multimedia[]', file);
        });

        // Campos extra dinámicos
        const extraFields = camposContainer.querySelectorAll('input, select, textarea');
        extraFields.forEach(field => {
            if (field.value.trim()) {
                formData.append(field.id, field.value.trim());
            }
        });

        // Loading
        Swal.fire({
            title: 'Guardando...',
            text: 'Subiendo imágenes y procesando datos',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`${BASE_URL}api/borradores.php`, {
                method: 'POST',
                body: formData
            });

            // Leer respuesta como texto primero para debug si falla JSON
            const text = await response.text();
            
            let result;
            try {
                result = JSON.parse(text);
            } catch (err) {
                console.error('Error parseando JSON:', text);
                throw new Error('Respuesta inválida del servidor: ' + text.substring(0, 50));
            }

            if (result.status === 'ok') {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: result.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = `${BASE_URL}src/views/pages/artista/mis-obras-validadas.php`;
                });
            } else {
                throw new Error(result.message || 'Error desconocido');
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', error.message, 'error');
        }
    });
});