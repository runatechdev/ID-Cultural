<?php
/**
 * EmailHelper.php
 * Maneja el envío de emails en la plataforma ID Cultural
 * Usa función mail() estándar de PHP
 */

class EmailHelper {
    private $from_email = 'noreply@idcultural.gob.ar';
    private $from_name = 'ID Cultural - Subsecretaría de Cultura';
    
    public function __construct() {
        // Configuración automática
    }
    
    /**
     * Envía un email
     */
    private function enviarMail($email_destino, $asunto, $html) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: " . $this->from_name . " <" . $this->from_email . ">" . "\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        
        return mail($email_destino, $asunto, $html, $headers);
    }
    
    /**
     * Envía email de confirmación de registro
     */
    public function enviarBienvenida($email, $nombre, $token = null) {
        try {
            $asunto = '¡Bienvenido a ID Cultural!';
            $html = $this->obtenerPlantilla('bienvenida', ['nombre' => $nombre]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error enviando bienvenida: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica al artista que su perfil fue validado
     */
    public function notificarPerfilValidado($email, $nombre) {
        try {
            $asunto = '¡Tu perfil ha sido aprobado en ID Cultural!';
            $html = $this->obtenerPlantilla('perfil_validado', ['nombre' => $nombre]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error notificando validación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica al artista que su obra fue aprobada
     */
    public function notificarObraAprobada($email, $nombre, $titulo_obra) {
        try {
            $asunto = "¡Tu obra '{$titulo_obra}' ha sido publicada!";
            $html = $this->obtenerPlantilla('obra_aprobada', [
                'nombre' => $nombre,
                'titulo_obra' => $titulo_obra
            ]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error notificando aprobación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica al artista que su obra fue rechazada
     */
    public function notificarObraRechazada($email, $nombre, $titulo_obra, $motivo = '') {
        try {
            $asunto = "Tu obra '{$titulo_obra}' requiere revisión";
            $html = $this->obtenerPlantilla('obra_rechazada', [
                'nombre' => $nombre,
                'titulo_obra' => $titulo_obra,
                'motivo' => $motivo
            ]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error notificando rechazo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envía enlace de recuperación de contraseña
     */
    public function enviarRecuperacionClave($email, $nombre, $token) {
        try {
            $enlace_recuperacion = 'https://idcultural.gob.ar/recuperar-clave?token=' . urlencode($token);
            $asunto = 'Recupera tu contraseña en ID Cultural';
            $html = $this->obtenerPlantilla('recuperar_clave', [
                'nombre' => $nombre,
                'enlace' => $enlace_recuperacion
            ]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error enviando recuperación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica al validador sobre nuevas obras pendientes
     */
    public function notificarObrasPendientes($email, $nombre, $cantidad) {
        try {
            $asunto = "[$cantidad] Nuevas obras pendientes de validación";
            $html = $this->obtenerPlantilla('obras_pendientes', [
                'nombre' => $nombre,
                'cantidad' => $cantidad
            ]);
            return $this->enviarMail($email, $asunto, $html);
        } catch (Exception $e) {
            error_log("Error notificando pendientes: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene plantilla HTML de email
     */
    private function obtenerPlantilla($tipo, $datos = []) {
        $plantillas = [
            'bienvenida' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #0066cc;'>¡Bienvenido a ID Cultural!</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>Tu registro ha sido completado exitosamente. Tu perfil está en estado <strong>pendiente de validación</strong>.</p>
                        <p>Te notificaremos cuando tu perfil sea aprobado.</p>
                        <hr style='border: none; border-top: 1px solid #ddd;'>
                        <p><small style='color: #666;'>Subsecretaría de Cultura - Santiago del Estero</small></p>
                    </div>
                </body>
                </html>
            ",
            'perfil_validado' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #28a745;'>✓ ¡Tu perfil ha sido aprobado!</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>¡Excelente noticia! Tu perfil en ID Cultural ha sido <strong>validado y aprobado</strong>.</p>
                        <p>Ahora puedes publicar tus obras y ser descubierto por la comunidad cultural.</p>
                        <p style='margin-top: 30px;'><a href='https://idcultural.gob.ar/dashboard' style='background-color: #0066cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Ir a mi panel</a></p>
                    </div>
                </body>
                </html>
            ",
            'obra_aprobada' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #28a745;'>✓ ¡Tu obra ha sido publicada!</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>Tu obra <strong>\"{$datos['titulo_obra']}\"</strong> ha sido <strong>aprobada y publicada</strong> en la galería de ID Cultural.</p>
                        <p>¡Ahora forma parte de nuestro catálogo cultural!</p>
                    </div>
                </body>
                </html>
            ",
            'obra_rechazada' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #dc3545;'>Revisión requerida</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>Tu obra <strong>\"{$datos['titulo_obra']}\"</strong> requiere revisión.</p>
                        " . (!empty($datos['motivo']) ? "<p><strong>Motivo:</strong> {$datos['motivo']}</p>" : "") . "
                        <p>Por favor, revisa y vuelve a enviar tu obra.</p>
                    </div>
                </body>
                </html>
            ",
            'recuperar_clave' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #0066cc;'>Recupera tu contraseña</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>Recibimos una solicitud para recuperar tu contraseña. Haz clic en el enlace a continuación:</p>
                        <p style='margin-top: 30px;'><a href='{$datos['enlace']}' style='background-color: #0066cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Recuperar contraseña</a></p>
                        <p><small style='color: #666;'>Si no solicitaste esto, ignora este email.</small></p>
                        <p><small style='color: #999;'>El enlace expira en 1 hora.</small></p>
                    </div>
                </body>
                </html>
            ",
            'obras_pendientes' => "
                <html>
                <head><meta charset='UTF-8'></head>
                <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
                    <div style='background-color: white; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #ff9800;'>📋 {$datos['cantidad']} obras pendientes de validación</h2>
                        <p>Hola <strong>{$datos['nombre']}</strong>,</p>
                        <p>Hay <strong>{$datos['cantidad']} nueva(s) obra(s)</strong> esperando tu validación.</p>
                        <p style='margin-top: 30px;'><a href='https://idcultural.gob.ar/dashboard-validador' style='background-color: #ff9800; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Ver panel de validación</a></p>
                    </div>
                </body>
                </html>
            "
        ];
        
        return $plantillas[$tipo] ?? '';
    }
}
