<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Csrf;
use Tayo\Core\Response;
use Tayo\Services\AuthService;

tayo_require_method('POST');
Csrf::guard();

$input = tayo_json_body();
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

$result = AuthService::login($input['identifier'] ?? '', $input['password'] ?? '', $ip);

if ($result['errors'] !== []) {
    $status = !empty($result['locked']) ? 423 : 401;
    Response::error(!empty($result['locked']) ? 'ACCOUNT_LOCKED' : 'INVALID_CREDENTIALS', $result['errors']['_form'], $status);
}

Response::json(['user' => $result['user']]);
