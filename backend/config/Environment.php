<?php

namespace Backend\Config;

class Environment
{
    private static bool $loaded = false;
    private static array $required = [
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
        'APP_KEY',
        'JWT_SECRET'
    ];

    /**
     * Carga variables de entorno desde .env
     */
    public static function load(string $path = null): void
    {
        if (self::$loaded) {
            return;
        }

        $envFile = $path ?? dirname(__DIR__, 2) . '/.env';

        if (!file_exists($envFile)) {
            throw new \RuntimeException(
                ".env file not found at: {$envFile}\n" .
                "Copy .env.example to .env and configure it."
            );
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes
                $value = trim($value, '"\'');

                // Set in $_ENV and putenv
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }

        self::validate();
        self::$loaded = true;
    }

    /**
     * Valida que existan las variables requeridas
     */
    private static function validate(): void
    {
        $missing = [];

        foreach (self::$required as $key) {
            if (empty($_ENV[$key])) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new \RuntimeException(
                "Missing required environment variables:\n" .
                implode("\n", array_map(fn($k) => "  - {$k}", $missing))
            );
        }
    }

    /**
     * Obtiene una variable de entorno
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Verifica si estamos en modo debug
     */
    public static function isDebug(): bool
    {
        return filter_var(
            self::get('APP_DEBUG', false),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    /**
     * Obtiene el entorno actual
     */
    public static function getEnv(): string
    {
        return self::get('APP_ENV', 'production');
    }

    /**
     * Verifica si estamos en producción
     */
    public static function isProduction(): bool
    {
        return self::getEnv() === 'production';
    }

    /**
     * Verifica si estamos en desarrollo
     */
    public static function isDevelopment(): bool
    {
        return self::getEnv() === 'development';
    }
}
