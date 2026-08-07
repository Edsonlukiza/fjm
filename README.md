# TAYO-TECH — MVP

Tanzania Youth-Tech Forum platform: landing page, 3-step registration, login,
and a foundation to build the rest of the platform on. See `docs/ARCHITECTURE.md`
for the full design and `docs/API.md` for the API contract.

## Stack

PHP 8.1+ (no framework, no Composer dependency required) · PostgreSQL 15+ ·
vanilla JS (fetch-based, progressive enhancement).

## 1. Requirements

- PHP 8.1+ with `pdo_pgsql` and `fileinfo` extensions enabled
- PostgreSQL 15+
- Apache (with `mod_rewrite`/`mod_headers`) or Nginx + PHP-FPM

## 2. Database setup

```bash
createdb tayotech
createuser tayotech_app --pwprompt
psql -d tayotech -f database/schema.sql
```

Grant the app user access:
```sql
GRANT CONNECT ON DATABASE tayotech TO tayotech_app;
GRANT USAGE, SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO tayotech_app;
```

## 3. App configuration

```bash
cp .env.example .env
```
Edit `.env` with your DB credentials, session settings, upload limits, and a
real CAPTCHA secret before going to production.

## 4. Web server

**Point the document root at `public/`, not the project root.** The `src/`,
`database/`, `docs/`, and `storage/` directories must never be web-accessible
— that's why `RegistrationService`, `AuthService`, etc. live outside
`public/`. The root `.htaccess` blocks access if this is ever misconfigured
on Apache; on Nginx, only serve from `public/` in your `root` directive.

Example Nginx server block:
```nginx
server {
    listen 443 ssl;
    server_name tayotech.co.tz;
    root /var/www/tayo-tech/public;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ ^/api/v1/_bootstrap\.php$ { deny all; }
}
```

Make sure `storage/uploads` and `storage/logs` are writable by the PHP
process user (`chown -R www-data:www-data storage`).

## 5. What's implemented vs. reserved

**Implemented end-to-end:** landing page, 3-step registration wizard
(personal/contact → occupation/education/skills/entrepreneurship →
account/documents/consent), login, logout, session auth, CSRF protection,
rate-limited login attempts, secure file uploads.

**Schema reserved, API stubbed (`501`):** jobs/opportunities, events,
trainings, mentorship, dashboard summary — see `docs/API.md` §"Platform
surface". The tables already exist in `database/schema.sql`; wiring the
Model → Service → API layer for each is the next milestone.

## 6. Before go-live checklist

- [ ] Replace the CAPTCHA stub (`Validator::verifyCaptcha`, `#captcha_checkbox`
      in `register-wizard.js`) with a real hCaptcha/reCAPTCHA v3 integration.
- [ ] Add email/SMS verification (the schema already has
      `email_verified_at`/`phone_verified_at` columns).
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false` in `.env`.
- [ ] Force HTTPS; confirm session cookies are `Secure` (automatic once
      `APP_ENV=production`).
- [ ] Move `storage/uploads` to S3-compatible object storage once volume
      grows (see `docs/ARCHITECTURE.md` §6).
- [ ] Add a cron/monitor to prune expired sessions and rotate `storage/logs`.

## 7. Directory map

See `docs/ARCHITECTURE.md` §7 for the full annotated tree.
# fjm
