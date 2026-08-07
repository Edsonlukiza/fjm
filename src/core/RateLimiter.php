<?php
declare(strict_types=1);

namespace Tayo\Core;

final class RateLimiter
{
    public static function tooManyAttempts(string $identifier, string $ip): bool
    {
        $config = require dirname(__DIR__) . '/config/config.php';
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE (identifier = :identifier OR ip_address = :ip)
               AND succeeded = false
               AND attempted_at > now() - (:minutes || \' minutes\')::interval'
        );
        $stmt->execute([
            'identifier' => $identifier,
            'ip' => $ip,
            'minutes' => $config['login']['lockout_minutes'],
        ]);

        return (int) $stmt->fetchColumn() >= $config['login']['max_attempts'];
    }

    public static function record(string $identifier, string $ip, bool $succeeded): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO login_attempts (identifier, ip_address, succeeded) VALUES (:identifier, :ip, :succeeded)'
        );
        $stmt->execute(['identifier' => $identifier, 'ip' => $ip, 'succeeded' => $succeeded]);
    }
}
