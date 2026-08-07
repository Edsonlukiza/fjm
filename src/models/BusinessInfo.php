<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class BusinessInfo
{
    public static function create(PDO $pdo, string $userId, array $b): void
    {
        $isOwner = (bool) ($b['is_business_owner'] ?? false);
        if (!$isOwner) {
            return; // optional section — nothing to store
        }

        $stmt = $pdo->prepare(
            'INSERT INTO business_info (
                user_id, is_business_owner, business_name, business_sector,
                business_registration_number
            ) VALUES (
                :user_id, true, :business_name, :business_sector, :business_registration_number
            )'
        );
        $stmt->execute([
            'user_id' => $userId,
            'business_name' => $b['business_name'] ?: null,
            'business_sector' => $b['business_sector'] ?: null,
            'business_registration_number' => $b['business_registration_number'] ?: null,
        ]);
    }
}
