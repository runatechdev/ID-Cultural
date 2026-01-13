<?php
// backend/helpers/cors.php

$allowedOrigins = [
    'http://localhost',
    'http://localhost:3000',
    'http://localhost:8080',
    'http://127.0.0.1',
    'https://idcultural.gob.ar',        // futuro dominio
    'https://www.idcultural.gob.ar'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin && in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
} else if ($origin) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'msg' => 'CORS origin not allowed'
    ]);
    exit;
}

// Headers permitidos
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
