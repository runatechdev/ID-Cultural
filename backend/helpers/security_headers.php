<?php
// backend/helpers/security_headers.php

// Evita sniffing de MIME
header('X-Content-Type-Options: nosniff');

// Protección básica XSS (fallback para navegadores viejos)
header('X-XSS-Protection: 1; mode=block');

// Evita clickjacking
header('X-Frame-Options: DENY');

// Política de referrer
header('Referrer-Policy: no-referrer');

// Restringe uso de APIs del navegador
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

// Fuerza HTTPS (solo si está bajo HTTPS)
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
