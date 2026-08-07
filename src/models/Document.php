<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class Document
{
    public static function create(
        PDO $pdo,
        string $userId,
        string $docType,
        string $storagePath,
        string $originalFilename,
        string $mimeType,
        int $sizeBytes
    ): void {
        $stmt = $pdo->prepare(
            'INSERT INTO documents (user_id, doc_type, storage_path, original_filename, mime_type, size_bytes)
             VALUES (:user_id, :doc_type, :storage_path, :original_filename, :mime_type, :size_bytes)'
        );
        $stmt->execute([
            'user_id' => $userId,
            'doc_type' => $docType,
            'storage_path' => $storagePath,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'size_bytes' => $sizeBytes,
        ]);
    }
}
