<?php
/**
 * Analytics Helper - ID Cultural
 * Sistema de analytics personalizado con integración a Google Analytics
 */

class Analytics {
    private static $conn;
    
    public static function init($connection) {
        self::$conn = $connection;
        self::createTablesIfNotExist();
    }
    
    /**
     * Crear tablas de analytics si no existen
     */
    private static function createTablesIfNotExist() {
        $queries = [
            // Tabla de visitas
            "CREATE TABLE IF NOT EXISTS analytics_visitas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NULL,
                pagina VARCHAR(255) NOT NULL,
                ip_address VARCHAR(45),
                user_agent TEXT,
                referrer VARCHAR(500),
                duracion_segundos INT DEFAULT 0,
                fecha_visita TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_pagina (pagina),
                INDEX idx_fecha (fecha_visita),
                INDEX idx_usuario (usuario_id)
            )",
            
            // Tabla de eventos
            "CREATE TABLE IF NOT EXISTS analytics_eventos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NULL,
                categoria VARCHAR(100) NOT NULL,
                accion VARCHAR(100) NOT NULL,
                etiqueta VARCHAR(255),
                valor INT,
                fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_categoria (categoria),
                INDEX idx_accion (accion),
                INDEX idx_fecha (fecha_evento)
            )",
            
            // Tabla de búsquedas
            "CREATE TABLE IF NOT EXISTS analytics_busquedas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                termino_busqueda VARCHAR(255) NOT NULL,
                resultados_encontrados INT DEFAULT 0,
                usuario_id INT NULL,
                fecha_busqueda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_termino (termino_busqueda),
                INDEX idx_fecha (fecha_busqueda)
            )"
        ];
        
        foreach ($queries as $query) {
            self::$conn->query($query);
        }
    }
    
    /**
     * Registrar visita a página
     */
    public static function trackPageView($pagina, $userId = null, $duracion = 0) {
        $ip = self::getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';
        
        $query = "INSERT INTO analytics_visitas (usuario_id, pagina, ip_address, user_agent, referrer, duracion_segundos) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('issssi', $userId, $pagina, $ip, $userAgent, $referrer, $duracion);
        return $stmt->execute();
    }
    
    /**
     * Registrar evento
     */
    public static function trackEvent($categoria, $accion, $etiqueta = null, $valor = null, $userId = null) {
        $query = "INSERT INTO analytics_eventos (usuario_id, categoria, accion, etiqueta, valor) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('isssi', $userId, $categoria, $accion, $etiqueta, $valor);
        return $stmt->execute();
    }
    
    /**
     * Registrar búsqueda
     */
    public static function trackSearch($termino, $resultados, $userId = null) {
        $query = "INSERT INTO analytics_busquedas (termino_busqueda, resultados_encontrados, usuario_id) 
                  VALUES (?, ?, ?)";
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('sii', $termino, $resultados, $userId);
        return $stmt->execute();
    }
    
    /**
     * Obtener estadísticas de visitas
     */
    public static function getVisitStats($fechaInicio = null, $fechaFin = null) {
        $where = "";
        if ($fechaInicio && $fechaFin) {
            $where = "WHERE fecha_visita BETWEEN '$fechaInicio' AND '$fechaFin'";
        }
        
        $query = "SELECT 
                    COUNT(*) as total_visitas,
                    COUNT(DISTINCT ip_address) as visitantes_unicos,
                    COUNT(DISTINCT usuario_id) as usuarios_registrados,
                    AVG(duracion_segundos) as duracion_promedio,
                    DATE(fecha_visita) as fecha
                  FROM analytics_visitas $where
                  GROUP BY DATE(fecha_visita)
                  ORDER BY fecha DESC
                  LIMIT 30";
        
        $result = self::$conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener páginas más visitadas
     */
    public static function getTopPages($limit = 10) {
        $query = "SELECT 
                    pagina,
                    COUNT(*) as visitas,
                    COUNT(DISTINCT ip_address) as visitantes_unicos,
                    AVG(duracion_segundos) as duracion_promedio
                  FROM analytics_visitas
                  WHERE fecha_visita >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY pagina
                  ORDER BY visitas DESC
                  LIMIT ?";
        
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener eventos más frecuentes
     */
    public static function getTopEvents($limit = 10) {
        $query = "SELECT 
                    categoria,
                    accion,
                    COUNT(*) as cantidad,
                    SUM(valor) as valor_total
                  FROM analytics_eventos
                  WHERE fecha_evento >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY categoria, accion
                  ORDER BY cantidad DESC
                  LIMIT ?";
        
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener búsquedas más populares
     */
    public static function getTopSearches($limit = 10) {
        $query = "SELECT 
                    termino_busqueda,
                    COUNT(*) as cantidad_busquedas,
                    AVG(resultados_encontrados) as promedio_resultados
                  FROM analytics_busquedas
                  WHERE fecha_busqueda >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY termino_busqueda
                  ORDER BY cantidad_busquedas DESC
                  LIMIT ?";
        
        $stmt = self::$conn->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener dashboard completo
     */
    public static function getDashboard() {
        return [
            'resumen' => self::getResumenGeneral(),
            'visitas_diarias' => self::getVisitStats(),
            'paginas_populares' => self::getTopPages(10),
            'eventos_populares' => self::getTopEvents(10),
            'busquedas_populares' => self::getTopSearches(10),
            'usuarios_activos' => self::getUsuariosActivos()
        ];
    }
    
    /**
     * Resumen general
     */
    private static function getResumenGeneral() {
        $queries = [
            'total_visitas_hoy' => "SELECT COUNT(*) as total FROM analytics_visitas WHERE DATE(fecha_visita) = CURDATE()",
            'total_visitas_mes' => "SELECT COUNT(*) as total FROM analytics_visitas WHERE MONTH(fecha_visita) = MONTH(CURDATE())",
            'total_eventos_hoy' => "SELECT COUNT(*) as total FROM analytics_eventos WHERE DATE(fecha_evento) = CURDATE()",
            'total_busquedas_hoy' => "SELECT COUNT(*) as total FROM analytics_busquedas WHERE DATE(fecha_busqueda) = CURDATE()"
        ];
        
        $resumen = [];
        foreach ($queries as $key => $query) {
            $result = self::$conn->query($query);
            $resumen[$key] = $result->fetch_assoc()['total'];
        }
        
        return $resumen;
    }
    
    /**
     * Usuarios más activos
     */
    private static function getUsuariosActivos() {
        $query = "SELECT 
                    u.id,
                    u.nombre,
                    u.email,
                    COUNT(v.id) as visitas,
                    COUNT(e.id) as eventos
                  FROM users u
                  LEFT JOIN analytics_visitas v ON u.id = v.usuario_id
                  LEFT JOIN analytics_eventos e ON u.id = e.usuario_id
                  WHERE v.fecha_visita >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                     OR e.fecha_evento >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  GROUP BY u.id
                  ORDER BY visitas DESC, eventos DESC
                  LIMIT 10";
        
        $result = self::$conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener IP del cliente
     */
    private static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }
    
    /**
     * Generar código de Google Analytics
     */
    public static function getGoogleAnalyticsCode($trackingId) {
        return "
<!-- Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id={$trackingId}'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$trackingId}');
</script>
<!-- End Google Analytics -->
        ";
    }
}
