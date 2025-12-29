<?php

/**
 * Unified Logs API Endpoint
 */
require_once __DIR__ . '/../../config.php';

// Rate Limiting
$rateLimiter = new RateLimiter();
$rateLimiter->check();

header('Content-Type: application/json');

use Backend\Controllers\Api\LogController;

$controller = new LogController();
$action = $_GET['action'] ?? $_POST['action'] ?? 'get_all'; // Default to get_all if mostly used for that
$controller->handleRequest($action);
