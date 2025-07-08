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
                $connection = DatabaseConfig::get('DB_CONNECTION', 'sqlite');

                switch ($connection) {
                    case 'sqlite':
                        $dbPath = DatabaseConfig::get('DB_DATABASE', 'database/database.sqlite');
                        $absolutePath = dirname(__DIR__, 2) . '/' . $dbPath;

                        // Buat folder jika belum ada
                        $folder = dirname($absolutePath);
                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        $isNewDatabase = !file_exists($absolutePath);

                        self::$pdo = new PDO("sqlite:$absolutePath");
                        break;

                    case 'mysql':
                        $host = DatabaseConfig::get('DB_HOST');
                        $port = DatabaseConfig::get('DB_PORT', 3306);
                        $db   = DatabaseConfig::get('DB_DATABASE');
                        $user = DatabaseConfig::get('DB_USERNAME');
                        $pass = DatabaseConfig::get('DB_PASSWORD');

                        self::$pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
                        break;

                    case 'pgsql':
                        $host = DatabaseConfig::get('DB_HOST');
                        $port = DatabaseConfig::get('DB_PORT', 5432);
                        $db   = DatabaseConfig::get('DB_DATABASE');
                        $user = DatabaseConfig::get('DB_USERNAME');
                        $pass = DatabaseConfig::get('DB_PASSWORD');

                        self::$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
                        break;

                    default:
                        throw new \Exception("Unsupported DB_CONNECTION: $connection");
                }

                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($isNewDatabase) && $isNewDatabase && $connection === 'sqlite') {
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
        $driver = self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    
        switch ($driver) {
            case 'sqlite':
                $sql = "CREATE TABLE IF NOT EXISTS locations (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            address TEXT NOT NULL,
                            lat REAL NOT NULL,
                            lng REAL NOT NULL
                        );";
                break;
    
            case 'mysql':
                $sql = "CREATE TABLE IF NOT EXISTS locations (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            address VARCHAR(255) NOT NULL,
                            lat DOUBLE NOT NULL,
                            lng DOUBLE NOT NULL
                        ) ENGINE=InnoDB;";
                break;
    
            case 'pgsql':
                $sql = "CREATE TABLE IF NOT EXISTS locations (
                            id SERIAL PRIMARY KEY,
                            address TEXT NOT NULL,
                            lat DOUBLE PRECISION NOT NULL,
                            lng DOUBLE PRECISION NOT NULL
                        );";
                break;
    
            default:
                throw new \Exception("Bootstrap not supported for driver: $driver");
        }
    
        self::$pdo->exec($sql);
    }
}