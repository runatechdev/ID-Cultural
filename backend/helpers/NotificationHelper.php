<?php
/**
 * NotificationHelper - Helper para crear y enviar notificaciones
 * ID Cultural
 */

class NotificationHelper {
    private static $pdo;
    
    /**
     * Inicializar con conexión PDO
     */
    public static function init($pdo) {
        self::$pdo = $pdo;
    }
    
    /**
     * Crear notificación desde plantilla
     */
    public static function crearDesdePlantilla($codigo_plantilla, $usuario_id, $variables = []) {
        try {
            // Obtener plantilla
            $stmt = self::$pdo->prepare("SELECT * FROM plantillas_notificaciones WHERE codigo = ?");
            $stmt->execute([$codigo_plantilla]);
            $plantilla = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$plantilla) {
                error_log("Plantilla de notificación no encontrada: " . $codigo_plantilla);
                return false;
            }
            
            // Reemplazar variables en título y mensaje
            $titulo = $plantilla['titulo_template'];
            $mensaje = $plantilla['mensaje_template'];
            $url_accion = $plantilla['url_accion_template'];
            
            foreach ($variables as $key => $value) {
                $titulo = str_replace('{' . $key . '}', $value, $titulo);
                $mensaje = str_replace('{' . $key . '}', $value, $mensaje);
                if ($url_accion) {
                    $url_accion = str_replace('{' . $key . '}', $value, $url_accion);
                }
            }
            
            // Crear notificación
            return self::crear(
                $usuario_id,
                $plantilla['tipo'],
                $titulo,
                $mensaje,
                $url_accion,
                $plantilla['icono'],
                $plantilla['color'],
                $variables
            );
            
        } catch (Exception $e) {
            error_log("Error al crear notificación desde plantilla: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crear notificación manualmente
     */
    public static function crear($usuario_id, $tipo, $titulo, $mensaje, $url_accion = null, $icono = null, $color = null, $datos_adicionales = null) {
        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje, url_accion, icono, color, datos_adicionales) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $datos_json = $datos_adicionales ? json_encode($datos_adicionales) : null;
            
            $stmt->execute([
                $usuario_id,
                $tipo,
                $titulo,
                $mensaje,
                $url_accion,
                $icono,
                $color,
                $datos_json
            ]);
            
            return self::$pdo->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error al crear notificación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notificar validación de publicación
     */
    public static function notificarPublicacionValidada($usuario_id, $titulo_obra, $publicacion_id) {
        return self::crearDesdePlantilla('publicacion_validada', $usuario_id, [
            'titulo_obra' => $titulo_obra
        ]);
    }
    
    /**
     * Notificar rechazo de publicación
     */
    public static function notificarPublicacionRechazada($usuario_id, $titulo_obra, $motivo) {
        return self::crearDesdePlantilla('publicacion_rechazada', $usuario_id, [
            'titulo_obra' => $titulo_obra,
            'motivo' => $motivo
        ]);
    }
    
    /**
     * Notificar validación de cambio de perfil
     */
    public static function notificarCambioPerfilValidado($usuario_id) {
        return self::crearDesdePlantilla('cambio_perfil_validado', $usuario_id, []);
    }
    
    /**
     * Notificar rechazo de cambio de perfil
     */
    public static function notificarCambioPerfilRechazado($usuario_id, $motivo) {
        return self::crearDesdePlantilla('cambio_perfil_rechazado', $usuario_id, [
            'motivo' => $motivo
        ]);
    }
    
    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public static function obtenerNoLeidas($usuario_id, $limit = 10) {
        try {
            $stmt = self::$pdo->prepare("
                SELECT * FROM notificaciones 
                WHERE usuario_id = ? AND leida = FALSE 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$usuario_id, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener notificaciones: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar notificaciones no leídas
     */
    public static function contarNoLeidas($usuario_id) {
        try {
            $stmt = self::$pdo->prepare("SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leida = FALSE");
            $stmt->execute([$usuario_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total']);
            
        } catch (Exception $e) {
            error_log("Error al contar notificaciones: " . $e->getMessage());
            return 0;
        }
    }
}
