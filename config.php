<?php
// Define la ruta base de tu proyecto.
// Si tu proyecto está en http://localhost/ID-Cultural/, la base es /ID-Cultural/
// Si está en la raíz, la base es /
define('BASE_URL', '/ID-Cultural/');

// Otras configuraciones futuras podrían ir aquí...
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Buen lugar para iniciar sesiones
?>