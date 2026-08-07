<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/config/config.php';
require dirname(__DIR__) . '/src/core/Session.php';
require dirname(__DIR__) . '/src/core/Csrf.php';
require dirname(__DIR__) . '/src/core/Response.php';
require dirname(__DIR__) . '/src/core/Database.php';
require dirname(__DIR__) . '/src/core/RateLimiter.php';
require dirname(__DIR__) . '/src/models/User.php';
require dirname(__DIR__) . '/src/services/AuthService.php';

use Tayo\Core\Session;
use Tayo\Core\Csrf;
use Tayo\Services\AuthService;

Session::start();
$user = AuthService::currentUser();
if (!$user) {
    header('Location: login.php');
    exit;
}
$csrfToken = Csrf::token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | TAYO-TECH</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a href="index.php" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
      <img src="assets/img/logo.png" alt="TAYO-TECH" class="brand-logo">
    </a>
    <div class="header-actions">
      <span style="font-weight:600;color:var(--ink-soft);">Hi, <?= htmlspecialchars($user['first_name']) ?></span>
      <form action="logout.php" method="post" style="display:inline;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="btn btn-outline">Log out</button>
      </form>
    </div>
  </div>
</header>

<section class="content-grid">
  <div class="container">
    <div class="panel" style="margin-top:32px;">
      <div class="panel-head"><h2>Welcome back, <?= htmlspecialchars($user['first_name']) ?> 👋</h2></div>
      <p style="color:var(--ink-soft);padding:0 4px 20px;">
        Your account status is <strong><?= htmlspecialchars(str_replace('_', ' ', $user['status'])) ?></strong>.
        This is a placeholder dashboard — wire up <code>/api/v1/dashboard/summary</code> and the
        opportunities/events/trainings endpoints from <code>docs/API.md</code> to bring it to life.
      </p>
    </div>
  </div>
</section>

</body>
</html>
