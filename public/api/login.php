<?php
// public/api/login.php

// 🔒 Endurecer cookies de sesión (ANTES de session_start)
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
require_once __DIR__ . '/../../backend/helpers/security_headers.php';
require_once __DIR__ . '/../../backend/helpers/cors.php';
require_once __DIR__ . '/../../backend/helpers/auth_guard.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../backend/controllers/verificar_usuario.php';
require_once __DIR__ . '/../../backend/helpers/security_logger.php';
require_once __DIR__ . '/../../backend/helpers/security_bruteforce.php';

global $pdo;

$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// ---------------------------------------------------------------------------
// 🔥 1) DETECCIÓN DE FUERZA BRUTA
// ---------------------------------------------------------------------------
$failCount = count_recent_failures($pdo, $email, $ip);

if ($failCount >= 5) {

    log_security_event($pdo, 'BRUTE_FORCE_SUSPECTED', 'CRITICAL', [
        'details' => [
            'email' => $email,
            'attempts_last_10m' => $failCount,
            'ip' => $ip
        ]
    ]);

    echo json_encode([
        "status" => "error",
        "msg" => "Acceso bloqueado temporalmente por actividad sospechosa. Espere 10 minutos."
    ]);
    exit;
}

// ---------------------------------------------------------------------------
// 2) VALIDAR CREDENCIALES
// ---------------------------------------------------------------------------
$result = checkUserCredentials($email, $password);

// ---------------------------------------------------------------------------
// ⚠️ 3) LOGIN FALLIDO
// ---------------------------------------------------------------------------
if ($result['status'] !== 'ok') {

    log_security_event($pdo, 'LOGIN_FAIL', 'WARNING', [
        'details' => ['email' => $email]
    ]);

    echo json_encode($result);
    exit;
}

// ---------------------------------------------------------------------------
// 🟢 4) LOGIN EXITOSO
// ---------------------------------------------------------------------------
log_security_event($pdo, 'LOGIN_SUCCESS', 'INFO', [
    'user_id' => $result['user_data']['id'],
    'details' => ['email' => $email]
]);

// ---------------------------------------------------------------------------
// 5) CREAR SESIÓN SEGURA
// ---------------------------------------------------------------------------
session_regenerate_id(true);

$_SESSION['user_data'] = [
    'id' => $result['user_data']['id'],
    'role' => $result['user_data']['role'],
    'nombre' => $result['user_data']['nombre'] ?? '',
    'apellido' => $result['user_data']['apellido'] ?? '',
    'email' => $result['user_data']['email'] ?? $email
];

// ✅ TIMESTAMP DE ACTIVIDAD
$_SESSION['last_activity'] = time();

echo json_encode($result);
exit;
