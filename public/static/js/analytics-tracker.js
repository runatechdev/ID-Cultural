/**
 * Analytics Tracker - ID Cultural
 * Sistema de tracking automático de eventos y páginas
 */

class AnalyticsTracker {
    constructor() {
        this.apiUrl = '/api/analytics.php';
        this.pageLoadTime = Date.now();
        this.init();
    }

    init() {
        // Track page view al cargar
        this.trackPageView();
        
        // Track duración al salir
        window.addEventListener('beforeunload', () => {
            this.trackPageDuration();
        });
        
        // Track clicks en enlaces importantes
        this.trackImportantClicks();
        
        // Track envío de formularios
        this.trackFormSubmissions();
    }

    /**
     * Registrar visita a página
     */
    trackPageView() {
        const pagina = window.location.pathname + window.location.search;
        
        fetch(this.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=track_page&pagina=${encodeURIComponent(pagina)}`
        }).catch(err => console.error('Analytics error:', err));
    }

    /**
     * Registrar duración de página
     */
    trackPageDuration() {
        const duracion = Math.floor((Date.now() - this.pageLoadTime) / 1000);
        const pagina = window.location.pathname + window.location.search;
        
        // Usar sendBeacon para garantizar que se envíe antes de salir
        const data = new URLSearchParams({
            action: 'track_page',
            pagina: pagina,
            duracion: duracion
        });
        
        navigator.sendBeacon(this.apiUrl, data);
    }

    /**
     * Registrar evento
     */
    trackEvent(categoria, accion, etiqueta = null, valor = null) {
        const data = new URLSearchParams({
            action: 'track_event',
            categoria: categoria,
            accion: accion
        });
        
        if (etiqueta) data.append('etiqueta', etiqueta);
        if (valor !== null) data.append('valor', valor);
        
        fetch(this.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data.toString()
        }).catch(err => console.error('Analytics error:', err));
    }

    /**
     * Registrar búsqueda
     */
    trackSearch(termino, resultados) {
        const data = new URLSearchParams({
            action: 'track_search',
            termino: termino,
            resultados: resultados
        });
        
        fetch(this.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data.toString()
        }).catch(err => console.error('Analytics error:', err));
    }

    /**
     * Track clicks en enlaces importantes
     */
    trackImportantClicks() {
        // Track clicks en botones de acción
        document.addEventListener('click', (e) => {
            const target = e.target.closest('a, button');
            if (!target) return;
            
            const href = target.getAttribute('href');
            const text = target.textContent.trim();
            
            // Track clicks externos
            if (href && (href.startsWith('http') || href.startsWith('mailto'))) {
                this.trackEvent('Click', 'Enlace Externo', href);
            }
            
            // Track clicks en botones importantes
            if (target.classList.contains('btn-primary') || 
                target.classList.contains('btn-success') ||
                target.id.includes('submit')) {
                this.trackEvent('Click', 'Botón', text);
            }
            
            // Track navegación
            if (target.classList.contains('nav-link')) {
                this.trackEvent('Navegación', 'Menu', text);
            }
        });
    }

    /**
     * Track envío de formularios
     */
    trackFormSubmissions() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const formId = form.id || 'form-sin-id';
            const formAction = form.action || 'sin-action';
            
            this.trackEvent('Formulario', 'Envío', formId);
        });
    }

    /**
     * Track scroll profundo
     */
    trackDeepScroll() {
        let scrollTracked = {
            25: false,
            50: false,
            75: false,
            100: false
        };
        
        window.addEventListener('scroll', () => {
            const scrollPercent = Math.round(
                (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
            );
            
            Object.keys(scrollTracked).forEach(threshold => {
                if (scrollPercent >= threshold && !scrollTracked[threshold]) {
                    scrollTracked[threshold] = true;
                    this.trackEvent('Scroll', `${threshold}%`, window.location.pathname);
                }
            });
        });
    }

    /**
     * Track tiempo en página (cada 30 segundos)
     */
    trackTimeOnPage() {
        let timeSpent = 0;
        
        setInterval(() => {
            timeSpent += 30;
            this.trackEvent('Tiempo', 'Permanencia', window.location.pathname, timeSpent);
        }, 30000);
    }

    /**
     * Obtener dashboard (solo para admin)
     */
    async getDashboard() {
        try {
            const response = await fetch(this.apiUrl + '?action=get_dashboard');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error al obtener dashboard:', error);
            return null;
        }
    }
}

// Inicializar tracker automáticamente
const tracker = new AnalyticsTracker();

// Exportar para uso global
window.AnalyticsTracker = tracker;
