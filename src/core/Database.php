<?php
declare(strict_types=1);

namespace Tayo\Core;

use PDO;
use PDOException;

/**
 * Thin PDO singleton. Every Model/Service pulls its connection from here —
 * no ORM for the MVP, prepared statements are enough and keep the stack
 * inspectable for a small team.
 */
final class Database
{
    private static ?PDO $instance = null;

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__) . '/config/config.php';
            $db = $config['db'];

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $db['host'],
                $db['port'],
                $db['name']
            );

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('[DB CONNECTION ERROR] ' . $e->getMessage());
                Response::error('SERVER_ERROR', 'Unable to reach the database.', 500);
            }
        }

        return self::$instance;
    }
}
