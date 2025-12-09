<?php

/**
 * Unified Logout Endpoint
 */

require_once __DIR__ . '/../config.php'; // config.php in root handles autoloader
use Backend\Controllers\Api\AuthController;

$controller = new AuthController();

// Capture JSON output to prevent it from being shown to user
ob_start();
$controller->logout();
ob_end_clean();

// Redirect to login page
header('Location: /src/views/pages/auth/login.php');
exit;
