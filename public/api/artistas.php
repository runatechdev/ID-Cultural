<?php

/**
 * Unified Artists API Endpoint
 * Routes requests to Backend\Controllers\Api\ArtistaController
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

use Backend\Controllers\Api\ArtistaController;

$controller = new ArtistaController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

$controller->handleRequest($action);
