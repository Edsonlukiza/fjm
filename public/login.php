<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/core/Session.php';
require dirname(__DIR__) . '/src/core/Csrf.php';
use Tayo\Core\Session;
use Tayo\Core\Csrf;
Session::start();

// Already logged in? Skip straight to the dashboard.
if (Session::userId()) {
    header('Location: dashboard.php');
    exit;
}

$csrfToken = Csrf::token();
$justRegistered = isset($_GET['registered']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | TAYO-TECH</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-page">

  <aside class="auth-side">
    <div class="brand">
      <img src="assets/img/logo.png" alt="TAYO-TECH">
      <span>TAYO-TECH</span>
    </div>
    <div>
      <h2>Welcome back.</h2>
      <p>Log in to continue applying for jobs, tracking your trainings, and connecting with mentors.</p>
      <ul class="auth-points">
        <li><span class="dot">✓</span> Pick up where you left off</li>
        <li><span class="dot">✓</span> See new opportunities matched to your profile</li>
        <li><span class="dot">✓</span> Manage your applications in one place</li>
      </ul>
    </div>
    <p style="color:#94a3b8;font-size:0.8rem;">&copy; <?= date('Y') ?> TAYO-TECH — Tanzania Youth-Tech Forum</p>
  </aside>

  <main class="auth-main">
    <div class="auth-topbar">
      <a href="index.php"><img src="assets/img/logo.png" alt="TAYO-TECH">TAYO-TECH</a>
      <a href="register.php" class="auth-topbar-link">New here? Create an account</a>
    </div>

    <div class="auth-card" style="max-width:440px;">
      <h1>Log in to your account</h1>
      <p class="auth-sub">Enter your username or email and password.</p>

      <div id="formBanner">
        <?php if ($justRegistered): ?>
          <div class="form-banner success">Account created! Please log in to continue.</div>
        <?php endif; ?>
      </div>

      <form id="loginForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="form-grid" style="grid-template-columns:1fr;">
          <div class="field">
            <label for="identifier">Username or Email</label>
            <input id="identifier" name="identifier" required autocomplete="username">
            <div class="field-error" data-error-for="identifier"></div>
          </div>
          <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <div class="field-error" data-error-for="password"></div>
          </div>
        </div>

        <div class="wizard-actions" style="margin-top:20px;">
          <a href="#" class="auth-topbar-link" style="font-size:0.85rem;">Forgot password?</a>
          <span class="spacer"></span>
          <button type="submit" class="btn btn-primary" id="loginBtn">Log In</button>
        </div>
      </form>

      <p class="auth-footer-link">Don't have an account? <a href="register.php">Register now</a></p>
    </div>
  </main>
</div>

<script src="assets/js/login.js"></script>
</body>
</html>
