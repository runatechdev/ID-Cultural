<?php

/**
 * Configuración Global - ID Cultural
 * Carga variables de entorno y configura la aplicación
 */

// Cargar variables de entorno
require_once __DIR__ . '/backend/config/Environment.php';
use Backend\Config\Environment;

try {
    Environment::load(__DIR__ . '/.env');
} catch (\RuntimeException $e) {
    // Si no existe .env, usar valores por defecto (solo desarrollo)
    if (!file_exists(__DIR__ . '/.env')) {
        error_log("WARNING: .env file not found. Using default values.");
        $_ENV['APP_ENV'] = 'development';
        $_ENV['DB_HOST'] = 'db';
        $_ENV['DB_USER'] = 'runatechdev';
        $_ENV['DB_PASS'] = '1234';
        $_ENV['DB_NAME'] = 'idcultural';
        $_ENV['APP_KEY'] = 'default_key_not_secure';
        $_ENV['JWT_SECRET'] = 'default_jwt_not_secure';
    } else {
        die($e->getMessage());
    }
}

// Autoloader manual para namespace Backend\
spl_autoload_register(function ($class) {
    $prefix = 'Backend\\';
    $base_dir = __DIR__ . '/backend/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    // Fix case mismatch: Controllers -> controllers, Helpers -> helpers, Config -> config
    $parts = explode('\\', $relative_class);
    if (isset($parts[0])) {
        if ($parts[0] === 'Controllers') $parts[0] = 'controllers';
        if ($parts[0] === 'Helpers') $parts[0] = 'helpers';
        if ($parts[0] === 'Config') $parts[0] = 'config';
    }

    $file = $base_dir . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Autoloader para helpers globales
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/backend/helpers/' . $class . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Detectar entorno y configurar BASE_URL
$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', 'idcultural_web']);
$is_tailscale = strpos($_SERVER['HTTP_HOST'] ?? '', '.ts.net') !== false;
$is_ngrok = strpos($_SERVER['HTTP_HOST'] ?? '', '.ngrok') !== false;

// Siempre usar HTTP_HOST si está disponible para detectar puerto correcto
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/');
} elseif ($is_local && !$is_ngrok) {
    define('BASE_URL', Environment::get('APP_URL', 'http://localhost:8080/'));
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

// Configuración de Base de Datos desde .env
define('DB_HOST', Environment::get('DB_HOST'));
define('DB_USER', Environment::get('DB_USER'));
define('DB_PASS', Environment::get('DB_PASS'));
define('DB_NAME', Environment::get('DB_NAME'));

// Configuración de errores según entorno
if (Environment::isDevelopment()) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', Environment::get('LOG_PATH', __DIR__ . '/storage/logs') . '/php-errors.log');
}

// Configuración de sesiones seguras (ANTES de cualquier session_start())
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', Environment::isProduction() ? 1 : 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    ini_set('session.gc_maxlifetime', Environment::get('SESSION_LIFETIME', 7200));
}

// Global Database Connection
require_once __DIR__ . '/backend/config/connection.php';
