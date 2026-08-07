<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class SkillsInfo
{
    public static function create(PDO $pdo, string $userId, array $s): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO skills_info (
                user_id, technical_skills, soft_skills, languages_spoken,
                career_interests, preferred_job_category
            ) VALUES (
                :user_id, :technical_skills, :soft_skills, :languages_spoken,
                :career_interests, :preferred_job_category
            )'
        );
        $stmt->execute([
            'user_id' => $userId,
            'technical_skills' => self::toPgArray($s['technical_skills'] ?? []),
            'soft_skills' => self::toPgArray($s['soft_skills'] ?? []),
            'languages_spoken' => self::toPgArray($s['languages_spoken'] ?? []),
            'career_interests' => self::toPgArray($s['career_interests'] ?? []),
            'preferred_job_category' => $s['preferred_job_category'] ?: null,
        ]);
    }

    /** Converts a PHP array of strings into a Postgres TEXT[] literal for a prepared statement. */
    private static function toPgArray(array $items): string
    {
        $escaped = array_map(
            static fn ($v) => '"' . str_replace('"', '\\"', trim((string) $v)) . '"',
            array_filter($items, static fn ($v) => trim((string) $v) !== '')
        );
        return '{' . implode(',', $escaped) . '}';
    }
}
