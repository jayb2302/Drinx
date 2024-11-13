<?php   
class Database {
    private static $connection = null;

    // Get the database connection
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                // Load environment variables
                $env = loadEnv(base_path('.env'));

                // Determine the environment
                $environment = $env['ENV'] ?? 'local';

                // Database configurations based on environment
                if ($environment === 'live') {
                    $host = $env['LIVE_DB_HOST'];
                    $dbname = $env['LIVE_DB_DATABASE'];
                    $username = $env['LIVE_DB_USERNAME'];
                    $password = $env['LIVE_DB_PASSWORD'];
                } else {
                    $host = $env['DB_HOST'];
                    $dbname = $env['DB_DATABASE'];
                    $username = $env['DB_USERNAME'];
                    $password = $env['DB_PASSWORD'];
                }
                
                // PDO connection
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}