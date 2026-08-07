<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Csrf;
use Tayo\Core\Response;
use Tayo\Services\RegistrationService;

tayo_require_method('POST');
Csrf::guard();

$input = tayo_json_body();
$result = RegistrationService::step2($input);

if ($result['errors'] !== []) {
    $status = isset($result['errors']['_step']) ? 409 : 422;
    Response::error('VALIDATION_ERROR', 'Please fix the highlighted fields.', $status, $result['errors']);
}

Response::json(['step' => 2, 'next' => 3]);
