<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Csrf;
use Tayo\Core\Response;
use Tayo\Services\AuthService;

tayo_require_method('POST');
Csrf::guard();

AuthService::logout();
Response::json(['loggedOut' => true]);
