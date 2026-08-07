<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class EducationInfo
{
    public static function create(PDO $pdo, string $userId, array $e): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO education_info (
                user_id, education_level, institution_name, programme_course,
                specialization, graduation_year, is_highest
            ) VALUES (
                :user_id, :education_level, :institution_name, :programme_course,
                :specialization, :graduation_year, true
            )'
        );
        $stmt->execute([
            'user_id' => $userId,
            'education_level' => $e['education_level'],
            'institution_name' => $e['institution_name'],
            'programme_course' => $e['programme_course'] ?: null,
            'specialization' => $e['specialization'] ?: null,
            'graduation_year' => $e['graduation_year'] ?: null,
        ]);

        foreach ((array) ($e['certifications'] ?? []) as $title) {
            $title = trim((string) $title);
            if ($title === '') {
                continue;
            }
            $cert = $pdo->prepare(
                'INSERT INTO certifications (user_id, title) VALUES (:user_id, :title)'
            );
            $cert->execute(['user_id' => $userId, 'title' => $title]);
        }
    }
}
