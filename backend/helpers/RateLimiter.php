<?php

/**
 * Rate Limiter - Previene abuso de APIs
 * Usa sistema de archivos temporales
 */
class RateLimiter
{
    private string $storageDir;
    private int $maxRequests;
    private int $windowSeconds;
    private bool $enabled;

    public function __construct()
    {
        $this->storageDir = sys_get_temp_dir() . '/idcultural_ratelimit';
        $this->maxRequests = (int)($_ENV['RATE_LIMIT_MAX_REQUESTS'] ?? 100);
        $this->windowSeconds = (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 3600);
        $this->enabled = filter_var(
            $_ENV['RATE_LIMIT_ENABLED'] ?? true,
            FILTER_VALIDATE_BOOLEAN
        );

        if (!is_dir($this->storageDir)) {
            @mkdir($this->storageDir, 0777, true);
        }
    }

    /**
     * Verifica si el cliente excedió el límite
     * 
     * @param string|null $identifier IP o user_id
     * @return bool true si está permitido, false si excedió
     */
    public function attempt(?string $identifier = null): bool
    {
        if (!$this->enabled) {
            return true;
        }

        $identifier = $identifier ?? $this->getClientIdentifier();
        $key = $this->getKey($identifier);
        $file = $this->getFilePath($key);

        // Limpiar archivos viejos (raramente)
        $this->cleanup();

        // Leer intentos actuales
        $attempts = $this->getAttempts($file);
        $now = time();

        // Filtrar solo intentos dentro de la ventana
        $validAttempts = array_filter($attempts, function($timestamp) use ($now) {
            return ($now - $timestamp) < $this->windowSeconds;
        });

        // Verificar límite
        if (count($validAttempts) >= $this->maxRequests) {
            $this->logBlocked($identifier, count($validAttempts));
            return false;
        }

        // Registrar nuevo intento
        $validAttempts[] = $now;
        @file_put_contents($file, implode("\n", $validAttempts));

        return true;
    }

    /**
     * Envía respuesta HTTP 429 y termina ejecución
     */
    public function tooManyRequests(): void
    {
        http_response_code(429);
        header('Content-Type: application/json');
        header("Retry-After: {$this->windowSeconds}");
        
        echo json_encode([
            'error' => 'Too Many Requests',
            'message' => 'Has excedido el límite de solicitudes. Intenta nuevamente más tarde.',
            'retry_after' => $this->windowSeconds
        ]);
        
        exit;
    }

    /**
     * Middleware helper para APIs
     */
    public function check(?string $identifier = null): void
    {
        if (!$this->attempt($identifier)) {
            $this->tooManyRequests();
        }
    }

    /**
     * Obtiene identificador del cliente (IP + User Agent)
     */
    private function getClientIdentifier(): string
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['HTTP_X_REAL_IP'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? 'unknown';

        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        return md5($ip . $userAgent);
    }

    /**
     * Genera key única para el identificador
     */
    private function getKey(string $identifier): string
    {
        return 'rl_' . hash('sha256', $identifier);
    }

    /**
     * Obtiene path del archivo de rate limit
     */
    private function getFilePath(string $key): string
    {
        return $this->storageDir . '/' . $key . '.txt';
    }

    /**
     * Lee intentos del archivo
     */
    private function getAttempts(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        $content = @file_get_contents($file);
        if (empty($content)) {
            return [];
        }

        return array_map('intval', explode("\n", trim($content)));
    }

    /**
     * Limpia archivos viejos (más de 24hs)
     */
    private function cleanup(): void
    {
        // Ejecutar limpieza solo 1% de las veces
        if (rand(1, 100) > 1) {
            return;
        }

        $files = @glob($this->storageDir . '/rl_*.txt') ?: [];
        $now = time();
        $maxAge = 86400; // 24 horas

        foreach ($files as $file) {
            if (($now - @filemtime($file)) > $maxAge) {
                @unlink($file);
            }
        }
    }

    /**
     * Log de bloqueos
     */
    private function logBlocked(string $identifier, int $attempts): void
    {
        $logFile = $this->storageDir . '/blocked.log';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $uri = $_SERVER['REQUEST_URI'] ?? 'unknown';
        
        $entry = sprintf(
            "[%s] BLOCKED: %s | IP: %s | URI: %s | Attempts: %d\n",
            date('Y-m-d H:i:s'),
            $identifier,
            $ip,
            $uri,
            $attempts
        );

        @file_put_contents($logFile, $entry, FILE_APPEND);
    }

    /**
     * Reinicia el contador para un identificador
     */
    public function reset(?string $identifier = null): void
    {
        $identifier = $identifier ?? $this->getClientIdentifier();
        $key = $this->getKey($identifier);
        $file = $this->getFilePath($key);

        if (file_exists($file)) {
            @unlink($file);
        }
    }

    /**
     * Obtiene info de rate limit para el cliente
     */
    public function getInfo(?string $identifier = null): array
    {
        if (!$this->enabled) {
            return [
                'enabled' => false,
                'limit' => $this->maxRequests,
                'remaining' => $this->maxRequests,
                'reset' => 0
            ];
        }

        $identifier = $identifier ?? $this->getClientIdentifier();
        $key = $this->getKey($identifier);
        $file = $this->getFilePath($key);
        
        $attempts = $this->getAttempts($file);
        $now = time();

        $validAttempts = array_filter($attempts, function($timestamp) use ($now) {
            return ($now - $timestamp) < $this->windowSeconds;
        });

        $remaining = max(0, $this->maxRequests - count($validAttempts));
        $oldestAttempt = !empty($validAttempts) ? min($validAttempts) : $now;
        $resetTime = $oldestAttempt + $this->windowSeconds;

        return [
            'enabled' => true,
            'limit' => $this->maxRequests,
            'remaining' => $remaining,
            'reset' => $resetTime,
            'reset_in' => max(0, $resetTime - $now)
        ];
    }
}
