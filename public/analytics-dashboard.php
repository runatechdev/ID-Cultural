<?php
/**
 * Redirección al Dashboard de Analytics
 */
session_start();

// Verificar que sea admin, validador o editor
if (!isset($_SESSION['user_data']) || 
    !in_array($_SESSION['user_data']['role'], ['admin', 'validador', 'editor'])) {
    header('Location: /src/views/pages/auth/login.php');
    exit;
}

// Incluir el dashboard
require_once __DIR__ . '/src/views/pages/admin/analytics_dashboard.php';
