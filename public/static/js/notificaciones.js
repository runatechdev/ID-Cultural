/**
 * Sistema de Notificaciones - ID Cultural
 * Manejo de notificaciones en tiempo real para artistas
 */

class NotificacionesManager {
    constructor() {
        this.baseUrl = window.BASE_URL || '/';
        this.noLeidasCount = 0;
        this.notificaciones = [];
        this.intervalId = null;
        
        this.init();
    }
    
    init() {
        // Cargar notificaciones iniciales
        this.cargarNotificaciones();
        
        // Actualizar cada 30 segundos
        this.intervalId = setInterval(() => {
            this.actualizarContador();
        }, 30000);
        
        // Event listeners
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        // Marcar todas como leídas
        const btnMarcarTodas = document.getElementById('marcar-todas-leidas');
        if (btnMarcarTodas) {
            btnMarcarTodas.addEventListener('click', () => this.marcarTodasLeidas());
        }
        
        // Abrir dropdown de notificaciones
        const btnNotificaciones = document.getElementById('btn-notificaciones');
        if (btnNotificaciones) {
            btnNotificaciones.addEventListener('click', (e) => {
                e.preventDefault();
                this.cargarNotificaciones();
            });
        }
    }
    
    async cargarNotificaciones(soloNoLeidas = false) {
        try {
            const url = `${this.baseUrl}api/notificaciones.php?action=get&limit=10${soloNoLeidas ? '&no_leidas=true' : ''}`;
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Error al cargar notificaciones');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.notificaciones = data.notifications || [];
                this.renderNotificaciones();
                this.actualizarContador();
            }
        } catch (error) {
            console.error('Error cargando notificaciones:', error);
        }
    }
    
    async actualizarContador() {
        try {
            const response = await fetch(`${this.baseUrl}api/notificaciones.php?action=count_no_leidas`, {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Error al obtener contador');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.noLeidasCount = data.total;
                this.actualizarBadge();
            }
        } catch (error) {
            console.error('Error actualizando contador:', error);
        }
    }
    
    actualizarBadge() {
        const badge = document.getElementById('notificaciones-badge');
        if (badge) {
            if (this.noLeidasCount > 0) {
                badge.textContent = this.noLeidasCount > 99 ? '99+' : this.noLeidasCount;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
    
    renderNotificaciones() {
        const container = document.getElementById('notificaciones-lista');
        if (!container) return;
        
        if (this.notificaciones.length === 0) {
            container.innerHTML = `
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="bi bi-inbox"></i>
                    <p class="mb-0 mt-2">No tienes notificaciones</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = this.notificaciones.map(notif => {
            const fecha = this.formatearFecha(notif.created_at);
            const leidaClass = notif.leida ? 'notif-leida' : 'notif-no-leida';
            const icono = notif.icono || 'bi-bell';
            const color = notif.color || 'primary';
            
            return `
                <div class="dropdown-item notificacion-item ${leidaClass}" data-id="${notif.id}" ${notif.url_accion ? `onclick="notificacionesManager.irA('${notif.url_accion}', ${notif.id})"` : ''}>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi ${icono} text-${color} fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">${notif.titulo}</h6>
                            <p class="mb-1 small">${notif.mensaje}</p>
                            <small class="text-muted">${fecha}</small>
                        </div>
                        ${!notif.leida ? '<div class="flex-shrink-0"><span class="badge bg-primary rounded-pill">Nueva</span></div>' : ''}
                    </div>
                </div>
            `;
        }).join('');
    }
    
    async marcarComoLeida(notificacionId) {
        try {
            const formData = new FormData();
            formData.append('action', 'marcar_leida');
            formData.append('notificacion_id', notificacionId);
            
            const response = await fetch(`${this.baseUrl}api/notificaciones.php`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Error al marcar como leída');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                // Actualizar UI
                const item = document.querySelector(`[data-id="${notificacionId}"]`);
                if (item) {
                    item.classList.remove('notif-no-leida');
                    item.classList.add('notif-leida');
                    const badge = item.querySelector('.badge');
                    if (badge) badge.remove();
                }
                
                this.actualizarContador();
            }
        } catch (error) {
            console.error('Error marcando notificación:', error);
        }
    }
    
    async marcarTodasLeidas() {
        try {
            const formData = new FormData();
            formData.append('action', 'marcar_todas_leidas');
            
            const response = await fetch(`${this.baseUrl}api/notificaciones.php`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Error al marcar todas');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                // Recargar notificaciones
                await this.cargarNotificaciones();
                
                // Usar SweetAlert2 si está disponible, sino usar notificación nativa
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Listo',
                        text: 'Todas las notificaciones marcadas como leídas',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        } catch (error) {
            console.error('Error marcando todas:', error);
        }
    }
    
    async eliminar(notificacionId) {
        try {
            const formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('notificacion_id', notificacionId);
            
            const response = await fetch(`${this.baseUrl}api/notificaciones.php`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Error al eliminar');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                await this.cargarNotificaciones();
            }
        } catch (error) {
            console.error('Error eliminando notificación:', error);
        }
    }
    
    async irA(url, notificacionId) {
        // Marcar como leída antes de navegar
        await this.marcarComoLeida(notificacionId);
        
        // Navegar a la URL
        if (url) {
            window.location.href = url;
        }
    }
    
    formatearFecha(fecha) {
        const ahora = new Date();
        const fechaNotif = new Date(fecha);
        const diffMs = ahora - fechaNotif;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHoras = Math.floor(diffMs / 3600000);
        const diffDias = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'Ahora mismo';
        if (diffMins < 60) return `Hace ${diffMins} min`;
        if (diffHoras < 24) return `Hace ${diffHoras}h`;
        if (diffDias < 7) return `Hace ${diffDias}d`;
        
        return fechaNotif.toLocaleDateString('es-ES', { 
            day: '2-digit', 
            month: 'short' 
        });
    }
    
    destruir() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }
}

// Inicializar cuando el DOM esté listo
let notificacionesManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        notificacionesManager = new NotificacionesManager();
    });
} else {
    notificacionesManager = new NotificacionesManager();
}

// Limpiar interval al salir
window.addEventListener('beforeunload', () => {
    if (notificacionesManager) {
        notificacionesManager.destruir();
    }
});
