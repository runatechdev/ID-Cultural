<?php

/**
 * Unified Admin API Endpoint
 */
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

use Backend\Controllers\Api\AdminController;

$controller = new AdminController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$controller->handleRequest($action);
