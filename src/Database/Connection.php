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
                // Ambil path dari konfigurasi
                $path = DatabaseConfig::PATH;
                $folder = dirname($path);

                // Buat folder jika belum ada
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                // Cek apakah file database belum ada
                $isNewDatabase = !file_exists($path);
                // Koneksi PDO
                self::$pdo = new PDO('sqlite:' . $path);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // jika database baru, jalankan bootstrap
                if($isNewDatabase) {
                    self::bootstrap();
                }
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
