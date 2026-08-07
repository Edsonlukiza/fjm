# TAYO-TECH — System Architecture (MVP)

## 1. Goal

Ship the smallest system that is still **production-shaped**: clean layering, a
real schema, a versioned API, server-side validation, and a UI that can grow
into a SPA later without a rewrite.

## 2. High-level architecture

```
                       ┌─────────────────────────┐
                       │        Browser           │
                       │  server-rendered pages +  │
                       │  fetch() for the wizard    │
                       └────────────┬─────────────┘
                                    │ HTTPS
                       ┌────────────▼─────────────┐
                       │   Nginx / Apache (TLS)     │
                       │   public/ = web root       │
                       └────────────┬─────────────┘
                                    │
                       ┌────────────▼─────────────┐
                       │   PHP 8.x (PHP-FPM)        │
                       │   public/*.php  → pages     │
                       │   public/api/v1/* → JSON     │
                       │   src/Core      → framework   │
                       │   src/Models    → data          │
                       │   src/Services  → business logic │
                       └────────────┬─────────────┘
                                    │ PDO (pgsql)
                       ┌────────────▼─────────────┐
                       │      PostgreSQL 15+         │
                       └───────────────────────────┘

  Cross-cutting: sessions (PHP native → Redis when scaling), file storage
  (local disk → S3-compatible object storage later), logs → storage/logs.
```

**Why this shape:** the existing landing page is PHP, so the MVP stays in PHP
instead of forcing a framework migration. It's a lightweight **layered
monolith** (thin API controllers → Services hold business logic → Models are
pure data-access) so it can later move into Laravel/Symfony or be split into
services without touching the DB schema or API contract.

## 3. Layers

| Layer | Location | Responsibility |
|---|---|---|
| Presentation | `public/*.php`, `public/assets` | Server-rendered HTML, progressive-enhancement JS |
| API | `public/api/v1/**` | Thin controllers: parse request → call Service → return JSON |
| Service | `src/Services` | Business rules, transactions, orchestration across models |
| Model | `src/Models` | One class per table, PDO prepared statements only |
| Core | `src/Core` | DB connection, Validator, Session, CSRF, Response, RateLimiter |
| Config | `src/Config` | Env-driven config, DB credentials |

Request flow for the final registration step:
`register.php` → `fetch('/api/v1/auth/register-step3.php')` →
`RegistrationService::completeRegistration()` → writes `users`, `addresses`,
`occupation_info`, `education_info`, `skills_info`, `business_info`,
`documents` inside **one DB transaction** → JSON response.

## 4. Security posture (MVP baseline, not optional)

- Every query goes through PDO **prepared statements** — no string-built SQL.
- Passwords hashed with `password_hash()` (bcrypt); the security-question
  answer is hashed too, never stored in plain text.
- CSRF token per session, required on every state-changing POST.
- Session cookies: `HttpOnly`, `Secure`, `SameSite=Lax`.
- Uploaded files stored **outside the web root** (`storage/uploads`), served
  through an authenticated `download.php`, extension + MIME whitelisted,
  renamed to a random UUID on disk (original filename never trusted).
- Login attempts throttled via the `login_attempts` table — lockout after
  repeated failures, per IP + per username.
- Server-side validation is the source of truth (`Validator`); client-side JS
  is UX sugar only.
- CAPTCHA is a stub hook (`Validator::verifyCaptcha`) — wire to hCaptcha /
  reCAPTCHA v3 before go-live.

## 5. Multi-step registration state

Wizard state for steps 1–2 lives in the **PHP session**
(`$_SESSION['reg_draft']`) and is only persisted to Postgres on step 3, inside
one transaction. That avoids half-created user rows in the database.

**Scaling note:** once drop-off analytics or cross-device resume matter,
promote this to a `registration_drafts` table (`id`, `session_token`, `step`,
`payload jsonb`, `expires_at`). `RegistrationService` is already the only
place that touches this state, so the swap won't change the API contract.

## 6. Scaling path (deferred deliberately, not designed-in prematurely)

| Concern | MVP | Next step when it matters |
|---|---|---|
| Sessions | PHP native file sessions | Redis-backed sessions (multi-server) |
| File storage | Local disk (`storage/uploads`) | S3 / Spaces + CDN |
| Search (jobs/events) | Postgres `ILIKE` / `tsvector` | Meilisearch / OpenSearch |
| Notifications (SMS/email) | Synchronous / cron | Queue (Redis + worker) |
| Database | Single Postgres instance | Read replica + PgBouncer pooling |
| Auth | Server session cookie | JWT + refresh token if a mobile app ships |

## 7. Directory layout

```
tayo-tech/
├── docs/{ARCHITECTURE.md, API.md}
├── database/schema.sql
├── public/                        # web root
│   ├── index.php                  # landing page
│   ├── register.php               # 3-step registration wizard
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php              # stub post-login landing
│   ├── download.php               # authenticated file download
│   ├── assets/{css,js,img}
│   └── api/v1/
│       ├── _bootstrap.php
│       └── auth/{register-step1,register-step2,register-step3,
│                  check-username,check-email,login,logout,me}.php
├── src/
│   ├── config/{config.php,database.php}
│   ├── core/{Database,Response,Validator,Session,Csrf,RateLimiter}.php
│   ├── models/{User,Address,OccupationInfo,EducationInfo,SkillsInfo,
│   │            BusinessInfo,Document,LoginAttempt}.php
│   └── services/{AuthService,RegistrationService,FileUploadService}.php
├── storage/{uploads,logs}          # outside web root
└── .env.example
```

## 8. Full platform API surface

The MVP implements **auth + registration + login** end to end. `docs/API.md`
also documents the rest of the platform (jobs, trainings, events, mentorship,
dashboard) so the schema/routes are reserved for the frontend team to build
against — those return `501 Not Implemented` stubs in this MVP.
