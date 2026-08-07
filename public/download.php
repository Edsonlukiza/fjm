<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/config/config.php';
require dirname(__DIR__) . '/src/core/Session.php';
require dirname(__DIR__) . '/src/core/Response.php';
require dirname(__DIR__) . '/src/core/Database.php';

use Tayo\Core\Database;
use Tayo\Core\Session;

/**
 * Serves a single document by id, only to the user who owns it (or an admin).
 * Files live in storage/uploads (outside the web root) so they are never
 * directly reachable by URL — this script is the only door in.
 */
Session::start();
$userId = Session::userId();
if (!$userId) {
    http_response_code(401);
    echo 'Please log in to view this file.';
    exit;
}

$docId = $_GET['id'] ?? '';
if (!preg_match('/^[0-9a-fA-F-]{36}$/', (string) $docId)) {
    http_response_code(400);
    echo 'Invalid document id.';
    exit;
}

$pdo = Database::connection();
$stmt = $pdo->prepare('SELECT * FROM documents WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $docId]);
$doc = $stmt->fetch();

if (!$doc) {
    http_response_code(404);
    echo 'File not found.';
    exit;
}

$userStmt = $pdo->prepare('SELECT role FROM users WHERE id = :id');
$userStmt->execute(['id' => $userId]);
$role = $userStmt->fetchColumn();

if ($doc['user_id'] !== $userId && $role !== 'admin') {
    http_response_code(403);
    echo 'You do not have permission to view this file.';
    exit;
}

$path = dirname(__DIR__) . '/' . $doc['storage_path'];
if (!is_file($path)) {
    http_response_code(404);
    echo 'File is missing from storage.';
    exit;
}

header('Content-Type: ' . $doc['mime_type']);
header('Content-Disposition: inline; filename="' . basename((string) $doc['original_filename']) . '"');
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
readfile($path);
exit;
