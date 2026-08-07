<?php
declare(strict_types=1);

namespace Tayo\Services;

use Tayo\Core\Database;
use Tayo\Core\RateLimiter;
use Tayo\Core\Session;
use Tayo\Models\User;

final class AuthService
{
    /** @return array{errors: array, user?: array} */
    public static function login(string $identifier, string $password, string $ip): array
    {
        $identifier = trim($identifier);
        if ($identifier === '' || $password === '') {
            return ['errors' => ['_form' => 'Please enter your username/email and password.']];
        }

        if (RateLimiter::tooManyAttempts($identifier, $ip)) {
            return ['errors' => ['_form' => 'Too many failed attempts. Please try again later.'], 'locked' => true];
        }

        $user = User::findByIdentifier($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            RateLimiter::record($identifier, $ip, false);
            return ['errors' => ['_form' => 'Incorrect username/email or password.']];
        }

        if ($user['status'] === 'suspended' || $user['status'] === 'deactivated') {
            RateLimiter::record($identifier, $ip, false);
            return ['errors' => ['_form' => 'This account is not active. Please contact support.']];
        }

        RateLimiter::record($identifier, $ip, true);

        Session::start();
        session_regenerate_id(true); // prevent session fixation
        Session::put('user_id', $user['id']);
        Session::put('user_role', $user['role']);

        $pdo = Database::connection();
        $audit = $pdo->prepare(
            "INSERT INTO audit_log (user_id, action, metadata, ip_address) VALUES (:uid, 'user.login', '{}', :ip)"
        );
        $audit->execute(['uid' => $user['id'], 'ip' => $ip]);

        return [
            'errors' => [],
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'role' => $user['role'],
            ],
        ];
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function currentUser(): ?array
    {
        $id = Session::userId();
        if (!$id) {
            return null;
        }
        $user = User::findById($id);
        if (!$user) {
            return null;
        }
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'status' => $user['status'],
        ];
    }
}
