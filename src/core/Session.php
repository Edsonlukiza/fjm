<?php
declare(strict_types=1);

namespace Tayo\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $config = require dirname(__DIR__) . '/config/config.php';

        session_set_cookie_params([
            'lifetime' => $config['session']['lifetime'],
            'path' => '/',
            'secure' => ($config['app']['env'] === 'production'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_name($config['session']['name']);
        session_start();
    }

    public static function put(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function userId(): ?string
    {
        return self::get('user_id');
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
