<?php
declare(strict_types=1);

namespace Tayo\Core;

/**
 * Small rule-based validator — no framework dependency. Every /auth/register-*
 * endpoint runs input through here; client-side JS mirrors these rules for
 * UX only and must never be trusted on its own.
 */
final class Validator
{
    private array $errors = [];

    public function __construct(private readonly array $data)
    {
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    private function value(string $field): mixed
    {
        return $this->data[$field] ?? null;
    }

    public function required(string $field, string $label): self
    {
        $v = $this->value($field);
        if ($v === null || (is_string($v) && trim($v) === '')) {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    public function string(string $field, string $label, int $min = 0, int $max = 255): self
    {
        $v = $this->value($field);
        if ($v === null || $v === '') {
            return $this;
        }
        if (!is_string($v)) {
            $this->errors[$field] = "{$label} must be text.";
        } elseif (mb_strlen($v) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        } elseif (mb_strlen($v) > $max) {
            $this->errors[$field] = "{$label} must be at most {$max} characters.";
        }
        return $this;
    }

    public function email(string $field, string $label): self
    {
        $v = $this->value($field);
        if ($v !== null && $v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email address.";
        }
        return $this;
    }

    public function phone(string $field, string $label): self
    {
        $v = $this->value($field);
        if ($v !== null && $v !== '' && !preg_match('/^\+?[0-9]{9,15}$/', (string) $v)) {
            $this->errors[$field] = "{$label} must be a valid phone number (e.g. +255712345678).";
        }
        return $this;
    }

    public function date(string $field, string $label): self
    {
        $v = $this->value($field);
        if ($v !== null && $v !== '') {
            $d = \DateTime::createFromFormat('Y-m-d', (string) $v);
            if (!$d || $d->format('Y-m-d') !== $v) {
                $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD).";
            }
        }
        return $this;
    }

    public function in(string $field, string $label, array $allowed): self
    {
        $v = $this->value($field);
        if ($v !== null && $v !== '' && !in_array($v, $allowed, true)) {
            $this->errors[$field] = "{$label} must be one of: " . implode(', ', $allowed) . '.';
        }
        return $this;
    }

    public function boolTrue(string $field, string $label): self
    {
        $v = $this->value($field);
        if (!($v === true || $v === '1' || $v === 1)) {
            $this->errors[$field] = "You must accept {$label}.";
        }
        return $this;
    }

    public function integer(string $field, string $label, ?int $min = null, ?int $max = null): self
    {
        $v = $this->value($field);
        if ($v === null || $v === '') {
            return $this;
        }
        if (filter_var($v, FILTER_VALIDATE_INT) === false) {
            $this->errors[$field] = "{$label} must be a whole number.";
            return $this;
        }
        $v = (int) $v;
        if ($min !== null && $v < $min) {
            $this->errors[$field] = "{$label} must be at least {$min}.";
        }
        if ($max !== null && $v > $max) {
            $this->errors[$field] = "{$label} must be at most {$max}.";
        }
        return $this;
    }

    public function matches(string $field, string $otherField, string $label): self
    {
        if ($this->value($field) !== $this->value($otherField)) {
            $this->errors[$field] = "{$label} does not match.";
        }
        return $this;
    }

    public function password(string $field, string $label): self
    {
        $v = (string) $this->value($field);
        if ($v !== '' && (mb_strlen($v) < 8 || !preg_match('/[A-Z]/', $v) || !preg_match('/[0-9]/', $v))) {
            $this->errors[$field] = "{$label} must be at least 8 characters and include an uppercase letter and a number.";
        }
        return $this;
    }

    /**
     * Stub — wire to hCaptcha / reCAPTCHA v3 verify endpoint using
     * config('captcha.secret') before go-live.
     */
    public static function verifyCaptcha(?string $token): bool
    {
        if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
            return true; // allow local/dev testing without a real captcha
        }
        return is_string($token) && $token !== '';
    }
}
