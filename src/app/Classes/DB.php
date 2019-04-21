<?php

namespace Hea\Classes;

use \PDO;
use \PDOException;

class DB
{
    public static $serverName;
    public static $databaseName;
    public static $userName;
    public static $password;

    /**
     * @return PDO
     */
    public static function connect()
    {
        try {
            self::$serverName = getenv('DB_HOST');
            self::$userName = getenv('DB_USERNAME');
            self::$password = getenv('DB_PASSWORD');
            self::$databaseName = getenv('DB_DATABASE');
            $pdo = new PDO("mysql:host=".self::$serverName.";dbname=".self::$databaseName.";charset=utf8", self::$userName, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }
    }
}