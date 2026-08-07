<?php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

use Tayo\Core\Csrf;
use Tayo\Core\Response;
use Tayo\Services\RegistrationService;

tayo_require_method('POST');
Csrf::guard();

$input = tayo_json_body();
$result = RegistrationService::step1($input);

if ($result['errors'] !== []) {
    Response::error('VALIDATION_ERROR', 'Please fix the highlighted fields.', 422, $result['errors']);
}

Response::json(['step' => 1, 'next' => 2]);
