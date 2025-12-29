<?php

/**
 * Unified Artists API Endpoint
 * Routes requests to Backend\Controllers\Api\ArtistaController
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config.php';

// Rate Limiting
$rateLimiter = new RateLimiter();
$rateLimiter->check();

use Backend\Controllers\Api\ArtistaController;

$controller = new ArtistaController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

$controller->handleRequest($action);
