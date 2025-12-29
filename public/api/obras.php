<?php

/**
 * Unified Obras API Endpoint
 */
require_once __DIR__ . '/../../config.php';

// Rate Limiting
$rateLimiter = new RateLimiter();
$rateLimiter->check();

header('Content-Type: application/json');

use Backend\Controllers\Api\ObraController;

$controller = new ObraController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$controller->handleRequest($action);
