<?php
declare(strict_types=1);

/**
 * Every endpoint under public/api/v1 requires this file first. It wires up
 * a tiny PSR-4-ish autoloader (no Composer needed for the MVP), starts the
 * session, and exposes helpers for reading JSON or multipart bodies.
 */

$root = dirname(__DIR__, 3);

spl_autoload_register(function (string $class) use ($root): void {
    // Tayo\Core\Foo -> src/core/Foo.php  (folders are lowercase on disk)
    if (!str_starts_with($class, 'Tayo\\')) {
        return;
    }
    $parts = explode('\\', substr($class, strlen('Tayo\\')));
    $className = array_pop($parts);
    $path = $root . '/src/' . strtolower(implode('/', $parts)) . '/' . $className . '.php';
    if (is_file($path)) {
        require $path;
    }
});

require $root . '/src/config/config.php';

use Tayo\Core\Response;
use Tayo\Core\Session;

$config = require $root . '/src/config/config.php';
if ($config['app']['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

set_exception_handler(function (\Throwable $e) use ($config) {
    error_log('[UNHANDLED] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    Response::error('SERVER_ERROR', $config['app']['debug'] ? $e->getMessage() : 'Something went wrong.', 500);
});

Session::start();

/** Reads a JSON request body into an assoc array (empty array on non-JSON). */
function tayo_json_body(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === '' || $raw === false) {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

/** True if the current request method matches, else sends 405. */
function tayo_require_method(string $method): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== $method) {
        Response::error('METHOD_NOT_ALLOWED', "This endpoint only accepts {$method}.", 405);
    }
}
