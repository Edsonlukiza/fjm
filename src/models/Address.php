<?php
declare(strict_types=1);

namespace Tayo\Models;

use PDO;

final class Address
{
    public static function create(PDO $pdo, string $userId, array $a): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO addresses (
                user_id, country, region, district, ward, street_village,
                house_number, postal_address, zip_code
            ) VALUES (
                :user_id, :country, :region, :district, :ward, :street_village,
                :house_number, :postal_address, :zip_code
            )'
        );
        $stmt->execute([
            'user_id' => $userId,
            'country' => $a['country'] ?: 'Tanzania',
            'region' => $a['region'],
            'district' => $a['district'],
            'ward' => $a['ward'],
            'street_village' => $a['street_village'],
            'house_number' => $a['house_number'] ?: null,
            'postal_address' => $a['postal_address'] ?: null,
            'zip_code' => $a['zip_code'] ?: null,
        ]);
    }
}
