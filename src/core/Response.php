<?php
declare(strict_types=1);

namespace Tayo\Core;

final class Response
{
    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function error(string $code, string $message, int $status = 400, array $fields = []): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        $error = ['code' => $code, 'message' => $message];
        if ($fields !== []) {
            $error['fields'] = $fields;
        }
        echo json_encode(['success' => false, 'error' => $error], JSON_UNESCAPED_SLASHES);
        exit;
    }
}
