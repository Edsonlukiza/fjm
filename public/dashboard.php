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

function icon(string $name, string $class = ''): string
{
    $icons = [
        'briefcase' => '<path d="M4 7h16v11H4z"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M4 12h16"/>',
        'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M3 10h18"/>',
        'book'      => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15.5A2.5 2.5 0 0 1 17.5 21H4z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"/>',
        'mentor'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>',
        'trophy'    => '<path d="M8 4h8v5a4 4 0 0 1-8 0V4z"/><path d="M6 4H4v2a4 4 0 0 0 4 4"/><path d="M18 4h2v2a4 4 0 0 1-4 4"/><path d="M12 13v3"/><path d="M9 20h6"/><path d="M10 16h4l1 4H9z"/>',
    ];
    $body = $icons[$name] ?? '';
    return "<svg class=\"icon {$class}\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">{$body}</svg>";
}

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
      <div class="panel-head">
        <h2>Welcome back, <?= htmlspecialchars($user['first_name']) ?></h2>
        <span class="dashboard-status">Status: <?= htmlspecialchars(str_replace('_', ' ', $user['status'])) ?></span>
      </div>
      <p style="color:var(--ink-soft);padding:0 4px 20px;">
        This dashboard shows your current activity summary and quick actions. Connect it with the API summary endpoint and the events/opportunities endpoints from <code>docs/API.md</code> to show live data.
      </p>

      <div class="dashboard-actions">
        <div class="panel dashboard-card">
          <div>
            <div class="dashboard-card-icon"><?= icon('briefcase') ?></div>
            <p class="dashboard-card-title">Open Applications</p>
            <p class="dashboard-card-value">3</p>
            <p class="dashboard-card-copy">Applications currently in review.</p>
          </div>
        </div>

        <div class="panel dashboard-card">
          <div>
            <div class="dashboard-card-icon"><?= icon('calendar') ?></div>
            <p class="dashboard-card-title">Upcoming Events</p>
            <p class="dashboard-card-value">2</p>
            <p class="dashboard-card-copy">Events and workshops you can still join.</p>
          </div>
        </div>

        <div class="panel dashboard-card">
          <div>
            <div class="dashboard-card-icon"><?= icon('book') ?></div>
            <p class="dashboard-card-title">New Courses</p>
            <p class="dashboard-card-value">5</p>
            <p class="dashboard-card-copy">Courses recommended for your profile.</p>
          </div>
        </div>
      </div>

      <div class="panel" style="margin-top:24px;">
        <div class="panel-head"><h2>Next steps</h2></div>
        <div class="dashboard-next-step">
          <?= icon('mentor') ?>
          <div>
            <p class="dashboard-next-step-title">Complete your profile</p>
            <p class="dashboard-next-step-copy">Add your education, skills, and documents so opportunities match your experience.</p>
          </div>
        </div>
        <div class="dashboard-next-step">
          <?= icon('trophy') ?>
          <div>
            <p class="dashboard-next-step-title">Review new opportunities</p>
            <p class="dashboard-next-step-copy">Check the latest job postings and scholarship applications available now.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</body>
</html>
