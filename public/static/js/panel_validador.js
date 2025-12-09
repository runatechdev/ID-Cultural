/**
 * JavaScript para Panel del Validador
 * Archivo: /static/js/panel_validador.js
 */

document.addEventListener('DOMContentLoaded', function () {
    cargarEstadisticas();

    // Actualizar estadísticas cada 30 segundos
    setInterval(cargarEstadisticas, 30000);
});

async function cargarEstadisticas() {
    try {
        const response = await fetch(`${BASE_URL}api/stats.php?action=admin`);
        const data = await response.json();

        console.log('Estadísticas recibidas:', data); // Debug

        if (data.status === 'ok') {
            actualizarEstadisticas(data.data);
        } else if (data.artistas_pendientes !== undefined) {
            // Formato nuevo de respuesta con artistas
            actualizarEstadisticas(data);
        } else if (data.pendientes !== undefined) {
            // Formato antiguo de respuesta
            actualizarEstadisticas(data);
        } else {
            console.error('Error al cargar estadísticas:', data.message);
        }
    } catch (error) {
        console.error('Error de conexión:', error);
    }
}

function actualizarEstadisticas(stats) {
    console.log('Actualizando estadísticas con:', stats); // Debug

    // Actualizar números con animación - usando estadísticas de ARTISTAS
    // Usar el operador ?? (nullish coalescing) en lugar de ||
    const artistasPendientes = stats.artistas_pendientes !== undefined ? stats.artistas_pendientes : (stats.pendientes || 0);
    const artistasValidados = stats.artistas_validados !== undefined ? stats.artistas_validados : (stats.validados || 0);
    const artistasRechazados = stats.artistas_rechazados !== undefined ? stats.artistas_rechazados : (stats.rechazados || 0);

    animarNumero('stat-pendientes', artistasPendientes, 'text-warning');
    animarNumero('stat-validados', artistasValidados, 'text-success');
    animarNumero('stat-rechazados', artistasRechazados, 'text-danger');

    // Si hay elementos para obras (opcional, por si se agregan luego)
    if (document.getElementById('stat-obras-pendientes')) {
        animarNumero('stat-obras-pendientes', stats.obras_pendientes || 0, 'text-warning');
    }
    if (document.getElementById('stat-obras-validadas')) {
        animarNumero('stat-obras-validadas', stats.obras_validadas || 0, 'text-success');
    }
    if (document.getElementById('stat-obras-rechazadas')) {
        animarNumero('stat-obras-rechazadas', stats.obras_rechazadas || 0, 'text-danger');
    }
}

function animarNumero(elementId, valorFinal, colorClass = '') {
    const elemento = document.getElementById(elementId);
    if (!elemento) return;

    const valorActual = parseInt(elemento.textContent) || 0;
    const diferencia = valorFinal - valorActual;
    const duracion = 500; // ms
    const pasos = 20;
    const incremento = diferencia / pasos;
    const intervalo = duracion / pasos;

    let paso = 0;

    const timer = setInterval(() => {
        paso++;
        const nuevoValor = Math.round(valorActual + (incremento * paso));
        elemento.textContent = nuevoValor;

        // Aplicar clase de color si hay cambios
        if (colorClass && diferencia !== 0) {
            elemento.classList.add(colorClass);
            setTimeout(() => elemento.classList.remove(colorClass), duracion);
        }

        if (paso >= pasos) {
            elemento.textContent = valorFinal;
            clearInterval(timer);
        }
    }, intervalo);
}

// Inicializar tooltips de Bootstrap
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});