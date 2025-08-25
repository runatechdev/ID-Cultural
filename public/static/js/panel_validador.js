document.addEventListener('DOMContentLoaded', () => {

    // Función para cargar las estadísticas en las tarjetas
    async function cargarEstadisticas() {
        try {
            const response = await fetch(`${BASE_URL}api/get_artist_stats.php`);
            const stats = await response.json();
            document.getElementById('stat-pendientes').textContent = stats.pendientes || 0;
            document.getElementById('stat-validados').textContent = stats.validados || 0;
            document.getElementById('stat-rechazados').textContent = stats.rechazados || 0;
        } catch (error) {
            console.error("Error al cargar estadísticas:", error);
            // Opcional: mostrar un error en las tarjetas
            document.getElementById('stat-pendientes').textContent = 'Error';
            document.getElementById('stat-validados').textContent = 'Error';
            document.getElementById('stat-rechazados').textContent = 'Error';
        }
    }

    // Inicializar tooltips de los botones del menú
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Carga inicial de las estadísticas
    cargarEstadisticas();
});
