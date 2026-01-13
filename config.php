<?php

/**
 * Configuración Global - ID Cultural
 * Detecta automáticamente el entorno y configura BASE_URL
 */

/* ============================================================
   CONTROL SEGURO DE SESIÓN (SIEMPRE PRIMERO)
   ============================================================ */

// Evitar errores si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {

    // Configuración de cookies de sesión seguras
    session_set_cookie_params([
        'secure'   => true,     // Solo HTTPS
        'httponly' => true,     // Evita JS
        'samesite' => 'Strict'  // Anti-CSRF
    ]);

    session_start();
}

/* ============================================================
   HEADERS DE SEGURIDAD
   ============================================================ */

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline';");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
header("X-XSS-Protection: 1; mode=block");
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Resource-Policy: same-origin");
header("Cross-Origin-Embedder-Policy: require-corp");


/* ============================================================
   DETECCIÓN DE ENTORNO Y BASE_URL
   ============================================================ */

$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', 'idcultural_web']);
$is_tailscale = strpos($_SERVER['HTTP_HOST'] ?? '', '.ts.net') !== false;
$is_ngrok = strpos($_SERVER['HTTP_HOST'] ?? '', '.ngrok') !== false;

if ($is_local && !$is_ngrok) {
    define('BASE_URL', 'http://localhost:8080/');
} elseif ($is_tailscale) {
    define('BASE_URL', 'https://server-itse.tail0ce263.ts.net/');
} elseif ($is_ngrok) {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $server_ip = $_SERVER['SERVER_NAME'] ?? $_SERVER['SERVER_ADDR'] ?? 'localhost';
    $server_port = $_SERVER['SERVER_PORT'] ?? '80';
    $port_suffix = ($server_port == '80' || $server_port == '443') ? '' : ':' . $server_port;
    define('BASE_URL', $protocol . '://' . $server_ip . $port_suffix . '/');
}


/* ============================================================
   CONFIGURACIÓN DE BASE DE DATOS
   ============================================================ */

define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'runatechdev');
define('DB_PASS', getenv('DB_PASS') ?: '1234');
define('DB_NAME', getenv('DB_NAME') ?: 'idcultural');


/* ============================================================
   ERRORES
   ============================================================ */

if ($is_local) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/php-errors.log');
}

?>
