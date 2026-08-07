<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Csrf;
use Tayo\Core\Response;
use Tayo\Services\RegistrationService;

tayo_require_method('POST');
Csrf::guard(); // reads csrf_token from $_POST since this is multipart, not JSON

$config = require dirname(__DIR__, 4) . '/src/config/config.php';

$result = RegistrationService::step3($_POST, $_FILES, $config);

if ($result['errors'] !== []) {
    $status = isset($result['errors']['_step']) ? 409 : (isset($result['errors']['_server']) ? 500 : 422);
    Response::error('VALIDATION_ERROR', 'We could not complete your registration.', $status, $result['errors']);
}

Response::json(['user_id' => $result['user_id'], 'status' => 'pending_verification'], 201);
