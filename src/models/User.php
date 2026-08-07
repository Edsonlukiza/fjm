<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;
use Tayo\Core\Database;

final class User
{
    public static function findByIdentifier(string $identifier): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT * FROM users WHERE (email = :id OR username = :id) AND deleted_at IS NULL LIMIT 1'
        );
        $stmt->execute(['id' => $identifier]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(string $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function emailExists(string $email): bool
    {
        return self::exists('email', $email);
    }

    public static function usernameExists(string $username): bool
    {
        return self::exists('username', $username);
    }

    public static function phoneExists(string $phone): bool
    {
        return self::exists('mobile_phone', $phone);
    }

    private static function exists(string $column, string $value): bool
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT 1 FROM users WHERE {$column} = :value AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['value' => $value]);
        return (bool) $stmt->fetchColumn();
    }

    /** @return string new user UUID */
    public static function create(PDO $pdo, array $personal, array $contact, array $account): string
    {
        $stmt = $pdo->prepare(
            'INSERT INTO users (
                first_name, middle_name, last_name, gender, date_of_birth, nationality,
                national_id, passport_number, mobile_phone, alt_phone, email,
                username, password_hash, security_question, security_answer_hash,
                terms_accepted_at, privacy_accepted_at
            ) VALUES (
                :first_name, :middle_name, :last_name, :gender, :date_of_birth, :nationality,
                :national_id, :passport_number, :mobile_phone, :alt_phone, :email,
                :username, :password_hash, :security_question, :security_answer_hash,
                now(), now()
            ) RETURNING id'
        );

        $stmt->execute([
            'first_name' => $personal['first_name'],
            'middle_name' => $personal['middle_name'] ?: null,
            'last_name' => $personal['last_name'],
            'gender' => $personal['gender'],
            'date_of_birth' => $personal['date_of_birth'],
            'nationality' => $personal['nationality'],
            'national_id' => $personal['national_id'] ?: null,
            'passport_number' => $personal['passport_number'] ?: null,
            'mobile_phone' => $contact['mobile_phone'],
            'alt_phone' => $contact['alt_phone'] ?: null,
            'email' => $contact['email'],
            'username' => $account['username'],
            'password_hash' => password_hash($account['password'], PASSWORD_BCRYPT),
            'security_question' => $account['security_question'],
            'security_answer_hash' => password_hash(strtolower(trim($account['security_answer'])), PASSWORD_BCRYPT),
        ]);

        return (string) $stmt->fetchColumn();
    }
}
