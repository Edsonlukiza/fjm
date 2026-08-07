-- ============================================================
-- TAYO-TECH — PostgreSQL schema (MVP)
-- Target: PostgreSQL 15+
-- ============================================================

CREATE EXTENSION IF NOT EXISTS "pgcrypto"; -- gen_random_uuid()
CREATE EXTENSION IF NOT EXISTS "citext";   -- case-insensitive email/username

-- ------------------------------------------------------------
-- Enums
-- ------------------------------------------------------------
CREATE TYPE gender_enum            AS ENUM ('male', 'female', 'other', 'prefer_not_to_say');
CREATE TYPE employment_status_enum AS ENUM ('student', 'employed', 'self_employed', 'unemployed', 'freelancer');
CREATE TYPE education_level_enum   AS ENUM ('primary', 'secondary', 'certificate', 'diploma', 'bachelor', 'master', 'phd', 'other');
CREATE TYPE document_type_enum     AS ENUM ('photo', 'cv', 'academic_certificate', 'professional_certificate');
CREATE TYPE user_status_enum       AS ENUM ('pending_verification', 'active', 'suspended', 'deactivated');
CREATE TYPE user_role_enum         AS ENUM ('user', 'moderator', 'admin');

-- ------------------------------------------------------------
-- A. + G. Core identity & account  (users)
-- ------------------------------------------------------------
CREATE TABLE users (
    id                  UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    -- A. Personal information
    first_name          VARCHAR(100) NOT NULL,
    middle_name          VARCHAR(100),
    last_name            VARCHAR(100) NOT NULL,
    gender               gender_enum NOT NULL,
    date_of_birth        DATE NOT NULL,
    nationality          VARCHAR(100) NOT NULL,
    national_id          VARCHAR(30) UNIQUE,          -- NIDA, optional
    passport_number      VARCHAR(30) UNIQUE,          -- non-Tanzanians

    -- B. Contact information
    mobile_phone         VARCHAR(20) NOT NULL UNIQUE,
    alt_phone            VARCHAR(20),
    email                CITEXT NOT NULL UNIQUE,

    -- G. Account information
    username             CITEXT NOT NULL UNIQUE,
    password_hash        VARCHAR(255) NOT NULL,
    security_question    VARCHAR(255) NOT NULL,
    security_answer_hash VARCHAR(255) NOT NULL,

    -- I. Verification / consent
    terms_accepted_at    TIMESTAMPTZ,
    privacy_accepted_at  TIMESTAMPTZ,
    email_verified_at    TIMESTAMPTZ,
    phone_verified_at    TIMESTAMPTZ,

    role                 user_role_enum NOT NULL DEFAULT 'user',
    status                user_status_enum NOT NULL DEFAULT 'pending_verification',

    created_at            TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at            TIMESTAMPTZ NOT NULL DEFAULT now(),
    deleted_at            TIMESTAMPTZ                 -- soft delete

    ,CONSTRAINT chk_dob_reasonable CHECK (date_of_birth <= now() - INTERVAL '13 years')
);

CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_users_username ON users (username);
CREATE INDEX idx_users_status ON users (status) WHERE deleted_at IS NULL;

