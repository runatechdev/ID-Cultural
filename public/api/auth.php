<?php

/**
 * API Unificada de Autenticación
 * Maneja login, logout, cambio de clave y recuperación.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';

// Rate Limiting para prevenir brute force
$rateLimiter = new RateLimiter();
$rateLimiter->check();

use Backend\Controllers\Api\AuthController;

// Capturar acción
$action = $_GET['action'] ?? 'login'; // Default to login if not specified, though explicit is better

$controller = new AuthController();
$controller->handleRequest($action);
