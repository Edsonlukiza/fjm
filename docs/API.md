# TAYO-TECH API — v1

Base URL: `/api/v1`
Format: JSON in, JSON out. Every response is shaped:

```json
{ "success": true,  "data": { ... } }
{ "success": false, "error": { "code": "VALIDATION_ERROR", "message": "...", "fields": { "email": "Already registered" } } }
```

Every state-changing request (`POST`/`PUT`/`DELETE`) must include header
`X-CSRF-Token`, issued via the `csrf_token` embedded in each server-rendered
page. Session auth via `HttpOnly` cookie — no tokens in JS-readable storage.

## Auth & Registration (implemented in this MVP)

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/auth/register-step1` | Validate + stage **Personal + Contact** info (sections A, B) in session draft |
| POST | `/auth/register-step2` | Validate + stage **Occupation, Education, Skills, Entrepreneurship** (sections C–F) |
| POST | `/auth/register-step3` | Validate **Account info + documents + consent** (G, H, I), create the user in one transaction, clear draft |
| GET  | `/auth/check-username?username=` | Live-availability check while typing |
| GET  | `/auth/check-email?email=` | Live-availability check while typing |
| POST | `/auth/login` | `{ identifier, password }` → session cookie |
| POST | `/auth/logout` | Destroy session |
| GET  | `/auth/me` | Current authenticated user (or 401) |

### `POST /auth/register-step1`
```json
{
  "first_name": "Amina", "middle_name": "", "last_name": "Juma",
  "gender": "female", "date_of_birth": "2001-04-12",
  "nationality": "Tanzanian", "national_id": "", "passport_number": "",
  "mobile_phone": "+255712345678", "alt_phone": "", "email": "amina@example.com",
  "country": "Tanzania", "region": "Dar es Salaam", "district": "Kinondoni",
  "ward": "Msasani", "street_village": "Chole Rd", "house_number": "",
  "postal_address": "", "zip_code": ""
}
```
`200` → `{ "success": true, "data": { "step": 1, "next": 2 } }`
`422` → per-field validation errors.

### `POST /auth/register-step2`
```json
{
  "occupation": "Student", "profession": "Computer Science",
  "employment_status": "student", "organization_name": "", "job_title": "",
  "years_experience": 0,
  "education_level": "bachelor", "institution_name": "University of Dar es Salaam",
  "programme_course": "BSc Computer Science", "specialization": "Software Engineering",
  "graduation_year": 2025, "certifications": ["AWS Cloud Practitioner"],
  "technical_skills": ["JavaScript", "PHP"], "soft_skills": ["Communication"],
  "languages_spoken": ["Swahili", "English"], "career_interests": ["Software Development"],
  "preferred_job_category": "Software Engineering",
  "is_business_owner": false, "business_name": "", "business_sector": "",
  "business_registration_number": ""
}
```

### `POST /auth/register-step3` (multipart/form-data — includes files)
```
username, password, confirm_password, security_question, security_answer,
agree_terms=1, agree_privacy=1, captcha_token,
passport_photo (file), cv (file), academic_certificates[] (files, optional),
professional_certificates[] (files, optional)
```
`201` → `{ "success": true, "data": { "user_id": "uuid", "status": "pending_verification" } }`
On any failure, **nothing is written** (single transaction) and the session
draft from steps 1–2 is preserved so the user doesn't retype anything.

### `POST /auth/login`
```json
{ "identifier": "amina@example.com", "password": "••••••••" }
```
`200` → sets session cookie, `{ "data": { "user": { "id", "username", "role" } } }`
`423` → account locked (too many attempts), includes `retry_after_seconds`.

## Platform surface (reserved, `501` in this MVP)

Schema exists in `database/schema.sql`; wiring these up is pure CRUD once the
frontend needs them.

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/opportunities` | List jobs/internships/scholarships (filter by category, region) |
| GET | `/opportunities/{id}` | Detail |
| GET | `/events` | Upcoming events |
| POST | `/events/{id}/register` | Register current user for an event |
| GET | `/dashboard/summary` | Applications, trainings, bookmarks counts for the logged-in user |
| GET | `/users/me/profile` | Full profile (joins all registration sections) |
| PUT | `/users/me/profile` | Edit profile section-by-section |

## Error codes

| Code | HTTP | Meaning |
|---|---|---|
| `VALIDATION_ERROR` | 422 | One or more fields failed validation |
| `DUPLICATE` | 409 | Email/username/phone/national ID already registered |
| `INVALID_CREDENTIALS` | 401 | Login failed |
| `ACCOUNT_LOCKED` | 423 | Too many failed login attempts |
| `CSRF_MISMATCH` | 403 | Missing/invalid CSRF token |
| `UNAUTHENTICATED` | 401 | No active session |
| `SERVER_ERROR` | 500 | Unhandled exception (logged to `storage/logs`) |
