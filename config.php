<?php
/**
 * Archivo de Configuración Principal
 */

// 1. Inicia la sesión de forma segura
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Define la URL base para todos los enlaces públicos (CSS, JS, <a>, <img>)
// Esto hace que funcione tanto en XAMPP como en cualquier otro servidor.
define('BASE_URL', '/ID-Cultural/');

// 3. Define la ruta raíz del servidor para los 'include' y 'require' de PHP.
// Esto nos dará la ruta absoluta al directorio del proyecto.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__));
}