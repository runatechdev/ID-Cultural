<?php
// test_sendgrid.php - Prueba de SendGrid con configuración segura

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "=== PRUEBA DE SENDGRID ===\n\n";

// Cargar .env
$env_file = __DIR__ . '/.env';
if (!file_exists($env_file)) {
    die("ERROR: Archivo .env no encontrado. Copia .env.example y configura tus credenciales.\n");
}

$lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
    list($key, $value) = explode('=', $line, 2);
    putenv(trim($key) . '=' . trim($value));
}

$api_key = getenv('SENDGRID_API_KEY');
$from_email = getenv('SENDGRID_FROM_EMAIL') ?: 'noreply@idcultural.gob.ar';
$test_email = 'maximilianodell2@gmail.com';

if (empty($api_key) || $api_key === 'your_sendgrid_api_key_here') {
    die("ERROR: Configura SENDGRID_API_KEY en .env\n");
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.sendgrid.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'apikey';
    $mail->Password = $api_key;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    $mail->setFrom($from_email, 'ID Cultural Test');
    $mail->addAddress($test_email);
    $mail->isHTML(true);
    $mail->Subject = 'Test SendGrid';
    $mail->Body = '<h1>Test exitoso!</h1><p>SendGrid funciona correctamente.</p>';
    
    echo "Enviando email...\n";
    $mail->send();
    echo "¡Éxito! Email enviado a $test_email\n";
    
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}\n";
}
