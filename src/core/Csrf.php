<?php
declare(strict_types=1);

namespace Tayo\Core;

final class Csrf
{
    public static function token(): string
    {
        Session::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verify(?string $submitted): bool
    {
        Session::start();
        $expected = $_SESSION['csrf_token'] ?? null;
        return is_string($submitted) && is_string($expected) && hash_equals($expected, $submitted);
    }

    /** Reads token from X-CSRF-Token header or `csrf_token` body field, then dies with 403 if invalid. */
    public static function guard(): void
    {
        $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        $body = $_POST['csrf_token'] ?? null;
        if (!self::verify($header ?? $body)) {
            Response::error('CSRF_MISMATCH', 'Your session has expired. Please refresh and try again.', 403);
        }
    }
}
