<?php
// config/database.php

class Database {
    private static $host = 'localhost';
    private static $dbName = 'bakery_production';
    private static $username = 'root'; // Change this as needed
    private static $password = ''; // Change this as needed
    private static $pdo;

    public static function getConnection() {
        // If connection is already established, return the existing connection
        if (self::$pdo === null) {
            try {
                // Create a new PDO connection
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8';
                self::$pdo = new PDO($dsn, self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // If connection fails, display an error message
                echo 'Connection failed: ' . $e->getMessage();
                exit();
            }
        }
        return self::$pdo;
    }
}

