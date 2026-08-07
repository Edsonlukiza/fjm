<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class OccupationInfo
{
    public static function create(PDO $pdo, string $userId, array $o): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO occupation_info (
                user_id, occupation, profession, employment_status,
                organization_name, job_title, years_experience
            ) VALUES (
                :user_id, :occupation, :profession, :employment_status,
                :organization_name, :job_title, :years_experience
            )'
        );
        $stmt->execute([
            'user_id' => $userId,
            'occupation' => $o['occupation'],
            'profession' => $o['profession'] ?: null,
            'employment_status' => $o['employment_status'],
            'organization_name' => $o['organization_name'] ?: null,
            'job_title' => $o['job_title'] ?: null,
            'years_experience' => (int) ($o['years_experience'] ?? 0),
        ]);
    }
}
