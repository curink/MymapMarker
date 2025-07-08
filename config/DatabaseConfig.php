<?php
namespace Config;

class DatabaseConfig
{
    public static function get(string $key, $default = null): mixed
    {
        $env = self::loadEnv();
        return $env[$key] ?? $default;
    }

    private static function loadEnv(): array
    {
        static $env = null;

        if ($env !== null) return $env;

        $envFile = __DIR__ . '/../.env';
        $env = [];

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                [$key, $value] = explode('=', $line, 2);
                $env[trim($key)] = trim($value);
            }
        }

        return $env;
    }
}