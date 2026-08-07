<?php
/**
 * Minimal .env loader — no Composer dependency required for the MVP.
 * Loads KEY=VALUE pairs from the project root .env into getenv()/$_ENV.
 */
declare(strict_types=1);

function tayo_load_env(string $path): void
{
    static $loaded = false;
    if ($loaded || !is_file($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if ($key !== '') {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
    $loaded = true;
}

tayo_load_env(dirname(__DIR__, 2) . '/.env');

function env(string $key, mixed $default = null): mixed
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return match (strtolower($value)) {
        'true', '(true)' => true,
        'false', '(false)' => false,
        'null', '(null)' => null,
        default => $value,
    };
}

return [
    'app' => [
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', false),
        'url' => env('APP_URL', 'http://localhost'),
    ],
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '5432'),
        'name' => env('DB_NAME', 'tayotech'),
        'user' => env('DB_USER', 'tayotech_app'),
        'password' => env('DB_PASSWORD', ''),
    ],
    'session' => [
        'name' => env('SESSION_NAME', 'tayo_session'),
        'lifetime' => (int) env('SESSION_LIFETIME', 7200),
    ],
    'upload' => [
        'max_bytes' => (int) env('UPLOAD_MAX_BYTES', 5 * 1024 * 1024),
        'allowed_mime' => explode(',', (string) env(
            'UPLOAD_ALLOWED_MIME',
            'image/jpeg,image/png,application/pdf'
        )),
    ],
    'captcha' => [
        'secret' => env('CAPTCHA_SECRET', ''),
    ],
    'login' => [
        'max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
        'lockout_minutes' => (int) env('LOGIN_LOCKOUT_MINUTES', 15),
    ],
];
