<?php
class Database {
    private static $connection = null;
    // Get the database connection
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $host = 'database';  
                $dbname = 'drinx_db';  
                $username = 'root';    
                $password = 'tiger';       

                self::$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}