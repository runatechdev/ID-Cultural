/**
 * Perfil Artista - JavaScript
 * Funcionalidades para la página de perfil del artista
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeProfile();
});

/**
 * Inicializa los elementos del perfil
 */
function initializeProfile() {
    // Inicializar tabs
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(event) {
            //console.log('Tab activado:', event.target.textContent);
            // Aquí puedes cargar contenido dinámico si es necesario
        });
    });

    // Inicializar botones
    const editButton = document.querySelector('.btn-primary.btn-lg');
    if (editButton) {
        editButton.addEventListener('click', handleEditProfile);
    }

    // Inicializar enlaces sociales
    const socialLinks = document.querySelectorAll('.btn-outline-primary.rounded-circle');
    socialLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            //console.log('Enlace social clickeado:', this.title);
        });
    });

    // AOS - Animate On Scroll (si está disponible)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: false,
            mirror: true
        });
    }

    // Agregar animaciones a las imágenes de la galería
    addGalleryAnimations();
}

/**
 * Maneja el evento de editar perfil
 */
function handleEditProfile(e) {
    e.preventDefault();
    //console.log('Editar perfil clickeado');
    
    // Aquí puedes implementar la lógica de edición
    // Por ejemplo, redirigir a una página de edición o abrir un modal
}

/**
 * Agrega animaciones a las imágenes de la galería
 */
function addGalleryAnimations() {
    const galleryImages = document.querySelectorAll('.tab-content img');
    
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', function(e) {
            e.preventDefault();
            openImagePreview(this.src, this.alt);
        });

        img.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
}

/**
 * Abre una vista previa de la imagen (modal)
 */
function openImagePreview(imageSrc, imageAlt) {
    // Crear modal con Bootstrap
    const modalHTML = `
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 d-flex justify-content-between align-items-center p-0">
                        <div></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <img src="${imageSrc}" alt="${imageAlt}" class="img-fluid w-100" style="border-radius: 8px;">
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-center gap-2 p-3">
                        <p class="text-center text-muted mb-0">${imageAlt}</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remover modal anterior si existe
    const oldModal = document.getElementById('imagePreviewModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Insertar nuevo modal
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    modal.show();

    // Limpiar modal al cerrar
    document.getElementById('imagePreviewModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}



/**
 * Función auxiliar para cargar contenido dinámico en los tabs
 */
function loadTabContent(tabName) {
    //console.log('Cargando contenido del tab:', tabName);
    
    // Implementar lógica para cargar contenido dinámico desde API
    // Ejemplo:
    // fetch(`/api/artist-content/${tabName}`)
    //     .then(response => response.json())
    //     .then(data => updateTabContent(data))
    //     .catch(error => console.error('Error:', error));
}

/**
 * Actualiza el contenido de un tab
 */
function updateTabContent(data) {
    //console.log('Actualizando contenido:', data);
    // Implementar lógica para actualizar el contenido del tab
}

/**
 * Utilidad: Formatear números
 */
function formatNumber(num) {
    return new Intl.NumberFormat('es-AR').format(num);
}

/**
 * Utilidad: Mostrar notificación
 */
function showNotification(message, type = 'info') {
    const alertClass = `alert-${type === 'error' ? 'danger' : type}`;
    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `;
    
    // Insertar notificación en la parte superior del contenido principal
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.insertAdjacentHTML('beforeend', alertHTML);
    }
}

// Exportar funciones para uso global
window.perfilArtista = {
    loadTabContent,
    updateTabContent,
    formatNumber,
    showNotification,
    openImagePreview
};
