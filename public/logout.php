<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/core/Session.php';
require dirname(__DIR__) . '/src/core/Csrf.php';
use Tayo\Core\Session;
use Tayo\Core\Csrf;

Session::start();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && Csrf::verify($_POST['csrf_token'] ?? null)) {
    Session::destroy();
}

header('Location: index.php');
exit;
