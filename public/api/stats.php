<?php

/**
 * Unified Stats API Endpoint
 */
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

use Backend\Controllers\Api\StatsController;

$controller = new StatsController();
// Default action 'public' if not specified? Or require action?
// Legacy get_estadisticas_inicio.php was GET without params mostly.
// Let's rely on explicit action param for clarity, or default to public if clean URL needed?
// Frontend likely does plain fetch.
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$controller->handleRequest($action);
