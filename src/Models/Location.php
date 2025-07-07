<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Location
{
    public int $id;
    public string $address;
    public float $lat;
    public float $lng;

    public static function all(): array
    {
        $stmt = Connection::get()->query('SELECT * FROM locations');
        return $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    public static function create(string $address, float $lat, float $lng): bool
    {
        $stmt = Connection::get()->prepare(
            'INSERT INTO locations (address, lat, lng) VALUES (:address, :lat, :lng)'
        );

        return $stmt->execute([
            ':address' => $address,
            ':lat' => $lat,
            ':lng' => $lng,
        ]);
    }
    
    public static function findById(int $id): ?array
{
    $pdo = Connection::get();
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

// Update lokasi
public static function update(int $id, string $address, float $lat, float $lng): void
{
    $pdo = Connection::get();
    $stmt = $pdo->prepare("UPDATE locations SET address = ?, lat = ?, lng = ? WHERE id = ?");
    $stmt->execute([$address, $lat, $lng, $id]);
}

// Hapus lokasi
public static function delete(int $id): void
{
    $pdo = Connection::get();
    $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
    $stmt->execute([$id]);
}
}