-- ------------------------------------------------------------
-- B. Physical address (1:1 with user for MVP)
-- ------------------------------------------------------------
CREATE TABLE addresses (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id         UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    country         VARCHAR(100) NOT NULL DEFAULT 'Tanzania',
    region          VARCHAR(100) NOT NULL,
    district        VARCHAR(100) NOT NULL,
    ward            VARCHAR(100) NOT NULL,
    street_village  VARCHAR(150) NOT NULL,
    house_number    VARCHAR(30),
    postal_address  VARCHAR(150),
    zip_code        VARCHAR(20),
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ------------------------------------------------------------
-- C. Occupation & professional information
-- ------------------------------------------------------------
CREATE TABLE occupation_info (
    id                  UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id             UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    occupation          VARCHAR(150) NOT NULL,   -- e.g. "Software Developer" (current activity)
    profession          VARCHAR(150),            -- e.g. "Computer Engineering" (trained field)
    employment_status   employment_status_enum NOT NULL,
    organization_name   VARCHAR(150),
    job_title           VARCHAR(150),
    years_experience    SMALLINT DEFAULT 0 CHECK (years_experience >= 0),
    created_at          TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at          TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ------------------------------------------------------------
-- D. Education information (a user may list more than one)
-- ------------------------------------------------------------
CREATE TABLE education_info (
    id                   UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id              UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    education_level      education_level_enum NOT NULL,
    institution_name     VARCHAR(200) NOT NULL,
    programme_course     VARCHAR(200),
    specialization       VARCHAR(200),
    graduation_year      SMALLINT CHECK (graduation_year BETWEEN 1950 AND 2100),
    is_highest           BOOLEAN NOT NULL DEFAULT true,
    created_at           TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_education_user ON education_info (user_id);

CREATE TABLE certifications (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id         UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title           VARCHAR(200) NOT NULL,
    issuer          VARCHAR(200),
    year_obtained   SMALLINT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_certifications_user ON certifications (user_id);

-- ------------------------------------------------------------
-- E. Skills & career information — stored as arrays for MVP speed;
--    normalize into skills/skill_tags tables once search/matching
--    features need it (see docs/ARCHITECTURE.md §6).
-- ------------------------------------------------------------
CREATE TABLE skills_info (
    id                     UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id                UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    technical_skills       TEXT[] NOT NULL DEFAULT '{}',
    soft_skills            TEXT[] NOT NULL DEFAULT '{}',
    languages_spoken       TEXT[] NOT NULL DEFAULT '{}',
    career_interests       TEXT[] NOT NULL DEFAULT '{}',
    preferred_job_category VARCHAR(150),
    created_at             TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at             TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_skills_technical ON skills_info USING GIN (technical_skills);

-- ------------------------------------------------------------
-- F. Entrepreneurship information (optional, 1:1)
-- ------------------------------------------------------------
CREATE TABLE business_info (
    id                          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id                     UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    is_business_owner           BOOLEAN NOT NULL DEFAULT false,
    business_name               VARCHAR(200),
    business_sector             VARCHAR(150),
    business_registration_number VARCHAR(100),
    created_at                  TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at                  TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ------------------------------------------------------------
-- H. Uploaded documents
-- ------------------------------------------------------------
CREATE TABLE documents (
    id                UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id           UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    doc_type          document_type_enum NOT NULL,
    storage_path      VARCHAR(500) NOT NULL,   -- path under storage/uploads, never web-accessible directly
    original_filename VARCHAR(255) NOT NULL,
    mime_type         VARCHAR(100) NOT NULL,
    size_bytes        INTEGER NOT NULL CHECK (size_bytes > 0),
    uploaded_at       TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_documents_user ON documents (user_id);

-- ------------------------------------------------------------
-- Security / auth support tables
-- ------------------------------------------------------------
CREATE TABLE login_attempts (
    id            BIGSERIAL PRIMARY KEY,
    identifier    CITEXT NOT NULL,        -- username or email attempted
    ip_address    INET NOT NULL,
    succeeded     BOOLEAN NOT NULL,
    attempted_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_login_attempts_identifier ON login_attempts (identifier, attempted_at);
CREATE INDEX idx_login_attempts_ip ON login_attempts (ip_address, attempted_at);

CREATE TABLE audit_log (
    id            BIGSERIAL PRIMARY KEY,
    user_id       UUID REFERENCES users(id) ON DELETE SET NULL,
    action        VARCHAR(100) NOT NULL,     -- e.g. 'user.registered', 'user.login'
    metadata      JSONB NOT NULL DEFAULT '{}',
    ip_address    INET,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_audit_log_user ON audit_log (user_id);

-- ------------------------------------------------------------
-- Reserved for platform features (schema locked in now, API in
-- docs/API.md; endpoints return 501 in the MVP). Kept here so
-- the frontend team can build against a stable data model.
-- ------------------------------------------------------------
CREATE TABLE opportunities (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title         VARCHAR(200) NOT NULL,
    company_name  VARCHAR(200) NOT NULL,
    location      VARCHAR(150),
    category      VARCHAR(100),     -- job | internship | scholarship
    description   TEXT,
    posted_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
    expires_at    TIMESTAMPTZ
);

CREATE TABLE events (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title         VARCHAR(200) NOT NULL,
    venue         VARCHAR(200),
    starts_at     TIMESTAMPTZ NOT NULL,
    description   TEXT,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE event_registrations (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    event_id      UUID NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    user_id       UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    registered_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (event_id, user_id)
);

-- ------------------------------------------------------------
-- updated_at trigger helper
-- ------------------------------------------------------------
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_users_updated_at BEFORE UPDATE ON users
  FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_addresses_updated_at BEFORE UPDATE ON addresses
  FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_occupation_updated_at BEFORE UPDATE ON occupation_info
  FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_skills_updated_at BEFORE UPDATE ON skills_info
  FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_business_updated_at BEFORE UPDATE ON business_info
  FOR EACH ROW EXECUTE FUNCTION set_updated_at();
