<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Response;
use Tayo\Models\User;

tayo_require_method('GET');

$username = trim((string) ($_GET['username'] ?? ''));
if (!preg_match('/^[a-zA-Z0-9_.]{4,30}$/', $username)) {
    Response::json(['available' => false, 'reason' => 'invalid_format']);
}

Response::json(['available' => !User::usernameExists($username)]);
