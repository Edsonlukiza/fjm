/**
 * TAYO-TECH — registration wizard
 * Client-side validation here is UX sugar only; the server (Validator.php)
 * is the source of truth and re-validates everything on every step.
 */
(() => {
  const form = document.getElementById('regForm');
  const steps = [...document.querySelectorAll('.wizard-step')];
  const stepItems = [...document.querySelectorAll('.step-item')];
  const banner = document.getElementById('formBanner');
  const submitBtn = document.getElementById('submitBtn');
  const csrfToken = form.querySelector('input[name="csrf_token"]').value;

  let current = 1;

  /* ---------- Step navigation ---------- */
  function showStep(n) {
    steps.forEach(s => { s.hidden = Number(s.dataset.step) !== n; });
    stepItems.forEach(item => {
      const stepNum = Number(item.dataset.step);
      item.classList.toggle('active', stepNum === n);
      item.classList.toggle('done', stepNum < n);
    });
    current = n;
    banner.innerHTML = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function clearErrors(scope) {
    scope.querySelectorAll('.field-error').forEach(el => (el.textContent = ''));
    scope.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));
  }

  function applyErrors(scope, errors) {
    Object.entries(errors).forEach(([field, message]) => {
      if (field.startsWith('_')) {
        showBanner(message, 'error');
        return;
      }
      const errEl = scope.querySelector(`[data-error-for="${field}"]`);
      const inputEl = scope.querySelector(`[name="${field}"]`);
      if (errEl) errEl.textContent = message;
      if (inputEl) inputEl.classList.add('has-error');
    });
  }

  function showBanner(message, type = 'error') {
    banner.innerHTML = `<div class="form-banner ${type}">${message}</div>`;
  }

  function setLoading(btn, loading) {
    btn.disabled = loading;
    btn.dataset.label = btn.dataset.label || btn.textContent;
    btn.textContent = loading ? 'Please wait…' : btn.dataset.label;
  }

  /* ---------- Collect step payloads ---------- */
  function collectStep1() {
    const f = new FormData(form);
    return {
      first_name: f.get('first_name')?.trim(),
      middle_name: f.get('middle_name')?.trim() || '',
      last_name: f.get('last_name')?.trim(),
      gender: f.get('gender'),
      date_of_birth: f.get('date_of_birth'),
      nationality: f.get('nationality')?.trim(),
      national_id: f.get('national_id')?.trim() || '',
      passport_number: f.get('passport_number')?.trim() || '',
      mobile_phone: f.get('mobile_phone')?.trim(),
      alt_phone: f.get('alt_phone')?.trim() || '',
      email: f.get('email')?.trim(),
      country: f.get('country')?.trim() || 'Tanzania',
      region: f.get('region')?.trim(),
      district: f.get('district')?.trim(),
      ward: f.get('ward')?.trim(),
      street_village: f.get('street_village')?.trim(),
      house_number: f.get('house_number')?.trim() || '',
      postal_address: f.get('postal_address')?.trim() || '',
      zip_code: f.get('zip_code')?.trim() || '',
    };
  }

  function collectStep2() {
    const f = new FormData(form);
    return {
      occupation: f.get('occupation')?.trim(),
      profession: f.get('profession')?.trim() || '',
      employment_status: f.get('employment_status') || '',
      organization_name: f.get('organization_name')?.trim() || '',
      job_title: f.get('job_title')?.trim() || '',
      years_experience: f.get('years_experience') || 0,
      education_level: f.get('education_level'),
      institution_name: f.get('institution_name')?.trim(),
      programme_course: f.get('programme_course')?.trim() || '',
      specialization: f.get('specialization')?.trim() || '',
      graduation_year: f.get('graduation_year') || '',
      certifications: (f.get('certifications') || '').split(',').map(s => s.trim()).filter(Boolean),
      technical_skills: f.get('technical_skills') || '',
      soft_skills: f.get('soft_skills') || '',
      languages_spoken: f.get('languages_spoken') || '',
      career_interests: f.get('career_interests') || '',
      preferred_job_category: f.get('preferred_job_category')?.trim() || '',
      is_business_owner: f.get('is_business_owner') || '0',
      business_name: f.get('business_name')?.trim() || '',
      business_sector: f.get('business_sector')?.trim() || '',
      business_registration_number: f.get('business_registration_number')?.trim() || '',
    };
  }

  async function postJson(url, payload) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
      body: JSON.stringify(payload),
      credentials: 'same-origin',
    });
    const body = await res.json().catch(() => ({}));
    return { ok: res.ok, status: res.status, body };
  }

  /* ---------- Step 1 → 2 ---------- */
  document.querySelector('[data-step="1"] [data-next]').addEventListener('click', async (e) => {
    const btn = e.currentTarget;
    const scope = steps[0];
    clearErrors(scope);
    setLoading(btn, true);

    const { ok, body } = await postJson('api/v1/auth/register-step1.php', collectStep1());
    setLoading(btn, false);

    if (!ok) {
      applyErrors(scope, body.error?.fields || {});
      if (!body.error?.fields) showBanner(body.error?.message || 'Please check your details.', 'error');
      return;
    }
    showStep(2);
  });

  /* ---------- Step 2 → 3 ---------- */
  document.querySelector('[data-step="2"] [data-next]').addEventListener('click', async (e) => {
    const btn = e.currentTarget;
    const scope = steps[1];
    clearErrors(scope);
    setLoading(btn, true);

    const { ok, body } = await postJson('api/v1/auth/register-step2.php', collectStep2());
    setLoading(btn, false);

    if (!ok) {
      applyErrors(scope, body.error?.fields || {});
      if (!body.error?.fields) showBanner(body.error?.message || 'Please check your details.', 'error');
      return;
    }
    showStep(3);
  });

  /* ---------- Back buttons ---------- */
  document.querySelectorAll('[data-back]').forEach(btn => {
    btn.addEventListener('click', () => showStep(Math.max(1, current - 1)));
  });

  /* ---------- Employment status pills ---------- */
  document.querySelectorAll('input[name="employment_status"]').forEach(radio => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('input[name="employment_status"]').forEach(r =>
        r.closest('.radio-pill').classList.toggle('checked', r.checked));
    });
  });

  /* ---------- Business owner toggle ---------- */
  const bizFields = ['businessNameField', 'businessSectorField', 'businessRegField'].map(id => document.getElementById(id));
  document.querySelectorAll('input[name="is_business_owner"]').forEach(radio => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('input[name="is_business_owner"]').forEach(r =>
        r.closest('.radio-pill').classList.toggle('checked', r.checked));
      const isOwner = radio.value === '1' && radio.checked;
      bizFields.forEach(f => { f.hidden = !isOwner; });
    });
  });

  /* ---------- File pickers: show chosen filename(s) ---------- */
  const fileLabels = {
    passport_photo: 'passportPhotoName',
    cv: 'cvName',
    'academic_certificates[]': 'academicName',
    'professional_certificates[]': 'professionalName',
  };
  Object.entries(fileLabels).forEach(([name, labelId]) => {
    const input = form.querySelector(`[name="${name}"]`);
    const label = document.getElementById(labelId);
    input?.addEventListener('change', () => {
      if (!input.files.length) return;
      label.textContent = input.files.length > 1
        ? `${input.files.length} files selected`
        : input.files[0].name;
    });
  });

  /* ---------- Password strength meter ---------- */
  const pwInput = document.getElementById('password');
  const pwBar = document.getElementById('pwStrengthBar');
  pwInput.addEventListener('input', () => {
    const v = pwInput.value;
    let score = 0;
    if (v.length >= 8) score += 1;
    if (/[A-Z]/.test(v)) score += 1;
    if (/[0-9]/.test(v)) score += 1;
    if (/[^A-Za-z0-9]/.test(v)) score += 1;
    const pct = (score / 4) * 100;
    const colors = ['#dc2626', '#dc2626', '#f59e0b', '#eab308', '#16a34a'];
    pwBar.style.width = pct + '%';
    pwBar.style.background = colors[score];
  });

  /* ---------- Dev-mode "captcha" stub: checking the box mints a token.
     In production, swap for a real hCaptcha/reCAPTCHA widget that fills
     #captcha_token via its callback. ---------- */
  document.getElementById('captcha_checkbox').addEventListener('change', (e) => {
    document.getElementById('captcha_token').value = e.target.checked
      ? 'dev-' + Math.random().toString(36).slice(2)
      : '';
  });

  /* ---------- Final submit ---------- */
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const scope = steps[2];
    clearErrors(scope);
    setLoading(submitBtn, true);

    const fd = new FormData(form); // includes all fields + files across the whole form
    try {
      const res = await fetch('api/v1/auth/register-step3.php', {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrfToken },
        body: fd,
        credentials: 'same-origin',
      });
      const body = await res.json().catch(() => ({}));
      setLoading(submitBtn, false);

      if (!res.ok) {
        applyErrors(scope, body.error?.fields || {});
        if (!body.error?.fields) showBanner(body.error?.message || 'Registration failed.', 'error');
        return;
      }

      showBanner('Account created! Redirecting to login…', 'success');
      setTimeout(() => { window.location.href = 'login.php?registered=1'; }, 1200);
    } catch (err) {
      setLoading(submitBtn, false);
      showBanner('Network error — please check your connection and try again.', 'error');
    }
  });

  showStep(1);
})();
