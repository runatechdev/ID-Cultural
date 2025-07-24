<?php
// Configuración para la conexión a MariaDB (MySQL) en Docker
$db_host = 'db';      // Nombre del servicio de la base de datos en tu docker-compose.yml
$db_user = 'runatechdev';   // Usuario de la base de datos (según tu docker-compose.yml)
$db_pass = '1234';    // Contraseña de la base de datos (según tu docker-compose.yml)
$db_name = 'idcultural'; // Nombre de la base de datos (según tu docker-compose.yml)

try {
    // Crea una nueva instancia de PDO para MySQL
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);

    // Establece el modo de error para que PDO lance excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: Para verificar la conexión (puedes comentar o borrar esta línea después)
    // echo "Conexión a MariaDB exitosa desde connection.php!";

} catch (PDOException $e) {
    // Captura cualquier error de conexión y termina el script
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>