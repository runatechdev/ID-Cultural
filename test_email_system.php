<?php
/**
 * test_email_system.php
 * Prueba completa del sistema de EmailHelper con configuración segura
 */

require_once __DIR__ . '/backend/helpers/EmailHelper.php';

echo "=== PRUEBA DEL SISTEMA EMAILHELPER ===\n\n";

// Verificar que .env existe
if (!file_exists(__DIR__ . '/.env')) {
    die("❌ Archivo .env no encontrado. Copia .env.example a .env y configura tus credenciales.\n");
}

// Email de destino para pruebas
$test_email = 'maximilianodell2@gmail.com'; // ¡CAMBIA ESTO POR TU EMAIL!
$test_nombre = 'Usuario de Prueba';

$emailHelper = new EmailHelper();

echo "🧪 Probando diferentes tipos de email...\n\n";

// 1. Email de bienvenida
echo "1️⃣ Probando email de bienvenida...\n";
$resultado1 = $emailHelper->enviarBienvenida($test_email, $test_nombre);
echo $resultado1 ? "✅ Bienvenida enviada\n" : "❌ Error en bienvenida\n";

sleep(1); // Pausa para no sobrecargar

// 2. Email de perfil validado
echo "\n2️⃣ Probando email de perfil validado...\n";
$resultado2 = $emailHelper->notificarPerfilValidado($test_email, $test_nombre);
echo $resultado2 ? "✅ Validación enviada\n" : "❌ Error en validación\n";

sleep(1);

// 3. Email de obra aprobada
echo "\n3️⃣ Probando email de obra aprobada...\n";
$resultado3 = $emailHelper->notificarObraAprobada($test_email, $test_nombre, 'Mi Primera Obra');
echo $resultado3 ? "✅ Aprobación enviada\n" : "❌ Error en aprobación\n";

sleep(1);

// 4. Email de recuperación de contraseña
echo "\n4️⃣ Probando email de recuperación...\n";
$token_prueba = 'test-token-' . time();
$resultado4 = $emailHelper->enviarRecuperacionClave($test_email, $test_nombre, $token_prueba);
echo $resultado4 ? "✅ Recuperación enviada\n" : "❌ Error en recuperación\n";

echo "\n=== RESUMEN DE PRUEBAS ===\n";
$total_exitosos = ($resultado1 ? 1 : 0) + ($resultado2 ? 1 : 0) + ($resultado3 ? 1 : 0) + ($resultado4 ? 1 : 0);
echo "📊 Emails enviados exitosamente: $total_exitosos/4\n";

if ($total_exitosos == 4) {
    echo "🎉 ¡TODOS LOS EMAILS FUNCIONAN PERFECTAMENTE!\n";
    echo "📧 Revisa tu bandeja de entrada en: $test_email\n";
    echo "📁 Si no los ves, revisa la carpeta de SPAM\n\n";
    echo "✅ El sistema de emails está listo para producción.\n";
} else {
    echo "⚠️ Algunos emails fallaron. Revisa los logs de errores.\n";
    echo "🔍 Ejecuta: tail -f /var/log/apache2/error.log\n";
}

echo "\n💡 PRÓXIMOS PASOS:\n";
echo "1. Verifica que lleguen todos los emails\n";
echo "2. Revisa que el diseño se vea bien\n";
echo "3. Prueba desde la aplicación real\n";
echo "4. Configura un dominio verificado en SendGrid\n";
?>