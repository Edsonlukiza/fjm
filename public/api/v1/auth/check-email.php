<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Response;
use Tayo\Models\User;

tayo_require_method('GET');

$email = trim((string) ($_GET['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Response::json(['available' => false, 'reason' => 'invalid_format']);
}

Response::json(['available' => !User::emailExists($email)]);
