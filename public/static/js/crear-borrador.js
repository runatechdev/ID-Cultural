document.addEventListener('DOMContentLoaded', () => {
    const categoriaSelect = document.getElementById('categoria');
    const camposContainer = document.getElementById('campos-condicionales-container');
    const form = document.getElementById('form-borrador');
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('multimedia');
    const previewContainer = document.getElementById('preview-container');
    const imagePreviews = document.getElementById('image-previews');
    const btnSelectImages = document.getElementById('btn-select-images');
    
    console.log('Elementos inicializados:', {
        categoriaSelect,
        camposContainer,
        form,
        dropZone,
        fileInput,
        previewContainer,
        imagePreviews,
        btnSelectImages
    });
    
    let selectedFiles = [];

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

    // Manejo de categorías
    categoriaSelect.addEventListener('change', () => {
        const categoria = categoriaSelect.value;
        camposContainer.innerHTML = camposPorCategoria[categoria] || '';
    });

    // ===== DRAG & DROP FUNCTIONALITY =====
    
    // Prevenir comportamiento por defecto
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone cuando arrastramos archivos
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.background = '#e7f3ff';
    }

    function unhighlight() {
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.background = '#f8f9fa';
    }

    // Manejar drop
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Manejar click en el botón de seleccionar
    btnSelectImages.addEventListener('click', (e) => {
        e.stopPropagation(); // Evitar que se propague al dropZone
        console.log('Botón seleccionar clickeado');
        fileInput.click();
    });

    // Manejar click en la zona de drop (solo fuera del botón)
    dropZone.addEventListener('click', (e) => {
        // Si el click fue en el botón, no hacer nada (el botón ya lo maneja)
        if (e.target.closest('#btn-select-images')) {
            return;
        }
        console.log('DropZone clickeado');
        fileInput.click();
    });

    // Manejar selección de archivos
    fileInput.addEventListener('change', (e) => {
        console.log('File input changed, files:', e.target.files);
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        console.log('handleFiles called with:', files);
        files = [...files];
        console.log('Files array:', files);
        
        // Filtrar solo imágenes y verificar tamaño
        const validFiles = files.filter(file => {
            if (!file.type.startsWith('image/')) {
                Swal.fire('Error', `El archivo ${file.name} no es una imagen válida`, 'error');
                return false;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', `El archivo ${file.name} supera los 5MB`, 'error');
                return false;
            }
            return true;
        });

        // Agregar archivos válidos a la lista
        validFiles.forEach(file => {
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });

        updatePreviews();
    }

    function updatePreviews() {
        if (selectedFiles.length === 0) {
            previewContainer.style.display = 'none';
            imagePreviews.innerHTML = '';
            return;
        }

        previewContainer.style.display = 'block';
        imagePreviews.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted d-block text-truncate">${file.name}</small>
                            <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                            <button type="button" class="btn btn-sm btn-danger w-100 mt-2" onclick="removeImage(${index})">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                `;
                imagePreviews.appendChild(col);
            };
            
            reader.readAsDataURL(file);
        });
    }

    // Función global para eliminar imágenes
    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        updatePreviews();
    };

    // ===== SUBMIT FORM =====
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const estado = e.submitter.id === 'btn-enviar-validacion' ? 'pendiente' : 'borrador';
        
        // Validaciones básicas
        const titulo = document.getElementById('titulo').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const categoria = document.getElementById('categoria').value;
        
        if (!titulo || !descripcion || !categoria) {
            Swal.fire('Error', 'Por favor completa todos los campos obligatorios.', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'save');
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('categoria', categoria);
        formData.append('estado', estado);

        // Agregar archivos multimedia
        if (selectedFiles.length > 0) {
            selectedFiles.forEach((file, index) => {
                formData.append('multimedia[]', file);
            });
        }

        // Recolectar campos extra
        const extraFields = camposContainer.querySelectorAll('input, select, textarea');
        extraFields.forEach(field => {
            if (field.value.trim()) {
                formData.append(field.id, field.value.trim());
            }
        });

        // Mostrar indicador de carga
        Swal.fire({
            title: 'Procesando...',
            text: `Guardando tu ${estado === 'borrador' ? 'borrador' : 'publicación'}...`,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            console.log('Enviando formulario a:', `${BASE_URL}api/borradores.php`);
            
            const response = await fetch(`${BASE_URL}api/borradores.php`, {
                method: 'POST',
                body: formData
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            console.log('Response length:', responseText.length);
            console.log('First 500 chars:', responseText.substring(0, 500));
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Full response text:', responseText);
                throw new Error('La respuesta del servidor no es JSON válido. Ver consola para detalles.');
            }
            
            console.log('Parsed response:', result);

            if (result.status === 'ok') {
                Swal.fire({
                    title: '¡Éxito!',
                    text: result.message,
                    icon: 'success',
                }).then(() => {
                    window.location.href = `${BASE_URL}src/views/pages/artista/dashboard-artista.php`;
                });
            } else {
                Swal.fire('Error', result.message || 'Error desconocido del servidor', 'error');
            }
        } catch (error) {
            console.error('Error details:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor. Error: ' + error.message,
                icon: 'error'
            });
        }
    });
});
