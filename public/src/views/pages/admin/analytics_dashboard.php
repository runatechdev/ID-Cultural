<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../../../backend/config/connection.php';

// Verificar que sea admin o validador
if (!isset($_SESSION['user_data']) || 
    !in_array($_SESSION['user_data']['role'], ['admin', 'validador', 'editor'])) {
    header('Location: /src/views/pages/auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics - ID Cultural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
    </style>
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../../../../../components/navbar.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="bi bi-graph-up"></i> Dashboard de Analytics</h2>
                <p class="text-muted">Estadísticas y métricas de la plataforma</p>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4" id="summary-cards">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="bi bi-eye"></i> Visitas Hoy
                        </h6>
                        <h3 class="card-title" id="visitas-hoy">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="bi bi-calendar-month"></i> Visitas Este Mes
                        </h6>
                        <h3 class="card-title" id="visitas-mes">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="bi bi-cursor"></i> Eventos Hoy
                        </h6>
                        <h3 class="card-title" id="eventos-hoy">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="bi bi-search"></i> Búsquedas Hoy
                        </h6>
                        <h3 class="card-title" id="busquedas-hoy">-</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de visitas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-graph-up-arrow"></i> Visitas Diarias (Últimos 30 días)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="visitasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Páginas más visitadas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-file-earmark-text"></i> Páginas Populares</h5>
                    </div>
                    <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                        <ul class="list-group" id="top-pages">
                            <li class="list-group-item">Cargando...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Eventos más frecuentes -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-lightning"></i> Eventos Populares</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="eventosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Búsquedas más populares -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-search-heart"></i> Búsquedas Populares</h5>
                    </div>
                    <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-striped" id="top-searches">
                            <thead>
                                <tr>
                                    <th>Término</th>
                                    <th>Búsquedas</th>
                                    <th>Resultados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="3">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios activos -->
        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-people"></i> Usuarios Más Activos (Última Semana)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="usuarios-activos">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Visitas</th>
                                    <th>Eventos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="4">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cargar datos del dashboard
        async function loadDashboard() {
            try {
                const response = await fetch('/api/analytics.php?action=get_dashboard');
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message);
                }
                
                const data = result.data;
                
                // Actualizar tarjetas de resumen
                document.getElementById('visitas-hoy').textContent = data.resumen.total_visitas_hoy || 0;
                document.getElementById('visitas-mes').textContent = data.resumen.total_visitas_mes || 0;
                document.getElementById('eventos-hoy').textContent = data.resumen.total_eventos_hoy || 0;
                document.getElementById('busquedas-hoy').textContent = data.resumen.total_busquedas_hoy || 0;
                
                // Crear gráfico de visitas
                createVisitChart(data.visitas_diarias);
                
                // Mostrar páginas populares
                displayTopPages(data.paginas_populares);
                
                // Crear gráfico de eventos
                createEventChart(data.eventos_populares);
                
                // Mostrar búsquedas populares
                displayTopSearches(data.busquedas_populares);
                
                // Mostrar usuarios activos
                displayActiveUsers(data.usuarios_activos);
                
            } catch (error) {
                console.error('Error al cargar dashboard:', error);
                alert('Error al cargar estadísticas');
            }
        }

        // Gráfico de visitas
        function createVisitChart(data) {
            const ctx = document.getElementById('visitasChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.fecha),
                    datasets: [{
                        label: 'Visitas',
                        data: data.map(d => d.total_visitas),
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
                    }, {
                        label: 'Visitantes Únicos',
                        data: data.map(d => d.visitantes_unicos),
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Mostrar páginas populares
        function displayTopPages(pages) {
            const list = document.getElementById('top-pages');
            list.innerHTML = pages.map(page => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${page.pagina}
                    <span class="badge bg-primary rounded-pill">${page.visitas}</span>
                </li>
            `).join('');
        }

        // Gráfico de eventos
        function createEventChart(data) {
            const ctx = document.getElementById('eventosChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(e => `${e.categoria} - ${e.accion}`),
                    datasets: [{
                        label: 'Cantidad',
                        data: data.map(e => e.cantidad),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Mostrar búsquedas populares
        function displayTopSearches(searches) {
            const tbody = document.querySelector('#top-searches tbody');
            tbody.innerHTML = searches.map(search => `
                <tr>
                    <td>${search.termino_busqueda}</td>
                    <td><span class="badge bg-info">${search.cantidad_busquedas}</span></td>
                    <td>${Math.round(search.promedio_resultados)}</td>
                </tr>
            `).join('');
        }

        // Mostrar usuarios activos
        function displayActiveUsers(users) {
            const tbody = document.querySelector('#usuarios-activos tbody');
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay datos disponibles</td></tr>';
                return;
            }
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.nombre || 'Sin nombre'}</td>
                    <td>${user.email}</td>
                    <td><span class="badge bg-success">${user.visitas}</span></td>
                    <td><span class="badge bg-primary">${user.eventos}</span></td>
                </tr>
            `).join('');
        }

        // Cargar al inicio
        loadDashboard();
        
        // Recargar cada 5 minutos
        setInterval(loadDashboard, 300000);
    </script>
</body>
</html>
