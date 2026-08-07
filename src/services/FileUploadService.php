<?php
declare(strict_types=1);

namespace Tayo\Services;

final class FileUploadService
{
    private const EXT_BY_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];

    /**
     * Validates and moves an uploaded file into storage/uploads/{userId}/,
     * outside the web root. Returns [storagePath, originalName, mime, size]
     * or throws \RuntimeException with a user-facing message on failure.
     *
     * @return array{path:string, name:string, mime:string, size:int}
     */
    public static function store(array $file, string $userId, array $allowedMime, int $maxBytes): array
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload failed. Please try again.');
        }
        if ($file['size'] > $maxBytes) {
            throw new \RuntimeException('File is too large (max ' . round($maxBytes / 1024 / 1024, 1) . 'MB).');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($realMime, $allowedMime, true)) {
            throw new \RuntimeException('Unsupported file type: ' . $realMime);
        }

        $ext = self::EXT_BY_MIME[$realMime] ?? 'bin';
        $baseDir = dirname(__DIR__, 2) . '/storage/uploads/' . $userId;
        if (!is_dir($baseDir) && !mkdir($baseDir, 0750, true) && !is_dir($baseDir)) {
            throw new \RuntimeException('Could not prepare storage directory.');
        }

        $randomName = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = $baseDir . '/' . $randomName;

        if (!is_uploaded_file($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Could not save uploaded file.');
        }

        return [
            'path' => 'storage/uploads/' . $userId . '/' . $randomName,
            'name' => basename((string) $file['name']),
            'mime' => $realMime,
            'size' => (int) $file['size'],
        ];
    }
}
