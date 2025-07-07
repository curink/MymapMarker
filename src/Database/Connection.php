<?php
namespace App\Database;

use PDO;
use PDOException;
use Config\DatabaseConfig;

class Connection
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO('sqlite:' . DatabaseConfig::PATH);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::bootstrap();
            } catch (PDOException $e) {
                exit('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    private static function bootstrap(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS locations (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    address TEXT NOT NULL,
                    lat REAL NOT NULL,
                    lng REAL NOT NULL
                );";

        self::$pdo->exec($sql);
    }
}
