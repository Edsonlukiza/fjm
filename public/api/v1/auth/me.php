<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Response;
use Tayo\Services\AuthService;

tayo_require_method('GET');

$user = AuthService::currentUser();
if (!$user) {
    Response::error('UNAUTHENTICATED', 'No active session.', 401);
}

Response::json(['user' => $user]);
