(() => {
  const form = document.getElementById('loginForm');
  const banner = document.getElementById('formBanner');
  const btn = document.getElementById('loginBtn');
  const csrfToken = form.querySelector('input[name="csrf_token"]').value;

  function clearErrors() {
    form.querySelectorAll('.field-error').forEach(el => (el.textContent = ''));
    form.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));
  }

  function showBanner(message, type = 'error') {
    banner.innerHTML = `<div class="form-banner ${type}">${message}</div>`;
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    btn.disabled = true;
    btn.textContent = 'Logging in…';

    const payload = {
      identifier: document.getElementById('identifier').value.trim(),
      password: document.getElementById('password').value,
    };

    try {
      const res = await fetch('api/v1/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify(payload),
        credentials: 'same-origin',
      });
      const body = await res.json().catch(() => ({}));

      if (!res.ok) {
        showBanner(body.error?.message || 'Could not log in. Please try again.', 'error');
        btn.disabled = false;
        btn.textContent = 'Log In';
        return;
      }

      showBanner('Welcome back! Redirecting…', 'success');
      window.location.href = 'dashboard.php';
    } catch (err) {
      showBanner('Network error — please check your connection and try again.', 'error');
      btn.disabled = false;
      btn.textContent = 'Log In';
    }
  });
})();
