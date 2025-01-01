<?php
// config/database.php

class Database {
    // Make these private and load from environment variables or config file
    private static $host;
    private static $dbName;
    private static $username;
    private static $password;
    private static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    private static $pdo = null;

    public static function init($config = null) {
        // Load configuration from parameter or environment
        self::$host = $config['host'] ?? getenv('DB_HOST') ?? 'localhost';
        self::$dbName = $config['dbname'] ?? getenv('DB_NAME') ?? 'bakery_production';
        self::$username = $config['username'] ?? getenv('DB_USER') ?? 'root';
        self::$password = $config['password'] ?? getenv('DB_PASS') ?? '';
    }

    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    self::$host,
                    self::$dbName
                );
                
                self::$pdo = new PDO($dsn, self::$username, self::$password, self::$options);
                
                return self::$pdo;
            } catch (PDOException $e) {
                // Log error instead of exposing it
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed. Please check the logs or contact support.");
            }
        }
        return self::$pdo;
    }

    public static function closeConnection() {
        self::$pdo = null;
    }

    // Method to check connection health
    public static function checkConnection() {
        try {
            $conn = self::getConnection();
            $stmt = $conn->query('SELECT 1');
            return $stmt !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Initialize the database configuration
Database::init();
